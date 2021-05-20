<?php
    include '../controller/doctor_controller.php';
    include '../controller/schedule_controller.php';
    !isset($_SESSION['id']) ? header('location: login') : NULL;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <img src="../images/profile/<?= $_SESSION['username']; ?>.png">
    Welcome Dr. <?= $_SESSION['full_name']; ?>
    <button><a href="logout">Logout</a></button><br>
    Select Schedule<br>
    <form method="POST" action="../controller/schedule_controller.php">
        <select name="schedule">
            <?php
                $schedule = getSchedule($_SESSION['department_id']);
                if(!empty($schedule)){
                    while($row = $schedule->fetch_assoc()){
                        echo "<option value='$row[id]'>$row[time]</option>";
                    }
                } else {
                    echo "<option>-</option>";
                }
            ?>
        </select>
        <button type="submit" name="select_schedule">Select</button>
    </form>
</body>
</html>