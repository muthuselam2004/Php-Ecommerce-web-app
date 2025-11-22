<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'weblogin';
$port = 3307;

$conn = mysqli_connect($host,$username,$password,$dbname,$port);

$result = mysqli_query($conn,"SELECT id, name, email, mobile FROM customers WHERE status='pending'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Customers</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6fb1fc, #4364f7, #0052d4);
            font-family: "Segoe UI", Arial, sans-serif;
            color: #fff;
        }

        
        .back-btn {
            position: absolute;
            top: 18px;
            left: 18px;
            background: #ffffffcc;
            color: #2d3e50;
            font-size: 18px;
            padding: 8px 14px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.25);
            transition: 0.3s;
        }
        .back-btn:hover {
            background: #fff;
            transform: scale(1.08);
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            font-size: 32px;
            font-weight: bold;
            color: #fff;
        }

        .container {
            width: 80%;
            max-width: 900px;
            margin: 20px auto;
        }

        .card {
            background: #ffffffee;
            color: #2d3e50;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0px 6px 18px rgba(0,0,0,0.2);
            transition: 0.3s ease;
        }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0px 12px 25px rgba(0,0,0,0.25);
        }

        .details {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .btn {
            padding: 10px 18px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            margin-right: 10px;
            display: inline-block;
        }

        .approve {
            background: #28a745;
            color: white;
        }

        .approve:hover {
            background: #1e7e34;
        }

        .reject {
            background: #dc3545;
            color: white;
        }

        .reject:hover {
            background: #b52a37;
        }
    </style>
</head>
<body>


<a href="admindash.php" class="back-btn">← Back</a>

<h2>Pending Customers</h2>

<div class="container">

<?php
while($row = mysqli_fetch_assoc($result)) {
    echo "<div class='card'>";
    
    echo "<div class='details'>
            <b>ID:</b> {$row['id']}<br>
            <b>Name:</b> {$row['name']}<br>
            <b>Email:</b> {$row['email']}<br>
            <b>Mobile:</b> {$row['mobile']}
          </div>";

    echo "<a class='btn approve' href='process.php?id={$row['id']}&action=approve'>✔ Approve</a>";
    echo "<a class='btn reject' href='process.php?id={$row['id']}&action=reject'>✖ Reject</a>";

    echo "</div>";
}
?>
</div>
</body>
</html>
