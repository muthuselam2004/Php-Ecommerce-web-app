<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$key_id = "rzp_test_ERi8OTWrDXP06W";
$key_secret = "OKoqpHyItVsgJQVhwckW37j4";


$amount = $_GET['amount'] ?? 0;
$amount_in_paise = $amount * 100;  


$_SESSION['payment_amount'] = $amount;


$host = "localhost";
$username = "root";
$password = "";
$dbname = "webecommerce";
$port = 3307;
$conn = new mysqli($host, $username, $password, $dbname, $port);

$user_id = $_SESSION['user']['id'];
$sale_result = $conn->query("SELECT id FROM sales WHERE user_id = $user_id ORDER BY id DESC LIMIT 1");

if ($sale_result && $sale_result->num_rows > 0) {
    $sale_data = $sale_result->fetch_assoc();
    $_SESSION['last_sale_id'] = $sale_data['id'];
} else {
    die("No sale record found. Please place an order first.");
}
$conn->close();


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    json_encode([
        "amount" => $amount_in_paise,
        "currency" => "INR",
        "receipt" => "receipt_" . $_SESSION['last_sale_id'],
        "payment_capture" => 1
    ])
);

curl_setopt($ch, CURLOPT_USERPWD, $key_id . ":" . $key_secret);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$order = json_decode($result, true);

if ($http_code !== 200 || !isset($order['id'])) {
    die("Error creating Razorpay order: " . ($order['error']['description'] ?? 'Unknown error'));
}
?>

<html>
<head>
    <title>Processing Payment...</title>
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
        .loading {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="loading">
        <h3>Initializing Payment...</h3>
        <p>Please wait while we redirect you to secure payment gateway.</p>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    var options = {
        "key": "<?php echo $key_id; ?>",
        "amount": "<?php echo $amount_in_paise; ?>",
        "currency": "INR",
        "name": "WebEcommerce Store",
        "description": "Order Payment",
        "order_id": "<?php echo $order['id']; ?>",
        "handler": function (response) {
            window.location.href = "verify.php?payment_id=" + response.razorpay_payment_id 
                                  + "&order_id=" + response.razorpay_order_id 
                                  + "&signature=" + response.razorpay_signature
                                  + "&amount=<?php echo $amount; ?>";
        },
        "prefill": {
            "name": "<?php echo $_SESSION['user']['name']; ?>",
            "email": "<?php echo $_SESSION['user']['email']; ?>",
            "contact": "<?php echo $_SESSION['user']['mobile']; ?>"
        },
        "theme": {
            "color": "#3399cc"
        }
    };

    
    var rzp1 = new Razorpay(options);
    rzp1.open();

    rzp1.on('payment.failed', function (response) {
        alert("Payment failed. Please try again.");
        window.location.href = 'userdash.php';
    });
    </script>
</body>
</html>