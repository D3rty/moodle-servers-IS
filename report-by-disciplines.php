<?php include('header.php');?>

<script>
  $(document).attr("title", "Отчёт по дисциплинам - ИС «Серверы Moodle»");
  $('#collapse-reports').addClass("show");
  $('#report-by-disciplines').addClass("active");
  $('#currentDirection').append("ИС Серверы Moodle > 📊 Подсистема Отчёты");
</script>

<h1>Отчёт по дисциплинам</h1>
<br>

<div class="superklass" style="max-width: 70%;">
  <form>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text" for="server">
          <i class="fas fa-server"></i>
        </label>
      </div>
      <select class="form-control selector" name="server" id="server" onchange="selectSpeciality()" required>
        <option value=''>Выберите cервер*</option>
        <script>
          $.getJSON(ROOT_URL + "/api/getServers.php", function (data) {
            $.each(data, function (key, value) {
              if (value["id"] == getUrlParameter('server')) {
                $('#server').append("<option value='" + value["id"] + "' selected>" + value["name"] + "</option>");
                selectSpeciality();
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
        <label class="input-group-text" for="speciality">
          <i class="fas fa-graduation-cap"></i>
        </label>
      </div>
      <select class="form-control selector" name="speciality" id="speciality">
        <option value="">Выберите специальность</option>
        <script>
          function selectSpeciality() {
            const serverId = $('select[name="server"]').val();
            $('#speciality').empty();
            $('#speciality').append("<option value=''>Выберите специальность</option>");
            $.getJSON(ROOT_URL + "/api/getSpecialities.php?server=" + serverId, function (data) {
              $.each(data, function (key, value) {
                if (value["kemsu_speciality_id"] == getUrlParameter('speciality')) {
                  $('#speciality').append("<option value='" + value["kemsu_speciality_id"] + "' selected>" + value["speciality_name"] + "</option>");
                } else {
                  $('#speciality').append("<option value='" + value["kemsu_speciality_id"] + "'>" + value["speciality_name"] + "</option>");
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

    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text" for="semester">
          <i class="fas fa-calendar-plus"></i>
        </label>
      </div>
      <select class="form-control selector" name="semester" id="semester" onchange="">
        <option value="">Выберите семестр</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary" id='showReport' style="margin-right: 5px; margin-top: 5px;">Показать отчёт</button>
    <button type="button" class="btn btn-outline-secondary" id='clearParameters' style="margin-top: 5px;">Очистить параметры</button>

  </form>
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
            $course.id AS 'course_id',
            $course.fullname AS 'course_name',
            CONCAT(teacher.lastname,' ',teacher.firstname) AS 'teacher_name',
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
          GROUP BY course_id";
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
              disciplines_linking.server_id AS 'server_id'
            FROM
              disciplines_linking LEFT JOIN elements_linking ON disciplines_linking.id = elements_linking.disciplines_linking_id
            GROUP BY disciplines_linking.kemsu_discipline_id";
if ($_GET["server"] != 0) {
    $sql = $sql . " HAVING server_id = " . $_GET["server"];
}
if ($_GET["speciality"] != 0) {
    $sql = $sql . " AND kemsu_speciality_id = " . $_GET["speciality"];
}
if ($_GET["start_year"] != 0) {
    $sql = $sql . " AND disciplines_linking.start_year = " . $_GET["start_year"];
}
if ($_GET["semester"] != 0) {
    $sql = $sql . " AND term = " . $_GET["semester"];
}

$result2 = $systemConn->query($sql);
$rows2 = array();
while ($row = mysqli_fetch_assoc($result2)) {
    $rows2[] = $row;
}
$systemConn->close();

for ($i = 0; $i < sizeof($rows2); $i++) {
    for ($j = 0; $j < sizeof($rows1); $j++) {
        if ($rows2[$i]['moodle_discipline_id'] == $rows1[$j]['course_id']) {
            $rows2[$i]['course_name'] = $rows1[$j]['course_name'];
            $rows2[$i]['teacher_name'] = $rows1[$j]['teacher_name'];
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
            <th scope=col>Преподаватель</th>
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
              <td>" . $row["teacher_name"] . "</td>
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