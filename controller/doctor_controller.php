<?php

switch ($_GET['action'] ?? '') {
    case 'forgotPassword':
        echo json_encode(['message' => 'success', 'data' => forgotPassword(@$_POST['username'])]);
        break;
}

if (isset($_POST['register'])) {
    require 'connect.php';

    $username =  $_POST['username'];

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
    if ($_FILES["fileToUpload"]["size"] > 50000000) {
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

    if ($uploadOk == 1) {
        $department_id = $_POST['department'];
        $full_name =  $_POST['full_name'];
        $password =  $_POST['password'];
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `doctors` (`id`, `department_id`, `full_name`, `username`, `password`, `session_id`) 
                    VALUES (NULL, ?, ?, ?, ?, NULL)";

        $query = $conn->prepare($sql);
        $query->bind_param("isss", $department_id, $full_name, $username, $password);

        if ($query->execute()) {
            $sql = "SELECT `id`, `department_id`, `full_name`, `username` FROM `doctors` WHERE `username` = ?";
            $query = $conn->prepare($sql);
            $query->bind_param("s", $username);

            if ($query->execute()) {
                $result = $query->get_result();
                $result = $result->fetch_assoc();

                session_start();
                $_SESSION['id'] = $result['id'];
                $_SESSION['department_id'] = $result['department_id'];
                $_SESSION['full_name'] = $result['full_name'];
                $_SESSION['username'] = $result['username'];
                header('location: ../doctor/panel');
            }
        }
    } else {
        header('location: ../doctor/authentication');
    }
    $conn->close();
}

if (isset($_POST['login'])) {
    require 'connect.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM doctors WHERE username=?";
    $query = $conn->prepare($sql);
    $query->bind_param("s", $username);

    if ($query->execute()) {
        $result = $query->get_result();

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

if (isset($_POST['changePassword'])) {
    require 'connect.php';

    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $new_password = password_hash($new_password, PASSWORD_DEFAULT);

    $sql = "SELECT `doctor_id`, `token` FROM `doctors_token` WHERE `token` = ?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $token);

    if ($query->execute()) {
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $result = $result->fetch_assoc();
            $doctor_id = $result['doctor_id'];

            $sql = "DELETE FROM `doctors_token` WHERE `doctor_id` = ?";
            $query = $conn->prepare($sql);
            $query->bind_param("i", $doctor_id);

            if ($query->execute()) {
                changePassword($new_password, $doctor_id);
                header('location: ../doctor/authentication');
            }
        }
    }

    $conn->close();
}

function forgotPassword($username) {
    require 'connect.php';

    $token = rand(1000, 9999);
    $subject = "Forgot Password Token";
    $link = "http://localhost/polyclinic/doctor/forgot?token=$token&username=$_POST[username]"; /*-- jika sudah hosting, ubah dengan link URL website --*/
    $msg = "Token Forgot Password anda adalah $token atau anda bisa mengganti password anda melalui link berikut $link";

    $sql = "SELECT id, email FROM doctors WHERE username=?";
    $query = $conn->prepare($sql);
    $query->bind_param("s", $_POST['username']);

    if ($query->execute()) {
        $data = $query->get_result();
        $data = $data->fetch_assoc();
        $doctor_id = $data['id'];
        $target_email = $data['email'];

        $sql = "INSERT INTO `doctors_token` (`doctor_id`, `token`) VALUES (?, ?)";
        $query = $conn->prepare($sql);
        $query->bind_param("ii", $doctor_id, $token);

        if ($query->execute()) {

            require 'mail_controller.php';
            sendMail($subject, $target_email, $msg);
        }
    }

    $conn->close();
}

function validateToken($token) {
    require 'connect.php';

    $sql = "SELECT id, token FROM doctors_token WHERE token = ?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $token);

    if ($query->execute()) {
        $result = $query->get_result();
        $result = $result->fetch_assoc();
        if ($result['token'] == $token) {
            return true;
        } else {
            return false;
        }
    }
    $conn->close();
}

function changePassword($new_password, $doctor_id) {
    require 'connect.php';
    $sql = "UPDATE `doctors` SET `password` = ? WHERE `id` = ?";
    $query = $conn->prepare($sql);
    $query->bind_param("si", $new_password, $doctor_id);
    $query->execute();
    $conn->close();
}

function getDoctor($doctor_id, $request) {
    require 'connect.php';
    $sql = "SELECT * FROM doctors WHERE id='$doctor_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        if ($request == "name") {
            $name = $result->fetch_assoc();
            return $name['full_name'];
        }
    }

    $conn->close();
}
