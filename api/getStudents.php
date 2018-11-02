<?php

if (isset($_GET["disciplineid"])) {
    require '../db/selectDB.php';
    $disciplineid = $_GET["disciplineid"];
    //50 - дисциплина, 5 - студент
    $sql = "SELECT student.id, CONCAT(student.lastname,' ',student.firstname) AS 'fullname'
            FROM $user student, $role_assignments role_assignments, $context context, $course course
            WHERE role_assignments.userid = student.id
            AND role_assignments.contextid = context.id
            AND context.instanceid = course.id
            AND context.contextlevel = '50'
            AND role_assignments.roleid = '5'
            AND course.id = $disciplineid
            GROUP BY student.id";

    $result = $conn->query($sql);
    if ($result != FALSE) {
        $rows = array();
        while($row = mysqli_fetch_assoc($result)) {
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