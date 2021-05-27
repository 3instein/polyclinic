<?php
include '../controller/appointment_controller.php';
include '../controller/doctor_controller.php';
include '../controller/schedule_controller.php';
include '../controller/patient_controller.php';
session_start();
if (!isset($_SESSION['id'])) {
    // header('location: ../doctor/login');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        $appointment = getDoctorAppointment($_SESSION['id']);
        if (!empty($appointment)) {
            echo "<tr>";
            echo "<td>$appointment[full_name]</td>";
            echo "<td>$appointment[time]</td>";
            echo "<td>$appointment[status]</td>";
            if($appointment['status'] == "Upcoming"){
                echo "<td><button id='start' name='start' value='$appointment[id]'>Start</td></button>";
            } else if($appointment['status'] == "Ongoing"){
                echo "<td><button id='finish' name='start' value='$appointment[id]'>Finish</td></button>";;
            }
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
        if (!empty($schedule)) {
            while ($row = $schedule->fetch_assoc()) {
                switch ($row['department_id']) {
                    case 1:
                        $department = "General";
                }
                $doctor_name = $row['full_name'];
                echo "<tr>";
                echo "<td>$department</td>";
                echo "<td>$doctor_name</td>";
                echo "<td>$row[time]</td>";
                echo "<td>";
                if ($row['availability'] = "available" && empty($doctor_name)) {
                    echo "<button id='select' name='schedule_id' value='$row[schedule_id]' data-value='$_SESSION[id]'>Select</button>";
                } else if ($row['id'] == $_SESSION['id']) {
                    echo "<button id='cancel' name='schedule_id' value='$row[schedule_id]'>Cancel</button>";
                }
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>

    <script>
        $(document).ready(function() {
            $('#select').click(function() {
                let button = document.getElementById('select');
                let schedule_id = $('#select').val();
                let doctor_id = button.getAttribute('data-value');
                $.ajax({
                    url: 'http://localhost/polyclinic/controller/schedule_controller.php?action=select',
                    type: 'POST',
                    data: {
                        schedule_id,
                        doctor_id
                    },
                    success: (payload) => {
                        payload = JSON.parse(payload);
                        if (payload.message === 'success') {
                            location.reload();
                        } else {
                            alert(payload.message);
                        }
                    },
                });
            });

            $('#cancel').click(function() {
                let schedule_id = $('#cancel').val();
                $.ajax({
                    url: 'http://localhost/polyclinic/controller/schedule_controller.php?action=cancel',
                    type: 'POST',
                    data: {
                        schedule_id,
                    },
                    success: (payload) => {
                        payload = JSON.parse(payload);
                        if (payload.message === 'success') {
                            location.reload();
                        } else {
                            alert(payload.message);
                        }
                    },
                });
            });

            $('#start').click(function() {
                let appointment_id = $('#start').val();
                $.ajax({
                    url: 'http://localhost/polyclinic/controller/appointment_controller.php?action=startAppointment',
                    type: 'POST',
                    data: {
                        appointment_id
                    },
                    success: (payload) => {
                        payload = JSON.parse(payload);
                        if (payload.message === 'success') {
                            location.reload();
                        } else {
                            alert(payload.message);
                        }
                    },
                });
            });

            $('#finish').click(function() {
                let appointment_id = $('#finish').val();
                $.ajax({
                    url: 'http://localhost/polyclinic/controller/appointment_controller.php?action=finishAppointment',
                    type: 'POST',
                    data: {
                        appointment_id
                    },
                    success: (payload) => {
                        payload = JSON.parse(payload);
                        if (payload.message === 'success') {
                            location.reload();
                        } else {
                            alert(payload.message);
                        }
                    },
                });
            });
        });
    </script>
</body>

</html>