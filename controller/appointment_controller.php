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

    $sql = "INSERT INTO `appointments` (`schedule_id`, `patient_id`) VALUES (?, ?)";

    $query = $conn->prepare($sql);
    $query->bind_param("ii", $schedule_id, $id);

    if($query->execute()){
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
    $sql = "SELECT * FROM appointments JOIN schedules ON appointments.schedule_id = schedules.schedule_id 
            JOIN patients ON appointments.patient_id = patients.id 
            WHERE schedules.doctor_id=?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $doctor_id);

    if($query->execute()){
        $result = $query->get_result();
        return $result->fetch_assoc();
    }
    $conn->close();
}

function getPatientAppointment($patient_id){
    require 'connect.php';
    $sql = "SELECT * FROM appointments JOIN schedules ON appointments.schedule_id = schedules.schedule_id 
            JOIN departments ON schedules.department_id = departments.id 
            JOIN doctors ON schedules.doctor_id = doctors.id 
            WHERE appointments.patient_id = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $patient_id);
 
    if($query->execute()){
        $result = $query->get_result();
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        }
    }
    $conn->close();
}
    