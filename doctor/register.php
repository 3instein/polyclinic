<?php
    include '../controller/main_controller.php';
    isset($_SESSION['doctor']) ? header('location: panel.php') : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Panel</title>
</head>

<body>
    <form method="post">
        Full Name <input type="text" name="full_name"><br>
        Username <input type="text" name="username"><br>
        Password <input type="password" name="password"><br>
        Department<br>
        <select name="department">
            <option value="1">General</option>
        </select>
        <button type="submit" name="register">Register</button>
    </form>
</body>

</html>