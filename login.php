<?php

require_once 'db/config.php';

$username = $password = "";
$username_err = $password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(empty(trim($_POST["username"]))) {
        $username_err = 'Пожалуйтса введите логин';
    } else {
        $username = trim($_POST["username"]);
    }
    if(empty(trim($_POST['password']))) {
        $password_err = 'Пожалуйтса введите пароль';
    } else {
        $password = trim($_POST['password']);
    }
    if(empty($username_err) && empty($password_err)) {
        $sql = "SELECT email, password FROM admins WHERE email = ?";
        if($stmt = mysqli_prepare($systemConn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1) {                    
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            $_SESSION['username'] = $username;      
                            header("location: index.php");
                        } else {
                            $password_err = 'Пароль введен неверно';
                        }
                    }
                } else {
                    $username_err = 'Такой логин не найден';
                }
            } else {
                echo "Что-то пошло не так. Попробуйте еще раз.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($systemConn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Вход в ИС "Сервера Moodle"</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/signin.css">
</head>

<body class="text-center">
    <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]); ?>" method="post">
        <img class="mb-4" src="img/logo.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">ИС «Серверы Moodle»</h1>
        <label for="inputUsername" class="sr-only">E-mail</label>
        <input type="text" id="inputUsername" name="username" class="form-control" placeholder="E-mail" required autofocus value="<?php echo $username; ?>">
        <span class="help-block">
            <?php echo $username_err; ?>
        </span>
        <label for="inputPassword" class="sr-only">Пароль</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Пароль" required>
        <span class="help-block">
            <?php echo $password_err; ?>
        </span>
        <input type="submit" class="btn btn-lg btn-info btn-block" value="Войти">
        <p class="mt-5 mb-3 text-muted">Кемеровский государственный университет</p>
    </form>
</body>

</html>