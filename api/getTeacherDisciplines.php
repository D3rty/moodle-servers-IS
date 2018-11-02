<?php

if (isset($_GET["moodle_teacher"])) {
    require '../db/selectDB.php';
    $chosen_moodle_teacher = $_GET["moodle_teacher"];
    $sql = "SELECT
                teacher.id AS teacher_id,
                CONCAT(teacher.lastname,' ',teacher.firstname) AS teacher_name,
                $course.id AS course_id,
                $course.fullname AS course_fullname
            FROM
                $user teacher,
                $role_assignments,
                $context,
                $course
            WHERE $role_assignments.userid = teacher.id
            AND $role_assignments.contextid = $context.id
            AND $context.instanceid = $course.id
            AND $context.contextlevel = '50'
            AND $role_assignments.roleid = '3'
            AND teacher.id = $chosen_moodle_teacher
            GROUP BY teacher_id, course_id";
    
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