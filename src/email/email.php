<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function send_email($to , $subject , $body) : bool{

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'medicalhubsystem@gmail.com';
    $mail->Password = 'dxvsjcopmohhtmqk';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('medicalhubsystem@gmail.com');
    $mail->addAddress($to);

    $mail->isHTML(true);

    $mail->Subject = $subject;
    $mail->Body    = "$body";

    return $mail->send();

}
// send_email('ahmniab11@gmail.com',"fuck php",'fuck php');


?>
