<?php

include '../controller/appointment_controller.php';
include '../controller/patient_controller.php';

session_start();
if (!isset($_SESSION['patient_id'])) {
    header('location: ' . base . '/patient/authentication');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= base; ?>">

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

    <div class="blurred_bg"></div>

    <div class="container">
        <nav class="first_nav">
            <a href="./panel" class="navbar_brand">
                <img src="<?= base ?>dist/img/logo.svg" alt="Logo" />
                <p>Polyclinic</p>
            </a>
            <p>Patient's Panel</p>
        </nav>
        <nav class="second_nav">
            <p class="view_appointment">Appointment</p>
            <p class="view_profile">Profile</p>
            <p class="view_screening">Screening</p>
        </nav>
        <div class="view_appointment_page">
            <table>
                <tr>
                    <th>Department</th>
                    <th>Doctor</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php
                $appointment = getPatientAppointment($_SESSION['patient_id']);
                if (!empty($appointment)) {
                    while ($row = $appointment->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>$row[name]</td>";
                        echo "<td>$row[full_name]</td>";
                        echo "<td>$row[day]</td>";
                        echo "<td>$row[time]</td>";
                        echo "<td>$row[status]</td>";
                        if ($row['status'] == "Upcoming") {
                            if($row['day'] == "Monday"){
                                $day = 1;
                            } else if($row['day'] == "Tuesday"){
                                $day = 2;
                            } else if($row['day'] == "Wednesday"){
                                $day = 3;
                            } else if($row['day'] == "Thursday"){
                                $day = 4;
                            } else if($row['day'] == "Friday"){
                                $day = 5;
                            } else if($row['day'] == "Saturday"){
                                $day = 6;
                            } else if($row['day'] == "Sunday"){
                                $day = 0;
                            }
                            if($day != date("w")){
                                echo "<td><button id='cancel_appointment' name='cancelAppointment' value='$row[id]' data-value='$row[schedule_id]'>Cancel Appointment</button></td>";
                            } else {
                                echo "<td></td>";
                            }
                        } else if ($row['status'] == "Finished") {
                            echo "<td><button id='view_note' name='view_note' value='$row[id]'>View Note</button></td>";
                        } else {
                            echo "<td></td>";
                        }
                        echo "</tr>";
                    }
                }
                ?>
                <tr>
                    <td>
                        <a href="<?= base; ?>patient/appointment.php" id="make_new_appointment">New Appointment</a>
                    </td>
                </tr>
            </table>
            <p>Appointment can only be canceled 1 day before!</p>
        </div>
        <div class="view_profile_page">
            <div class="user_profile_section">
                <img src="<?= base ?>dist/img/user_logo.svg" alt="user">
                <p>Hello, <?= $_SESSION['full_name']; ?></p>
                <p><?= $_SESSION['id_number']; ?></p>
                <p id="edit_user_profile">Edit Profile</p>
                <a href="<?= base; ?>patient/logout" id="patientLogout">Logout</a>
            </div>
            <div class="user_profile_detail">
                <img src="<?= base ?>dist/img/email.svg" />
                <p><?= $_SESSION['email']; ?></p>
                <img src="<?= base ?>dist/img/phone.svg" />
                <p><?= $_SESSION['contact']; ?></p>
                <img src="<?= base ?>dist/img/location.svg" />
                <p><?= $_SESSION['address']; ?></p>
            </div>

            <div class="change_profile_detail">
                <form method="POST" action="<?= base ?>controller/patient_controller.php">
                    <label for="email">Email</label>
                    <input type="text" name="email" <?php echo "placeholder='" . $_SESSION['email'] . "'"; ?> id="email">
                    <label for="contact">Contact</label>
                    <input type="text" name="contact" <?php echo "placeholder='" . $_SESSION['contact'] . "'"; ?> id="contact">
                    <label for="address">Address</label>
                    <input type="text" name="address" <?php echo "placeholder='" . $_SESSION['address'] . "'"; ?> id="address">
                    <button name="edit_patient" id="edit_patient">Save Change</button>
                </form>
            </div>
        </div>
        <div class="view_screening_page">
            <p>Screening</p>
            <ol>
                <?php
                $screening = readScreening($_SESSION['patient_id']);
                $data = $screening->fetch_assoc();
                $result = json_decode($data['result']);
                echo "<p>Time : $data[time]</p>";
                echo "<li>Any allergic reaction to medication</li>";
                if ($result->question1 == "true") {
                    echo "<input type='radio' name='question1' checked disabled>Yes";
                    echo "<input type='radio' name='question1' disabled>No";
                } else {
                    echo "<input type='radio' name='question1' disabled>Yes";
                    echo "<input type='radio' name='question1' checked disabled>No";
                }
                echo "<li>High blood pressure</li>";
                if ($result->question2 == "true") {
                    echo "<input type='radio' name='question2' checked disabled>Yes";
                    echo "<input type='radio' name='question2' disabled>No";
                } else {
                    echo "<input type='radio' name='question2' disabled>Yes";
                    echo "<input type='radio' name='question2' checked disabled>No";
                }
                ?>
            </ol>
        </div>
        <span id="ct"></span>
    </div>

    <div class="view_note_overlay">
        <h1>Note</h1>
        <textarea id="display_note" cols="30" rows="10"></textarea>
    </div>

    <script>
        $(document).ready(function(e) {
            let base = $('head base').attr('href');
            $('.view_profile_page, .change_profile_detail').hide();
            $('.view_screening_page, .blurred_bg, .view_note_overlay').hide();

            $('.view_appointment').css(
                "font-weight", "800"
            ).addClass('selected_menu');

            $('.view_appointment').click(function(e) {
                e.preventDefault();

                $('.view_appointment').css(
                    "font-weight", "800"
                ).addClass('selected_menu');

                $('.view_profile').css(
                    "font-weight", "500"
                ).removeClass('selected_menu');

                $('.view_screening').css(
                    "font-weight", "500"
                ).removeClass('selected_menu');

                $('.view_appointment_page').show();
                $('.view_profile_page').hide();
                $('.view_screening_page').hide();
            });

            $('.view_profile').click(function(e) {
                e.preventDefault();

                $('.view_profile').css(
                    "font-weight", "800"
                ).addClass('selected_menu');

                $('.view_appointment').css(
                    "font-weight", "500"
                ).removeClass('selected_menu');

                $('.view_screening').css(
                    "font-weight", "500"
                ).removeClass('selected_menu');

                $('.view_profile_page').show();
                $('.view_appointment_page').hide();
                $('.view_screening_page').hide();
            });

            $('.view_screening').click(function(e) {
                e.preventDefault();

                $('.view_screening').css(
                    "font-weight", "800"
                ).addClass('selected_menu');

                $('.view_appointment').css(
                    "font-weight", "500"
                ).removeClass('selected_menu');

                $('.view_profile').css(
                    "font-weight", "500"
                ).removeClass('selected_menu');

                $('.view_screening_page').show();
                $('.view_appointment_page').hide();
                $('.view_profile_page').hide();
            });

            $('#cancel_appointment').click(function() {
                let button = document.getElementById('cancel_appointment');
                let schedule_id = button.getAttribute('data-value');
                let appointment_id = $('#cancel_appointment').val();
                $.ajax({
                    url: base + '/controller/appointment_controller.php?action=cancelAppointment',
                    type: 'POST',
                    data: {
                        appointment_id,
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

            $('#view_note').click(function() {
                let appointment_id = $('#view_note').val();
                $.ajax({
                    url: base + '/controller/appointment_controller.php?action=viewNote',
                    type: 'POST',
                    data: {
                        appointment_id
                    },
                    success: (payload) => {
                        payload = JSON.parse(payload);
                        if (payload.message === 'success') {
                            let data = '';
                            payload.data.forEach(element => {
                                let json = $.parseJSON(element.note);
                                data += json.Note;
                            });
                            $('.blurred_bg, #display_note').show();
                            $('.view_note_overlay, display_note').animate({
                                height: 'toggle'
                            }, 200);
                            $('.view_note_overlay #display_note').html(data);
                        } else {
                            alert(payload.message);
                        }
                    },
                });
            });

            $('.blurred_bg').click(function() {
                $('.view_note_overlay, #display_note, .blurred_bg').hide();
            });

            $('#edit_user_profile').click(
                (e) => {
                    e.preventDefault();

                    $('.change_profile_detail').show();
                    $('.user_profile_detail').hide();
                }
            );

            $('#edit_patient').click(function() {
                let email = $('#email').attr('placeholder');
                let contact = $('#contact').attr('placeholder');
                let address = $('#address').attr('placeholder');

                if ($('#email').val() == "") {
                    $('#email').val(email);
                }

                if ($('#contact').val() == "") {
                    $('#contact').val(contact);
                }

                if ($('#address').val() == "") {
                    $('#address').val(address);
                }
            });
        });
    </script>
</body>

</html>