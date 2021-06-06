<?php

include 'screening_controller.php';

switch ($_GET['action'] ?? '') {
    case 'forgotPIN':
        echo json_encode(['message' => 'success', 'data' => forgotPin(@$_POST['id_number'])]);
        break;
}

if (isset($_POST['register'])) {
    require_once 'connect.php';
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $id_number = $_POST['id_number'];
    $pin = $_POST['pin'];

    $pin = password_hash($pin, PASSWORD_DEFAULT);

    $sql = "INSERT INTO `patients` (`id_number`, `email`, `full_name`, `address`, `contact`, `pin`) 
            VALUES (?, ?, ?, ?, ?, ?)";


    $query = $conn->prepare($sql);
    $query->bind_param("isssis", $id_number, $email, $full_name, $address, $contact, $pin);

    if ($query->execute()) {
        $sql = "SELECT `id` FROM `patients` WHERE `id_number` = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("i", $id_number);

        if ($query->execute()) {
            $result = $query->get_result();
            $result = $result->fetch_assoc();

            session_start();
            $_SESSION['patient_id'] = $result['id'];
            $_SESSION['id_number'] = $_POST['id_number'];
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['full_name'] = $_POST['full_name'];
            $_SESSION['address'] = $_POST['address'];
            $_SESSION['contact'] = $_POST['contact'];
            header('location: ../patient/appointment');
        }
    } else {
        session_start();
        $_SESSION['error'] = "Email / ID Card Number already registered!";
        header('location: ../patient/authentication');
    }
    $conn->close();
}

if (isset($_POST['login'])) {
    require_once 'connect.php';
    $id_number = $_POST['id_number'];
    $pin = $_POST['pin'];

    $sql = "SELECT * FROM `patients` WHERE `id_number` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("i", $id_number);

    if ($query->execute()) {
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (password_verify($pin, $row['pin'])) {
                    session_start();
                    $_SESSION['patient_id'] = $row['id'];
                    $_SESSION['id_number'] = $row['id_number'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['full_name'] = $row['full_name'];
                    $_SESSION['address'] = $row['address'];
                    $_SESSION['contact'] = $row['contact'];
                    header('location: ../patient/panel');
                } else {
                    session_start();
                    $_SESSION['error'] = "Wrong ID Number / PIN!";
                    header('location: ../patient/authentication');
                }
            }
        } else {
            session_start();
            $_SESSION['error'] = "Account not found!";
            header('location: ../patient/authentication');
        }
    }
    $conn->close();
}


if (isset($_POST['edit_patient'])) {
    require_once 'connect.php';
    session_start();

    $email = $_POST['email'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $sql = "UPDATE `patients` SET `email` = ?, `address` = ?, `contact` = ? WHERE `id` = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("sssi", $email, $address, $contact, $_SESSION['patient_id']);
    if ($query->execute()) {
        $_SESSION['email'] = $email;
        $_SESSION['address'] =  $address;
        $_SESSION['contact'] = $contact;
        header("location: ../patient/panel");
    }
}

if (isset($_POST['changePin'])) {
    require 'connect.php';

    $token = $_POST['token'];
    $new_pin = $_POST['new_pin'];
    $new_pin = password_hash($new_pin, PASSWORD_DEFAULT);

    $sql = "SELECT `patient_id`, `token` FROM `patients_token` WHERE `token` = ?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $token);

    if ($query->execute()) {
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $result = $result->fetch_assoc();
            $patient_id = $result['patient_id'];

            $sql = "DELETE FROM `patients_token` WHERE `patient_id` = ?";
            $query = $conn->prepare($sql);
            $query->bind_param("i", $patient_id);

            if ($query->execute()) {
                changePin($new_pin, $patient_id);
                header('location: ../patient/authentication');
            }
        }
    }
    $conn->close();
}

function changePin($new_pin, $patient_id) {
    require 'connect.php';
    $sql = "UPDATE `patients` SET `pin` = ? WHERE `id` = ?";
    $query = $conn->prepare($sql);
    $query->bind_param("si", $new_pin, $patient_id);
    $query->execute();
    $conn->close();
}

function forgotPin($id_number) {
    require 'connect.php';
    $id_number = $_POST['id_number'];

    $token = rand(1000, 9999);
    $subject = "Forgot PIN Token";
    $link = "http://polyclinic.hackerexperience.net/patient/forgot?token=$token&id_number=$_POST[id_number]"; /*-- jika sudah hosting, ubah dengan link URL website --*/
    $msg = "Token Forgot PIN anda adalah $token atau anda bisa mengganti password anda melalui link berikut $link";

    $sql = "SELECT id, email FROM patients WHERE id_number=?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $id_number);

    if ($query->execute()) {
        $data = $query->get_result();
        $data = $data->fetch_assoc();
        $patient_id = $data['id'];
        $target_email = $data['email'];

        $sql = "INSERT INTO `patients_token` (`patient_id`, `token`) VALUES (?, ?)";
        $query = $conn->prepare($sql);
        $query->bind_param("ii", $patient_id, $token);

        if ($query->execute()) {

            require 'mail_controller.php';
            sendMail($subject, $target_email, $msg);
        }
    }
    $conn->close();
}

function validatePatientToken($token) {
    require 'connect.php';

    $sql = "SELECT id, token FROM patients_token WHERE token = ?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $token);

    if ($query->execute()) {
        $result = $query->get_result();
        $result = $result->fetch_assoc();
        if ($result['token'] == $token) {
            return true;
        } else {
            return false;
        }
    }
    $conn->close();
}
