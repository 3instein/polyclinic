<?php
include '../controller/base_url.php';
include './../controller/appointment_controller.php';
include './../controller/patient_controller.php';
include './../controller/department_controller.php';
include './../controller/schedule_controller.php';
include './../controller/doctor_controller.php';

session_start();
if (!isset($_SESSION['id'])) {
    header('location: ' . BASE_URL . '/patient/authentication');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        <?php include './../dist/css/main.css'; ?>
    </style>
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        function display_c() {
            var refresh = 1000; // Refresh rate in milli seconds
            mytime = setTimeout('display_ct()', refresh)
        }

        function display_ct() {
            var x = new Date()
            document.getElementById('ct').innerHTML = x;
            display_c();
        }
    </script>
    <title>Document</title>
</head>

<body onload=display_ct();>
    <div class="container">
        <nav class="first_nav">
            <a href="./panel" class="navbar_brand">
                <img src="./../dist/img/logo.svg" alt="Logo" />
                <p>Polyclinic</p>
            </a>
            <div class="user_profile_name">
                <p><?= $_SESSION['full_name']; ?></p>
                <img src="./../dist/img/user_logo.svg" alt="user_icon" />
            </div>
        </nav>
        <nav class="second_nav">
            <p class="view_appointment">Appointment</p>
            <p class="view_profile">Profiles</p>
        </nav>
        <div class="view_appointment_page">
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
                    echo "<tr>";
                    echo "<td>$appointment[name]</td>";
                    echo "<td>$appointment[full_name]</td>";
                    echo "<td>$appointment[time]</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <p>Appointment can only be canceled 1 hour before the scheduled time</p>
        </div>
        <div class="view_profile_page">
            <p>Ini profile</p>
            <button id="patientLogout"><a href="BASE_URL/patient/logout">Logout</a></button>
        </div>
    </div>
    <span id="ct"></span>

    <script>
        $(document).ready(function(e) {
            $('.view_profile_page').hide();

            $('.view_appointment').click(function(e) {
                e.preventDefault();

                $('.view_appointment_page').show();
                $('.view_profile_page').hide();
            });

            $('.view_profile').click(function(e) {
                e.preventDefault();

                $('.view_profile_page').show();
                $('.view_appointment_page').hide();
            });
        });
    </script>
</body>

</html>