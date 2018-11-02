<?php

switch($_GET["server"]){
    case '1':
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "moodle";  
        $dbprefix = "mdl_";
        break;
    case '2':
        $servername = "anothermoodlesrver.xyz";
        $username = "u907256701_dazam";
        $password = "PuHaPyqeWe";
        $dbname = "u907256701_jypyz";  
        $dbprefix = "jkzk_";
        break;
    default:
        echo 'Ошибка. Такой сервер не зарегистрирован';
}

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
} 

$course = $dbprefix.course;
$user = $dbprefix.user;
$role_assignments = $dbprefix.role_assignments;
$context = $dbprefix.context;
$course = $dbprefix.course;
$grade_items = $dbprefix.grade_items;

?>