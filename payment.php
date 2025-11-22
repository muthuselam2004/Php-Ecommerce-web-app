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

$user_id = $_SESSION['user']['id'];
$user_name = $_SESSION['user']['name'];
$user_email = $_SESSION['user']['email'];
$user_mobile = $_SESSION['user']['mobile'];

$id = $_GET['id'] ?? null;

if ($id === null || !is_numeric($id)) {
    header("Location: userdescription.php");
    exit();
}

$qtyGet = $_GET['qty'] ?? 1;

$product = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$p = mysqli_fetch_assoc($product);

$price = $p['sellingprice'];





?>

<!DOCTYPE html>
<html>

<head>
    <title>Buy Now</title>
    
    <link href="https://fonts.googleapis.com/css?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e8f0fe 0%, #f2f3f7 100%);
            margin: 0;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            width: 95%;
            max-width: 480px;
            background: #fff;
            margin: 65px auto;
            box-shadow: 0 6px 40px rgba(0, 0, 0, 0.12), 0 1.5px 6px rgba(30, 115, 255, 0.12);
            border-radius: 26px;
            padding: 42px 32px;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        h2 {
            text-align: center;
            color: #1e3ff7;
            margin: 0;
            font-size: 29px;
            font-weight: 700;
        }

        .info-card {
            background: linear-gradient(100deg, #f8faff 70%, #e3eafc 100%);
            padding: 18px;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(30, 115, 255, 0.07);
        }

        .info-card h3 {
            margin: 0 0 10px 0;
            font-size: 18.5px;
            color: #21203f;
            font-weight: 600;
        }

        .info-card p {
            margin: 6px 0;
            font-size: 16px;
            color: #444;
        }

        label {
            font-weight: 600;
            font-size: 15px;
            color: #283d6a;
        }

        .qty-box {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 18px;
            margin-top: 10px;
        }

        .qty-btn {
            width: 38px;
            height: 38px;
            background: #1e3ff7;
            border: none;
            border-radius: 50%;
            color: #fff;
            font-size: 23px;
            cursor: pointer;
            box-shadow: 0 3px 10px #e3eafc;
        }

        #qty {
            font-size: 18px;
            width: 48px;
            text-align: center;
            font-weight: 700;
            background: #e3eafc;
            padding: 5px;
            border-radius: 8px;
            border: none;
        }

        select {
            width: 100%;
            padding: 13px;
            font-size: 16px;
            border: 1px solid #bcccf7;
            border-radius: 11px;
            background: #f8faff;
            margin-top: 7px;
        }

        .total {
            margin-top: 15px;
            font-size: 23px;
            font-weight: 700;
            color: #32a25e;
            text-align: center;
        }

        .btn {
            width: 100%;
            padding: 17px;
            background: linear-gradient(90deg, #ff823d 70%, #d94419 100%);
            color: #fff;
            font-size: 21px;
            border: none;
            margin-top: 30px;
            border-radius: 13px;
            cursor: pointer;
            font-weight: 700;
        }


        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #1e3ff7;
            color: white;
            padding: 10px 14px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            z-index: 999;
        }
    </style>
</head>

<body>

    <button onclick="history.back()" class="back-btn">← Back</button>

    <div class="container">
        <h2>Complete Your Order</h2>

        <form method="POST" action="placeorder.php">

            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $p['name']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $p['image']; ?>">
            <input type="hidden" name="price" id="finalPrice" value="<?php echo $price * $qtyGet; ?>">
            <input type="hidden" name="name" value="<?php echo $user_name; ?>">
            <input type="hidden" name="email" value="<?php echo $user_email; ?>">
            <input type="hidden" name="mobile" value="<?php echo $user_mobile; ?>">

            <div class="info-card">
                <h3>Your Details</h3>
                <p>👤 <?php echo $user_name; ?></p>
                <p>📧 <?php echo $user_email; ?></p>
                <p>📱 <?php echo $user_mobile; ?></p>
            </div>

            <label>Quantity</label>
            <div class="qty-box">
                <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                <span id="qty"><?php echo $qtyGet; ?></span>
                <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
            </div>

            <input type="hidden" name="quantity" id="qtyInput" value="<?php echo $qtyGet; ?>">

            <label>Payment Method</label>
            <select name="payment" required>
                <option value="">Select Payment</option>
                <option value="Online Payment">Online Payment</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
            </select>

            <div class="total">
                Total Amount: ₹<span id="totalPrice"><?php echo $price * $qtyGet; ?></span>
            </div>

            <button class="btn" type="button" onclick="submitForm()">Confirm Order</button>
        </form>
    </div>
    <script>
        let qty = <?php echo $qtyGet; ?>;
        let price = <?php echo $price; ?>;

        function changeQty(val) {
            qty += val;
            if (qty < 1) qty = 1;

            document.getElementById("qty").innerText = qty;
            document.getElementById("qtyInput").value = qty;

            let total = qty * price;
            document.getElementById("totalPrice").innerText = total;
            document.getElementById("finalPrice").value = total;
        }
        function submitForm() {
            const payment = document.querySelector("select[name='payment']").value;
            const amount = document.getElementById("finalPrice").value;

            if (payment === "") {
                alert("Please select a payment method");
                return;
            }


            if (payment === "Online Payment") {
                window.location.href = "razerpay.php?amount=" + amount;
                return;
            }

            document.querySelector("form").submit();
        }


    </script>

</body>

</html>