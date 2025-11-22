<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'webecommerce';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $img = $conn->query("SELECT image FROM products WHERE id='$id'")->fetch_assoc()['image'];
    if (file_exists($img)) {
        unlink($img);
    }

    $conn->query("DELETE FROM products WHERE id='$id'");
    echo "<script>alert('Product Deleted'); window.location.href='viewproducts.php';</script>";
}

$editData = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $editData = $conn->query("SELECT * FROM products WHERE id='$edit_id'")->fetch_assoc();
}

if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $originalprice = $_POST['originalprice'];
    $sellingprice = $_POST['sellingprice'];

    if (!empty($_FILES['image']['name'])) {

        $oldImage = $_POST['old_image'];
        if (file_exists($oldImage)) {
            unlink($oldImage);
        }

        $img = "uploads/" . uniqid() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $img);

    } else {
        $img = $_POST['old_image'];
    }

    $sql = "UPDATE products 
            SET category_id='$category_id',
                name='$name',
                description='$description',
                originalprice='$originalprice',
                sellingprice='$sellingprice',
                image='$img',
                updated_by='Admin',
                updated_at=NOW()
            WHERE id='$id'";

    $conn->query($sql);

    echo "<script>alert('Product Updated'); window.location.href='viewproducts.php';</script>";
}

$categories = $conn->query("SELECT * FROM categories");

$sql = "SELECT p.*, c.name AS category FROM products p 
        JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id DESC";

$products = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products List</title>

    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #eef1f7;
            margin: 0;
            padding: 30px;
            color: #333;
        }

        
        .back-btn {
            position: absolute;
            top: 16px;
            left: 16px;
            background: #ffffffcc;
            color: #2d3e50;
            font-size: 16px;
            padding: 8px 14px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.25);
            transition: 0.3s;
        }
        .back-btn:hover {
            background: #fff;
            transform: scale(1.08);
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        h2 {
            margin: 0;
            color: #1a3f6b;
            font-size: 26px;
            font-weight: 700;
        }

        .add-btn {
            display: inline-block;
            background: #1a73e8;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px 0;
            transition: 0.2s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .add-btn:hover {
            background: #1259b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        th {
            background: #1a73e8;
            color: white;
            padding: 13px;
            font-size: 15px;
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #e5e9f2;
            font-size: 14px;
        }

        tr:hover {
            background: #f5f9ff;
        }

        img {
            border-radius: 6px;
            width: 70px;
            height: auto;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .edit-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            width: 450px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
            margin-bottom: 25px;
        }

        .edit-box input,
        .edit-box textarea,
        .edit-box select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #c7ccd6;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .save-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 18px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .save-btn:hover {
            background: #1f7f34;
        }

        .action-links a {
            color: #1a73e8;
            font-weight: bold;
            text-decoration: none;
        }

        .action-links a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>


    <a href="admindash.php" class="back-btn">← Back</a>
    <h2 style="text-align: center;">All Products</h2>


<a href="productadd.php" class="add-btn" >+ Add Product</a>


<?php if ($editData): ?>
<div class="edit-box">
    <h3 style="color:#1a3f6b; margin-bottom:20px;">Edit Product</h3>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <input type="hidden" name="old_image" value="<?= $editData['image'] ?>">

        <label>Category</label>
        <select name="category_id" required>
            <?php while($c = $categories->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>" 
                    <?= $c['id'] == $editData['category_id'] ? 'selected' : '' ?>>
                    <?= $c['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Name</label>
        <input type="text" name="name" value="<?= $editData['name'] ?>" required>

        <label>Description</label>
        <textarea name="description" rows="4" required><?= $editData['description'] ?></textarea>

        <label>Original Price</label>
        <input type="number" name="originalprice" step="0.01" value="<?= $editData['originalprice'] ?>" required>

        <label>Selling Price</label>
        <input type="number" name="sellingprice" step="0.01" value="<?= $editData['sellingprice'] ?>" required>

        <label>Current Image</label><br>
        <img src="<?= $editData['image'] ?>" width="120"><br><br>

        <label>Change Image (Optional)</label>
        <input type="file" name="image">

        <button type="submit" name="update" class="save-btn">Update Product</button>
    </form>
</div>
<?php endif; ?>


<table>
    <tr>
        <th>ID</th>
        <th>Category</th>
        <th>Name</th>
        <th>Image</th>
        <th>Description</th>
        <th>Original Price</th>
        <th>Selling Price</th>
        <th>Action</th>
    </tr>

    <?php while($p = $products->fetch_assoc()): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= $p['category'] ?></td>
        <td><?= $p['name'] ?></td>
        <td><img src="<?= $p['image'] ?>"></td>
        <td><?= $p['description'] ?></td>
        <td>₹<?= $p['originalprice'] ?></td>
        <td>₹<?= $p['sellingprice'] ?></td>
        <td class="action-links">
            <a href="viewproducts.php?edit=<?= $p['id'] ?>">Edit</a> |
            <a href="viewproducts.php?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
