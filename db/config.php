<?php

const ROOT_URL = 'http://localhost/ismoodle';

$systemServername = "localhost";
$systemUsername = "root";
$systemPassword = "root";
$systemDBname = "moodle"; 

$systemConn = new mysqli($systemServername, $systemUsername, $systemPassword, $systemDBname);
$systemConn->set_charset("utf8");

    if ($systemConn->connect_error) {
        die("Ошибка соединения: " . $systemConn->connect_error);
    } 

?>