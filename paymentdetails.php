<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$type = $_GET['type'] ?? "";
$order_date = $_GET['order_date'] ?? "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Payment Details</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: #f4f6fc;
            padding: 20px;
        }

        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            color: #1e3ff7;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: 0.3s ease;
        }

        .back-btn:hover {
            transform: translateX(-4px);
            background: #e9edff;
        }

        .back-btn svg {
            width: 20px;
            height: 20px;
        }

        h1 {
            text-align: center;
            color: #1e3ff7;
            margin-bottom: 25px;
            font-size: 32px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .button-group .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .button-group .online {
            background: linear-gradient(90deg, #1e3ff7, #3399ff);
            color: #fff;
        }

        .button-group .cod {
            background: linear-gradient(90deg, #ff823d, #d94419);
            color: #fff;
        }

        .button-group .active {
            border: 2px solid #000;
        }

        .button-group .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .filter-form {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-form input[type="date"] {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .filter-form button {
            padding: 9px 15px;
            border-radius: 8px;
            border: none;
            background: #1e3ff7;
            color: #fff;
            cursor: pointer;
            font-weight: 600;
            margin-left: 8px;
            transition: 0.3s;
        }

        .filter-form button:hover {
            background: #3399ff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f1f3f7;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f0f4ff;
        }

        @media(max-width:768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            th {
                display: none;
            }

            td {
                display: flex;
                justify-content: space-between;
                padding: 10px;
                border-bottom: 1px solid #eee;
            }

            td::before {
                content: attr(data-label);
                font-weight: 600;
            }
        }
    </style>
</head>

<body>


    <a href="admindash.php" class="back-btn">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </a>

    <h1>Payment Details</h1>

    <div class="button-group">
        <a href="paymentdetails.php?type=online">
            <button class="btn online <?php if ($type == 'online')
                echo 'active'; ?>">Online Payment</button>
        </a>
        <a href="paymentdetails.php?type=cod">
            <button class="btn cod <?php if ($type == 'cod')
                echo 'active'; ?>">Cash on Delivery</button>
        </a>
    </div>

    <?php if ($type): ?>
        <form class="filter-form" method="GET">
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
            <label>Select Date:</label>
            <input type="date" name="order_date" value="<?php echo htmlspecialchars($order_date); ?>">
            <button type="submit">Filter</button>
        </form>
    <?php endif; ?>

    <?php
    if ($type == "online") {
        $sql = "SELECT * FROM sales WHERE payment='Online Payment'";
        if ($order_date) {
            $sql .= " AND DATE(order_date)='$order_date'";
        }
        $sql .= " ORDER BY order_date DESC";
        $orders = $conn->query($sql);
        echo "<h3 style='text-align:center; color:#1e3ff7;'>Online Payment Orders</h3>";
    } elseif ($type == "cod") {
        $sql = "SELECT * FROM sales WHERE payment='Cash on Delivery'";
        if ($order_date) {
            $sql .= " AND DATE(order_date)='$order_date'";
        }
        $sql .= " ORDER BY order_date DESC";
        $orders = $conn->query($sql);
        echo "<h3 style='text-align:center; color:#ff823d;'>Cash on Delivery Orders</h3>";
    } else {
        echo "<p style='text-align:center; color:#444;'>Select a payment type to view orders.</p>";
        exit();
    }

    if ($orders->num_rows > 0) {
        echo "<table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Order Code</th>
            <th>Date</th>
        </tr>";

        while ($row = $orders->fetch_assoc()) {
            echo "<tr>
                <td data-label='ID'>{$row['id']}</td>
                <td data-label='User'>{$row['user_name']}</td>
                <td data-label='Product'>{$row['product_name']}</td>
                <td data-label='Qty'>{$row['quantity']}</td>
                <td data-label='Total'>₹{$row['total']}</td>
                <td data-label='Order Code'>{$row['order_code']}</td>
                <td data-label='Date'>{$row['order_date']}</td>
            </tr>";
        }

        echo "</table>";
    } else {
        echo "<p style='text-align:center; color:#444;'>No orders found.</p>";
    }
    ?>

</body>

</html>