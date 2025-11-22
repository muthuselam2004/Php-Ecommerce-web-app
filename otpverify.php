<?php
session_start();

$enteredOTP = $_POST['otp'];

if ($enteredOTP == $_SESSION['otp']) {
    echo "OTP Verified Successfully!";
} else {
    echo "Invalid OTP!";
}
?>
