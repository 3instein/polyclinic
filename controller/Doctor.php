<?php


class Doctor
{

    private $id;
    private $department_id;
    private $full_name;
    private $username;
    private $password;

    public function __construct($id, $department_id, $full_name, $username, $password)
    {
        $this->id = $id;
        $this->department_id = $department_id;
        $this->full_name = $full_name;
        $this->username = $username;
        $this->password = $password;
    }

    public function Doctor()
    {
    }

    public function register()
    {
        require_once 'connect.php';
        $department_id = $conn->real_escape_string($this->getDepartment_id());
        $full_name = $conn->real_escape_string($this->getFull_name());
        $username = $conn->real_escape_string($this->getUsername());
        $password = $conn->real_escape_string($this->getPassword());
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `doctors` (`id`, `department_id`, `full_name`, `username`, `password`) 
                VALUES (NULL, '$department_id', '$full_name', '$username', '$password')";
        $conn->query($sql);
    }

    public function login()
    {
        require_once 'connect.php';
        $username = $conn->real_escape_string($this->getUsername());
        $password = $conn->real_escape_string($this->getPassword());

        $sql = "SELECT * FROM `doctors` WHERE username = '$username'";
        $results = $conn->query($sql);
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    if (empty($row['session_id'])) {
                        session_start();
                        $_SESSION['doctor'] = new Doctor($row['id'], $row['department_id'], $row['full_name'], $row['username'], "");
                        //Authentication Hash
                        $hash = hash('sha256', time());
                        $sql = "UPDATE `doctors` SET `session_id` = '$hash' WHERE `doctors`.`id` = $row[id]";
                        $conn->query($sql);
                        header('location: panel.php');
                    } else {
                        echo "User already logged in!";
                    }
                } else {
                    echo "Wrong username / password";
                }
            }
        } else {
            echo "Wrong username / password";
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDepartment_id()
    {
        return $this->department_id;
    }

    public function getFull_name()
    {
        return $this->full_name;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}
