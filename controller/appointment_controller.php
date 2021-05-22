<?php

switch ($_GET['action'] ?? '') {
    case 'getDepartmentId':
        echo json_encode(['message' => 'success', 'data' => getDepartmentid(@$_POST['selected'])]);
        break;

        // default:
        //     echo json_encode(['message' => 'invalid argument']);
}

if (isset($_POST['appointment'])) {
    require 'connect.php';
    session_start();
    $id = $_SESSION['id'];
    $schedule_id = $_POST['appointment'];

    $sql = "INSERT INTO `appointments` (`id`, `schedule_id`, `patient_id`) VALUES (NULL, '$schedule_id', '$id')";

    if ($conn->query($sql)) {
        header('location: ../patient/panel');
    }
    $conn->close();
}

function getDepartmentid($department_id) {
    require 'connect.php';
    $result = [];
    $department_id = mysqli_real_escape_string($conn, $department_id);
    $sql = "SELECT * FROM schedules JOIN doctors ON schedules.doctor_id = doctors.id WHERE schedules.department_id='{$department_id}' AND availability='available'";
    $data = $conn->query($sql);

    if ($data->num_rows > 0) {
        while ($row = $data->fetch_assoc()) $result[] = $row;
    }

    return $result;
    $conn->close();
}

function getDoctorAppointment($doctor_id){
    require 'connect.php';
    $sql = "SELECT * FROM appointments JOIN schedules ON schedules.doctor_id WHERE schedules.doctor_id='$doctor_id'";
    $result = $conn ->query($sql);

    if($result->num_rows > 0){
        return $result;
    }
    $conn->close();
}

function getPatientAppointment($patient_id){
    require 'connect.php';
    $sql = "SELECT * FROM appointments JOIN patients ON patients.id WHERE patients.id='$patient_id'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        return $result;
    }
    $conn->close();
}
    