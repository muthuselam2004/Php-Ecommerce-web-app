<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

$cat = mysqli_query($conn, "SELECT * FROM categories");

$filter = "";
if (isset($_GET['category']) && $_GET['category'] != "") {
    $cid = $_GET['category'];
    $filter = "WHERE category_id = '$cid'";
}

$products = mysqli_query($conn, "SELECT * FROM products $filter");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FakeStore - Modern E-commerce</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="theme.css">
    <script src="theme.js" defer></script>

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
            --card-shadow: 0 5px 20px rgba(108, 99, 255, 0.1);
            --card-shadow-hover: 0 10px 30px rgba(108, 99, 255, 0.2);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        
        .header {
            background: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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
            box-shadow: 0 4px 10px rgba(108, 99, 255, 0.3);
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

        .category-filter {
            position: relative;
        }

        .category-select {
            padding: 12px 20px;
            border-radius: 12px;
            border: 2px solid #f0f0f0;
            outline: none;
            font-size: 14px;
            font-weight: 500;
            background: white;
            color: var(--dark);
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            min-width: 200px;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
            transition: var(--transition);
        }

        .category-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.2);
        }

        .menu-toggle {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border: none;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            color: white;
            transition: var(--transition);
            box-shadow: 0 4px 10px rgba(108, 99, 255, 0.3);
        }

        .menu-toggle:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(108, 99, 255, 0.4);
        }

        
        .wishlist-counter {
            position: relative;
        }

        .wishlist-icon {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--dark);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wishlist-icon:hover {
            background: rgba(255, 101, 132, 0.1);
            color: var(--secondary);
        }

        .wishlist-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary);
            color: white;
            font-size: 12px;
            font-weight: 700;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        
        .drawer {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1100;
            top: 0;
            right: 0;
            background: white;
            overflow-x: hidden;
            transition: 0.4s;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .drawer-header {
            padding: 30px 25px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .drawer-title {
            font-size: 24px;
            font-weight: 700;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition);
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .drawer-content {
            padding: 30px 25px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
        }

        .drawer-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            transition: var(--transition);
            font-size: 16px;
            border: 1px solid transparent;
        }

        .drawer-link:hover {
            background: #f8f9ff;
            transform: translateX(-5px);
            border-color: var(--primary-light);
        }

        .drawer-link i {
            width: 24px;
            text-align: center;
            font-size: 18px;
            color: var(--primary);
        }

        .logout-btn-drawer {
            margin-top: auto;
            background: linear-gradient(135deg, var(--secondary), #FF4B6E);
            border: none;
            padding: 16px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(255, 101, 132, 0.3);
        }

        .logout-btn-drawer:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 101, 132, 0.4);
        }

        
        .hero {
            background: linear-gradient(135deg, rgba(108, 99, 255, 0.05), rgba(54, 209, 220, 0.05));
            padding: 60px 0;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(108, 99, 255, 0.1), rgba(54, 209, 220, 0.1));
            z-index: 0;
        }

        .hero-content {
            width: 95%;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 18px;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto 30px;
        }

        
        .container {
            width: 95%;
            max-width: 1200px;
            margin: 0 auto 60px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 30px;
        }

        .card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            position: relative;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: var(--card-shadow-hover);
        }

        .badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, var(--secondary), #FF4B6E);
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 20px;
            z-index: 2;
            box-shadow: 0 4px 10px rgba(255, 101, 132, 0.3);
        }

        .image-container {
            position: relative;
            overflow: hidden;
            height: 220px;
        }

        .card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .card:hover img {
            transform: scale(1.08);
        }

        .card-content {
            padding: 20px;
        }

        .name {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 12px;
            height: 44px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .prices {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .sell {
            color: var(--primary);
            font-size: 22px;
            font-weight: 800;
        }

        .orig {
            color: var(--gray);
            font-size: 14px;
            text-decoration: line-through;
        }

        .card-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .view-btn {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 10px rgba(108, 99, 255, 0.3);
            text-decoration: none;
        }

        .view-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(108, 99, 255, 0.4);
        }

        .wishlist-btn {
            background: none;
            border: none;
            color: var(--gray);
            font-size: 20px;
            cursor: pointer;
            transition: var(--transition);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .wishlist-btn.active {
            color: var(--secondary);
        }

        .wishlist-btn:hover {
            color: var(--secondary);
            background: rgba(255, 101, 132, 0.1);
            transform: scale(1.1);
        }

        
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 1000;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.4s ease;
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

        .toast-message {
            font-weight: 500;
            color: var(--dark);
        }

        
        .wishlist-header {
            text-align: center;
            margin: 30px 0;
        }

        .wishlist-title {
            font-size: 36px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 10px;
        }

        .wishlist-subtitle {
            color: var(--gray);
            font-size: 18px;
        }

        .empty-wishlist {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
        }

        .empty-wishlist i {
            font-size: 80px;
            margin-bottom: 20px;
            color: #e0e0e0;
        }

        .empty-wishlist h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .empty-wishlist p {
            margin-bottom: 30px;
        }

        .browse-btn {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
        }

        .browse-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(108, 99, 255, 0.4);
        }

        
        .footer {
            background: var(--dark);
            color: white;
            padding: 40px 0 20px;
        }

        .footer-content {
            width: 95%;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .footer-column h3 {
            font-size: 18px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 3px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #b8b8d0;
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #444466;
            color: #b8b8d0;
            font-size: 14px;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-link {
            color: white;
            font-size: 18px;
            transition: var(--transition);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .social-link:hover {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            transform: translateY(-3px);
        }

    
        @media screen and (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
            }

            .logo-text {
                font-size: 22px;
            }

            .nav-controls {
                width: 100%;
                justify-content: space-between;
            }

            .category-select {
                min-width: 160px;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 16px;
            }

            .container {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 20px;
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }

            .card-content {
                padding: 15px;
            }

            .name {
                font-size: 14px;
                height: 40px;
            }

            .sell {
                font-size: 18px;
            }

            .hero h1 {
                font-size: 28px;
            }

            .hero p {
                font-size: 14px;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="header-container">
            <a href="#" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="logo-text">FakeStore</div>
            </a>

            <div class="nav-controls">
                <div class="category-filter">
                    <form method="GET">
                        <select name="category" class="category-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <?php while ($c = mysqli_fetch_assoc($cat)) { ?>
                                <option value="<?= $c['id'] ?>" <?php if (isset($_GET['category']) && $_GET['category'] == $c['id'])
                                      echo "selected"; ?>>
                                    <?= $c['name'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </form>
                </div>

                <div class="wishlist-counter">
                    <button class="wishlist-icon" onclick="window.location.href='wistlist.php'">
                        <i class="fas fa-heart"></i>
                        <span class="wishlist-count" id="wishlistCount">0</span>
                    </button>
                </div>

                <button class="menu-toggle" onclick="openDrawer()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    
    <div id="drawer" class="drawer">
        <div class="drawer-header">
            <div class="drawer-title">Menu</div>
            <button class="close-btn" onclick="closeDrawer()">×</button>
        </div>
        <div class="drawer-content">
            <a href="myorder.php" class="drawer-link">
                <i class="fas fa-truck"></i>
                My Orders
            </a>
            <a href="addtocart.php" class="drawer-link">
                <i class="fas fa-shopping-cart"></i>
                Shopping Cart
            </a>
            <a href="wistlist.php" class="drawer-link">
                <i class="fas fa-heart"></i>
                My Wishlist
            </a>
            <!-- <a href="#" class="drawer-link">
                <i class="fas fa-user"></i>
                My Account
            </a>
            <a href="#" class="drawer-link">
                <i class="fas fa-cog"></i>
                Settings
            </a>
            <a href="#" class="drawer-link">
                <i class="fas fa-question-circle"></i>
                Help & Support
            </a> -->
            <form action="login.php" method="POST">
                <button class="logout-btn-drawer">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    
    <section class="hero">
        <div class="hero-content">
            <h1>Discover Amazing Products</h1>
            <p>Shop the latest trends with exclusive deals and fast delivery. Quality products at unbeatable prices.</p>
        </div>
    </section>

    
    <div class="container">
        <?php while ($product = mysqli_fetch_assoc($products)) {
            $discount = 0;
            if ($product['originalprice'] > 0) {
                $discount = round((($product['originalprice'] - $product['sellingprice']) / $product['originalprice']) * 100);
            }
            ?>

            <div class="card" data-product-id="<?= $product['id'] ?>">
                <?php if ($discount > 0) { ?>
                    <div class="badge"><?= $discount ?>% OFF</div>
                <?php } ?>

                <div class="image-container">
                    <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                </div>

                <div class="card-content">
                    <div class="name"><?= $product['name'] ?></div>

                    <div class="prices">
                        <span class="sell">₹<?= $product['sellingprice'] ?></span>
                        <?php if ($product['originalprice'] > $product['sellingprice']) { ?>
                            <span class="orig">₹<?= $product['originalprice'] ?></span>
                        <?php } ?>
                    </div>

                    <div class="card-actions">
                        <a href="userdescription.php?id=<?= $product['id'] ?>" class="view-btn">
                            <i class="fas fa-eye"></i>
                            View
                        </a>
                        <button class="wishlist-btn" data-product-id="<?= $product['id'] ?>">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>

    
    <div class="toast" id="toast">
        <div class="toast-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="toast-message" id="toastMessage">Product added to wishlist!</div>
    </div>

    
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-column">
                <h3>FakeStore</h3>
                <p>Your trusted online shopping destination for quality products at unbeatable prices.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h3>Shop</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> All Products</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Featured Items</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> New Arrivals</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> On Sale</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Support</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> FAQ</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Shipping Info</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Returns</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contact</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i> 123 Commerce St, City</a></li>
                    <li><a href="#"><i class="fas fa-phone"></i> +1 (555) 123-4567</a></li>
                    <li><a href="#"><i class="fas fa-envelope"></i> support@fakestore.com</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 FakeStore. All rights reserved.</p>
        </div>
    </footer>

    <script>
        
        class WishlistManager {
            constructor() {
                this.wishlist = this.loadWishlist();
                this.updateWishlistCount();
                this.initializeWishlistButtons();
            }

            loadWishlist() {
                const saved = localStorage.getItem('wishlist');
                return saved ? JSON.parse(saved) : [];
            }

            saveWishlist() {
                localStorage.setItem('wishlist', JSON.stringify(this.wishlist));
                this.updateWishlistCount();
            }

            updateWishlistCount() {
                const countElement = document.getElementById('wishlistCount');
                if (countElement) {
                    countElement.textContent = this.wishlist.length;
                }
            }

            initializeWishlistButtons() {
                document.querySelectorAll('.wishlist-btn').forEach(button => {
                    const productId = button.getAttribute('data-product-id');
                    if (this.isInWishlist(productId)) {
                        this.setButtonActive(button);
                    }

                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.toggleWishlist(productId, button);
                    });
                });
            }

            isInWishlist(productId) {
                return this.wishlist.includes(productId);
            }

            toggleWishlist(productId, button) {
                if (this.isInWishlist(productId)) {
                    this.removeFromWishlist(productId, button);
                } else {
                    this.addToWishlist(productId, button);
                }
            }

            addToWishlist(productId, button) {
                this.wishlist.push(productId);
                this.saveWishlist();
                this.setButtonActive(button);
                this.showToast('Product added to wishlist!');
            }

            removeFromWishlist(productId, button) {
                this.wishlist = this.wishlist.filter(id => id !== productId);
                this.saveWishlist();
                this.setButtonInactive(button);
                this.showToast('Product removed from wishlist!');
            }

            setButtonActive(button) {
                const icon = button.querySelector('i');
                icon.classList.remove('far');
                icon.classList.add('fas');
                button.classList.add('active');
            }

            setButtonInactive(button) {
                const icon = button.querySelector('i');
                icon.classList.remove('fas');
                icon.classList.add('far');
                button.classList.remove('active');
            }

            showToast(message) {
                const toast = document.getElementById('toast');
                const toastMessage = document.getElementById('toastMessage');
                
                toastMessage.textContent = message;
                toast.classList.add('show');
                
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }

            getWishlistProducts() {
                return this.wishlist;
            }
        }

        // Initialize wishlist manager
        const wishlistManager = new WishlistManager();

        // Theme functionality
        const usertheme = localStorage.getItem("theme");
        if (usertheme === "dark") {
            document.body.classList.add("dark")
        }

        function togglenutton() {
            document.body.classList.toggle("dark");

            if (document.body.classList.contains("dark")) {
                localStorage.setItem("theme", "dark")
            } else {
                localStorage.setItem("theme", "light")
            }
        }

        // Drawer functionality
        function openDrawer() {
            document.getElementById("drawer").style.width = "320px";
        }

        function closeDrawer() {
            document.getElementById("drawer").style.width = "0";
        }

        // Close drawer when clicking outside of it
        window.addEventListener('click', function(event) {
            const drawer = document.getElementById('drawer');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (event.target !== menuToggle && !menuToggle.contains(event.target) && 
                event.target !== drawer && !drawer.contains(event.target)) {
                closeDrawer();
            }
        });
    </script>

</body>

</html>