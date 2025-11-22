<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'weblogin';
$port = 3307;

$conn = mysqli_connect($host, $username, $password, $dbname, $port);


if (!isset($_GET['id']) || !isset($_GET['action'])) {
    die("Invalid Request");
}

$id = $_GET['id'];
$action = $_GET['action'];

if ($action == "approve") {
    $sql = "UPDATE customers SET status='approved' WHERE id=$id";
} 
elseif ($action == "reject") {
    $sql = "DELETE FROM customers WHERE id=$id";   
} 
else {
    die("Invalid Action");
}

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Action completed successfully!');
            window.location='admindash.php';
          </script>";
} else {
    echo "Error: " . mysqli_error($conn);
}

?>
