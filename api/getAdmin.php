<?php

if (isset($_POST["id"])) {
    require '../db/config.php';
    $sql = "SELECT id, email, last_name, first_name, patronymic, position
            FROM admins
            WHERE id = " . $_POST["id"];
    
    $result = $systemConn->query($sql);
    if ($result != FALSE) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        $systemConn->close();
        print json_encode($rows);
    } else {
        echo "Ошибка: " . $sql . "<br />" . $systemConn->error;
    }
} else {
    echo 'Ошибка параметров';
}

?>