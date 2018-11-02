<?php

if (isset($_POST['id'])) {
    require '../db/config.php';
    $sql = "DELETE FROM admins WHERE id =" . $_POST['id'];
    
    if ($systemConn->query($sql) === TRUE) {
        echo "Администратор удален";
        $systemConn->close();
        header('Location: ../manage-admins.php');
        exit;
    } else {
        echo "Ошибка: " . $sql . "<br />" . $systemConn->error;
    }
} else {
    echo 'Ошибка параметров';
}

?>