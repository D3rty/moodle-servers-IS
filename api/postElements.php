<?php

if(isset($_POST['moodle_element']) & isset($_POST['rating_element']) & isset($_POST['rating_discipline'])) {
    require '../db/config.php';
    $sql = "SELECT id
            FROM disciplines_linking
            WHERE kemsu_discipline_id = " . $_POST['rating_discipline'];
    
    $result = $systemConn->query($sql);
    if ($result != FALSE) {
        $rows = array();
        while($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    } else {
        echo "Ошибка: " . $sql . "<br />" . $systemConn->error;
    }

    $disciplines_linking_id = $rows[0]['id'];
    $kemsu_element_id = $_POST['rating_element'];
    $moodle_elements_id = $_POST['moodle_element'];
    $sql2 = "INSERT INTO elements_linking (kemsu_element_id, moodle_element_id, disciplines_linking_id) 
            VALUES ($kemsu_element_id, $moodle_elements_id, $disciplines_linking_id)";
    
    if ($systemConn->query($sql2) === TRUE) {
        echo "Элементы привязаны";
        header('Location: ../elements-linking.php');
    } else {
        echo "Ошибка: " . $sql2 . "<br>" . $systemConn->error;
    }
    $systemConn->close();
} else {
    echo 'Ошибка параметров';
}

?>