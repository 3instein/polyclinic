<?php

if (isset($_GET['token']) && isset($_GET['id_number'])) {
    require_once '../controller/patient_controller.php';
    if (validatePatientToken($_GET['token'])) {
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
    <title>Reset Your Password</title>

    <style>
        <?php include './../dist/css/main.css'; ?>
    </style>
</head>

<body>
    <div class="forgot_password_wrapper">
        <h1>Reset PIN</h1>
        <form method="POST" class="forgot_password">
            <label for="username">ID Number</label>
            <input type="text" name="id_number" value="<?= $_GET['id_number'] ?>">
            <label for="token">Token</label>
            <input type="text" name="token" value="<?= $_GET['token'] ?>"">
            <label for="password">New PIN</label>
            <input type="password" name="new_pin">
            <button type="submit" name="changePin">Reset PIN</button>
        </form>
    </div>
</body>

</html>