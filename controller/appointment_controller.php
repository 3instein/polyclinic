<?php

switch ($_GET['action'] ?? '') {
    case 'getDepartmentId':
        echo json_encode(['message' => 'success', 'data' => getDepartmentid(@$_POST['selected'])]);
        break;

        // default:
        //     echo json_encode(['message' => 'invalid argument']);
    case 'cancelAppointment':
        echo json_encode(['message' => 'success', 'data' => cancelAppointment(@$_POST['cancelAppointment'], @$_POST['schedule_id'])]);
        break;
    case 'startAppointment':
        echo json_encode(['message' => 'success', 'data' => startAppointment(@$_POST['appointment_id'])]);
        break;
    case 'finishAppointment':
        echo json_encode(['message' => 'success', 'data' => finishAppointment(@$_POST['appointment_id'])]);
        break;
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
        $sql = "UPDATE schedules SET availability='Unavailable' WHERE schedule_id=?";

        $query = $conn->prepare($sql);
        $query->bind_param("i", $schedule_id);

        if($query->execute()){
            require 'mail_controller.php';

            $sql = "SELECT appointments.id, email FROM appointments 
                    JOIN patients ON appointments.patient_id = patients.id 
                    WHERE patient_id=? ORDER BY id DESC LIMIT 1";
            $query = $conn->prepare($sql);
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();
            $result = $result->fetch_assoc();

            $subject = "Appointment #$result[id]";
            $target_email = $result['email'];
            $msg = "Your appointment has been scheduled!";
            sendMail($subject, $target_email, $msg);

            header('location: ../patient/screening');
        }
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
    $sql = "SELECT appointments.id, patients.full_name, schedules.time, appointments.status
            FROM appointments JOIN schedules ON appointments.schedule_id = schedules.schedule_id 
            JOIN patients ON appointments.patient_id = patients.id 
            WHERE schedules.doctor_id=? AND appointments.status != 'Finished'";
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
    $sql = "SELECT appointments.id, departments.name, doctors.full_name, schedules.schedule_id, schedules.day, schedules.time, appointments.status
            FROM appointments JOIN schedules ON appointments.schedule_id = schedules.schedule_id 
            JOIN departments ON schedules.department_id = departments.id 
            JOIN doctors ON schedules.doctor_id = doctors.id 
            WHERE appointments.patient_id = ?
            ORDER BY `appointments`.`id` DESC LIMIT 5";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $patient_id);
 
    if($query->execute()){
        $result = $query->get_result();
        if($result->num_rows > 0){
            return $result;
        }
    }
    $conn->close();
}
    
function cancelAppointment($appointment_id, $schedule_id){
    require 'connect.php';
    $sql = "UPDATE `appointments` SET `status` = 'Cancelled' WHERE `appointments`.`id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $appointment_id);

    if($query->execute()){
        $sql = "UPDATE `schedules` SET `availability` = 'Available' WHERE `schedule_id` = ?";

        $query = $conn->prepare($sql);
        $query->bind_param("i", $schedule_id);

        $query->execute();
    }

    $conn->close();
}

function startAppointment($appointment_id){
    require 'connect.php';
    $sql = "UPDATE `appointments` SET `status` = 'Ongoing' WHERE `appointments`.`id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $appointment_id);

    $query->execute();

    $conn->close();
}

function finishAppointment($appointment_id){
    require 'connect.php';
    $sql = "UPDATE `appointments` SET `status` = 'Finished' WHERE `appointments`.`id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $appointment_id);

    $query->execute();

    $conn->close();
}