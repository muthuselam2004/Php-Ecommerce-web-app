<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/SMTP.php';

$email = 'www.muthu1608@gmail.com';

$otp = rand(000000,999999);
$_SESSION['otp']= $otp;

$mail = new PHPMailer(true);

try{
    $mail->isSMTP();
    $mail->Host='smtp.gmail.com';
    $mail->SMTPAuth=true;
    $mail->Username= 'muthuselvam1608@gmail.com';
    $mail->Password= 'qqts hgev fakn tcui';
    $mail->SMTPSecure= 'tls';
    $mail->Port= 587;


    $mail->setFrom('muthuselvam1608@gmail.com','php-smtp');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject= 'Your OTP Code';
    $mail->Body= "Your OTP is <b>$otp</b>";


    $mail->send();
    echo"OTP sended Successfully";
}catch(Exception $e){
    echo"Message Could not to be sent.Error:{$mail->ErrorInfo}";
}





?>