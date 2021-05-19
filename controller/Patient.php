<?php

    class Patient {
        private $patient_id;
        private $id_number;
        private $full_name;
        private $address;
        private $contact;

        public function __construct($patient_id, $id_number, $full_name, $address, $contact){
            $this->patient_id = $patient_id;
            $this->id_number = $id_number;
            $this->full_name = $full_name;
            $this->address = $address;
            $this->contact = $contact;
        }
    
        public function getPatient_id() {
            return $this->patient_id;
        }
        
        public function setPatient_id($patient_id) {
            $this->patient_id = $patient_id;
        }

        public function getId_number() {
            return $this->id_number;
        }
    
        public function setId_number($id_number){
            $this->id_number = $id_number;
        }
    
        public function getFull_name() {
            return $this->full_name;
        }
    
        public function setFull_name($full_name){
            $this->full_name = $full_name;
        }
        
        public function getAddress() {
            return $this->address;
        }
        
        public function setAddress($address){
            $this->address = $address;
        }

        public function getContact(){
            return $this->contact;
        }
        
        public function setContact($contact){
            $this->contact = $contact;
        }

        public function register() {
            require_once 'connect.php';
            $id_number = $conn->real_escape_string($this->getId_number());
            $full_name = $conn->real_escape_string($this->getFull_name());
            $address = $conn->real_escape_string($this->getAddress());
            $contact = $conn->real_escape_string($this->getContact());
            $sql = "INSERT INTO `patient` (`patient_id`, `id_number`, `full_name`, `address`, `contact`) 
                VALUES (NULL, '$id_number', '$full_name', '$address', '$contact')";
            if ($conn->query($sql)) {
                echo "Patient Added !";
            }
        }
    } 
?>