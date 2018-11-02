<?php

require '../db/selectDB.php';
//50 - дисциплина, 3 - учитель
$sql = "SELECT teacher.id AS 'teacher_id', CONCAT(teacher.lastname,' ',teacher.firstname) AS 'fullname'
        FROM $user teacher, $role_assignments role_assignments, $context context, $course course
        WHERE role_assignments.userid = teacher.id
        AND role_assignments.contextid = context.id
        AND context.instanceid = course.id
        AND context.contextlevel = '50'
        AND role_assignments.roleid = '3'
        GROUP BY teacher_id";

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

?>