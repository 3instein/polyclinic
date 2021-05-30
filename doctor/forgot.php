<?php

if (isset($_GET['token']) && isset($_GET['username'])) {
    require_once '../controller/doctor_controller.php';
    if (validateToken($_GET['token'])) {

    } else {
        die("Link invalid");
    }
} else {
    die("Link invalid!");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="POST" action="" class="forgot_password">
        <label for="username">Username</label>
        <input type="text" name="username" value='<?=$_GET['username']?>'>
        <label for="token">Token</label>
        <input type="text" name="token" value='<?=$_GET['token']?>'>
        <label for="password">New Password</label>
        <input type="password" name="new_password">
        <button type="submit" name="changePassword">Reset Password</button>
    </form>
</body>

</html>