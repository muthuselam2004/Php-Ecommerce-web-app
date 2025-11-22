<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$dbname = "webecommerce";
$port = 3307;
$conn = new mysqli($host, $username, $password, $dbname, $port);

$sale_id = $_GET['id'];
$sale = $conn->query("SELECT * FROM sales WHERE id=$sale_id")->fetch_assoc();
$invoice_no = "INV" . str_pad($sale_id, 6, "0", STR_PAD_LEFT);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f6f8fa;
            color: #2c3e50;
            padding: 40px 20px;
        }

        .invoice-box {
            max-width: 850px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px 55px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgb(0 0 0 / 0.1);
            border: 1px solid #e1e4e8;
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo img {
            max-width: 140px;
        }

        h1 {
            text-align: center;
            font-weight: 700;
            font-size: 30px;
            color: #0078d7;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }

        h3 {
            text-align: center;
            color: #555d66;
            font-weight: 500;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.3;
        }

        hr {
            border: none;
            border-top: 1px solid #d1d5db;
            margin: 30px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }

        th,
        td {
            text-align: center;
            padding: 15px 12px;
            border-bottom: 1px solid #e1e4e8;
        }

        th {
            background-color: #0078d7;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 3px solid #005ea1;
        }

        .no-border td {
            border: none;
            padding: 10px 6px;
            text-align: left;
            font-size: 15px;
            color: #34495e;
        }

        .no-border td strong {
            color: #222f3e;
        }

        .total-row td {
            background: #e6f0fa;
            font-weight: 700;
            font-size: 17px;
            color: #004080;
            text-align: right;
            padding-right: 20px;
            border-top: 2px solid #b0c4de;
            border-bottom: none;
        }

        .total-row td:last-child {
            text-align: center;
        }

        p.thank-you {
            text-align: center;
            font-size: 17px;
            font-weight: 600;
            margin-top: 40px;
            color: #2d4059;
        }

        .print-btn {
            display: block;
            width: 220px;
            margin: 35px auto 15px;
            padding: 14px 0;
            background-color: #0078d7;
            color: #fff;
            font-weight: 700;
            font-size: 18px;
            border: none;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgb(0 120 215 / 0.3);
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
            user-select: none;
        }

        .print-btn:hover {
            background-color: #005ea1;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                background: none;
                padding: 0;
            }

            .invoice-box {
                box-shadow: none;
                border: none;
                max-width: 100%;
                padding: 0;
            }

            table,
            th,
            td {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="logo">
            <img src="uploads/scoto.jpg" alt="Company Logo">
        </div>
        <h1>Scoto Systec Pvt Ltd</h1>
        <h3>61/1A, HM Enclave, 1st Floor, Thiruvalluvar Nagar, Coimbatore, TN - 641045</h3>
        <hr>

        <table class="no-border">
            <tr>
                <td><strong>Invoice No:</strong> <?php echo $invoice_no; ?></td>
                <td><strong>Date:</strong> <?php echo $sale['order_date']; ?></td>
            </tr>
            <tr>
                <td><strong>Customer:</strong> <?php echo htmlspecialchars($sale['user_name']); ?></td>
                <td><strong>Mobile:</strong> <?php echo htmlspecialchars($sale['user_mobile']); ?></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Email:</strong> <?php echo htmlspecialchars($sale['user_email']); ?></td>
            </tr>
        </table>

        <hr>

        <table>
            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                    <td><?php echo (int) $sale['quantity']; ?></td>
                    <td>₹<?php echo number_format($sale['total'], 2); ?></td>
                </tr>

                <tr class="total-row">
                    <td colspan="3">Grand Total</td>
                    <td>₹<?php echo number_format($sale['total'], 2); ?></td>
                </tr>
            </tbody>
        </table>

        <hr>

        <p class="thank-you">Thank you for your purchase!</p>
        <button class="print-btn" onclick="window.print()">Print Invoice</button>
        <a id="downloadBtn" href="downloadpdf.php?order_id=<?php echo $sale['id']; ?>" class="btn btn-primary">
            Download Invoice
        </a>

    </div>
    <script>

        document.getElementById("downloadBtn").addEventListener("click", function (e) {
            e.preventDefault(); 
            
            let iframe = document.createElement("iframe");
            iframe.style.display = "none";
            iframe.src = this.href;
            document.body.appendChild(iframe);

            setTimeout(() => {
                window.location.href = "userdash.php";
            }, 1000); 
        });


    </script>

</body>

</html>