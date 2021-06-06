<?php

include '../controller/appointment_controller.php';
include '../controller/doctor_controller.php';

session_start();
if (!isset($_SESSION['doctor_id'])) {
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
                <img src="<?= base ?>images/profile/<?= $_SESSION['profile_picture']; ?>">
                <p>Welcome Dr. <?= $_SESSION['full_name']; ?></p>
                <p>
                    <?php
                    echo $_SESSION['department_id'] == 1 ? $department = "General" : NULL;
                    echo $_SESSION['department_id'] == 2 ? $department = "Eye" : NULL;
                    echo $_SESSION['department_id'] == 3 ? $department = "Dentist" : NULL;
                    ?>
                </p>
                <button><a href="<?= base ?>doctor/logout">Logout</a></button>
            </div>
        </div>

        <div class="right_panel">
            <nav>
                <p class="view_appointment_doctor">Appointment</p>
                <p class="view_schedule_doctor">Schedule</p>
            </nav>
            <div class="hamburger_doctor_icon">
                <p>Doctor's Panel</p>
                <img src="<?= base ?>/dist/img/list.svg" alt="">
            </div>
            <div class="hamburger_doctor_menu">
                <p class="view_appointment_doctor_mobile">Appointment</p>
                <p class="view_schedule_doctor_mobile">Schedule</p>
            </div>
            <div class="view_doctor_appointment">
                <table>
                    <tr>
                        <th>Patient</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $appointments = getDoctorAppointment($_SESSION['doctor_id']);
                    if (!empty($appointments)) {
                        while ($appointment = $appointments->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>$appointment[full_name]</td>";
                            echo "<td>$appointment[day]</td>";
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
                <div class="view_appointment_mobile">
                    <div class="doctor_profile_mobile">
                        <img src="<?= base ?>images/profile/<?= $_SESSION['profile_picture']; ?>">
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
                    <h1>Your Appointment</h1>
                    <?php
                    $appointments = getDoctorAppointment($_SESSION['doctor_id']);
                    if (!empty($appointments)) {
                        while ($appointment = $appointments->fetch_assoc()) {
                            echo "<div class=view_appointment_doctor_card_mobile>";
                            echo "<p>$appointment[status]</p>";
                            echo "<h2>$appointment[status]</h2>";
                            echo "<p>$appointment[day] $appointment[time]</p>";
                            if ($appointment['status'] == "Upcoming") {
                                echo "<button id='start' name='start' value='$appointment[id]'>Start</button>";
                            } else if ($appointment['status'] == "Ongoing") {
                                echo "<button id='finish' name='finish' value='$appointment[id]'>Finish</button>";;
                            }
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="view_doctor_schedules">
                <?php
                if (isset($_SESSION['hod'])) {
                    echo "<button id='create'>Create Schedule</button>";
                }
                ?>
                <table>
                    <tr>
                        <th>Department</th>
                        <th>Doctor</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Action</th>
                        <?php
                        if (isset($_SESSION['hod'])) {
                            echo "<th>HOD Action</th>";
                        }
                        ?>
                    </tr>
                    <?php
                    $schedule = getSchedule($_SESSION['department_id']);
                    if (!empty($schedule)) {
                        while ($row = $schedule->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>$row[name]</td>";
                            if (isset($_SESSION['hod'])) {
                                if (!empty($row['full_name'])) {
                                    echo "<td>$row[full_name] <button id='remove' value='$row[schedule_id]'>Remove</button></td>";
                                } else {
                                    echo "<td></td>";
                                }
                            } else {
                                echo "<td>$row[full_name]</td>";
                            }
                            echo "<td>$row[day]</td>";
                            echo "<td>$row[time]</td>";
                            echo "<td>";
                            if ($row['availability'] = "available" && empty($row['full_name'])) {
                                echo "<button id='select' name='schedule_id' value='$row[schedule_id]' data-value='$_SESSION[doctor_id]'>Select</button>";
                            } else if ($row['id'] == $_SESSION['doctor_id']) {
                                echo "<button id='cancel' name='schedule_id' value='$row[schedule_id]'>Cancel</button>";
                            }
                            echo "</td>";
                            if (isset($_SESSION['hod'])) {
                                echo "<td>";
                                echo "<button id='delete' value='$row[schedule_id]'>Delete</button>";
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
                <div class="view_schedule_mobile">

                </div>
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
            $('.view_doctor_schedules, .blurred_bg, .doctor_note_overlay, .hamburger_doctor_menu').hide();
            $(".view_appointment_doctor_mobile").addClass('selected_doctor_ham_menu');

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

            $('#create').click(function() {

            });

            $('#remove').click(function() {
                let schedule_id = $('#remove').val();
                $.ajax({
                    url: base + 'controller/schedule_controller.php?action=removeDoctor',
                    type: 'POST',
                    data: {
                        schedule_id
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

            $('#delete').click(function() {
                let schedule_id = $('#delete').val();
                $.ajax({
                    url: base + 'controller/schedule_controller.php?action=deleteSchedule',
                    type: 'POST',
                    data: {
                        schedule_id
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

            $('.hamburger_doctor_icon img').click(function() {
                $('.hamburger_doctor_menu').animate({
                    height: 'toggle'
                });
            });

            $('.view_appointment_doctor_mobile').click(function() {
                $('.view_doctor_appointment').show();
                $('.view_doctor_schedules, .hamburger_doctor_menu').hide();
                $(".view_appointment_doctor_mobile").addClass('selected_doctor_ham_menu');
                $(".view_schedule_doctor_mobile").removeClass('selected_doctor_ham_menu');
            });

            $('.view_schedule_doctor_mobile').click(function() {
                $('.view_doctor_appointment, .hamburger_doctor_menu').hide();
                $('.view_doctor_schedules').show();
                $(".view_appointment_doctor_mobile").removeClass('selected_doctor_ham_menu');
                $(".view_schedule_doctor_mobile").addClass('selected_doctor_ham_menu');
            });
        });
    </script>
</body>

</html>