<?php

    if(isset($_GET['action'])){
        if($_GET['action'] == "select"){
            selectSchedule($_GET['doctor_id'], $_GET['schedule_id']);
        }

        if($_GET['action'] == "cancel"){
            cancelSchedule($_GET['schedule_id']);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    function getSchedule($department_id){
        require 'connect.php';
        $sql = "SELECT * FROM schedules WHERE department_id='$department_id' AND availability='available'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result;
        }
        $conn->close();
    }

    function selectSchedule($doctor_id, $schedule_id){
        require_once 'connect.php';
        $sql = "UPDATE `schedules` SET `doctor_id` = '$doctor_id' WHERE `schedules`.`schedule_id` = '$schedule_id'";
        $conn->query($sql);
        $conn->close();
    }

    function cancelSchedule($schedule_id){
        require_once 'connect.php';
        $sql = "UPDATE `schedules` SET `doctor_id` = NULL WHERE `schedules`.`schedule_id` = '$schedule_id'";
        $conn->query($sql);
        $conn->close();
    }

?>