<?php

    if(isset($_POST['screening_submit'])){
        session_start();
        $screening = array(
            "question1" => $_POST['question-1'],
            "question2" => $_POST['question-2']
        );
        $screening = json_encode($screening);
        insertScreening($screening, $_SESSION['id']);
    }

    function insertScreening($screening, $patient_id){
        require 'connect.php';
        $sql = "INSERT INTO `screening` (`patient_id`, `result`, `time`) VALUES (?, ?, current_timestamp())";

        $query = $conn->prepare($sql);
        $query->bind_param("is", $patient_id, $screening);

        if($query->execute()){
            header('location: ../patient/panel');
        }
    }

    function readScreening($patient_id){
        require 'connect.php';
        $sql = "SELECT * FROM screening WHERE patient_id=?";

        $query = $conn->prepare($sql);
        $query->bind_param("i", $patient_id);

        if($query->execute()){
            $result = $query->get_result();
            return $result;
        }
    }
?>