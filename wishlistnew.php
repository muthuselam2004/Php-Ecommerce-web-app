<?php
// get_wishlist_products.php
header('Content-Type: application/json');

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);


if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}


$ids = isset($_GET['ids']) ? $_GET['ids'] : '';

if (empty($ids)) {
    echo json_encode([]);
    exit;
}


$query = "SELECT id, name, image, sellingprice, originalprice FROM products WHERE id IN ($ids)";
$result = $conn->query($query);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);
$conn->close();
?>