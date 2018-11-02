<?php

require '../db/selectDB.php';
$sql = "SELECT
            $course.id AS course_id,
            $course.fullname AS course_fullname
        FROM
            $role_assignments,
            $context,
            $course
        WHERE $role_assignments.contextid = $context.id
        AND $context.instanceid = $course.id
        AND $context.contextlevel = '50'
        GROUP BY course_id";

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