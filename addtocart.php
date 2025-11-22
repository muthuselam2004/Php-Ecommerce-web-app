<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

$user_id = $_SESSION['user']['id'];

$cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['qty'];
    mysqli_query($conn, "UPDATE cart SET quantity = $qty WHERE id = $cart_id");
    header("Location: addtocart.php");
    exit();
}

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = $remove_id");
    header("Location: addtocart.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #7209b7;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --danger: #f72585;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --border-radius: 12px;
            --shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            padding-bottom: 40px;
        }

        .header {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            font-size: 28px;
        }

        .nav-links {
            display: flex;
            gap: 25px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            padding: 8px 12px;
            border-radius: 6px;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .cart-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title i {
            color: var(--primary);
        }

        .cart-content {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }

        @media (max-width: 992px) {
            .cart-content {
                grid-template-columns: 1fr;
            }
        }

        .items-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cart-item {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
            display: flex;
        }

        .cart-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .item-image {
            width: 160px;
            height: 160px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .item-details {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .item-name {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .item-price {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
        }

        .item-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .qty-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-box {
            display: flex;
            align-items: center;
            border: 1px solid var(--gray-light);
            border-radius: 8px;
            overflow: hidden;
        }

        .qty-btn {
            width: 36px;
            height: 36px;
            background: var(--gray-light);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .qty-btn:hover {
            background: var(--primary);
            color: white;
        }

        .qty-input {
            width: 50px;
            height: 36px;
            text-align: center;
            border: none;
            border-left: 1px solid var(--gray-light);
            border-right: 1px solid var(--gray-light);
            font-size: 16px;
            font-weight: 500;
        }

        .update-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .update-btn:hover {
            background: var(--primary-dark);
        }

        .remove-btn {
            background: transparent;
            color: var(--danger);
            border: 1px solid var(--danger);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .remove-btn:hover {
            background: var(--danger);
            color: white;
        }

        .item-total {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
        }

        .order-summary {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 25px;
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .summary-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed var(--gray-light);
        }

        .summary-label {
            color: var(--gray);
        }

        .summary-value {
            font-weight: 500;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid var(--gray-light);
            font-size: 20px;
            font-weight: 700;
        }

        .total-amount {
            color: var(--primary);
        }

        .checkout-btn {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            margin-top: 25px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .checkout-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .empty-cart i {
            font-size: 80px;
            color: var(--gray-light);
            margin-bottom: 20px;
        }

        .empty-cart h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .empty-cart p {
            color: var(--gray);
            margin-bottom: 25px;
        }

        .continue-shopping {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .continue-shopping:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            color: var(--gray);
            font-size: 14px;
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-shopping-bag"></i>
                <span>WebEcommerce</span>
            </div>
            <div class="nav-links">
                <a href="userdash.php"><i class="fas fa-home"></i> Home</a>
                <a href="addtocart.php" class="active"><i class="fas fa-shopping-cart"></i> Cart</a>
                
            </div>
        </div>
    </header>

    <div class="cart-container">
        <h1 class="page-title">
            <i class="fas fa-shopping-cart"></i>
            Your Shopping Cart
        </h1>

        <?php
        $grandtotal = 0;
        $itemCount = mysqli_num_rows($cart_items);
        
        if ($itemCount > 0) {
            mysqli_data_seek($cart_items, 0); // Reset pointer to beginning
        ?>
            <div class="cart-content">
                <div class="items-list">
                    <?php
                    while ($item = mysqli_fetch_assoc($cart_items)) {
                        $total = $item['product_price'] * $item['quantity'];
                        $grandtotal += $total;
                    ?>
                        <div class="cart-item">
                            <img src="<?php echo $item['product_image']; ?>" class="item-image" alt="<?php echo $item['product_name']; ?>">
                            <div class="item-details">
                                <div class="item-header">
                                    <div>
                                        <div class="item-name"><?php echo $item['product_name']; ?></div>
                                        <div class="item-price">₹<?php echo number_format($item['product_price'], 2); ?></div>
                                    </div>
                                    <div class="item-total">₹<?php echo number_format($total, 2); ?></div>
                                </div>

                                <div class="item-actions">
                                    <form method="POST" class="qty-form">
                                        <div class="qty-box">
                                            <button type="button" class="qty-btn" onclick="changeQty(this, -1)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="qty" class="qty-input" value="<?php echo $item['quantity']; ?>" min="1">
                                            <button type="button" class="qty-btn" onclick="changeQty(this, 1)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        </div>
                                        <button class="update-btn" name="update_qty">
                                            <i class="fas fa-sync-alt"></i> Update
                                        </button>
                                    </form>

                                    <a class="remove-btn" href="addtocart.php?remove=<?php echo $item['id']; ?>">
                                        <i class="fas fa-trash"></i> Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                
            </div>
        <?php } else { ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="products.php" class="continue-shopping">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        <?php } ?>
    </div>

    <div class="footer">
        <p>&copy; 2023 WebEcommerce. All rights reserved.</p>
    </div>

    <script>
        function changeQty(btn, value) {
            let input = btn.parentElement.querySelector("input[name='qty']");
            let newValue = parseInt(input.value) + value;
            if (newValue >= 1) {
                input.value = newValue;
            }
        }
    </script>

</body>

</html>