<?php
    require '../controller/main_controller.php';
    
    !isset($_SESSION['doctor']) ? header('location: login.php') : null;
    $profile_picture = $_SESSION['doctor']->getUsername();
?>