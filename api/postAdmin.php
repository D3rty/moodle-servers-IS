<?php
require '../db/config.php';

if (!mysqli_set_charset($systemConn, "utf8")) {
	printf("Ошибка при загрузке набора символов utf8: %s\n", mysqli_error($systemConn));
	exit();
} else {
	mysqli_character_set_name($systemConn);
}

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$err_url = "../manage-admins.php?email=" . $_POST["inputEmail"] .
                "&last_name=" . $_POST["inputLastName"] .
                "&first_name=" . $_POST["inputFirstName"] .
                "&patronymic=" . $_POST["inputPatronymic"] .
                "&position=" . $_POST["inputPosition"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty(trim($_POST["inputEmail"]))) {
		$username_err = "Пожалуйста введите email";
		header("Location: $err_url&register_error=empty_email");
	} else {
		$sql = "SELECT id FROM admins WHERE email = ?";
		if ($stmt = mysqli_prepare($systemConn, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = trim($_POST["inputEmail"]);
			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_store_result($stmt);
				if (mysqli_stmt_num_rows($stmt) == 1) {
					$username_err = "Такой email уже занят";
					header("Location: $err_url&register_error=already_taken");
				} else {
					$username = trim($_POST["inputEmail"]);
				}
			} else {
				echo "Что-то не так с email. Попробуйте еще раз";
				print_r($stmt);
			}
		}
		mysqli_stmt_close($stmt);
	}

	if (empty(trim($_POST['inputPassword']))) {
		$password_err = "Пожалуйста введите пароль";
		header("Location: $err_url&register_error=empty_password");
	} elseif (strlen(trim($_POST['inputPassword'])) < 6) {
		$password_err = "Пароль должен быть не короче 6 символов";
		header("Location: $err_url&register_error=short_password");
	} else {
		$password = trim($_POST['inputPassword']);
		if (empty(trim($_POST["inputPasswordConfirm"]))) {
			$confirm_password_err = 'Пожалуйста подтвердите пароль';
			header("Location: $err_url&register_error=empty_confirm");
		} else {
			$confirm_password = trim($_POST['inputPasswordConfirm']);
			if ($password != $confirm_password) {
				$confirm_password_err = 'Пароли не совпадают';
				header("Location: $err_url&register_error=different_passwords");
			}
		}
	}

	if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
		$sql = "INSERT INTO admins (email, password, last_name, first_name, patronymic, position) 
                    VALUES (?, ?, ?, ?, ?, ?)";
		if ($stmt = mysqli_prepare($systemConn, $sql)) {
			mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $last_name, $first_name, $patronymic, $position);
			$param_username = $username;
			$param_password = password_hash($password, PASSWORD_DEFAULT);
			$last_name = $_POST["inputLastName"];
			$first_name = $_POST["inputFirstName"];
			$patronymic = $_POST["inputPatronymic"];
			$position = $_POST["inputPosition"];
			if (mysqli_stmt_execute($stmt)) {
				header("Location: ../manage-admins.php?register=success");
			} else {
				echo "Что-то не так с паролем. Попробуйте еще раз";
				print_r($stmt);
			}
		}
		mysqli_stmt_close($stmt);
	}
	mysqli_close($systemConn);
}

?>