<?php include('header.php');?>

<script>
    $(document).attr("title", "Создать ссылку для экспорта - ИС «Серверы Moodle»");
    $('#collapse-export').addClass("show");
    $('#create-export-url').addClass("active");
    $('#currentDirection').append("ИС Серверы Moodle > 📤 Подсистема Отчёты");
</script>

<h1>Создать ссылку для экспорта</h1>
<br>

<div style="max-width: 70%;">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="server">
                <i class="fas fa-server"></i>
            </label>
        </div>
        <select class="form-control selector" name="server" id="server" onchange="selectDiscipline()" required>
            <option value=''>Выберите сервер</option>
            <script>
                const url = ROOT_URL + "/api/getServers.php";
                $.getJSON(url, function (data) {
                    $.each(data, function (key, value) {
                        $('#server').append("<option value='" + value["id"] + "'>" + value["name"] + "</option>");
                    });
                });
            </script>
        </select>
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="discipline">
                <i class="fas fa-book"></i>
            </label>
        </div>
        <select class="form-control selector" name="discipline" id="discipline" onchange="selectStudent()" required>
            <option value=''>Выберите дисциплину</option>
            <script>
                function selectDiscipline() {
                    const serverId = $('select[name="server"]').val();
                    const url = ROOT_URL + "/api/getDisciplines.php/?server=" + serverId;
                    $('#discipline').empty();
                    $('#discipline').append("<option value=''>Выберите дисциплину</option>");
                    $.getJSON(url, function (data) {
                        $.each(data, function (key, value) {
                            $('#discipline').append("<option value='" + value["course_id"] + "'>" + value["course_fullname"] + "</option>");
                        });
                    });
                }
            </script>
        </select>
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="student">
                <i class="fas fa-user"></i>
            </label>
        </div>
        <select class="form-control selector" name="student" id="student">
            <option value=''>Выберите обучающегося</option>
            <script>
                function selectStudent() {
                    const serverId = $('select[name="server"]').val();
                    const disciplineId = $('select[name="discipline"]').val();
                    const url = ROOT_URL + "/api/getStudents.php/?server=" + serverId + "&disciplineid=" + disciplineId;
                    $('#student').empty();
                    $('#student').append("<option value=''>Выберите обучающегося</option>");
                    $.getJSON(url, function (data) {
                        $.each(data, function (key, value) {
                            $('#student').append("<option value='" + value["id"] + "'>" + value["fullname"] + "</option>");
                        });
                    });
                }
            </script>
        </select>
    </div>

    <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" id="XMLradio" name="reportFormat" class="custom-control-input" value="XML" checked>
        <label class="custom-control-label" for="XMLradio">XML</label>
    </div>
    <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" id="JSONradio" name="reportFormat" class="custom-control-input" value="JSON">
        <label class="custom-control-label" for="JSONradio">JSON</label>
    </div>
    <button type="button" class="btn btn-primary" id='createUrl' style="margin-left: 0px;">Создать ссылку</button>

    <br>
    <br>
    <br>

    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
                <i class="fas fa-external-link-alt"></i>
            </span>
        </div>
        <input id="url" name="url" type="text" class="form-control" placeholder="Ссылка для экспорта оценок" aria-label="Ссылка для экспорта"
            aria-describedby="basic-addon2">
        <div class="input-group-append">
            <a id='copyUrl' tabindex="0" class="btn btn-outline-info" role="button" data-toggle="popover" data-trigger="focus" data-content="Скопировано"
                data-placement="bottom">Копировать</a>
        </div>
    </div>
</div>

<script>

    $("#createUrl").click(function () {
        $('[data-toggle="popover"]').popover({
            delay: { "show": 50, "hide": 1000 }
        });

        const selectedServer = $('select[name="server"]').val();
        const selectedDiscipline = $('select[name="discipline"]').val();
        const selectedStudent = $('select[name="student"]').val();
        const selectedReportFormat = $('input[name=reportFormat]:checked').val();

        if (selectedServer != '' && selectedDiscipline != '') {
            $.getJSON(ROOT_URL + "/api/getDomainAndExportToken.php/?server=" + selectedServer, function (data) {
                let domain = data[0]["domain_name"];
                let token = data[0]["export_token"];

                let exportUrl =
                    domain + '/webservice/rest/server.php?' +
                    'wstoken=' + token +
                    '&wsfunction=export_get_gradereport' +
                    '&courseid=' + selectedDiscipline;

                if (selectedStudent != '') {
                    exportUrl += '&userid=' + selectedStudent;
                }
                if (selectedReportFormat == 'JSON') {
                    exportUrl += '&moodlewsrestformat=json';
                }

                $('#url').val(exportUrl);
            });
        }
    });

    //Копировать в буфер обмена
    $("#copyUrl").click(function () {
        const exportUrl = $('input[name="url"]').val();
        const tempElement = document.createElement('textarea');
        tempElement.value = exportUrl;
        tempElement.setAttribute('readonly', '');
        tempElement.style.position = 'absolute';
        tempElement.style.left = '-9999px';
        document.body.appendChild(tempElement);
        const selected =
            document.getSelection().rangeCount > 0
                ? document.getSelection().getRangeAt(0)
                : false;
        tempElement.select();
        document.execCommand('copy');
        document.body.removeChild(tempElement);
        if (selected) {
            document.getSelection().removeAllRanges();
            document.getSelection().addRange(selected);
        }
    });
</script>

<?php include('footer.php');?>