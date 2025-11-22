<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$dbname = "webecommerce";
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $user_id = $_SESSION['user']['id'];
    $user_name = $_POST['name'];
    $user_email = $_POST['email'];
    $user_mobile = $_POST['mobile'];

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_image = $_POST['product_image'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['price'];
    $payment_method = $_POST['payment'];

    
    $order_code = rand(100000, 999999);
    
    
    $_SESSION['order_code'] = $order_code;
    
    
    $check = $conn->query("SELECT * FROM sales WHERE user_id='$user_id' AND product_id='$product_id' AND status='Processing'");

    if ($check->num_rows > 0) {
        $old = $check->fetch_assoc();
        $new_qty = $quantity;
        $new_total = $total_price;
        
        $update_sql = "UPDATE sales SET quantity='$new_qty', total='$new_total', order_code='$order_code', order_date=NOW() WHERE id=" . $old['id'];
        
        if ($conn->query($update_sql)) {
            
            $_SESSION['last_sale_id'] = $old['id'];
            
            
            $email_sent = sendOrderEmail($user_name, $user_email, $order_code, $product_name, $quantity, $total_price, $payment_method);
            
            if ($email_sent) {
                error_log("OTP email sent successfully for order: $order_code");
            } else {
                error_log("Failed to send OTP email for order: $order_code");
            }
            
            if ($payment_method == "Online Payment") {
                header("Location: razerpay.php?amount=$total_price");
            } else {
                header("Location: bill.php?id=" . $old['id']);
            }
            exit();
        } else {
            echo "Error updating order: " . $conn->error;
            exit();
        }
    } else {
        
        $sql = "INSERT INTO sales (user_id, user_name, user_email, user_mobile, product_id, product_name, product_image, quantity, total, payment, order_date, status, order_code) 
                VALUES ('$user_id', '$user_name', '$user_email', '$user_mobile', '$product_id', '$product_name', '$product_image', '$quantity', '$total_price', '$payment_method', NOW(), 'Processing', '$order_code')";
        
        if ($conn->query($sql)) {
            $sale_id = $conn->insert_id;
            
            
            $_SESSION['last_sale_id'] = $sale_id;
            
            
            $email_sent = sendOrderEmail($user_name, $user_email, $order_code, $product_name, $quantity, $total_price, $payment_method);
            
            if ($email_sent) {
                error_log("OTP email sent successfully for order: $order_code");
            } else {
                error_log("Failed to send OTP email for order: $order_code");
            }
            
            
            if ($payment_method == "Online Payment") {
                header("Location: razerpay.php?amount=$total_price");
            } else {
                header("Location: bill.php?id=$sale_id");
            }
            exit();

        } else {
            echo "Error: " . $conn->error;
        }
    }
}

function sendOrderEmail($user_name, $user_email, $order_code, $product_name, $quantity, $total_price, $payment_method) {
    $subject = "Order Placed Successfully - Order Code: $order_code";
    
    $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
                .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { background: #0078d7; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .otp-box { background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center; margin: 20px 0; border: 2px dashed #0078d7; }
                .otp-code { font-size: 32px; font-weight: bold; color: #0078d7; letter-spacing: 5px; }
                .order-details { background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Order Confirmation</h1>
                </div>
                <h2>Hello $user_name,</h2>
                <p>Your order has been placed successfully!</p>
                
                <div class='otp-box'>
                    <h3>Your Order Verification Code</h3>
                    <div class='otp-code'>$order_code</div>
                    <p>Use this code for order verification and tracking</p>
                </div>
                
                <div class='order-details'>
                    <h3>Order Details:</h3>
                    <p><b>Product:</b> $product_name</p>
                    <p><b>Quantity:</b> $quantity</p>
                    <p><b>Total Price:</b> ₹$total_price</p>
                    <p><b>Payment Method:</b> $payment_method</p>
                </div>
                
                <p><strong>Note:</strong> Keep this order code safe for future reference and tracking.</p>
                <br>
                <p>Thank you for shopping with us!</p>
            </div>
        </body>
        </html>
    ";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'muthuselvam1608@gmail.com';
        $mail->Password = 'qqts hgev fakn tcui';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('muthuselvam1608@gmail.com', 'WebEcommerce Store');
        $mail->addAddress($user_email);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>