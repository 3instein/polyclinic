<?php

switch ($_GET['action'] ?? '') {
    case 'select':
        echo json_encode(['message' => 'success', 'data' => selectSchedule(@$_POST['doctor_id'], @$_POST['schedule_id'])]);
        break;

    case 'cancel':
        echo json_encode(['message' => 'success', 'data' => cancelSchedule(@$_POST['schedule_id'])]);
        break;
    case 'removeDoctor':
        echo json_encode(['message' => 'success', 'data' => removeDoctor(@$_POST['schedule_id'])]);
        break;
    case 'deleteSchedule':
        echo json_encode(['message' => 'success', 'data' => deleteSchedule(@$_POST['schedule_id'])]);
        break;
}

if (isset($_POST['createSchedule'])) {
    session_start();
    createSchedule($_SESSION['department_id'], $_POST['create_day'], $_POST['create_time']);
    header('location: ../doctor/panel');
}

function getSchedule($department_id) {
    require 'connect.php';
    $sql = "SELECT s.schedule_id, s.department_id, s.day, s.time, s.availability, doctors.id, doctors.full_name, departments.name 
    FROM schedules s LEFT JOIN doctors ON s.doctor_id = doctors.id JOIN departments ON s.department_id = departments.id
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

function createSchedule($department_id, $day, $time) {
    require 'connect.php';

    $sql = "INSERT INTO `schedules` (`department_id`, `day`, `time`) 
            VALUES (?, ?, ?)";

    $query = $conn->prepare($sql);
    $query->bind_param("iss", $department_id, $day, $time);
    $query->execute();
}

function deleteSchedule($schedule_id) {
    require 'connect.php';

    $sql = "DELETE FROM schedules WHERE schedule_id=?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $schedule_id);
    $query->execute();
}

function removeDoctor($schedule_id) {
    require 'connect.php';

    $sql = "UPDATE schedules SET doctor_id=NULL WHERE schedule_id=?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $schedule_id);
    $query->execute();
}
