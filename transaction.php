<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

$sql = "SELECT * FROM sales WHERE payment='Online Payment'";

if (isset($_GET['filter_date']) && !empty($_GET['filter_date'])) {
    $date = $_GET['filter_date'];
    $sql .= " AND DATE(order_date) = '$date' ";
}

$sql .= " ORDER BY order_date DESC";
$data = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Transaction History</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #c7d8ff, #e8efff);
            padding: 25px;
        }

        h1 {
            text-align: center;
            color: #1746ff;
            margin-bottom: 25px;
            font-weight: 800;
            letter-spacing: 1px;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        
        .back-btn {
            display: inline-block;
            padding: 10px 18px;
            background: rgba(23, 70, 255, 0.8);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(23, 70, 255, 0.3);
            backdrop-filter: blur(5px);
            transition: 0.3s;
        }

        .back-btn:hover {
            background: rgba(15, 50, 182, 0.9);
            transform: translateX(-3px);
        }

        .filter_box {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            padding: 18px 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 25px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        input[type=date] {
            padding: 10px;
            border: 1px solid #b8c4ff;
            border-radius: 8px;
            font-size: 15px;
            background: #f7f9ff;
            transition: 0.3s;
        }

        input[type=date]:focus {
            border-color: #1746ff;
            box-shadow: 0 0 6px rgba(23, 70, 255, 0.4);
            outline: none;
        }

        button {
            padding: 10px 20px;
            font-size: 15px;
            background: #1746ff;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0px 4px 10px rgba(23, 70, 255, 0.3);
        }

        button:hover {
            background: #0f32b6;
            transform: scale(1.05);
        }

        .excel-container {
            background: white;
            padding: 0;
            border-radius: 12px;
            border: 1px solid #d7dffa;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            min-width: 1200px;
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background: #e7eeff;
            font-weight: 700;
            border: 1px solid #bcccff;
            padding: 12px;
            text-align: center;
            color: #1746ff;
            font-size: 15px;
        }

        td {
            border: 1px solid #e3e7ff;
            padding: 12px 10px;
            font-size: 14px;
            color: #333;
        }

        tr:nth-child(even) {
            background: #f5f7ff;
        }

        tr:hover {
            background: #e9efff;
            transition: 0.2s;
        }
    </style>
</head>

<body>

    
    <a href="admindash.php" class="back-btn">⬅ Back</a>

    <h1>Online Transactions</h1>

    <form method="GET">
        <div class="filter_box">
            <div>
                <label><b>Select Date:</b></label><br>
                <input type="date" name="filter_date" value="<?= $_GET['filter_date'] ?? '' ?>">
            </div>
            <div>
                <br>
                <button type="submit">Filter</button>
            </div>
        </div>
    </form>

    <div class="excel-container">
        <table>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Order Code</th>
                <th>Date / Time</th>
            </tr>

            <?php while ($row = $data->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['user_name']; ?></td>
                    <td><?= $row['product_name']; ?></td>
                    <td><?= $row['quantity']; ?></td>
                    <td>₹<?= $row['total']; ?></td>
                    <td><?= $row['order_code']; ?></td>
                    <td><?= $row['order_date']; ?></td>
                </tr>
            <?php } ?>

        </table>
    </div>

</body>

</html>
