<?php session_start();
isset($_SESSION['doctor_id']) ? header('location: panel') : NULL;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form method="POST" action="../controller/doctor_controller.php">
        Username <input type="text" name="username"><br>
        Password <input type="password" name="password"><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>

</html>