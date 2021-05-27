<?php
    session_start();

    if(isset($_POST['screening_submit'])){
        $screening['question-1'] = $_POST['question-1'];
        $screening['question-2'] = $_POST['question-2'];
        echo json_encode($screening);
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
?>