<?php

include 'connection.php';


if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);


    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $product_id); 
        if ($stmt->execute()) {
            echo "<script>
                alert('Product deleted successfully.');
                window.location.href = 'admin_manage_products.php';
            </script>";
        } else {
            echo "<script>
                alert('Error deleting product.');
                window.location.href = 'admin_manage_products.php';
            </script>";
        }
        $stmt->close();
    } else {
        echo "<script>
            alert('Database error.');
            window.location.href = 'admin_manage_products.php';
        </script>";
    }

    $conn->close();
} else {
    echo "<script>
        alert('Invalid request.');
        window.location.href = 'admin_manage_products.php';
    </script>";
}
