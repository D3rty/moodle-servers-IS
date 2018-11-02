<?php

require '../db/config.php';
$sql = "SELECT 
          servers.name AS server_name,
          servers.id AS server_id,
          servers.kemsu_institute_id, 
          disciplines_linking.moodle_discipline_id, 
          disciplines_linking.kemsu_discipline_id, 
          elements_linking.kemsu_element_id, 
          elements_linking.moodle_element_id,
          elements_linking.id
        FROM 
          elements_linking, 
          servers, 
          disciplines_linking
        WHERE elements_linking.disciplines_linking_id = disciplines_linking.id
        AND disciplines_linking.server_id = servers.id";

$result = $systemConn->query($sql);
if ($result != FALSE) {
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    $systemConn->close();
    
    for ($i = 0; $i < sizeof($rows); $i++) {
        $urlForDiscipline = 'https://api.kemsu.ru/api-dev/moodle/disciplines/' . $rows[$i]['kemsu_discipline_id'];
        $dataForDiscipline = file_get_contents($urlForDiscipline);
        $kemsuDiscipline = json_decode($dataForDiscipline, true);
        $rows[$i]["disciplineName"] = $kemsuDiscipline["disciplineName"];
        
        $urlForKemsuElements = 'https://api.kemsu.ru/api-dev/moodle/getBsodElementsListByDekDisciplineId/' . $rows[$i]['kemsu_discipline_id'];
        $dataForKemsuElements = file_get_contents($urlForKemsuElements);
        $kemsuElementsOfDiscipline = json_decode($dataForKemsuElements, true);
        for ($j = 0; $j < sizeof($kemsuElementsOfDiscipline['result']); $j++) {
            if ($rows[$i]["kemsu_element_id"] == $kemsuElementsOfDiscipline['result'][$j]["BSOD_ELEMENT_ID"]) {
                $rows[$i]["kemsu_element_name"] = $kemsuElementsOfDiscipline['result'][$j]["BSOD_ELEMENT_NAME"];
            }
        }
        
        $urlForMoodleElements = ROOT_URL . '/api/getGradeitems.php/?server=' . $rows[$i]['server_id'] . '&moodle_discipline=' . $rows[$i]['moodle_discipline_id'];
        $dataForMoodleElements = file_get_contents($urlForMoodleElements);
        $moodleElementsOfDiscipline = json_decode($dataForMoodleElements, true);
        for ($j = 0; $j < sizeof($moodleElementsOfDiscipline); $j++) {
            if ($rows[$i]["moodle_element_id"] == $moodleElementsOfDiscipline[$j]["item_id"]) {
                $rows[$i]["moodle_element_name"] = $moodleElementsOfDiscipline[$j]["itemname"];
            }
        }
    }
    
    print json_encode($rows);
} else {
    echo "Ошибка: " . $sql . "<br />" . $systemConn->error;
}

?>