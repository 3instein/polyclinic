<?php

include('screening_controller.php');

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
            $_SESSION['id'] = $result['id'];
            $_SESSION['id_number'] = $_POST['id_number'];
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['full_name'] = $_POST['full_name'];
            $_SESSION['address'] = $_POST['address'];
            $_SESSION['contact'] = $_POST['contact'];
            header('location: ../patient/appointment');
        }
    } else {
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
        while ($row = $result->fetch_assoc()) {
            if (password_verify($pin, $row['pin'])) {
                session_start();
                $_SESSION['id'] = $row['id'];
                $_SESSION['id_number'] = $row['id_number'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['address'] = $row['address'];
                $_SESSION['contact'] = $row['contact'];
                header('location: ../patient/panel');
            }
        }
    }
    $conn->close();
}
