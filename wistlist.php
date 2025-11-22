<?php
// wistlist.php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all products from database to match with wishlist
$all_products = [];
$products_result = mysqli_query($conn, "SELECT * FROM products");
while ($product = mysqli_fetch_assoc($products_result)) {
    $all_products[$product['id']] = $product;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - FakeStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Keep all your existing CSS styles from before */
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

        /* Keep all your other CSS styles exactly as they were */
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

        .back-btn {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(108, 99, 255, 0.3);
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(108, 99, 255, 0.4);
        }

        .wishlist-header {
            text-align: center;
            margin: 40px 0 30px;
            padding: 0 20px;
        }

        .wishlist-title {
            font-size: 42px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 15px;
        }

        .wishlist-subtitle {
            color: var(--gray);
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
        }

        .wishlist-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
            padding: 15px 25px;
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--gray);
            font-weight: 500;
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

        .remove-btn {
            background: none;
            border: none;
            color: var(--secondary);
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

        .remove-btn:hover {
            background: rgba(255, 101, 132, 0.1);
            transform: scale(1.1);
        }

        .empty-wishlist {
            text-align: center;
            padding: 80px 20px;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
        }

        .empty-wishlist i {
            font-size: 100px;
            margin-bottom: 30px;
            color: #e0e0e0;
            opacity: 0.7;
        }

        .empty-wishlist h3 {
            font-size: 28px;
            margin-bottom: 15px;
            color: var(--dark);
            font-weight: 700;
        }

        .empty-wishlist p {
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.6;
        }

        .browse-btn {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }

        .browse-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(108, 99, 255, 0.4);
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

        .footer {
            background: var(--dark);
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
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

        /* Responsive Design */
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

            .wishlist-title {
                font-size: 32px;
            }

            .wishlist-stats {
                gap: 15px;
            }

            .stat-item {
                padding: 12px 20px;
            }

            .stat-number {
                font-size: 24px;
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

            .wishlist-title {
                font-size: 28px;
            }

            .wishlist-stats {
                flex-direction: column;
                align-items: center;
            }

            .footer-content {
                grid-template-columns: 1fr;
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

    <!-- Wishlist Header -->
    <section class="wishlist-header">
        <h1 class="wishlist-title">My Wishlist</h1>
        <p class="wishlist-subtitle">Your saved items are waiting for you. Don't let them get away!</p>

        <div class="wishlist-stats">
            <div class="stat-item">
                <div class="stat-number" id="totalItems">0</div>
                <div class="stat-label">Total Items</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="savedAmount">₹0</div>
                <div class="stat-label">Total Value</div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <div class="container" id="wishlistContainer">
        <!-- Wishlist items will be dynamically loaded here -->
    </div>

    <!-- Empty Wishlist State -->
    <div class="empty-wishlist" id="emptyWishlist" style="display: none;">
        <i class="fas fa-heart-broken"></i>
        <h3>Your wishlist is empty</h3>
        <p>Looks like you haven't added any products to your wishlist yet. Start exploring our amazing collection and
            save your favorite items!</p>
        <a href="userdash.php" class="browse-btn">
            <i class="fas fa-shopping-bag"></i>
            Start Shopping
        </a>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="toast-message" id="toastMessage">Product removed from wishlist!</div>
    </div>

    <!-- Footer -->
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
                    <li><a href="userdash.php"><i class="fas fa-chevron-right"></i> All Products</a></li>
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
        // Wishlist Manager
        class WishlistManager {
            constructor() {
                this.wishlist = this.loadWishlist();
                this.loadWishlistProducts();
            }

            loadWishlist() {
                const saved = localStorage.getItem('wishlist');
                return saved ? JSON.parse(saved) : [];
            }

            saveWishlist() {
                localStorage.setItem('wishlist', JSON.stringify(this.wishlist));
            }

            loadWishlistProducts() {
                if (this.wishlist.length === 0) {
                    this.showEmptyState();
                    return;
                }

                // Get all product data from PHP
                const allProducts = <?php echo json_encode($all_products); ?>;

                // Filter to only show wishlisted products
                this.products = this.wishlist.map(productId => allProducts[productId]).filter(Boolean);

                this.renderWishlist();
                this.updateStats();
            }

            renderWishlist() {
                const container = document.getElementById('wishlistContainer');
                const emptyState = document.getElementById('emptyWishlist');

                if (this.products.length === 0) {
                    this.showEmptyState();
                    return;
                }

                container.style.display = 'grid';
                emptyState.style.display = 'none';

                container.innerHTML = this.products.map(product => {
                    if (!product) return '';

                    const discount = product.originalprice > 0 ?
                        Math.round(((product.originalprice - product.sellingprice) / product.originalprice) * 100) : 0;

                    return `
                        <div class="card" data-product-id="${product.id}">
                            ${discount > 0 ? `<div class="badge">${discount}% OFF</div>` : ''}
                            
                            <div class="image-container">
                                <img src="${product.image}" alt="${product.name}" onerror="this.src='https://via.placeholder.com/400x300?text=Product+Image'">
                            </div>

                            <div class="card-content">
                                <div class="name">${product.name}</div>

                                <div class="prices">
                                    <span class="sell">₹${product.sellingprice}</span>
                                    ${product.originalprice > product.sellingprice ?
                            `<span class="orig">₹${product.originalprice}</span>` : ''}
                                </div>

                                <div class="card-actions">
                                    <a href="userdescription.php?id=${product.id}" class="view-btn">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                    <button class="remove-btn" onclick="wishlistManager.removeFromWishlist('${product.id}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            removeFromWishlist(productId) {
                this.wishlist = this.wishlist.filter(id => id !== productId);
                this.products = this.products.filter(product => product.id !== productId);

                this.saveWishlist();
                this.renderWishlist();
                this.updateStats();
                this.showToast('Product removed from wishlist!');

                // Update the main page if it's open
                if (window.opener) {
                    window.opener.postMessage({ type: 'wishlistUpdated' }, '*');
                }
            }

            updateStats() {
                const totalItems = document.getElementById('totalItems');
                const savedAmount = document.getElementById('savedAmount');

                if (totalItems) {
                    totalItems.textContent = this.products.length;
                }

                if (savedAmount) {
                    const totalValue = this.products.reduce((sum, product) => {
                        return sum + (Number(product.sellingprice) || 0);
                    }, 0);

                    
                    savedAmount.textContent = `₹${totalValue.toFixed(2)}`;
                }
            }

            showEmptyState() {
                const container = document.getElementById('wishlistContainer');
                const emptyState = document.getElementById('emptyWishlist');

                container.style.display = 'none';
                emptyState.style.display = 'block';

                this.updateStats();
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
        }

        // Initialize wishlist manager
        const wishlistManager = new WishlistManager();

        // Listen for messages from main page
        window.addEventListener('message', function (event) {
            if (event.data.type === 'wishlistUpdated') {
                location.reload(); // Simple refresh to get updated data
            }
        });

        // Also listen for storage changes
        window.addEventListener('storage', function (e) {
            if (e.key === 'wishlist') {
                location.reload();
            }
        });
    </script>

</body>

</html>