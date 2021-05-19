<?php
require '../controller/main_controller.php';

!isset($_SESSION['doctor']) ? header('location: login.php') : null;
$profile_picture = $_SESSION['doctor']->getUsername();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Dashboard</title>
</head>

<body>
    Welcome
    <img src="../images/profile/<?=$profile_picture?>.png" alt=""/>
    <a href="profile.php">Dr. <?php echo $_SESSION['doctor']->getFull_name(); ?></a>
    <form method="post">
        <button name="logout">Logout</button>
    </form>
</body>

<script>

</script>

</html>