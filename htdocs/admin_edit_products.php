<?php
include 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $prod_description = $_POST['prod_description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    // Check if price and stock are valid numbers and not negative
    if (!is_numeric($price) || $price < 0) {
        echo '<script>alert("Price cannot be negative. Please enter a valid price."); window.location.href="admin_manage_products.php?product_id=' . $product_id . '";</script>';
        exit();
    }

    if (!is_numeric($stock) || $stock < 0) {
        echo '<script>alert("Stock cannot be negative. Please enter a valid stock quantity."); window.location.href="admin_manage_products.php?product_id=' . $product_id . '";</script>';
        exit();
    }

    $query = "UPDATE products SET product_name = ?, prod_description = ?, price = ?, stock = ?, category = ?";

    // Handle image upload if a new image is provided
    if (!empty($_FILES['product_image']['name'])) {
        $product_image = $_FILES['product_image']['name'];
        $target_dir = "assets/products/";
        $target_file = $target_dir . basename($product_image);

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            $query .= ", product_image = ?";
        } else {
            echo '<script>alert("Image upload failed."); window.location.href="admin_manage_products.php?product_id=' . $product_id . '";</script>';
            exit();
        }
    }

    $query .= " WHERE product_id = ?";

    $stmt = $conn->prepare($query);

    if (!empty($_FILES['product_image']['name'])) {
        $stmt->bind_param('ssdissi', $product_name, $prod_description, $price, $stock, $category, $target_file, $product_id);
    } else {
        $stmt->bind_param('ssdiss', $product_name, $prod_description, $price, $stock, $category, $product_id);
    }

    if ($stmt->execute()) {
        echo '<script>alert("Product updated successfully!"); window.location.href="admin_manage_products.php";</script>';
    } else {
        echo '<script>alert("Failed to update product."); window.location.href="admin_manage_products.php?product_id=' . $product_id . '";</script>';
    }

    $stmt->close();
    $conn->close();
}
?>
