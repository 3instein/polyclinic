<?php include '../controller/base_url.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php
if (isset($_SESSION['patient_id'])) {
    header('location: panel');
}
session_start();
echo isset($_SESSION['error']) ? $_SESSION['error'] : NULL;

unset($_SESSION['error']);
?>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <base href="<?= base ?>">

    <style>
        <?php include '../dist/css/main.css'; ?>
    </style>

    <link rel="stylesheet" href="" />
    <title>Schedule Your Appoinment | PolyClinic</title>
</head>

<body>
    <div class="blurred_bg"></div>
    <nav class="container" id="home">
        <a href="" class="navbar_brand">
            <img src="<?= base ?>dist/img/logo.svg" alt="Logo" />
            <p>Polyclinic</p>
        </a>
    </nav>

    <section class="container">
        <div class="form_wrapper">
            <div class="slider"></div>
            <div class="register_form">
                <p id="change_authentication_register">Returning Patient?</p>
                <form method="POST" action="<?= base ?>controller/patient_controller.php">
                    <label for="name">Name</label>
                    <input type="text" name="full_name" />
                    <label for="address">Address</label>
                    <input type="text" name="address" />
                    <label for="address">Email</label>
                    <input type="text" name="email" />
                    <label for="contact">Contact</label>
                    <input type="text" name="contact" />
                    <label for="id">ID Card Number</label>
                    <input type="text" name="id_number" />
                    <label for="pin">PIN</label>
                    <input type="password" name="pin" />
                    <button type="submit" name="register">Register</button>
                </form>
            </div>
            <div class="login_form">
                <p id="change_authentication_login">New Patient?</p>
                <form method="POST" action="<?= base ?>controller/patient_controller.php">
                    <label for="id_number">ID Card Number</label>
                    <input type="text" name="id_number">
                    <label for="pin">PIN</label>
                    <input type="password" name="pin">
                    <p id="forgot_pin">Forgot PIN?</p>
                    <button type="submit" name="login">Login</button>
                </form>
            </div>
        </div>
    </section>

    <div class="user_forgot_password_overlay">
        <h1>Forgot PIN</h1>
        <div class="id_section">
            <label for="id">ID Card Number</label>
            <input type="text" name="id_number" id="id_number">
            <button id="next_id">Next</button>
        </div>
        <form method="POST" action="<?= base ?>controller/patient_controller.php" class="forgot_pin">
            <label for="token">Token</label>
            <input type="text" name="token" id="token">
            <label for="password">New Password</label>
            <input type="password" name="new_pin">
            <button type="submit" name="changePin">Reset PIN</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            let base = $('head base').attr('href');
            $('.blurred_bg, .forgot_pin, .id_section, .user_forgot_password_overlay').hide();

            $('#change_authentication_register').click(function() {
                $('.slider').animate({
                    right: '50%',
                }).css(
                    "border-radius", "1rem 0 0 1rem"
                );
            });

            $('#change_authentication_login').click(function() {
                $('.slider').animate({
                    right: '0%',
                }).css(
                    "border-radius", "0 1rem 1rem 0"
                );
            });

            $('#forgot_pin').click(function(e) {
                e.preventDefault();

                $('.blurred_bg').show();
                $('.id_section').fadeIn(750);
                $('.user_forgot_password_overlay').animate({
                    height: 'toggle'
                }, 200);
            });

            $('#next_id').click(function(e) {
                let id_number = $('#id_number').val();
                $.ajax({
                    url: base + 'controller/patient_controller.php?action=forgotPIN',
                    type: 'POST',
                    data: {
                        id_number
                    },
                    success: function() {
                        $('.forgot_pin').fadeIn(500);
                        $('.id_section').hide();
                    },
                });
            });
        });
    </script>
</body>

</html>