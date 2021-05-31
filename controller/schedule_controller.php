<?php

switch ($_GET['action'] ?? '') {
    case 'select':
        echo json_encode(['message' => 'success', 'data' => selectSchedule(@$_POST['doctor_id'], @$_POST['schedule_id'])]);
        break;

    case 'cancel':
        echo json_encode(['message' => 'success', 'data' => cancelSchedule(@$_POST['schedule_id'])]);
        break;
}

function getSchedule($department_id) {
    require 'connect.php';
    $sql = "SELECT s.schedule_id, s.department_id, s.day, s.time, s.availability, doctors.id, doctors.full_name 
    FROM schedules s LEFT JOIN doctors ON s.doctor_id = doctors.id 
    WHERE s.department_id=? AND availability='available'";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $department_id);

    if ($query->execute()) {
        $result = $query->get_result();
        return $result;
    }
    $conn->close();
}

function selectSchedule($doctor_id, $schedule_id) {
    require 'connect.php';
    $sql = "UPDATE `schedules` SET `doctor_id` = ? WHERE `schedules`.`schedule_id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("ii", $doctor_id, $schedule_id);

    $query->execute();
    $conn->close();
}

function cancelSchedule($schedule_id) {
    require 'connect.php';
    $sql = "UPDATE `schedules` SET `doctor_id` = NULL WHERE `schedules`.`schedule_id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $schedule_id);

    $query->execute();
    $conn->close();
}
