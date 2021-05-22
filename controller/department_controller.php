<?php

function listDepartment() {
    require_once 'connect.php';
    $sql = "SELECT * FROM departments";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result;
    }
    $conn->close();
}

function getDepartment($department_id, $request){
    require 'connect.php';
    $sql = "SELECT * FROM departments WHERE id='$department_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        if ($request == "name") {
            $name = $result->fetch_assoc();
            return $name['name'];
        }
    }
}
