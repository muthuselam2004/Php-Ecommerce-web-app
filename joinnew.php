<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'weblogin';
$port = 3307;

$conn = mysqli_connect($host, $username, $password, $dbname, $port);

if (isset($_POST['register'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = md5($_POST['password']);

    $check = mysqli_query($conn, "SELECT email FROM customers WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered! Try login.');</script>";
    } else {

        $sql = "INSERT INTO customers (name, email, mobile, password, status)
                VALUES ('$name', '$email', '$mobile', '$password', 'pending')";
        mysqli_query($conn, $sql);

        echo "<script>
                alert('Registration successful! Wait for admin approval.');
                window.location='login.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Join New Customer - WebEcommerce</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --accent: #06d6a0;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #64748b;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--gradient);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 20px;
        }

        .register-container {
            display: flex;
            width: 1000px;
            max-width: 95%;
            height: 650px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .register-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .register-left::before {
            content: "";
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            top: -50px;
            left: -50px;
        }

        .register-left::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            bottom: -30px;
            right: -30px;
        }

        .welcome-text h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .welcome-text p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }

        .benefits {
            list-style: none;
            position: relative;
            z-index: 2;
        }

        .benefits li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .benefits li i {
            margin-right: 12px;
            background: rgba(255, 255, 255, 0.2);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .register-right {
            flex: 1.2;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--light);
        }

        .register-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .register-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .register-header p {
            color: var(--gray);
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .input-icon {
            position: absolute;
            right: 16px;
            top: 42px;
            color: var(--gray);
            font-size: 16px;
        }

        .password-strength {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-weak { background: #ef4444; width: 33%; }
        .strength-medium { background: #f59e0b; width: 66%; }
        .strength-strong { background: #10b981; width: 100%; }

        .btn-register {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-register:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: var(--primary-dark);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .alert-danger {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
                height: auto;
            }
            
            .register-left {
                padding: 30px 25px;
            }
            
            .register-right {
                padding: 30px 25px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="register-container">
    <div class="register-left">
        <div class="welcome-text">
            <h1>Join Our Community!</h1>
            <p>Create your account and start shopping with amazing benefits and exclusive offers.</p>
        </div>
        <ul class="benefits">
            <li><i class="fas fa-shipping-fast"></i> Fast & Free Shipping</li>
            <li><i class="fas fa-tags"></i> Exclusive Member Discounts</li>
            <li><i class="fas fa-shield-alt"></i> Secure Payment Processing</li>
            <li><i class="fas fa-clock"></i> 24/7 Customer Support</li>
            <li><i class="fas fa-gift"></i> Early Access to Sales</li>
        </ul>
    </div>
    
    <div class="register-right">
        <div class="register-header">
            <h2>Create Account</h2>
            <p>Fill in your details to get started</p>
        </div>
        
        <div class="alert alert-danger" id="errorAlert"></div>
        <div class="alert alert-success" id="successAlert"></div>
        
        <form method="POST" id="registerForm">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-user"></i> Full Name
                </label>
                <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                <i class="fas fa-user input-icon"></i>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                <i class="fas fa-envelope input-icon"></i>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-mobile-alt"></i> Mobile Number
                    </label>
                    <input type="text" name="mobile" pattern="[0-9]{10}" maxlength="10" class="form-control" placeholder="10-digit number" required>
                    <i class="fas fa-mobile-alt input-icon"></i>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Create password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                </div>
            </div>
            
            <button type="submit" name="register" class="btn-register">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>
        
        <div class="login-link">
            <a href="login.php">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</div>

<script>
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        if (password.length >= 6) strength += 1;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
        if (password.match(/\d/)) strength += 1;
        if (password.match(/[^a-zA-Z\d]/)) strength += 1;
        
        // Update strength bar
        strengthBar.className = 'strength-bar';
        if (strength === 0) {
            strengthBar.style.width = '0%';
        } else if (strength === 1) {
            strengthBar.classList.add('strength-weak');
        } else if (strength === 2 || strength === 3) {
            strengthBar.classList.add('strength-medium');
        } else {
            strengthBar.classList.add('strength-strong');
        }
    });
    
    // Form validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const name = document.querySelector('input[name="name"]').value;
        const email = document.querySelector('input[name="email"]').value;
        const mobile = document.querySelector('input[name="mobile"]').value;
        const password = document.getElementById('password').value;
        
        // Basic validation
        if (!name || !email || !mobile || !password) {
            e.preventDefault();
            showError('Please fill in all fields');
            return;
        }
        
        // Mobile validation
        const mobileRegex = /^[0-9]{10}$/;
        if (!mobileRegex.test(mobile)) {
            e.preventDefault();
            showError('Please enter a valid 10-digit mobile number');
            return;
        }
        
        // Password validation
        if (password.length < 6) {
            e.preventDefault();
            showError('Password must be at least 6 characters long');
            return;
        }
        
        // Clear any previous errors
        hideError();
    });
    
    function showError(message) {
        const errorAlert = document.getElementById('errorAlert');
        errorAlert.textContent = message;
        errorAlert.style.display = 'block';
        
        
        setTimeout(hideError, 5000);
    }
    
    function hideError() {
        document.getElementById('errorAlert').style.display = 'none';
    }
</script>

</body>
</html>