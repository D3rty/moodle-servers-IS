<?php

if (isset($_POST['id'])) {
    require '../db/config.php';
    $sql = "DELETE FROM elements_linking WHERE id =" . $_POST['id'];
    
    if ($systemConn->query($sql) === TRUE) {
        echo "Привязка удалена";
        $systemConn->close();
        header('Location: ../elements-linking.php');
        exit;
    } else {
        echo "Ошибка: " . $sql . "<br>" . $systemConn->error;
    }
} else {
    echo 'Ошибка параметров';
}

?>