<?php

if (isset($_GET["server"])) {
    require '../db/config.php';
    $sql = "SELECT id, kemsu_teacher_id, moodle_teacher_id 
            FROM teachers_linking
            WHERE server_id = " . $_GET["server"];
    
    $result = $systemConn->query($sql);
    if ($result != FALSE) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        $systemConn->close();
        
        for ($i = 0; $i < sizeof($rows); $i++) {
            $urlForTeacher = 'https://api.kemsu.ru/api-dev/moodle/users/' . $rows[$i]['kemsu_teacher_id'];
            $dataForTeacher = file_get_contents($urlForTeacher);
            $kemsuTeacher = json_decode($dataForTeacher, true);
            $rows[$i]["fullname"] = $kemsuTeacher["lastName"] . ' ' . $kemsuTeacher["firstName"] . ' ' . $kemsuTeacher["middleName"];
        }
        
        print json_encode($rows);
    } else {
        echo "Ошибка: " . $sql . "<br />" . $systemConn->error;
    }
} else {
    echo 'Ошибка в параметрах';
}

?>