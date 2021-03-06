<?php include('header.php');?>

<script>
  $(document).attr("title", "Отчёт по выбранному преподавателю - ИС «Серверы Moodle»");
  $('#collapse-reports').addClass("show");
  $('#report-by-teachers').addClass("active");
  $('#currentDirection').append("ИС Серверы Moodle > 📊 Подсистема Отчёты");
</script>

<h1>Отчёт по выбранному преподавателю</h1>
<br>

<div class="superklass" style="max-width: 50%;">
  <form action="">
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text" for="server">
          <i class="fas fa-server"></i>
        </label>
      </div>
      <select class="form-control selector" name="server" id="server" onchange="selectTeacher()" required>
        <option value=''>Выберите cервер*</option>
        <script>
          $.getJSON(ROOT_URL + "/api/getServers.php", function (data) {
            $.each(data, function (key, value) {
              if (value["id"] == getUrlParameter('server')) {
                $('#server').append("<option value='" + value["id"] + "' selected>" + value["name"] + "</option>");
                selectTeacher();
              } else {
                $('#server').append("<option value='" + value["id"] + "'>" + value["name"] + "</option>");
              }
            });
          });
        </script>
      </select>
    </div>

    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text" for="teacher">
          <i class="fas fa-user"></i>
        </label>
      </div>
      <select class="form-control selector" name="teacher" id="teacher" required>
        <option value=''>Выберите преподавателя*</option>
        <script>
          function selectTeacher() {
            const serverId = $('select[name="server"]').val();
            $('#teacher').empty();
            $('#teacher').append("<option value=''>Выберите преподавателя</option>");
            $.getJSON(ROOT_URL + "/api/getTeachers.php/?server=" + serverId, function (data) {
              $.each(data, function (key, value) {
                if (value["teacher_id"] == getUrlParameter('teacher')) {
                  $('#teacher').append("<option value='" + value["teacher_id"] + "' selected>" + value["fullname"] + "</option>");
                } else {
                  $('#teacher').append("<option value='" + value["teacher_id"] + "'>" + value["fullname"] + "</option>");
                }
              });
            });
          }
        </script>
      </select>
    </div>

    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text" for="start_year">
          <i class="fas fa-calendar-alt"></i>
        </label>
      </div>
      <select class="form-control selector" name="start_year" id="start_year">
        <option value=''>Выберите учебный год</option>
        <script>
          $.getJSON(ROOT_URL + "/api/getAcademicYears.php", function (data) {
            $.each(data, function (key, value) {
              if (value["start_year"] == getUrlParameter('start_year')) {
                $('#start_year').append("<option value='" + value["start_year"] + "' selected>" + value["start_year"] + ' - ' + value["end_year"] + "</option>");
              } else {
                $('#start_year').append("<option value='" + value["start_year"] + "'>" + value["start_year"] + ' - ' + value["end_year"] + "</option>");
              }
            });
          });
        </script>
      </select>
    </div>

    <button type="submit" class="btn btn-primary" id='showReport' style="margin-right: 5px; margin-top: 5px;">Показать отчёт</button>
    <button type="button" class="btn btn-outline-secondary" id='clearParameters' style="margin-top: 5px;">Очистить параметры</button>
</div>

<script>
  function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
  };

  $("#clearParameters").click(function () {
    location.replace(location.origin + location.pathname);
  });
</script>

<br>
<br>

<?php
if (isset($_GET["server"])) {
    require 'db/config.php';
    require 'db/selectDB.php';
}

$sql1 = "SELECT
              teacher.id AS 'teacher_id',
              $course.id AS 'course_id',
              $course.fullname AS 'course_name',
              COUNT(DISTINCT student.id) AS 'students_quantity',
              COUNT(DISTINCT $grade_items.id) AS 'elements_quantity'
            FROM
              $user teacher,
              $user student,
              $role_assignments teacher_role,
              $role_assignments student_role,
              $context,
              $grade_items RIGHT JOIN $course ON ($grade_items.courseid = $course.id AND $grade_items.itemtype = 'mod')
            WHERE
              teacher.id = teacher_role.userid
              AND student.id = student_role.userid
              AND teacher_role.contextid = $context.id
              AND student_role.contextid = $context.id
              AND $context.instanceid = $course.id
              AND $context.contextlevel = '50'
              AND teacher_role.roleid = '3'
              AND student_role.roleid = '5'
            GROUP BY course_id, teacher_id
            HAVING teacher.id = " . $_GET["teacher"];
$result1 = $conn->query($sql1);
$rows1 = array();
while ($row = mysqli_fetch_assoc($result1)) {
    $rows1[] = $row;
}
$conn->close();

$sql = "SELECT
          disciplines_linking.moodle_discipline_id AS 'moodle_discipline_id',
          disciplines_linking.kemsu_speciality_id AS 'kemsu_speciality_id',
          disciplines_linking.speciality_name AS 'speciality_name',
          disciplines_linking.start_year AS 'start_year',
          disciplines_linking.end_year AS 'end_year',
          disciplines_linking.term AS 'term',
          disciplines_linking.report_type AS 'report_type',
          COUNT(DISTINCT elements_linking.id) AS 'linked_elements_quantity',
          disciplines_linking.server_id AS 'server_id',
          teachers_linking.moodle_teacher_id AS 'moodle_teacher_id'
        FROM
          teachers_linking, disciplines_linking LEFT JOIN elements_linking ON disciplines_linking.id = elements_linking.disciplines_linking_id
        WHERE disciplines_linking.teachers_linking_id = teachers_linking.id
        GROUP BY disciplines_linking.kemsu_discipline_id, moodle_teacher_id";
if ($_GET["server"] != 0) {
    $sql = $sql . " HAVING server_id = " . $_GET["server"];
}
if ($_GET["teacher"] != 0) {
    $sql = $sql . " AND moodle_teacher_id = " . $_GET["teacher"];
}
if ($_GET["start_year"] != 0) {
    $sql = $sql . " AND disciplines_linking.start_year = " . $_GET["start_year"];
}
$result2 = $systemConn->query($sql);
$rows2 = array();
while ($row = mysqli_fetch_assoc($result2)) {
    $rows2[] = $row;
}
$systemConn->close();

for ($i = 0; $i < sizeof($rows2); $i++) {
    for ($j = 0; $j < sizeof($rows1); $j++) {
        if ($rows2[$i]['moodle_teacher_id'] == $rows1[$j]['teacher_id'] && $rows2[$i]['moodle_discipline_id'] == $rows1[$j]['course_id']) {
            $rows2[$i]['course_name'] = $rows1[$j]['course_name'];
            $rows2[$i]['students_quantity'] = $rows1[$j]['students_quantity'];
            $rows2[$i]['elements_quantity'] = $rows1[$j]['elements_quantity'];
        }
    }
}

if (sizeof($rows2) > 0) {
    echo '<table class="table table-hover">
          <thead>
            <tr>
              <th scope=col>#</th>
              <th scope=col>Дисциплина</th>
              <th scope=col>Направление</th>
              <th scope=col>Учебный год</th>
              <th scope=col>Семестр</th>
              <th scope=col>Отчётность</th>
              <th scope=col>Число слушателей</th>
              <th scope=col>Число заданий</th>
              <th scope=col>Число связанных заданий</th>
            </tr>
          </thead>
          <tbody>';
    $num = 0;
    foreach ($rows2 as $row) {
        $num = $num + 1;
        echo "<tr>
                <td>" . $num . "</td>
                <td>" . $row["course_name"] . "</td>
                <td>" . $row["speciality_name"] . "</td>
                <td>" . $row["start_year"] . ' - ' . $row["end_year"] . "</td>
                <td>" . $row["term"] . "</td>
                <td>" . $row["report_type"] . "</td>
                <td>" . $row["students_quantity"] . "</td>
                <td>" . $row["elements_quantity"] . "</td>
                <td>" . $row["linked_elements_quantity"] . "</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "Нет результатов";
}
?>

<?php include('footer.php');?>