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
        Username <input type="text" name="username"><br>
        Password <input type="password" name="password"><br>
        <button type="submit" name="login">Login</button>
    </form>
    
</body>
</html>