<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

$id = $_GET['id'] ?? null;

if ($id === null || !is_numeric($id)) {
    header("Location: userdash.php");
    exit();
}

$id = (int) $id;

$product = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
$p = mysqli_fetch_assoc($product);

if (!$p) {
    header("Location: userdash.php");
    exit();
}

$user_id = $_SESSION['user']['id'] ?? 0;

if (isset($_POST['addcart'])) {
    $product_id = $p['id'];
    $name = $p['name'];
    $price = $p['sellingprice'];
    $image = $p['image'];
    $quantity = $_POST['qty'];

    $check = mysqli_query(
        $conn,
        "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'"
    );

    if (mysqli_num_rows($check) > 0) {
        mysqli_query(
            $conn,
            "UPDATE cart SET quantity = quantity + $quantity 
            WHERE user_id='$user_id' AND product_id='$product_id'"
        );
    } else {
        mysqli_query(
            $conn,
            "INSERT INTO cart (user_id, product_id, product_name, product_price, product_image, quantity)
             VALUES ('$user_id', '$product_id', '$name', '$price', '$image', '$quantity')"
        );
    }

    echo "<script>showToast('Product Added to Cart Successfully!');</script>";
}

$discount = 0;
if ($p['originalprice'] > 0) {
    $discount = round((($p['originalprice'] - $p['sellingprice']) / $p['originalprice']) * 100);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $p['name']; ?> - FakeStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6C63FF;
            --primary-light: #8B85FF;
            --primary-dark: #554FD8;
            --secondary: #FF6584;
            --accent: #36D1DC;
            --light: #F8F9FF;
            --dark: #2D2B55;
            --gray: #8C8CA1;
            --success: #4CD964;
            --warning: #FF9500;
            --card-shadow: 0 10px 40px rgba(108, 99, 255, 0.12);
            --card-shadow-hover: 0 20px 60px rgba(108, 99, 255, 0.18);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7ff 0%, #f0f2ff 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-container {
            width: 95%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }

        .logo-text {
            font-size: 26px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .nav-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .back-btn {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 99, 255, 0.4);
        }

        /* Elegant Container */
        .elegant-container {
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: start;
        }

        /* Premium Image Gallery */
        .premium-gallery {
            position: sticky;
            top: 100px;
        }

        .main-image-container {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative;
            overflow: hidden;
        }

        .main-image-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
        }

        .main-image {
            width: 100%;
            height: 450px;
            object-fit: contain;
            transition: var(--transition);
            border-radius: 12px;
        }

        .main-image:hover {
            transform: scale(1.02);
        }

        .image-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--secondary), #FF4B6E);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(255, 101, 132, 0.3);
        }

        /* Thumbnail Gallery */
        .thumbnail-gallery {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            justify-content: center;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: white;
            padding: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .thumbnail:hover, .thumbnail.active {
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Premium Info Panel */
        .premium-panel {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .product-title {
            font-size: 36px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .product-description {
            font-size: 16px;
            color: var(--gray);
            line-height: 1.7;
            margin-bottom: 30px;
        }

        /* Elegant Pricing */
        .elegant-pricing {
            background: linear-gradient(135deg, #f8f9ff, #f0f2ff);
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #e8ebff;
            position: relative;
        }

        .price-display {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .current-price {
            font-size: 42px;
            font-weight: 800;
            color: var(--primary);
        }

        .original-price {
            font-size: 20px;
            color: var(--gray);
            text-decoration: line-through;
        }

        .discount-badge {
            background: linear-gradient(135deg, var(--secondary), #FF4B6E);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
        }

        .savings-text {
            color: var(--success);
            font-weight: 600;
            font-size: 14px;
        }

        /* Premium Controls */
        .premium-controls {
            margin-bottom: 30px;
        }

        .control-label {
            display: block;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
            font-size: 16px;
        }

        .premium-quantity {
            display: flex;
            align-items: center;
            gap: 0;
            background: #f8f9ff;
            border-radius: 15px;
            overflow: hidden;
            border: 2px solid #e8ebff;
            width: fit-content;
        }

        .premium-qty-btn {
            background: var(--primary);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 18px;
        }

        .premium-qty-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        .premium-qty-btn:disabled {
            background: var(--gray);
            cursor: not-allowed;
            transform: none;
        }

        .premium-qty-display {
            padding: 0 25px;
            font-size: 20px;
            font-weight: 700;
            min-width: 60px;
            text-align: center;
            color: var(--dark);
        }

        /* Action Buttons */
        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }

        .premium-btn {
            padding: 18px 25px;
            border: none;
            border-radius: 15px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-buy {
            background: linear-gradient(135deg, var(--secondary), #FF4B6E);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 101, 132, 0.3);
        }

        .btn-buy:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 101, 132, 0.4);
        }

        .btn-cart {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }

        .btn-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 99, 255, 0.4);
        }

        /* Features Grid */
        .features-section {
            background: #f8f9ff;
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .features-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .feature-text {
            font-size: 14px;
            font-weight: 500;
            color: var(--dark);
        }

        /* Product Meta */
        .product-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray);
            font-size: 14px;
        }

        .meta-item i {
            color: var(--primary);
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 20px 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 1000;
            transform: translateY(100px);
            opacity: 0;
            transition: var(--transition);
            border-left: 4px solid var(--success);
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--success);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .elegant-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .premium-gallery {
                position: static;
            }
            
            .main-image {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            .action-buttons {
                grid-template-columns: 1fr;
            }
            
            .product-title {
                font-size: 28px;
            }
            
            .current-price {
                font-size: 36px;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .premium-panel {
                padding: 30px;
            }
        }

        @media (max-width: 480px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .main-image-container {
                padding: 25px;
            }
            
            .main-image {
                height: 300px;
            }
            
            .thumbnail-gallery {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <a href="userdash.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="logo-text">FakeStore</div>
            </a>

            <div class="nav-controls">
                <a href="userdash.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Shop
                </a>
            </div>
        </div>
    </header>

    <!-- Elegant Container -->
    <div class="elegant-container">
        <!-- Premium Image Gallery -->
        <div class="premium-gallery">
            <div class="main-image-container">
                <?php if ($discount > 0): ?>
                    <div class="image-badge"><?php echo $discount; ?>% OFF</div>
                <?php endif; ?>
                <img src="<?php echo $p['image']; ?>" alt="<?php echo $p['name']; ?>" class="main-image" id="mainImage">
            </div>
            <div class="thumbnail-gallery">
                <div class="thumbnail active" onclick="changeImage('<?php echo $p['image']; ?>')">
                    <img src="<?php echo $p['image']; ?>" alt="Main Image">
                </div>
                <!-- Add more thumbnails if available -->
            </div>
        </div>

        <!-- Premium Info Panel -->
        <div class="premium-panel">
            <h1 class="product-title"><?php echo $p['name']; ?></h1>
            
            <!-- Product Meta -->
            <div class="product-meta">
                <div class="meta-item">
                    <i class="fas fa-star"></i>
                    <span>4.8 (128 reviews)</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-shopping-bag"></i>
                    <span>200+ sold</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-check-circle"></i>
                    <span>In Stock</span>
                </div>
            </div>

            <p class="product-description"><?php echo $p['description']; ?></p>

            <!-- Elegant Pricing -->
            <div class="elegant-pricing">
                <div class="price-display">
                    <span class="current-price" id="sellPrice">₹<?php echo $p['sellingprice']; ?></span>
                    <?php if ($p['originalprice'] > $p['sellingprice']): ?>
                        <span class="original-price" id="origPrice">₹<?php echo $p['originalprice']; ?></span>
                        <span class="discount-badge">Save <?php echo $discount; ?>%</span>
                    <?php endif; ?>
                </div>
                <?php if ($p['originalprice'] > $p['sellingprice']): ?>
                    <div class="savings-text">
                        You save ₹<?php echo ($p['originalprice'] - $p['sellingprice']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <form method="POST">
                <!-- Premium Controls -->
                <div class="premium-controls">
                    <label class="control-label">Select Quantity:</label>
                    <div class="premium-quantity">
                        <button type="button" class="premium-qty-btn" onclick="decrease()" id="decreaseBtn">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="premium-qty-display" id="qty">1</span>
                        <button type="button" class="premium-qty-btn" onclick="increase()">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <input type="hidden" id="formQty" name="qty" value="1">
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a id="buyNowBtn" href="payment.php" class="premium-btn btn-buy">
                        <i class="fas fa-bolt"></i>
                        Buy Now
                    </a>
                    <button type="submit" name="addcart" class="premium-btn btn-cart">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                </div>
            </form>

            <!-- Features Section -->
            <div class="features-section">
                <h3 class="features-title">Why Choose This Product?</h3>
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <span class="feature-text">Free Shipping</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="feature-text">1 Year Warranty</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-undo"></i>
                        </div>
                        <span class="feature-text">Easy Returns</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <span class="feature-text">24/7 Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="toast-message" id="toastMessage">Product added to cart successfully!</div>
    </div>

    <script>
        let qty = 1;
        let sell = <?php echo $p['sellingprice']; ?>;
        let orig = <?php echo $p['originalprice']; ?>;
        const decreaseBtn = document.getElementById('decreaseBtn');

        function updatePrice() {
            document.getElementById("sellPrice").innerHTML = "₹" + (sell * qty).toFixed(2);
            if (orig > sell) {
                document.getElementById("origPrice").innerHTML = "₹" + (orig * qty).toFixed(2);
            }
            document.getElementById("formQty").value = qty;
            
            decreaseBtn.disabled = qty <= 1;
        }

        function updateBuyNow() {
            document.getElementById("buyNowBtn").href =
                "payment.php?id=<?php echo $p['id']; ?>&qty=" + qty;
        }

        function increase() {
            qty++;
            document.getElementById("qty").innerHTML = qty;
            updatePrice();
            updateBuyNow();
        }

        function decrease() {
            if (qty > 1) {
                qty--;
                document.getElementById("qty").innerHTML = qty;
                updatePrice();
                updateBuyNow();
            }
        }

        function changeImage(imageUrl) {
            document.getElementById('mainImage').src = imageUrl;
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Initialize
        updatePrice();
        updateBuyNow();
    </script>

</body>

</html>