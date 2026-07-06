<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include 'connection.php';

// Fetch products from the database
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
        }

        /* Fixed Sidebar */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #007bb5;
            color: white;
            padding-top: 20px;
            overflow-y: auto;
        }

        .sidebar h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }

        .sidebar a:hover {
            background-color: #005f8f;
        }

        .logout-btn {
            position: absolute;
            bottom: 50px;
            width: 90%;
            left: 5%;
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #cc0000;
        }

        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 20px;
            flex-grow: 1;
        }

        .main-content h1 {
            color: #007bb5;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-bar {
            padding: 8px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #007bb5;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .btn-group a {
            text-decoration: none;
            padding: 8px 12px;
            margin: 0 5px;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-group .edit-btn {
            background-color: #ffc107;
        }

        .btn-group .delete-btn {
            background-color: #ff4d4d;
        }

        .btn-group .edit-btn:hover {
            background-color: #e0a800;
        }

        .btn-group .delete-btn:hover {
            background-color: #cc0000;
        }

        .add-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .add-btn:hover {
            background-color: #218838;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 50px;
            width: 400px;
            border-radius: 8px;
            position: relative;
        }

        .modal-content h2 {
            margin-top: 0;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 50%;
        }

        .modal-content label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .modal-content input, .modal-content textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modal-content .submit-btn {
            width: 100%;
            background-color: #007bb5;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #005f8f;
        }

        select {
    padding: 8px;
    font-size: 16px;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 10px;
    }
    </style>
</head>
<body>

<div class="sidebar">
    <h1>Admin</h1>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_manage_products.php">View and Manage Products</a>
    <a href="admin_manage_orders.php"> View and Manage Orders</a>
    <form method="POST" action="logout.php">
        <button class="logout-btn" type="submit">Log Out</button>
    </form>
</div>

<div class="main-content">
    <h1>Manage Products</h1>

    <div class="actions">
        <input type="text" class="search-bar" id="search-bar" placeholder="Search products..." onkeyup="filterTable()">
        <button class="add-btn" id="add-product-btn">Add Product</button>
    </div>

    <table id="product-table">
        <tr>
            <th>Product ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
        <?php
    if ($result->num_rows > 0) {
    // Output data for each product
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['product_id'] . "</td>";
        echo "<td><img src='" . htmlspecialchars($row['product_image']) . "' alt='Product Image' width='100'></td>";
        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['prod_description']) . "</td>";
        echo "<td>₱" . number_format($row['price'], 2) . "</td>";
        echo "<td>" . $row['stock'] . "</td>";
        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
        echo "<td>
                <a href='#' onclick=\"openEditModal(
                    '{$row['product_id']}',
                    '" . addslashes($row['product_name']) . "',
                    '" . addslashes($row['prod_description']) . "',
                    '{$row['price']}',
                    '{$row['stock']}',
                    '{$row['category']}'
                )\">Edit</a> 
                |
                <a href='admin_delete_products.php?product_id=" . $row['product_id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No products found</td></tr>";
}
?>

    </table>
</div>

<!-- Add Product Modal -->
<div class="modal" id="product-modal">
    <div class="modal-content">
        <button class="close-btn" id="close-modal">&times;</button>
        <h2>Add New Product</h2>
        <form action="admin_add_products.php" method="POST" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" required>

            <label for="prod_description">Description:</label>
            <textarea name="prod_description" required></textarea>

            <label for="price">Price: (Php)</label>
            <input type="number" step="0.01" name="price" required>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" required>

             <label for="category">Category:</label>
          <select name="category" id="category-dropdown" required>
        <option value="" disabled selected>Select a category</option>
        <option value="AIO Cooling">AIO Cooling</option>
        <option value="GPU">GPU</option>
        <option value="Memory">Memory</option>
        <option value="Monitor">Monitor</option>
        <option value="Motherboard">Motherboard</option>
        <option value="PC Case">PC Case</option>
        <option value="Peripherals">Peripherals</option>
        <option value="Power Supply">PSU</option>
        <option value="Processor">Processor</option>
        <option value="SSD">SSD</option>
        <option value="UPS/AVR">UPS & AVR</option>
        </select>


            <label for="product_image">Product Image:</label>
            <input type="file" name="product_image" required>

            <button class="submit-btn" type="submit">Add Product</button>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal" id="edit-product-modal">
    <div class="modal-content">
        <button class="close-btn" id="close-edit-modal">&times;</button>
        <h2>Edit Product</h2>
        <form action="admin_edit_products.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" id="edit-product-id">

            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="edit-product-name" required>

            <label for="prod_description">Description:</label>
            <textarea name="prod_description" id="edit-product-description" required></textarea>

            <label for="price">Price: (Php)</label>
            <input type="number" step="0.01" name="price" id="edit-product-price" required>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="edit-product-stock" required>

            <label for="category">Category:</label>
            <select name="category" id="edit-product-category" required>
                <option value="AIO Cooling">AIO Cooling</option>
                <option value="GPU">GPU</option>
                <option value="Memory">Memory</option>
                <option value="Monitor">Monitor</option>
                <option value="Motherboard">Motherboard</option>
                <option value="PC Case">PC Case</option>
                <option value="Peripherals">Peripherals</option>
                <option value="Power Supply">PSU</option>
                <option value="Processor">Processor</option>
                <option value="SSD">SSD</option>
                <option value="UPS & AVR">UPS & AVR</option>
            </select>

            <label for="product_image">Product Image:</label>
            <input type="file" name="product_image">

            <button class="submit-btn" type="submit">Save Changes</button>
        </form>
    </div>
</div>


<script>
    // Search Bar Functionality
    document.getElementById('search-bar').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase().trim();
    let rows = document.querySelectorAll('#product-table tbody tr'); // Ensure tbody is used for clarity

    rows.forEach(row => {
        let name = row.cells[2].textContent.toLowerCase(); // Adjust index if product name is in another column
        if (name.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});


    // Add Modal Functionality
    const modal = document.getElementById('product-modal');
    const addProductBtn = document.getElementById('add-product-btn');
    const closeModal = document.getElementById('close-modal');
    

    addProductBtn.addEventListener('click', () => modal.style.display = 'flex');
    closeModal.addEventListener('click', () => modal.style.display = 'none');
    

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    //Edit Modal
    function openEditModal(productId, name, description, price, stock, category) {
    document.getElementById('edit-product-id').value = productId;
    document.getElementById('edit-product-name').value = name;
    document.getElementById('edit-product-description').value = description;
    document.getElementById('edit-product-price').value = price;
    document.getElementById('edit-product-stock').value = stock;
    document.getElementById('edit-product-category').value = category;
    document.getElementById('edit-product-modal').style.display = 'flex';
    
    
    const closeButton = document.getElementById('close-edit-modal');
    const modal = document.getElementById('edit-product-modal');

    closeButton.onclick = function() {
        modal.style.display = 'none';
    };
}
</script>

</body>
</html>
