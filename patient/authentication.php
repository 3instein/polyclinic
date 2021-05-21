<!DOCTYPE html>
<html lang="en">

<head>
    <base href="http://localhost/polyclinic/">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./dist/css/main.css" />
    <title>Schedule Your Appoinment | PolyClinic</title>
</head>

<body>
    <nav class="container" id="home">
        <a href="./../" class="navbar_brand">
            <img src="./../dist/img/logo.svg" alt="Logo" />
        </a>
        <div class="navbar_nav">
            <a href="./../index.html" class="navbar_link">Home</a>
            <a href="#appoinment" class="navbar_link">View Appoinment</a>
        </div>
    </nav>  

    <section class="container">
        <div class="register_form">
            <form method="POST" action="./../controller/patient_controller.php">
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
            <form action="controller/patient_controller.php" method="POST">
                <label for="id_number">ID Card Number</label>
                <input type="text" name="id_number">
                <label for="pin">PIN</label>
                <input type="password" name="pin">
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </section>
</body>

</html>