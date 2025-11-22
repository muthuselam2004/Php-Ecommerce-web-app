<?php
require('fpdf186/fpdf.php'); 

if (!isset($_GET['order_id'])) {
    die("Order ID missing!");
}

$order_id = $_GET['order_id'];

$host = "localhost";
$username = "root";
$password = "";
$dbname = "webecommerce";
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$query = "SELECT * FROM sales WHERE id = $order_id";
$result = mysqli_query($conn, $query);
$o = mysqli_fetch_assoc($result);

if (!$o) {
    die("Order Not Found!");
}

$pdf = new FPDF();
$pdf->AddPage();


$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 15, 'INVOICE', 0, 1, 'C');
$pdf->Ln(5);


$pdf->SetFont('Arial', '', 14);
$pdf->Cell(0, 10, "Order ID: " . $o['id'], 0, 1);
$pdf->Cell(0, 10, "Order Date: " . $o['order_date'], 0, 1);
$pdf->Cell(0, 10, "Customer: " . $o['user_name'], 0, 1);
$pdf->Cell(0, 10, "Email: " . $o['user_email'], 0, 1);
$pdf->Cell(0, 10, "Mobile: " . $o['user_mobile'], 0, 1);
$pdf->Ln(10);


$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(100, 10, "Product", 1);
$pdf->Cell(40, 10, "Qty", 1);
$pdf->Cell(50, 10, "Total", 1);
$pdf->Ln();


$pdf->SetFont('Arial', '', 14);
$pdf->Cell(100, 10, $o['product_name'], 1);
$pdf->Cell(40, 10, $o['quantity'], 1);
$pdf->Cell(50, 10, "INR : " . $o['total'], 1);
$pdf->Ln(20);


$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 12, "Grand Total: Rs." . $o['total'], 0, 1);


$pdf->Output("D", "invoice_$order_id.pdf");

echo "<script>
    setTimeout(() => {
        window.location.href = 'userdash.php';
    }, 1500);
</script>";

?>
