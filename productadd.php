<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);


$cats = $conn->query("SELECT * FROM categories");


if (isset($_POST['save'])) {

    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $originalprice = $_POST['originalprice'];
    $sellingprice = $_POST['sellingprice'];

    
    $folder = "uploads";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

 
    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    
    $newName = uniqid() . "_" . $img;
    $path = $folder . "/" . $newName;

    if (move_uploaded_file($tmp, $path)) {

        
        $sql = "INSERT INTO products(category_id, name, image, description, originalprice, sellingprice, created_by)
                VALUES ('$category_id', '$name', '$path', '$description', '$originalprice', '$sellingprice', 'Admin')";

        $conn->query($sql);

        echo "<script>alert('Product Added Successfully');</script>";
    } else {
        echo "<script>alert('Image Upload Failed');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            margin: 0;
            padding: 0;
        }

       
        .dash-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.25);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            color: #6a11cb;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .dash-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 18px rgba(0,0,0,0.35);
        }

        .container {
            width: 500px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.25);
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #6a11cb;
            font-size: 28px;
        }

        label {
            font-weight: bold;
            color: #444;
            margin-bottom: 5px;
            display: block;
        }

        input, select, textarea {
            width: 100%;
            padding: 14px;
            margin-bottom: 18px;
            border: 2px solid #eee;
            border-radius: 12px;
            font-size: 15px;
            transition: 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 8px rgba(106, 17, 203, 0.5);
        }

        textarea {
            resize: vertical;
            height: 90px;
        }

        button {
            width: 100%;
            padding: 15px;
            font-size: 17px;
            background: linear-gradient(135deg, #ff8a00, #e52e71);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        button:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 20px rgba(229,46,113,0.5);
        }
    </style>

</head>
<body>


<a class="dash-btn" href="admindash.php">🏠</a>

<div class="container">

    <h2>🎉 Add New Product</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Category:</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php while($c = $cats->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Product Name:</label>
        <input type="text" name="name" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Original Price:</label>
        <input type="number" name="originalprice" step="0.01" required>

        <label>Selling Price:</label>
        <input type="number" name="sellingprice" step="0.01" required>

        <label>Upload Image:</label>
        <input type="file" name="image" required>

        <button type="submit" name="save">Save Product</button>
    </form>

</div>

</body>
</html>

