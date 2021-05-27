<?php

if (isset($_POST['register'])) {
    require_once 'connect.php';
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $id_number = $_POST['id_number'];
    $pin = $_POST['pin'];

    $pin = password_hash($pin, PASSWORD_DEFAULT);

    $sql = "INSERT INTO `patients` (`id_number`, `full_name`, `address`, `contact`, `pin`) 
            VALUES (?, ?, ?, ?, ?)";


    $query = $conn->prepare($sql);
    $query->bind_param("issis", $id_number, $full_name, $address, $contact, $pin);

    if ($query->execute()) {
        header('location: ../patient/appointment');
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
                $_SESSION['full_name'] = $row['full_name'];
                header('location: ../patient/panel');
            }
        }
    }
    $conn->close();
}
