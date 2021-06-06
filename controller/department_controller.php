<?php

function listDepartment() {
    require 'connect.php';
    $sql = "SELECT * FROM departments";
    
    $query = $conn->prepare($sql);

    if ($query->execute()) {
        $result = $query->get_result();
        return $result;
    }
    $conn->close();
}

