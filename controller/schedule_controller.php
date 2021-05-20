<?php

    if(isset($_POST['select_schedule'])){
        session_start();
        selectSchedule($_SESSION['id'], $_POST['schedule']);
        header('location: ../doctor/panel');
    }

    function getSchedule($department_id){
        require 'connect.php';
        $sql = "SELECT * FROM schedules WHERE department_id='$department_id' AND availability='available' AND doctor_id is null";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result;
        }
        $conn->close();
    }

    function selectSchedule($doctor_id, $schedule_id){
        require_once 'connect.php';
        $sql = "UPDATE `schedules` SET `doctor_id` = '$doctor_id' WHERE `schedules`.`id` = '$schedule_id'";
        $conn->query($sql);
        $conn->close();
    }

?>