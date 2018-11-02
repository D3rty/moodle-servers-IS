<?php

if(isset($_POST["id"])) {
    require '../db/config.php';
    $id = $_POST["id"];
    $lastName = $_POST["lastName"];
    $firstName = $_POST["firstName"];
    $patronymic = $_POST["patronymic"];
    $position = $_POST["position"];

    $sql = "UPDATE admins
            SET last_name = '$lastName',
            first_name = '$firstName',
            patronymic = '$patronymic',
            position = '$position'
            WHERE id = $id";
    
    if ($systemConn->query($sql) === TRUE) {
        echo "Информация обновлена";
        header('Location: ../manage-admins.php');
    } else {
        echo "Error: " . $sql . "<br>" . $systemConn->error;
    }

    $systemConn->close();
} else {
    echo 'Ошибка параметров';
}

?>