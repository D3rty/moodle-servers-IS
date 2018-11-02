<?php

if (isset($_GET["server"])) {
    require '../db/config.php';
    $serverId = $_GET["server"];
    $sql = "SELECT domain_name, export_token
            FROM servers
            WHERE id = $serverId";
    
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