<?php
include '../controller/base_url.php';
include '../controller/appointment_controller.php';
include '../controller/doctor_controller.php';
include '../controller/schedule_controller.php';
include '../controller/patient_controller.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('location: authentication');
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
    <base href="<?= base ?>">
    <style>
        <?php include './../dist/css/main.css'; ?>
    </style>
</head>

<body>
    <div class="blurred_bg"></div>

    <section class="container doctor_dashboard">
        <div class="left_panel">
            <nav>
                <img src="<?= base ?>dist/img/logo.svg" alt="">
                <p>Polyclinic</p>
            </nav>
            <div class="doctor_profile">
                <img src="<?= base ?>images/profile/<?= $_SESSION['username']; ?>.png">
                <p>Welcome Dr. <?= $_SESSION['full_name']; ?></p>
                <p>
                    <?php
                    $_SESSION['department_id'] == 1 ? $department = "General" : NULL;
                    $_SESSION['department_id'] == 2 ? $department = "Eye" : NULL;
                    $_SESSION['department_id'] == 3 ? $department = "Dentist" : NULL;
                    echo $department;
                    ?>
                </p>
                <button><a href="<?= base ?>doctor/logout">Logout</a></button>
            </div>
        </div>

        <div class="right_panel">
            <nav>
                <p class="view_appointment_doctor">Appointment</p>
                <p class="view_schedule_doctor">Schedule</p>
                <p class="view_profile_doctor">Profile</p>
            </nav>
            <div class="view_doctor_appointment">
                <table>
                    <tr>
                        <th>Patient</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $appointments = getDoctorAppointment($_SESSION['id']);
                    if (!empty($appointments)) {
                        while ($appointment = $appointments->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>$appointment[full_name]</td>";
                            echo "<td>$appointment[time]</td>";
                            echo "<td>$appointment[status]</td>";
                            if ($appointment['status'] == "Upcoming") {
                                echo "<td><button id='start' name='start' value='$appointment[id]'>Start</td></button>";
                            } else if ($appointment['status'] == "Ongoing") {
                                echo "<td><button id='finish' name='finish' value='$appointment[id]'>Finish</td></button>";;
                            } else {
                                echo "<td></td>";
                            }
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div>
            <div class="view_doctor_schedules">
                <table>
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
            </div>
            <div class="view_doctor_profile">

            </div>
        </div>

        <div class="doctor_note_overlay">
            <h1>Give Note to Patient</h1>
            <form method="POST" action="<?= base ?>controller/appointment_controller.php" class="doctor_note">
                <textarea name="note" cols="50" rows="10" id="note"></textarea>
                <button id="addNote" type="submit" name="addNote" value="">Done</button>
            </form>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            let base = $('head base').attr('href');
            $('.view_appointment_doctor').addClass('selected_doctor_menu');
            $('.view_doctor_schedules, .blurred_bg, .doctor_note_overlay').hide();

            $('.view_appointment_doctor').click(function(e) {
                e.preventDefault();

                $('.view_appointment_doctor').addClass('selected_doctor_menu');
                $('.view_profile_doctor').removeClass('selected_doctor_menu');
                $('.view_schedule_doctor').removeClass('selected_doctor_menu');
                $('.view_doctor_appointment').show();
                $('.view_doctor_schedules').hide();
                $('.view_doctor_profile').hide();
            });

            $('.view_schedule_doctor').click(function(e) {
                e.preventDefault();

                $('.view_schedule_doctor').addClass('selected_doctor_menu');
                $('.view_appointment_doctor').removeClass('selected_doctor_menu');
                $('.view_profile_doctor').removeClass('selected_doctor_menu');
                $('.view_doctor_schedules').show();
                $('.view_doctor_appointment').hide();
                $('.view_doctor_profile').hide();
            });

            $('.view_profile_doctor').click(function(e) {
                e.preventDefault();

                $('.view_profile_doctor').addClass('selected_doctor_menu');
                $('.view_schedule_doctor').removeClass('selected_doctor_menu');
                $('.view_appointment_doctor').removeClass('selected_doctor_menu');
                $('.view_doctor_schedules').hide();
                $('.view_doctor_appointment').hide();
                $('.view_doctor_profile').show();
            });

            $('.blurred_bg').click(function() {
                $('.blurred_bg').hide();
                $('.doctor_note').hide();
                $('.doctor_note_overlay').hide();
            });

            $('#select').click(function() {
                let button = document.getElementById('select');
                let schedule_id = $('#select').val();
                let doctor_id = button.getAttribute('data-value');
                $.ajax({
                    url: base + 'controller/schedule_controller.php?action=select',
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
                    url: base + 'controller/schedule_controller.php?action=cancel',
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
                    url: base + 'controller/appointment_controller.php?action=startAppointment',
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
                    url: base + 'controller/appointment_controller.php?action=finishAppointment',
                    type: 'POST',
                    data: {
                        appointment_id
                    },
                    success: (payload) => {
                        payload = JSON.parse(payload);
                        if (payload.message === 'success') {
                            $('.blurred_bg').show();
                            $('.doctor_note').fadeIn(500);
                            $('.doctor_note_overlay').animate({
                                height: 'toggle'
                            }, 200);
                            $('#addNote').val(appointment_id);
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