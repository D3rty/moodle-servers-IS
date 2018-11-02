<?php

if (isset($_GET["moodle_discipline"])) {
    require '../db/selectDB.php';
    $moodleDisciplineId = $_GET["moodle_discipline"];
    $sql = "SELECT $course.id AS course_id, $grade_items.id AS item_id, $grade_items.itemname AS itemname
            FROM $course, $grade_items
            WHERE $grade_items.courseid = $course.id
            AND $grade_items.itemtype = 'mod'
            AND $course.id = $moodleDisciplineId";
    
    $result = $conn->query($sql);
    if ($result != FALSE) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        $conn->close();
        print json_encode($rows);
    } else {
        echo "Ошибка: " . $sql . "<br />" . $conn->error;
    }
} else {
    echo 'Ошибка параметров';
}

?>