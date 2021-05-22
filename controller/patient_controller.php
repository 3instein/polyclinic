<?php

if (isset($_POST['register'])) {
    require_once 'connect.php';
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $id_number = $conn->real_escape_string($_POST['id_number']);
    $pin = $conn->real_escape_string($_POST['pin']);

    $pin = password_hash($pin, PASSWORD_DEFAULT);

    $sql = "INSERT INTO `patients` (`id`, `id_number`, `full_name`, `address`, `contact`, `pin`) 
            VALUES (NULL, '$id_number', '$full_name', '$address', '$contact', '$pin')";

    if ($conn->query($sql)) {
        header('location: ../patient/appointment');
    }

    $conn->close();
}

if (isset($_POST['login'])) {
    require_once 'connect.php';
    $id_number = $conn->real_escape_string($_POST['id_number']);
    $pin = $conn->real_escape_string($_POST['pin']);

    $sql = "SELECT * FROM `patients` WHERE `id_number` = $id_number";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($pin, $row['pin'])) {
                session_start();
                $_SESSION['patient_id'] = $row['id'];
                header('location: ./../patient/appointment');
            }
        }
    }
    $conn->close();
}
