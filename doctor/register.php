<?php
include '../controller/department_controller.php';
session_start();
isset($_SESSION['id']) ? header('location: panel') : NULL;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <form method="POST" action="../controller/doctor_controller.php" enctype="multipart/form-data">
        Full name<input type="text" name="full_name"><br>
        Username<input type="text" name="username"><br>
        Password<input type="password" name="password"><br>
        Department
        <select name="department">
            <?php
            $department = listDepartment();
            if (!empty($department)) {
                while ($row = $department->fetch_assoc()) {
                    echo "<option value='$row[id]'>$row[name]</option>";
                }
            }
            ?>
        </select>
        <br>
        Photo<input type="file" name="fileToUpload" id="fileToUpload">
        <br>
        <button type="submit" name="register">Register</button>
    </form>
</body>

</html>