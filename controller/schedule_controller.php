<?php

if (isset($_GET['action'])) {
    if ($_GET['action'] == "select") {
        selectSchedule($_GET['doctor_id'], $_GET['schedule_id']);
    }

    if ($_GET['action'] == "cancel") {
        cancelSchedule($_GET['schedule_id']);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function getSchedule($department_id) {
    require 'connect.php';
    $sql = "SELECT s.schedule_id, s.department_id, s.time, s.availability, doctors.id, doctors.full_name 
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
    require_once 'connect.php';
    $sql = "UPDATE `schedules` SET `doctor_id` = ? WHERE `schedules`.`schedule_id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("ii", $doctor_id, $schedule_id);

    if ($query->execute()) {
        $response['msg'] = "Success";
    }
    $conn->close();
}

function cancelSchedule($schedule_id) {
    require_once 'connect.php';
    $sql = "UPDATE `schedules` SET `doctor_id` = NULL WHERE `schedules`.`schedule_id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $schedule_id);

    if ($query->execute()) {
        $response['msg'] = "Success";
    }
    $conn->close();
}
