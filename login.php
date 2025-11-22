<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'weblogin';
$port = 3307;

$conn = mysqli_connect($host, $username, $password, $dbname, $port);

if (isset($_POST['login'])) {

    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    if ($role == "admin") {
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='admin'";
        $res = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) == 1) {
            $_SESSION['user'] = mysqli_fetch_assoc($res);
            header("Location: admindash.php");
            exit();
        } else {
            echo "<script>alert('Invalid Admin Login');</script>";
        }
    }

    if ($role == "customer") {
    $sql = "SELECT * FROM customers WHERE email='$username' AND password='$password'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);

        if ($row['status'] == "approved") {

            $_SESSION['user'] = [
                'id'     => $row['id'],
                'name'   => $row['name'],
                'email'  => $row['email'],
                'mobile' => $row['mobile'],
                'role'   => 'customer'
            ];

            header("Location: userdash.php");
            exit();
        } else {
            echo "<script>alert('Your account is not approved yet.');</script>";
        }

    } else {
        echo "<script>alert('Invalid Customer Login');</script>";
    }
}

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - WebEcommerce</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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

        .login-container {
            display: flex;
            width: 900px;
            max-width: 95%;
            height: 550px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-left {
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

        .login-left::before {
            content: "";
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            top: -50px;
            left: -50px;
        }

        .login-left::after {
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

        .features {
            list-style: none;
            position: relative;
            z-index: 2;
        }

        .features li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .features li i {
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

        .login-right {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--gray);
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-select, .form-control {
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-select:focus, .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .btn-login {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }

        .signup-link {
            text-align: center;
            margin-top: 25px;
        }

        .signup-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
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

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
            }
            
            .login-left {
                padding: 30px 25px;
            }
            
            .login-right {
                padding: 30px 25px;
            }
        }
    </style>
</head>

<body>

<div class="login-container">
    <div class="login-left">
        <div class="welcome-text">
            <h1>Welcome Back!</h1>
            <p>Sign in to access your account and manage your dashboard with ease.</p>
        </div>
        <ul class="features">
            <li><i class="fas fa-shield-alt"></i> Secure & encrypted login</li>
            <li><i class="fas fa-bolt"></i> Fast and responsive dashboard</li>
            <li><i class="fas fa-headset"></i> 24/7 Customer support</li>
        </ul>
    </div>
    
    <div class="login-right">
        <div class="login-header">
            <h2>Sign In</h2>
            <p>Enter your credentials to access your account</p>
        </div>
        
        <div class="alert alert-danger" id="errorAlert"></div>
        
        <form method="POST" id="loginForm">
            <div class="form-group">
                <label class="form-label">Login As</label>
                <select name="role" class="form-select">
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Username or Email</label>
                <input type="text" name="username" class="form-control" placeholder="Enter your username or email" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" name="login" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
        
        <div class="signup-link">
            <a href="joinnew.php">
                <i class="fas fa-user-plus"></i> Create New Customer Account
            </a>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const errorAlert = document.getElementById('errorAlert');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>';
    });
    
    // Handle form submission with validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const username = document.querySelector('input[name="username"]').value;
        const password = document.getElementById('password').value;
        
        if (!username || !password) {
            e.preventDefault();
            showError('Please fill in all fields');
            return;
        }
        
        // Clear any previous errors
        hideError();
    });
    
    function showError(message) {
        errorAlert.textContent = message;
        errorAlert.style.display = 'block';
    }
    
    function hideError() {
        errorAlert.style.display = 'none';
    }
</script>

</body>
</html>