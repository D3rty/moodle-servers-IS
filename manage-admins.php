<?php include('header.php');?>

<script>
    $(document).attr("title", "Управление администраторами - ИС «Серверы Moodle»");
    $('#manage-admins').addClass("active");
    $('#currentDirection').append("ИС Серверы Moodle");
</script>

<h1>Управление администраторами</h1>
<br>

<?php
$url = ROOT_URL . '/api/getAdmins.php';
$dataForAdminsTable = file_get_contents($url);
$adminsTable = json_decode($dataForAdminsTable, true);

if (sizeof($adminsTable) > 0) {
    echo '<table class="table table-hover">
            <thead>
            <tr>
                <th scope=col>#</th>
                <th scope=col>Email</th>
                <th scope=col>ФИО</th>
                <th scope=col>Должность</th>
                <th scope=col></th>
            </tr>
            </thead>
            <tbody>';
    $num = 0;
    foreach ($adminsTable as $row) {
        $num = $num + 1;
        echo "<tr>
                <td>" . $num . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["last_name"] . ' ' . $row["first_name"] . ' ' . $row["patronymic"] . "</td>
                <td>" . $row["position"] . "</td>
                <td><button type='button' class='btn btn-outline-warning btn-edit' data-toggle='modal' 
                data-target='#editModal' value='edit' id='" . $row["id"] . "'><i class='fas fa-pencil-alt'></i></button></td>
            </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "Нет результатов";
}
?>

<button type="button" class="btn btn-primary" style="margin-top: 5px;" data-toggle="modal" data-target="#registerModal">Добавить администратора</button>
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="padding-left: 10px; padding-right: 10px;">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Добавить администратора 👨🏻‍💻</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="api/postAdmin.php" method="post">
                    <div class="form-group">
                        <label for="inputEmail">Введите email</label>
                        <input type="email" class="form-control" id="inputEmail" name="inputEmail" value="<?php echo $username; ?>" placeholder="Email"
                            required>
                        <small class="help-block" id="emailHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Придумайте пароль не менее 6 символов</label>
                        <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Пароль" required>
                        <small class="help-block" id="passwordHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="inputPasswordConfirm">Повторите пароль</label>
                        <input type="password" class="form-control" id="inputPasswordConfirm" name="inputPasswordConfirm" placeholder="Пароль" required>
                        <small class="help-block" id="confirmHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="inputLastName">Введите фамилию</label>
                        <input type="text" class="form-control" id="inputLastName" name="inputLastName" value="<?php echo $_POST["inputLastName"]; ?>" placeholder="Фамилия" required>
                    </div>
                    <div class="form-group">
                        <label for="inputFirstName">Введите имя</label>
                        <input type="text" class="form-control" id="inputFirstName" name="inputFirstName" value="<?php echo $_POST["inputFirstName"]; ?>" placeholder="Имя" required>
                    </div>
                    <div class="form-group">
                        <label for="inputPatronymic">Введите отчество</label>
                        <input type="text" class="form-control" id="inputPatronymic" name="inputPatronymic" value="<?php echo $_POST["inputPatronymic"]; ?>" placeholder="Отчество" required>
                    </div>
                    <div class="form-group">
                        <label for="inputPosition">Введите должность</label>
                        <input type="text" class="form-control" id="inputPosition" name="inputPosition" value="<?php echo $_POST["inputPosition"]; ?>" placeholder="Должность" required>
                    </div>
                    <button type="submit" class="btn btn-info btn-lg btn-block" style="margin-top: 20px;">Зарегистрировать</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Редактировать</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="api/updateAdmin.php" method="post">
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="editId">
                                <i class="fas fa-list-ol"></i>
                            </label>
                        </div>
                        <input type="text" class="form-control" id="editId" placeholder="ID" name="id" readonly>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="editEmail">
                                <i class="fas fa-at"></i>
                            </label>
                        </div>
                        <input type="email" class="form-control" id="editEmail" placeholder="Введите email" name="email" readonly>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="editLastName">
                                <i class="fas fa-user"></i>
                            </label>
                        </div>
                        <input type="text" class="form-control" id="editLastName" placeholder="Введите фамилию" name="lastName" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="editFirstName">
                                <i class="fas fa-user"></i>
                            </label>
                        </div>
                        <input type="text" class="form-control" id="editFirstName" placeholder="Введите имя" name="firstName" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="editPatronymic">
                                <i class="fas fa-user"></i>
                            </label>
                        </div>
                        <input type="text" class="form-control" id="editPatronymic" placeholder="Введите отчество" name="patronymic" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="editPosition">
                                <i class="fas fa-briefcase"></i>
                            </label>
                        </div>
                        <input type="text" class="form-control" id="editPosition" placeholder="Введите должность" name="position" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Изменить</button>
                    <button type="button" class="btn btn-outline-danger" id="deleteAdmin">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("button[value='edit']").click(function () {
        let adminId = $(this).attr("id");
        $.post("api/getAdmin.php", {
            id: adminId
        })
            .done(function (data) {
                var parsed = JSON.parse(data);
                $('#editId').val(parsed[0]['id']);
                $('#editEmail').val(parsed[0]['email']);
                $('#editLastName').val(parsed[0]['last_name']);
                $('#editFirstName').val(parsed[0]['first_name']);
                $('#editPatronymic').val(parsed[0]['patronymic']);
                $('#editPosition').val(parsed[0]['position']);
            });

        $("#deleteAdmin").click(function () {
            $.post("api/deleteAdmin.php", { id: adminId }).done(function (data) { location.reload(); });
        });
    });

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };

    $(document).ready(function () {
        if (getUrlParameter('register_error') != '') {
            $("#registerModal").modal('show');

            $('#inputEmail').val(getUrlParameter('email'));
            $('#inputLastName').val(getUrlParameter('last_name'));
            $('#inputFirstName').val(getUrlParameter('first_name'));
            $('#inputPatronymic').val(getUrlParameter('patronymic'));
            $('#inputPosition').val(getUrlParameter('position'));

            switch (getUrlParameter('register_error')) {
                case 'empty_email':
                    $("#emailHelp").append("Пожалуйста введите email");
                    break;
                case 'already_taken':
                    $("#emailHelp").append("Такой email уже занят");
                    break;
                case 'empty_password':
                    $("#passwordHelp").append("Пожалуйста введите пароль");
                    break;
                case 'short_password':
                    $("#passwordHelp").append("Пароль должен быть не короче 6 символов");
                    break;
                case 'empty_confirm':
                    $("#confirmHelp").append("Пожалуйста подтвердите пароль");
                    break;
                case 'different_passwords':
                    $("#confirmHelp").append("Пароли не совпадают");
                    break;
            }
        }
    });
</script>

<?php include('footer.php');?>