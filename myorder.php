<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);
$user_id = $_SESSION['user']['id'];

$orders = $conn->query("SELECT * FROM sales WHERE user_id = $user_id ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Tracking</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6fa;
            margin: 0;
            padding: 20px;
        }

        /* BACK BUTTON */
        .back-btn {
            display: inline-block;
            margin-bottom: 10px;
            font-size: 18px;
            text-decoration: none;
            color: #0d6efd;
            font-weight: 600;
        }

        h1 {
            text-align: center;
            color: #0d6efd;
            margin-bottom: 40px;
        }

        .order-card {
            background: #fff;
            padding: 20px;
            margin-bottom: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .order-header {
            font-weight: 600;
            margin-bottom: 15px;
        }

        .product-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .product-info img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
            border: 2px solid #0d6efd;
        }

        .timeline-container {
            position: relative;
            height: 60px;
            margin-top: 20px;
        }

        .timeline-line {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 6px;
            background: #d1d5db;
            border-radius: 3px;
            overflow: hidden;
        }

        .timeline-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #0d6efd, #28a745);
            border-radius: 3px;
            animation: progressAnim 2s forwards;
        }

        @keyframes progressAnim {
            from { width: 0%; }
            to { width: var(--progress); }
        }

        .timeline-step {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            text-align: center;
            color: #555;
            font-weight: 600;
        }

        .timeline-step .icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 6px;
            transition: background 0.5s;
        }

        .timeline-step.completed .icon {
            background: #28a745;
        }

        .timeline-step span {
            font-size: 12px;
        }

        @media screen and (max-width: 768px) {
            .product-info img { width: 60px; height: 60px; }
            .timeline-step span { font-size: 10px; }
        }
    </style>
</head>
<body>


<a href="userdash.php" class="back-btn">← Back</a>

<h1>My Orders</h1>

<?php
if ($orders->num_rows > 0) {
    while($order = $orders->fetch_assoc()) {
        $orderDate = new DateTime($order['order_date']);
        $now = new DateTime();
        $deliveryDays = isset($order['delivery_days']) ? (int)$order['delivery_days'] : 3;
        $daysPassed = $now->diff($orderDate)->days;

        if($daysPassed >= $deliveryDays) {
            $progress = 100;
        } elseif ($daysPassed >= 1) {
            $progress = 50;
        } else {
            $progress = 20;
        }
?>
    <div class="order-card">
        <div class="order-header">Order: <?= htmlspecialchars($order['order_code']) ?> | Date: <?= htmlspecialchars($order['order_date']) ?></div>
        <div class="product-info">
            <img src="<?= htmlspecialchars($order['product_image']) ?>" alt="Product">
            <div>
                <div><?= htmlspecialchars($order['product_name']) ?> (Qty: <?= (int)$order['quantity'] ?>)</div>
                <div>Total: ₹<?= number_format($order['total'],2) ?></div>
            </div>
        </div>

        <div class="timeline-container">
            <div class="timeline-line">
                <div class="timeline-progress" style="--progress: <?= $progress ?>%;"></div>
            </div>
            <?php
            $steps = ['Pending','Shipped','Delivered'];
            $positions = [0,50,100];
            foreach($steps as $i=>$step) {
                $cls = ($progress >= $positions[$i]) ? 'completed' : '';
                $leftPos = ($positions[$i] === 100) ? 'calc(100% - 12px)' : "{$positions[$i]}%";
                echo "<div class='timeline-step $cls' style='left: $leftPos;'>
                        <div class='icon'>✓</div>
                        <span>$step</span>
                      </div>";
            }
            ?>
        </div>
    </div>
<?php
    }
} else {
    echo '<p style="text-align:center;">No orders found.</p>';
}
?>

</body>
</html>
