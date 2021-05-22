<?php 
include './../controller/appointment_controller.php'; 
include '../controller/patient_controller.php';
include './../controller/department_controller.php';
include './../controller/schedule_controller.php';
include './../controller/doctor_controller.php';

session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Welcome <?= $_SESSION['full_name']; ?></h1>
    Appointment
    <table border="5">
        <tr>
            <th>Department</th>
            <th>Doctor</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
        <?php
            $appointment = getPatientAppointment($_SESSION['id']);
            if (!empty($appointment)) {
                while($row = $appointment->fetch_assoc()){
                    $department_id = scheduleData($row['schedule_id'], "department");
                    echo "<tr>";
                    echo "<td>".getDepartment($department_id, "name")."</td>";
                    $doctor_id = scheduleData($row['schedule_id'], "doctor");
                    echo "<td>".getDoctor($doctor_id, "name")."</td>";
                    echo "<td>".scheduleData($row['schedule_id'], "time")."</td>";
                    echo "</tr>";
                }
            }
        ?>
    </table>
</body>

</html>