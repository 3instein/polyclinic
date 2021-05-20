<?php
session_start();

if (isset($_POST['register'])) {
    require_once 'connect.php';

    $username =  $conn->real_escape_string($_POST['username']);

    $target_dir = "../images/profile/";
    $target_file = $target_dir . $username . ".png";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    if($uploadOk == 1){
        $department_id = $conn->real_escape_string($_POST['department']);
        $full_name =  $conn->real_escape_string($_POST['full_name']);
        $password =  $conn->real_escape_string($_POST['password']);
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `doctors` (`id`, `department_id`, `full_name`, `username`, `password`, `session_id`) 
                    VALUES (NULL, '$department_id', '$full_name', '$username', '$password', NULL)";

        if ($conn->query($sql)) {
            header('location: ../doctor/login');
        } else {
            echo "Register failed";
        }
    } else {
        header('location: ../doctor/register');
    }
    $conn->close();

}

if (isset($_POST['login'])) {
    require_once 'connect.php';

    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM doctors WHERE username='$username'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                if (empty($row['session_id'])) {
                    session_start();
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['department_id'] = $row['department_id'];
                    $_SESSION['full_name'] = $row['full_name'];
                    $_SESSION['username'] = $row['username'];
                    header('location: ../doctor/panel');
                }
            }
        }
    }
    $conn->close();
}

    function getDoctor($doctor_id, $request){
        require 'connect.php';
        $sql = "SELECT * FROM doctors WHERE id='$doctor_id'";
        $result = $conn->query($sql);
        if($result-> num_rows > 0){
            if($request == "name"){
                $name = $result->fetch_assoc();
                return $name['full_name'];
            }
        }
    }

?>
