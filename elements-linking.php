<?php include('header.php');?>

<script>
  $(document).attr("title", 'Привязка элементов оценивания - ИС «Серверы Moodle»');
  $('#collapse-linking').addClass("show");
  $('#elements-linking').addClass("active");
  $('#currentDirection').append("ИС Серверы Moodle > 📥 Подсистема Импорт");
</script>

<h1>Привязка элементов оценивания</h1>
<br>

<form action="api/postElements.php" method="POST" class="linking-form">
  <div class="row selector-group">
    <div class="col">
      <h5 class="selector-group-name">Рейтинг</h5>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="rating_faculty">
            <i class="fas fa-graduation-cap"></i>
          </label>
        </div>
        <select class="form-control" name="rating_faculty" id="rating_faculty" onchange="selectRatingTeacher()" required>
          <option value="0">Выберите институт</option>
          <script>
            $.getJSON("https://api.kemsu.ru/api-dev/moodle/faculties", function (data) {
              $.each(data, function (key, value) {
                $('#rating_faculty').append("<option value='" + value["facultyId"] + "'>" + value["facultyName"] + "</option>");
              });
            });
          </script>
        </select>
      </div>

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="rating_teacher">
            <i class="fas fa-user"></i>
          </label>
        </div>
        <select class="form-control" name="rating_teacher" id="rating_teacher" onchange="selectRatingDiscipline()" required>
          <option value="0">Выберите преподавателя</option>
          <script>
            function selectRatingTeacher() {
              $('#rating_teacher').empty();
              $('#rating_teacher').append("<option value=''>Выберите преподавателя</option>");
              const ratingFacultyId = $('select[name="rating_faculty"]').val();
              $.getJSON("https://api.kemsu.ru/api-dev/moodle/getPrepList?facultyId=" + ratingFacultyId, function (data) {
                $.each(data.result, function (key, value) {
                  $('#rating_teacher').append("<option value='" + value["USER_ID"] + "'>" + value["FIO"] + "</option>");
                });
              });
            }
          </script>
        </select>
      </div>

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="rating_discipline">
            <i class="fas fa-book"></i>
          </label>
        </div>
        <select class="form-control" name="rating_discipline" id="rating_discipline" onchange="selectRatingElement()" required>
          <option value="0">Выберите дисциплину</option>
          <script>
            function selectRatingDiscipline() {
              $('#rating_discipline').empty();
              $('#rating_discipline').append("<option value=''>Выберите дисциплину</option>");
              const ratingTeacherId = $('select[name="rating_teacher"]').val();
              $.getJSON("https://api.kemsu.ru/api-dev/moodle/getPrepDisciplineList/?userId=" + ratingTeacherId, function (data) {
                $.each(data.disciplineList, function (key, value) {
                  let groups = '';
                  Object.keys(value["GROUP_LIST"]).forEach(function (key) {
                    groups = groups + value["GROUP_LIST"][key]['GROUP_NAME'] + ' ';
                    console.log(groups);
                  });
                  if (groups != '') {
                    groups = ' - ' + groups;
                  }
                  $('#rating_discipline').append("<option value='" + value["DISCIPLINE_ID"] + "'>" + value["START_YEAR"] + '-' + value["END_YEAR"] + ' > ' + value["DISCIPLINE"] + groups + "</option>");
                });
              });
            }
          </script>
        </select>
      </div>

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="rating_element">
            <i class="fas fa-list-ol"></i>
          </label>
        </div>
        <select class="form-control" name="rating_element" id="rating_element" required>
          <option value="0">Выберите элемент оценивания</option>
          <script>
            function selectRatingElement() {
              $('#rating_element').empty();
              $('#rating_element').append("<option value=''>Выберите элемент оценивания</option>");
              var ratingDisciplineId = $('select[name="rating_discipline"]').val();
              $.getJSON("https://api.kemsu.ru/api-dev/moodle/getBsodElementsListByDekDisciplineId/" + ratingDisciplineId, function (data) {
                $.each(data.result, function (key, value) {
                  $('#rating_element').append("<option value='" + value["BSOD_ELEMENT_ID"] + "'>" + value["BSOD_ELEMENT_NAME"] + ': ' + value["COUNT"] + 'шт.' + "</option>");
                });
              });
            }
          </script>
        </select>
      </div>

    </div>
    <div class="col">
      <h5 class="selector-group-name">Moodle</h5>

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="server">
            <i class="fas fa-server"></i>
          </label>
        </div>
        <select class="form-control" name="server" id="server" onchange="selectMoodleTeacher()" required>
          <option value=''>Выберите cервер</option>
          <script>
            $.getJSON(ROOT_URL + "/api/getServers.php", function (data) {
              $.each(data, function (key, value) {
                $('#server').append("<option value='" + value["id"] + "'>" + value["name"] + "</option>");
              });
            });
          </script>
        </select>
      </div>

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="linked_teacher">
            <i class="fas fa-user"></i>
          </label>
        </div>
        <select class="form-control" name="linked_teacher" id="linked_teacher" onchange="selectMoodleDiscipline()" required>
          <option value=''>Выберите преподавателя</option>
          <script>
            function selectMoodleTeacher() {
              const serverId = $('select[name="server"]').val();
              $('#linked_teacher').empty();
              $('#linked_teacher').append("<option value=''>Выберите преподавателя</option>");
              $.getJSON(ROOT_URL + "/api/getLinkedTeachers.php?server=" + serverId, function (data) {
                $.each(data, function (key, value) {
                  $('#linked_teacher').append("<option value='" + value["id"] + "'>" + value["fullname"] + "</option>");
                });
              });
            }
          </script>
        </select>
      </div>

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="moodle_discipline">
            <i class="fas fa-book"></i>
          </label>
        </div>
        <select class="form-control" name="moodle_discipline" id="moodle_discipline" onchange="selectMoodleElement()" required>
          <option value="0">Выберите дисциплину</option>
          <script>
            function selectMoodleDiscipline() {
              const serverId = $('select[name="server"]').val();
              const teacherLinkingId = $('select[name="linked_teacher"]').val();
              $('#moodle_discipline').empty();
              $('#moodle_discipline').append("<option value=''>Выберите дисциплину</option>");
              $.getJSON(ROOT_URL + "/api/getLinkedDisciplines.php/?server=" + serverId + "&teachers_linking_id=" + teacherLinkingId, function (data) {
                $.each(data, function (key, value) {
                  $('#moodle_discipline').append("<option value='" + value["moodle_discipline_id"] + "'>" + value["disciplineName"] + "</option>");
                });
              });
            }
          </script>
        </select>
      </div>

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="moodle_element">
            <i class="fas fa-list-ol"></i>
          </label>
        </div>
        <select class="form-control" name="moodle_element" id="moodle_element" required>
          <option value="0">Выберите элемент оценивания</option>
          <script>
            function selectMoodleElement() {
              const serverId = $('select[name="server"]').val();
              const moodleDisciplineId = $('select[name="moodle_discipline"]').val();
              $('#moodle_element').empty();
              $('#moodle_element').append("<option value=''>Выберите элемент оценивания</option>");
              $.getJSON(ROOT_URL + "/api/getGradeitems.php/?server=" + serverId + "&moodle_discipline=" + moodleDisciplineId, function (data) {
                $.each(data, function (key, val) {
                  $('#moodle_element').append("<option value='" + val["item_id"] + "'>" + val["itemname"] + "</option>");
                });
              });
            }
          </script>
        </select>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-center" style="margin-top:6px;">
    <button type="submit" class="btn btn-primary" style="width:200px; margin-top:8px; margin-bottom:9px;">Привязать</button>
  </div>
</form>
<br>

<h5>Привязанные элементы</h5>

<?php
    $url = ROOT_URL . '/api/getLinkedElements.php'; 
    $dataForElementsTable = file_get_contents($url); 
    $elementsTable = json_decode($dataForElementsTable, true);

    if (sizeof($elementsTable)) {
      echo '<table class="table table-hover">
      <thead>
        <tr>
          <th scope=col style="width:5%;">#</th>
          <th scope=col>Сервер</th>
          <th scope=col>Дисциплина</th>
          <th scope=col>Элемент из Рейтинга (ID)</th>
          <th scope=col>Элемент из Moodle (ID)</th>
          <th scope=col style="width:10%;"></th>
        </tr>
      </thead>
      <tbody>';

        $num = 0;
        foreach ($elementsTable as $row) {
          $num = $num + 1;
            echo "<tr>
            <td>".$num."</td>
            <td>".$row["server_name"]."</td>
            <td>".$row["disciplineName"]."</td>
            <td>".$row["kemsu_element_name"]. ' (' .$row["kemsu_element_id"]. ')' ."</td>
            <td>".$row["moodle_element_name"]. ' (' .$row["moodle_element_id"]. ')' ."</td>
            <td><button class='btn btn-outline-danger btn-edit' value='deleteRow' id='".$row["id"]."'><i class='fas fa-times'></i></button></td>
            </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "Нет результатов";
    }
    ?>

<script>
  $("#linking-alert").hide();

  $("button[value='deleteRow']").click(function () {
    let confirmation = confirm("Вы точно хотите удалить эту привязку?");
    if (confirmation == true) {
      let rowId = $(this).attr("id");
      console.log(rowId);
      $.post("api/deleteLinkedElement.php", { id: rowId }).done(function (data) { location.reload(); });
    }
  });
</script>

<?php include('footer.php');?>