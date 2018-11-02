<?php

if (isset($_GET["server"]) && isset($_GET["teachers_linking_id"])) {
    require '../db/config.php';
    $serverId          = $_GET["server"];
    $teachersLinkingId = $_GET["teachers_linking_id"];
    $sql               = "SELECT id, kemsu_discipline_id, moodle_discipline_id
                            FROM disciplines_linking
                            WHERE server_id = $serverId
                            AND teachers_linking_id = $teachersLinkingId";
    
    $result = $systemConn->query($sql);
    if ($result != FALSE) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        $systemConn->close();
        
        for ($i = 0; $i < sizeof($rows); $i++) {
            $urlForDiscipline           = 'https://api.kemsu.ru/api-dev/moodle/disciplines/' . $rows[$i]['kemsu_discipline_id'];
            $dataForDiscipline          = file_get_contents($urlForDiscipline);
            $kemsuDiscipline            = json_decode($dataForDiscipline, true);
            $rows[$i]["disciplineName"] = $kemsuDiscipline["disciplineName"];
            
            if ($rows[$i]["disciplineName"] == '') {
                $urlForDisciplinesFromMoodle  = ROOT_URL . '/api/getDisciplines.php?&server=' . $serverId;
                $dataForDisciplinesFromMoodle = file_get_contents($urlForDisciplinesFromMoodle);
                $disciplines                  = json_decode($dataForDisciplinesFromMoodle, true);
                
                for ($j = 0; $j < sizeof($disciplines); $j++) {
                    if ($rows[$i]["moodle_discipline_id"] == $disciplines[$j]["course_id"]) {
                        $rows[$i]["disciplineName"] = $disciplines[$j]["course_fullname"];
                    }
                }
            }
        }
        print json_encode($rows);
    } else {
        echo "Ошибка: " . $sql . "<br />" . $systemConn->error;
    }
} else {
    echo 'Ошибка в параметрах';
}

?>