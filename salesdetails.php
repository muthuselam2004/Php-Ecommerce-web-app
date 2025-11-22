<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$dbname = "webecommerce";
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

// Get payment statistics for debugging
$payment_stats = $conn->query("SELECT payment, COUNT(*) as count FROM sales GROUP BY payment");
$total_online = 0;
$total_cod = 0;

while($stat = $payment_stats->fetch_assoc()) {
    if (strpos($stat['payment'], 'Online') !== false) {
        $total_online += $stat['count'];
    } else {
        $total_cod += $stat['count'];
    }
}

if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    $conn->query("DELETE FROM sales WHERE id = $delete_id");
    header("Location: salesdetails.php");
    exit();
}

$date = $_GET['date'] ?? '';

$query = "SELECT * FROM sales";

if (!empty($date)) {
    $query .= " WHERE DATE(order_date) = '$date'";
}

$query .= " ORDER BY order_date DESC";

$sales = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Details</title>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #eef2f7;
            margin: 0;
            padding: 0;
        }

        .back-btn {
            position: absolute;
            top: 16px;
            left: 16px;
            background: #ffffffcc;
            color: #2d3e50;
            font-size: 16px;
            padding: 8px 14px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25);
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #fff;
            transform: scale(1.08);
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #1f3b66;
            font-size: 28px;
        }

        .stats-box {
            max-width: 900px;
            background: #fff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .stat-item {
            padding: 15px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #1a73e8;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        .filter-box {
            max-width: 900px;
            background: #fff;
            margin: 20px auto;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .filter-box input {
            padding: 10px 12px;
            font-size: 15px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }

        .filter-box button {
            background: #1a73e8;
            color: white;
            padding: 10px 20px;
            border: none;
            font-size: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
        }

        .filter-box button:hover {
            background: #0c56c3;
        }

        .sales-table-wrapper {
            max-width: 1400px;
            background: #fff;
            margin: 20px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(50, 71, 214, 0.10);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1200px;
        }

        thead th {
            background: #1a73e8;
            color: #fff;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            text-align: left;
        }

        td {
            padding: 14px;
            font-size: 14px;
            border-bottom: 1px solid #e4e9f2;
        }

        tbody tr:hover {
            background: #f3f8ff;
        }

        img {
            width: 65px;
            height: 55px;
            object-fit: contain;
            border-radius: 8px;
            background: #f6f6f6;
            padding: 5px;
        }

        .delete-btn {
            background: #e63946;
            border: none;
            padding: 8px 15px;
            color: #fff;
            font-size: 13px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .view-link {
            color: #1e63d3;
            font-weight: 600;
            text-decoration: none;
        }

        .payment-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .payment-online {
            background: #d1fae5;
            color: #065f46;
        }

        .payment-cod {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>

<body>

    <a href="admindash.php" class="back-btn">← Back</a>
    <h2>Sales Details</h2>

    <!-- Payment Statistics -->
    <div class="stats-box">
        <div class="stat-item">
            <div class="stat-value"><?php echo $sales->num_rows; ?></div>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $total_online; ?></div>
            <div class="stat-label">Online Payments</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $total_cod; ?></div>
            <div class="stat-label">Cash on Delivery</div>
        </div>
    </div>

    <form class="filter-box" method="GET">
        <div>
            <label>Select Date :</label><br>
            <input type="date" name="date" value="<?php echo $date; ?>">
        </div>

        <button type="submit">Filter</button>

        <?php if (!empty($date)): ?>
            <a href="salesdetails.php"
                style="padding:10px 15px; background:#777; color:#fff; border-radius:6px; text-decoration:none;">Clear</a>
        <?php endif; ?>
    </form>

    <div class="sales-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>SNo</th>
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>View Invoice</th>
                    <th>Delete</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $i = 1;
                while ($sale = $sales->fetch_assoc()):
                    $invoice_no = "INV" . str_pad($sale['id'], 6, "0", STR_PAD_LEFT);
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $invoice_no; ?></td>
                        <td><?php echo $sale['order_date']; ?></td>
                        <td><?php echo htmlspecialchars($sale['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($sale['user_mobile']); ?></td>
                        <td><?php echo htmlspecialchars($sale['user_email']); ?></td>
                        <td><?php echo htmlspecialchars($sale['product_name']); ?></td>

                        <td><img src="<?php echo htmlspecialchars($sale['product_image']); ?>"></td>

                        <td><?php echo $sale['quantity']; ?></td>
                        <td>₹<?php echo $sale['total']; ?></td>
                        <td>
                            <?php if (strpos($sale['payment'], 'Online') !== false): ?>
                                <span class="payment-badge payment-online"><?php echo htmlspecialchars($sale['payment']); ?></span>
                            <?php else: ?>
                                <span class="payment-badge payment-cod"><?php echo htmlspecialchars($sale['payment']); ?></span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a class="view-link" href="bill.php?id=<?php echo $sale['id']; ?>" target="_blank">View</a>
                        </td>

                        <td>
                            <a class="delete-btn" href="salesdetails.php?delete=<?php echo $sale['id']; ?>"
                                onclick="return confirm('Are you sure?');">
                                Delete
                            </a>
                        </td>
                    </tr>

                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>