<?php

include '../controller/base_url.php';
include '../controller/department_controller.php';
include '../controller/schedule_controller.php';

switch ($_GET['action'] ?? '') {
    case 'getDepartmentId':
        echo json_encode(['message' => 'success', 'data' => getDepartmentid(@$_POST['selected'])]);
        break;
        // default:
        //     echo json_encode(['message' => 'invalid argument']);
    case 'cancelAppointment':
        echo json_encode(['message' => 'success', 'data' => cancelAppointment(@$_POST['appointment_id'], @$_POST['schedule_id'])]);
        break;
    case 'startAppointment':
        echo json_encode(['message' => 'success', 'data' => startAppointment(@$_POST['appointment_id'])]);
        break;
    case 'finishAppointment':
        echo json_encode(['message' => 'success', 'data' => finishAppointment(@$_POST['appointment_id'])]);
        break;
    case 'viewNote':
        echo json_encode(['message' => 'success', 'data' => readNote(@$_POST['appointment_id'])]);
        break;
}

if (isset($_POST['appointment'])) {
    require 'connect.php';
    session_start();
    $id = $_SESSION['patient_id'];
    $schedule_id = $_POST['appointment'];

    $sql = "INSERT INTO `appointments` (`schedule_id`, `patient_id`) VALUES (?, ?)";

    $query = $conn->prepare($sql);
    $query->bind_param("ii", $schedule_id, $id);

    if ($query->execute()) {
        $sql = "UPDATE schedules SET availability='Unavailable' WHERE schedule_id=?";

        $query = $conn->prepare($sql);
        $query->bind_param("i", $schedule_id);

        if ($query->execute()) {
            require 'mail_controller.php';

            $sql = "SELECT appointments.id, schedules.day, schedules.time, patients.email, patients.full_name, schedules.doctor_id FROM appointments 
                    JOIN patients ON appointments.patient_id = patients.id JOIN schedules ON appointments.schedule_id = schedules.schedule_id 
                    WHERE patient_id=? ORDER BY id DESC LIMIT 1";
            $query = $conn->prepare($sql);
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();
            $result = $result->fetch_assoc();

            $patient_name = $result['full_name'];
            $appointment_day = $result['day'];
            $appointment_time = $result['time'];
            $doctor_id = $result['doctor_id'];

            $subject = "Appointment #$result[id]";
            $target_email = $result['email'];
            $msg = "Your appointment on $appointment_day at $appointment_time has been scheduled!";
            
            sendMail($subject, $target_email, $msg);
            
            $sql = "SELECT doctors.email FROM doctors WHERE id=?";
            $query= $conn->prepare($sql);
            $query->bind_param("i", $doctor_id);
            $query->execute();
            $result = $query->get_result();
            $result = $result->fetch_assoc();

            $target_email = $result['email'];
            $msg = "You have an appointment with Mr/Mrs $patient_name on $appointment_day at $appointment_time";

            sendMail($subject, $target_email, $msg);

            header('location: ../patient/screening');
        }
    }
    $conn->close();
}

if (isset($_POST['addNote'])) {
    include 'base_url.php';
    $note = array(
        "Note" => $_POST['note']
    );
    $note = json_encode($note);
    createNote($note, $_POST['addNote']);
    header('location: ' . base . 'doctor/panel');
}

function createNote($note, $appointment_id) {
    require 'connect.php';
    $sql = "UPDATE `appointments` SET `note` = ? WHERE `id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("si", $note, $appointment_id);
    $query->execute();
    $conn->close();
}

function readNote($appointment_id) {
    require 'connect.php';

    $sql = "SELECT note FROM appointments WHERE id=?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $appointment_id);
    $query->execute();
    $data = $query->get_result();

    if ($data->num_rows > 0) {
        while ($row = $data->fetch_assoc()) $result[] = $row;
    }

    return $result;

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

function getDoctorAppointment($doctor_id) {
    require 'connect.php';
    $sql = "SELECT appointments.id, patients.full_name, schedules.time, appointments.status, schedules.day
            FROM appointments JOIN schedules ON appointments.schedule_id = schedules.schedule_id 
            JOIN patients ON appointments.patient_id = patients.id 
            WHERE schedules.doctor_id=? ORDER BY appointments.id DESC";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $doctor_id);

    if ($query->execute()) {
        $result = $query->get_result();
        return $result;
    }
    $conn->close();
}

function getPatientAppointment($patient_id) {
    require 'connect.php';
    $sql = "SELECT appointments.id, departments.name, doctors.full_name, schedules.schedule_id, schedules.day, schedules.time, appointments.status
            FROM appointments JOIN schedules ON appointments.schedule_id = schedules.schedule_id 
            JOIN departments ON schedules.department_id = departments.id 
            JOIN doctors ON schedules.doctor_id = doctors.id 
            WHERE appointments.patient_id = ?
            ORDER BY `appointments`.`id` DESC LIMIT 5";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $patient_id);

    if ($query->execute()) {
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
    }
    $conn->close();
}

function cancelAppointment($appointment_id, $schedule_id) {
    require 'connect.php';
    require 'mail_controller.php';
    $sql = "UPDATE `appointments` SET `status` = 'Cancelled' WHERE `appointments`.`id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $appointment_id);

    if ($query->execute()) {
        $sql = "UPDATE `schedules` SET `availability` = 'Available' WHERE `schedule_id` = ?";

        $query = $conn->prepare($sql);
        $query->bind_param("i", $schedule_id);

        if ($query->execute()) {
            $sql = "SELECT patients.email, patients.full_name, schedules.doctor_id, schedules.day, schedules.time FROM appointments 
                    JOIN patients ON appointments.patient_id = patients.id JOIN schedules ON appointments.schedule_id = schedules.schedule_id
                    WHERE appointments.id = ?";
            $query = $conn->prepare($sql);
            $query->bind_param("i", $appointment_id);
            $query->execute();
            $result = $query->get_result();
            $result = $result->fetch_assoc();

            $doctor_id = $result['doctor_id'];
            $appointment_day = $result['day'];
            $appointment_time = $result['time'];
            $patient_name = $result['full_name'];

            $subject = "Appointment #$appointment_id cancelled";
            $target_email = $result['email'];
            $msg = "You have canceled your appointment on $appointment_day at $appointment_time!";

            sendMail($subject, $target_email, $msg);

            $sql = "SELECT doctors.email FROM doctors WHERE id=?";
            $query= $conn->prepare($sql);
            $query->bind_param("i", $doctor_id);
            $query->execute();
            $result = $query->get_result();
            $result = $result->fetch_assoc();

            $target_email = $result['email'];
            $msg = "Appointment with Mr/Mrs $patient_name on $appointment_day at $appointment_time has been cancelled!";

            sendMail($subject, $target_email, $msg);
            
        }
    }
    $conn->close();
}

function startAppointment($appointment_id) {
    require 'connect.php';
    $sql = "UPDATE `appointments` SET `status` = 'Ongoing' WHERE `appointments`.`id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $appointment_id);

    $query->execute();

    $conn->close();
}

function finishAppointment($appointment_id) {
    require 'connect.php';
    $sql = "UPDATE `appointments` SET `status` = 'Finished' WHERE `appointments`.`id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $appointment_id);

    if ($query->execute()) {
        $sql = "SELECT patients.email, schedule_id FROM appointments 
                JOIN patients ON appointments.patient_id = patients.id 
                WHERE appointments.id=? ";

        $query = $conn->prepare($sql);
        $query->bind_param("i", $appointment_id);
        $query->execute();
        $result = $query->get_result();
        $result = $result->fetch_assoc();

        $subject = "Appointment #$appointment_id has finished";
        $target_email = $result['email'];
        $msg = "Thank you for visiting polyclinic. Hope you have a speedy recovery!";

        $sql = "UPDATE schedules SET availability = 'Available' WHERE schedule_id=?";

        $query = $conn->prepare($sql);
        $query->bind_param("i", $result['schedule_id']);
        $query->execute();

        require 'mail_controller.php';

        sendMail($subject, $target_email, $msg);
    }

    $conn->close();
}
