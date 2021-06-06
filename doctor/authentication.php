<?php
include '../controller/base_url.php';
include '../controller/department_controller.php';
session_start();
isset($_SESSION['doctor_id']) ? header('location: panel') : NULL;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Document</title>
    <base href="<?= base ?>">
    <style>
        <?php include './../dist/css/main.css'; ?>
    </style>
</head>

<body>

    <div class="blurred_bg"></div>

    <div class="doctor_authentication_wrapper">
        <div class="doctor_register">
            <h1>Doctor Register</h1>
            <form method="POST" action="<?= base ?>controller/doctor_controller.php" enctype="multipart/form-data" id="doctorRegisterForm">
                <label for="full_name">Full name</label>
                <input type="text" name="full_name" id="register_full_name">
                <label for="email">Email</label>
                <input type="text" name="email" id="register_email">
                <label for="username">Username</label>
                <input type="text" name="username" id="register_username">
                <label for="password">Password</label>
                <input type="password" name="password" id="register_password">
                <label for="department">Department</label>
                <select name="department">
                    <?php
                    $department = listDepartment();
                    if (!empty($department)) {
                        while ($row = $department->fetch_assoc()) {
                            echo "<option value='$row[id]'>$row[name]</option>";
                        }
                    }
                    ?>
                </select>
                <label for="photo">Photo</label>
                <input type="file" name="fileToUpload" id="fileToUpload" id="register_photo">
                <button type="submit" name="register" id="registerDoc">Register</button>
            </form>
            <p id="isRegisterTrue">
                <?php
                echo isset($_SESSION['error']) ? $_SESSION['error'] : NULL;
                ?>
            </p>
            <p id="doctor_login"><i class="fa fa-arrow-left"></i> Back</p>
        </div>

        <div class="doctor_login">
            <h1>Login Doctor</h1>
            <form method="POST" action="<?= base ?>controller/doctor_controller.php" id="doctorLoginForm">
                <label for="username">Username</label>
                <input type="text" name="username" id="login_username">
                <label for="password">Password</label>
                <input type="password" name="password" id="login_password">
                <p id="doctor_forgot_password">Forgot Password?</p>
                <button type="submit" name="login" id="loginDoc">Login</button>
            </form>
            <p id="isLoginTrue">
                <?php
                echo isset($_SESSION['error']) ? $_SESSION['error'] : NULL;
                ?>
            </p>
            <button id="doctor_register">Create Account?</p>
        </div>
    </div>

    <div class="doctor_forgot_password_overlay">
        <h1>Forgot Password</h1>
        <div class="username_section">
            <label for="username">Username</label>
            <input type="text" name="username" id="username">
            <button id="next_username">Next</button>
        </div>
        <form method="POST" action="<?= base ?>controller/doctor_controller.php" class="forgot_password">
            <label for="token">Token</label>
            <input type="text" name="token" id="token">
            <label for="password">New Password</label>
            <input type="password" name="new_password">
            <button type="submit" name="changePassword">Reset Password</button>
        </form>
    </div>

    <?php unset($_SESSION['error']); ?>
    <script>
        $(document).ready(function() {
            let base = $('head base').attr('href');
            $('.doctor_register, .blurred_bg, .doctor_forgot_password_overlay').hide();
            $('.token_section, .forgot_password, .username_section').hide();
            $('#doctorLoginForm, #doctorRegisterForm').removeAttr('action');

            $('.blurred_bg').click(
                () => {
                    $('.blurred_bg, .doctor_forgot_password_overlay, .username_section').hide();
                    $('.token_section, .forgot_password').hide();
                }
            );

            $('#doctor_register').click(
                () => {
                    $('.doctor_register').fadeIn(500);
                    $('.doctor_login').hide();
                }
            );

            $('#doctor_login').click(
                () => {
                    $('.doctor_login').fadeIn(500);
                    $('.doctor_register').hide();
                }
            );

            $('#doctor_forgot_password').click(
                () => {
                    $('.blurred_bg').show();
                    $('.username_section').fadeIn(750);
                    $('.token_section, .forgot_password').hide();
                    $('.doctor_forgot_password_overlay').animate({
                        height: 'toggle'
                    }, 200);
                }
            );

            $('#next_username').click(function() {
                let username = $('#username').val();
                $.ajax({
                    url: base + 'controller/doctor_controller.php?action=forgotPassword',
                    type: 'POST',
                    data: {
                        username
                    },
                    success: function() {
                        $('.forgot_password').fadeIn(500);
                        $('.username_section').hide();
                    },
                });
            });


            $('#loginDoc').click(function(e) {
                let username = $('#login_username').val();
                let password = $('#login_password').val();

                if (username != "" && password != "") {
                    $('#doctorLoginForm').attr('action', base + 'controller/doctor_controller.php');
                } else {
                    e.preventDefault();
                    $('#doctorLoginForm').removeAttr('action');
                    alert("Fields cannot be empty")
                }
            });

            $('#registerDoc').click(function(e) {
                let full_name = $('#register_full_name').val();
                let email = $('#register_email').val();
                let username = $('#register_username').val();
                let password = $('#register_password').val();

                if (full_name != "" && email != "" && username != "" && password != "") {
                    $('#doctorRegisterForm').attr('action', base + 'controller/doctor_controller.php');
                } else {
                    e.preventDefault();
                    $('#doctorLoginForm').removeAttr('action');
                    alert("Fields cannot be empty")
                }
            });
        });
    </script>
</body>

</html>