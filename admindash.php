<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'weblogin';
$port = 3307;

$conn = mysqli_connect($host, $username, $password, $dbname, $port);


if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
        }

        .topbar {
            text-align: right;
            padding: 20px;
        }

        .logout-btn {
            padding: 10px 20px;
            border: none;
            background: #ff4b5c;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #e63946;
        }

        .container {
            width: 100%;
            max-width: 700px;
            margin: 60px auto;
            background: rgba(255,255,255,0.1);
            padding: 25px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            font-weight: 600;
            font-size: 26px;
        }

        .btn {
            display: block;
            width: 95%;
            padding: 15px;
            margin: 15px 0;
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .btn:hover {
            background: rgba(255,255,255,0.35);
        }
    </style>
</head>

<body>

<div class="topbar">
    <form method="POST">
        <button type="submit" name="logout" class="logout-btn">Logout</button>
    </form>
</div>

<div class="container">
    <h2>Welcome Admin <br><?php echo $_SESSION['user']['username']; ?></h2>


    <a href="productadd.php" class="btn">Product Add</a>
    <a href="viewproducts.php" class="btn">Product Details</a>
    <a href="salesdetails.php" class="btn">Sales Details</a>
    <a href="customerdetails.php" class="btn">Customer Approval</a>
    <a href="customerlist.php" class="btn">Customer List</a>
    <a href="paymentdetails.php"class="btn">Payments Details</a>
    <a href="transaction.php"class="btn">Transaction History</a>
    <a href="ordersdetails.php"class="btn">Order Details</a>

</div>

</body>
</html>
