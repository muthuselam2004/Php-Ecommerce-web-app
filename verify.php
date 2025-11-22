<?php
session_start();


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}


$payment_id = $_GET['payment_id'] ?? '';
$amount = $_GET['amount'];
$sale_id = $_SESSION['last_sale_id'];


if (empty($payment_id)) {
    die("Payment ID not found. Payment may have failed.");
}

if (empty($sale_id)) {
    die("Sale information not found. Please try placing the order again.");
}


$host = "localhost";
$username = "root";
$password = "";
$dbname = "webecommerce";
$port = 3307;
$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$check_column = $conn->query("SHOW COLUMNS FROM sales LIKE 'payment_id'");
$has_payment_column = $check_column->num_rows > 0;

if ($has_payment_column) {
   
    $update_sql = "UPDATE sales SET 
                   payment_status = 'completed', 
                   payment_id = '$payment_id',
                   status = 'Completed'
                   WHERE id = $sale_id";
} else {
    
    $update_sql = "UPDATE sales SET 
                   status = 'Completed',
                   payment = 'Online Payment - $payment_id'
                   WHERE id = $sale_id";
}

if ($conn->query($update_sql)) {
    
    unset($_SESSION['payment_amount']);
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Payment Successful</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0;
            }
            .success-container {
                background: white;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                text-align: center;
                max-width: 500px;
            }
            .success-icon {
                font-size: 60px;
                color: #4CAF50;
                margin-bottom: 20px;
            }
            h2 {
                color: #2c3e50;
                margin-bottom: 20px;
            }
            .payment-details {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 10px;
                margin: 20px 0;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <div class='success-container'>
            <div class='success-icon'>✓</div>
            <h2>Payment Successful!</h2>
            <div class='payment-details'>
                <p><strong>Payment ID:</strong> $payment_id</p>
                <p><strong>Amount Paid:</strong> ₹$amount</p>
                <p><strong>Order ID:</strong> $sale_id</p>
                <p><strong>Status:</strong> Completed</p>
            </div>
            <p>Redirecting to invoice...</p>
        </div>
    </body>
    </html>";

    
    echo "<script>
        setTimeout(function() {
            window.location.href = 'bill.php?id=$sale_id&download=yes';
        }, 2000);
    </script>";

} else {
    echo "Error updating payment: " . $conn->error;
    echo "<br><a href='userdash.php'>Back to Dashboard</a>";
}

$conn->close();
?>