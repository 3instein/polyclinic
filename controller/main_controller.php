<?php
include 'Doctor.php';
include 'Patient.php';

session_start();

if (isset($_POST['register'])) {
    $doctor = new Doctor("", $_POST['department'], $_POST['full_name'], $_POST['username'], $_POST['password']);
    $doctor->register();
}

if (isset($_POST['login'])) {
    $doctor = new Doctor("", "", "", $_POST['username'], $_POST['password']);
    $doctor->login();
}

if (isset($_POST['appoinment'])) {
    $patient = new Patient("", $_POST['id_number'], $_POST['full_name'], $_POST['address'], $_POST['contact']);
    $patient->register();
}

if (isset($_POST['logout'])) {
    require_once('connect.php');
    $id = $_SESSION['doctor']->getId();
    session_destroy();
    $sql = "UPDATE `doctors` SET `session_id` = '' WHERE `doctors`.`id` = $id";
    $conn->query($sql);
    header('location: login.php');
}
?>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<script>
    $(window).unload(function() {
        $.ajax({
            type: "POST",
            url: "main_controller.php",
            data: {
                logout: "John"
            }
        }).done(function(msg) {
            alert("Data Saved: " + msg);
        });
    });
</script>