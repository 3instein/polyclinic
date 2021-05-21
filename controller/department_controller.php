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
