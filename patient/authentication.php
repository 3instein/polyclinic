<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        <?php include '../dist/css/main.css'; ?>
    </style>

    <link rel="stylesheet" href="" />
    <title>Schedule Your Appoinment | PolyClinic</title>
</head>

<body>
    <nav class="container" id="home">
        <a href="" class="navbar_brand">
            <img src="./../dist/img/logo.svg" alt="Logo" />
        </a>
        <div class="navbar_nav">
            <a href="" class="navbar_link">Home</a>
        </div>
    </nav>

    <section class="container">
        <div class="form_wrapper">
            <div class="slider"></div>
            <div class="register_form">
                <p id="change_authentication_register">Returning Patient?</p>
                <form method="POST" action="../controller/patient_controller.php">
                    <label for="name">Name</label>
                    <input type="text" name="full_name" />
                    <label for="address">Address</label>
                    <input type="text" name="address" />
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
                <form method="POST" action="../controller/patient_controller.php">
                    <label for="id_number">ID Card Number</label>
                    <input type="text" name="id_number">
                    <label for="pin">PIN</label>
                    <input type="password" name="pin">
                    <button type="submit" name="login">Login</button>
                </form>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
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
        });
    </script>
</body>

</html>