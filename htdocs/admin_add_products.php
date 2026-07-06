<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $prod_description = $_POST['prod_description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category']; 

    // Check if price and stock are valid numbers and not negative
    if (!is_numeric($price) || $price < 0) {
        echo '<script>alert("Price cannot be negative. Please enter a valid price."); window.location.href="admin_manage_products.php";</script>';
        exit();
    }

    if (!is_numeric($stock) || $stock < 0) {
        echo '<script>alert("Stock cannot be negative. Please enter a valid stock quantity."); window.location.href="admin_manage_products.php";</script>';
        exit();
    }

    // Check if category is selected
    if (empty($category)) {
        echo '<script>alert("Please select a valid category."); window.location.href="admin_manage_products.php";</script>';
        exit();
    } else {
        $product_image = $_FILES['product_image']['name'];
        $target_dir = "assets/products/";
        $target_file = $target_dir . basename($product_image);

        // Check for file upload errors
        if ($_FILES['product_image']['error'] > 0) {
            echo 'Error uploading file: ' . $_FILES['product_image']['error'];
        } else {
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                $stmt = $conn->prepare('INSERT INTO products (product_name, prod_description, price, stock, category, product_image) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->bind_param('ssdiss', $product_name, $prod_description, $price, $stock, $category, $target_file);

                if ($stmt->execute()) {
                    echo '<script>alert("Product added successfully!"); window.location.href="admin_manage_products.php";</script>';
                    exit();
                } else {
                    echo '<script>alert("Failed to add product."); window.location.href="admin_manage_products.php";</script>';
                }
            } else {
                echo '<script>alert("Image upload failed."); window.location.href="admin_manage_products.php";</script>';
                exit();
            }
        }
    }
}

$conn->close();
?>
