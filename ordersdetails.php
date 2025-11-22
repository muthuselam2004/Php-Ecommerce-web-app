<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

$orders = $conn->query("SELECT * FROM sales ORDER BY order_date DESC");


?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Order Summary</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6fa;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #0d6efd;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #e1e4e8;
        }

        th {
            background-color: #0d6efd;
            color: white;
            font-weight: 600;
        }

        .status {
            padding: 6px 12px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            display: inline-block;
        }

        .processing {
            background-color: #17a2b8;

        }

        .pending {
            background-color: #ffc107;
        }

        .shipped {
            background-color: #0d6efd;
        }

        .delivered {
            background-color: #28a745;
        }
    </style>
</head>

<body>
    <a href="admindash.php" 
       style="position:absolute; left:20px; top:20px; 
              font-size:28px; color:#0d6efd; text-decoration:none;">
        ←Back
    </a>
    
    <h1>Admin Order Summary</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>UserEmail</th>
                <th>Product Name</th>
                <th>Order ID</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>

            <?php
            if ($orders->num_rows > 0) {
                while ($order = $orders->fetch_assoc()) {


                    $rawStatus = strtolower(trim($order['status']));

                    if ($rawStatus == "" || $rawStatus == null) {
                        $status = "Pending";
                        $statusClass = "pending";
                    } else if ($rawStatus == "pending") {
                        $status = "Pending";
                        $statusClass = "pending";
                    } else if ($rawStatus == "processing") {
                        $status = "Processing";
                        $statusClass = "processing";
                    } else if ($rawStatus == "shipped") {
                        $status = "Shipped";
                        $statusClass = "shipped";
                    } else if ($rawStatus == "delivered") {
                        $status = "Delivered";
                        $statusClass = "delivered";
                    } else {
                        $status = ucfirst($rawStatus);
                        $statusClass = "pending";
                    }



                    ?>

                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['user_email']) ?></td>
                        <td><?= htmlspecialchars($order['product_name']) ?></td>
                        <td><?= htmlspecialchars($order['order_code']) ?></td>
                        <td>₹<?= number_format($order['total'], 2) ?></td>
                        <td><span class="status <?= $statusClass ?>"><?= $status ?></span></td>
                    </tr>

                    <?php
                }
            } else {
                ?>

                <tr>
                    <td colspan="6">No orders found.</td>
                </tr>

                <?php
            }
            ?>

        </tbody>
    </table>

</body>

</html>

