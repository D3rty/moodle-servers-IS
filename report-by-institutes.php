<?php include('header.php');?>

<script>
  $(document).attr("title", "Отчёт по институтам - ИС «Серверы Moodle»");
  $('#collapse-reports').addClass("show");
  $('#report-by-institutes').addClass("active");
  $('#currentDirection').append("ИС Серверы Moodle > 📊 Подсистема Отчёты");
</script>

<h1>Отчёт по институтам</h1>
<br>

<div class="superklass" style="max-width: 50%;">
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
              $('#start_year').append("<option value='" + value["start_year"] + "' selected>" + value["start_year"] + "</option>");
            } else {
              $('#start_year').append("<option value='" + value["start_year"] + "'>" + value["start_year"] + "</option>");
            }
          });
        });
      </script>
    </select>
  </div>

  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <label class="input-group-text" for="end_year">
        <i class="fas fa-calendar-alt"></i>
      </label>
    </div>
    <select class="form-control selector" name="end_year" id="end_year">
      <option value=''>Выберите учебный год</option>
      <script>
        $.getJSON(ROOT_URL + "/api/getAcademicYears.php", function (data) {
          $.each(data, function (key, value) {
            if (value["end_year"] == getUrlParameter('end_year')) {
              $('#end_year').append("<option value='" + value["end_year"] + "' selected>" + value["end_year"] + "</option>");
            } else {
              $('#end_year').append("<option value='" + value["end_year"] + "'>" + value["end_year"] + "</option>");
            }
          });
        });
      </script>
    </select>
  </div>

  <button type="button" class="btn btn-primary" id='showReport' style="margin-right: 5px; margin-top: 5px;">Показать отчёт</button>
  <button type="button" class="btn btn-outline-secondary" id='clearParameters' style="margin-top: 5px;">Очистить параметры</button>
</div>

<br>
<br>

<?php
require 'db/config.php';
if (isset($_GET["start_year"]) & isset($_GET["end_year"])) {
    $start = $_GET["start_year"];
    $end = $_GET["end_year"];
    $sql = "SELECT servers.id, servers.name, COUNT(DISTINCT disciplines_linking.id) quantity1, COUNT(DISTINCT disciplines_linking.teachers_linking_id) quantity2
              FROM servers, disciplines_linking
              WHERE disciplines_linking.server_id = servers.id
              AND disciplines_linking.start_year BETWEEN $start AND $end
              GROUP BY servers.id";
} else {
    $sql = "SELECT servers.id, servers.name, COUNT(DISTINCT disciplines_linking.id) quantity1, COUNT(DISTINCT disciplines_linking.teachers_linking_id) quantity2
            FROM servers, disciplines_linking
            WHERE disciplines_linking.server_id = servers.id
            GROUP BY servers.id";
}

$result = $systemConn->query($sql);
if ($result->num_rows > 0) {
    echo '<table class="table table-hover">
        <thead>
          <tr>
            <th scope=col>#</th>
            <th scope=col>Институт</th>
            <th scope=col>Количество дисциплин</th>
            <th scope=col>Количество преподавателей</th>
          </tr>
        </thead>
        <tbody>';
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row["id"] . "</td>
            <td>" . $row["name"] . "</td>
            <td>" . $row["quantity1"] . "</td>
            <td>" . $row["quantity2"] . "</td>
            </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "Нет результатов";
}
$systemConn->close();
?>

<script>
  $("#showReport").click(function () {
    var start = $('select[name="start_year"]').val();
    var end = $('select[name="end_year"]').val();
    location.replace(location.origin + location.pathname + "?start_year=" + start + "&end_year=" + end);
  });

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

<?php include('footer.php');?>