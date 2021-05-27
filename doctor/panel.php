<?php
    include '../controller/appointment_controller.php';
    include '../controller/doctor_controller.php';
    include '../controller/schedule_controller.php';
    include '../controller/patient_controller.php';
    session_start();
    if(!isset($_SESSION['id'])){
        // header('location: ../doctor/login');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <img src="../images/profile/<?= $_SESSION['username']; ?>.png">
    Welcome Dr. <?= $_SESSION['full_name']; ?>
    <button><a href="logout">Logout</a></button><br>
    Appointment<br>
    <table border="5">
        <tr>
            <th>Patient</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
        <?php
            $appointment = getDoctorAppointment($_SESSION['id']);
            if(!empty($appointment)){
                echo "<tr>";
                echo "<td>$appointment[full_name]</td>";
                echo "<td>$appointment[time]</td>";
                echo "</tr>";
            }
        ?>
    </table>
    Schedule<br>
    <table border="5">
        <tr>
            <th>Department</th>
            <th>Doctor</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
        <?php
            $schedule = getSchedule($_SESSION['department_id']);
            if(!empty($schedule)){
                while($row = $schedule->fetch_assoc()){
                    switch($row['department_id']){
                        case 1:
                            $department = "General";
                    }
                    $doctor_name = $row['full_name'];
                    echo "<tr>";
                    echo "<td>$department</td>";
                    echo "<td>$doctor_name</td>";
                    echo "<td>$row[time]</td>";
                    echo "<td>";
                    if($row['availability'] = "available" && empty($doctor_name)){
                        echo "<button><a href='../controller/schedule_controller?schedule_id=$row[schedule_id]&doctor_id=$_SESSION[id]&action=select'>Select</a></button>";
                    } else if($row['id'] == $_SESSION['id']){
                        echo "<button><a href='../controller/schedule_controller?schedule_id=$row[schedule_id]&doctor_id=$_SESSION[id]&action=cancel'>Cancel</a></button>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            }
        ?>
    </table>
</body>
</html>