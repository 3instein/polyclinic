<?php

function sendMail($subject, $target_email, $msg) {
    require_once '../PHPMailer/src/PHPMailer.php';
    require_once '../PHPMailer/src/SMTP.php';
    require_once '../PHPMailer/src/Exception.php';

    $email_sender = "rkindarto@student.ciputra.ac.id";

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "smtp.gmail.com"; //host masing2 provider email
    $mail->SMTPDebug = 0;
    $mail->Port = 465; //--tls: 587, ssl: 465
    $mail->SMTPAuth = true;
    $mail->Username = $email_sender; //user email
    $mail->Password = "24W95903e2709"; //password email
    $mail->SetFrom($email_sender, "no-reply"); //set email pengirim
    $mail->Subject = $subject; //subyek email
    $mail->AddAddress($target_email); //tujuan email
    $mail->MsgHTML($msg);

    $mail->send();
}
