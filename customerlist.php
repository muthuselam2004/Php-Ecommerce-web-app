<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'weblogin';
$port = 3307;

$conn = mysqli_connect($host, $username, $password, $dbname, $port);

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT id, name, email, mobile FROM customers WHERE status = 'approved'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Approved Customers</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #43cea2, #185a9d);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #ffffffcc;
            padding: 10px 16px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            color: #1a3d6b;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
        }

        .back-btn:hover {
            background: #f1f6ff;
        }

        h2 {
            text-align: center;
            margin-top: 60px;
            font-size: 30px;
            color: white;
        }

        .table-container {
            width: 85%;
            max-width: 900px;
            margin: 30px auto;
            background: #ffffffee;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }

        th {
            background: #185a9d;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            color: #333;
        }

        tr:hover {
            background: #f0f8ff;
        }
    </style>
</head>
<body>

<a href="admindash.php" class="back-btn">⬅ Back</a>

<h2>Approved Customer List</h2>

<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['mobile']; ?></td>
        </tr>
        <?php } ?>

    </table>
</div>

</body>
</html>
