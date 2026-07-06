<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
session_start();
include 'connection.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];

 //order cancellation 
if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the order is still pending
    $orderCheckQuery = "SELECT status FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($orderCheckQuery);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order && $order['status'] === 'Pending') {
        // Update the order status to Cancelled
        $cancelOrderQuery = "UPDATE orders SET status = 'Cancelled' WHERE order_id = ?";
        $stmt = $conn->prepare($cancelOrderQuery);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        // Update the product stock
        $updateStockQuery = "UPDATE products SET stock = stock + ? WHERE product_id = ?";
        $stmt = $conn->prepare($updateStockQuery);
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();

        $_SESSION['message'] = "Order cancelled successfully!";
    } else {
        $_SESSION['message'] = "Order cannot be cancelled.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the orders page
    header("Location: customer_orders.php"); // Correct redirection after cancellation
    exit();
}

// Fetch orders for the user
$query = "
    SELECT o.order_id, p.product_name, o.total_amount, o.payment_method, o.status, od.quantity, od.product_id 
    FROM orders o
    JOIN order_details od ON o.order_id = od.order_id
    JOIN products p ON od.product_id = p.product_id
    WHERE o.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>Customer Orders - DigiList</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
<style>

body {
    height: 100%;
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Navbar Styles */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #007bb5;
    padding: 15px 30px;
    color: white;
    position: sticky;
    top: 0;
    z-index: 10;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.navbar .logo h1 {
    font-size: 1.8rem;
    font-weight: bold;
    margin: 0;
}

.navbar .search-bar input {
    padding: 8px 12px;
    width: 300px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 1rem;
}

.navbar .nav-links ul {
    list-style: none;
    display: flex;
    gap: 20px;
    margin: 0;
    padding: 0;
}

.navbar .nav-links a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

.navbar .nav-links a:hover {
    color: #ffd700;
}

.navbar .dropdown {
    position: relative;
}

.navbar .dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: -50px;
    background-color: white;
    min-width: 150px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.navbar .dropdown-content a {
    display: block;
    padding: 10px 15px;
    color: #007bb5;
    text-decoration: none;
    font-weight: normal;
    white-space: nowrap;
    transition: background-color 0.3s ease;
}

.navbar .dropdown-content a:hover {
    background-color: #f5f5f5;
}

.profile-icon i {
    margin-left: 5px;
    font-size: 1.1rem;
}

.navbar .dropdown:hover .dropdown-content {
    display: block;
}

.profile-icon i {
    margin-left: 5px;
    font-size: 1.1rem;
}


.navbar .dropdown > a {
    display: flex;
    align-items: center;
    cursor: pointer;
}

/* Search Bar with Categories */
.search-bar {
    display: flex;
    align-items: center;
    gap: 10px;
}

#category-dropdown {
    padding: 8px 12px;
    font-size: 1rem;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.search-bar input {
    padding: 8px 12px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 1rem;
    width: 300px;
}

.search-button {
    padding: 8px 12px;
    font-size: 0.6rem;
    background-color: #f5f5f5; 
    color: #333; 
    border: 1px solid #ccc; /
    border-radius: 5px;
    cursor: pointer;
}

.search-button i {
    font-size: 0.6rem;
}

.search-button:hover {
    background-color: #e0e0e0;
    color: #007bb5; 
}

/* Main Content */
.main-content {
    padding: 30px;
    background-color: #f4f4f4;
    text-align: center;
    font-family: 'Arial', sans-serif;
}

.main-content h1 {
    font-size: 2.5rem;
    color: #007bb5;
    margin-bottom: 30px;
    font-weight: 600;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 15px;
    text-align: left;
    font-size: 1rem;
    color: #333;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bb5;
    color: white;
    font-weight: bold;
}

td {
    background-color: #fff;
}

/* Table Row Hover Effect */
tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

/* No Orders Message */
.main-content p {
    font-size: 1.2rem;
    color: #555;
    margin-top: 20px;
}
button[name="cancel_order"] {
    background-color: #ff4d4d; /* Light red background */
    color: white; /* White text */
    border: none; /* No border */
    border-radius: 5px; /* Rounded corners */
    padding: 8px 12px; /* Padding inside the button */
    cursor: pointer; /* Pointer cursor on hover */
    font-size: 14px; /* Font size */
    transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth hover effect */
}

button[name="cancel_order"]:hover {
    background-color: #ff1a1a; /* Darker red on hover */
    transform: scale(1.05); /* Slightly enlarge on hover */
}

button[name="cancel_order"]:active {
    background-color: #e60000; /* Even darker red on click */
    transform: scale(0.95); /* Slightly shrink on click */
}


/* Responsive Design */
@media (max-width: 768px) {
    .main-content {
        padding: 20px;
    }

    table {
        font-size: 0.9rem;
    }

    th, td {
        padding: 12px;
    }
}


/* Footer Section */
.footer {
    background-color: #333;
    color: white;
    padding: 5px;
    text-align: center;
    position: fixed;
    bottom: 0;
    width: 100%;
}

.footer a {
    color: #ffd700;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer a:hover {
    color: #ffffff;
}


@media (max-width: 1024px) {
    .category-item {
        width: calc(50% - 20px);
    }
}

@media (max-width: 768px) {
    .category-item {
        width: 100%;
    }

    .navbar .search-bar input {
        width: 200px;
    }
}
</style>

</head>
<body>
    <!-- Navbar Section -->
<header class="navbar">
    <div class="logo">
        <h1>DigiList</h1>
    </div>
    <div class="search-bar">
        <!-- Dropdown -->
<select id="category-dropdown" onchange="redirectToCategory()">
    <option value="">Select Category</option>
    <option value="Processor">Processor</option>
    <option value="Motherboard">Motherboard</option>
    <option value="GPU">GPU</option>
    <option value="Memory">Memory</option>
    <option value="Monitor">Monitor</option>
    <option value="PC Case">PC Case</option>
    <option value="Peripherals">Peripherals</option>
    <option value="Power Supply">PSU</option>
    <option value="SSD">SSD</option>
    <option value="UPS/AVR">UPS & AVR</option>
</select>

    <form action="products_list.php" method="GET">   
        <input type="text" name="search" placeholder="Search for products" value="">
        <button type="submit" class="search-button">Search <i class="fas fa-search"></i></button>
    </form>
    </div>
    <div class="nav-links">
        <ul>
            <li><a href="customer_dashboard.php">Home</a></li>
            <li><a href="customer_cart.php">Cart</a></li>
            <li class="dropdown">
                <a href="#" class="profile-icon">Profile <i class="fa fa-user"></i></a>
                <div class="dropdown-content">
                    <a href="customer_profile.php">My Account</a>
                    <a href="customer_orders.php">My Orders</a>
                    <a href="logout.php">Log Out</a>
                </div>
            </li>
        </ul>
    </div>
</header>

   <div class="main-content">
        <h1>My Orders</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo number_format($row['total_amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['product_id']); ?>">
                                        <input type="hidden" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>">
                                        <button type="submit" name="cancel_order">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <span>N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no orders yet.</p>
        <?php endif; ?>
    </div>

    <script>  
     // Redirection
       function redirectToCategory() {
    const selectedCategory = document.getElementById("category-dropdown").value;
    if (selectedCategory) {
        window.location.href = `products_list.php?category=${encodeURIComponent(selectedCategory)}`;
      }
    }
        function redirectToCategoryPage(category) {
      window.location.href = `products_list.php?category=${encodeURIComponent(category)}`;
    }
    
    function redirectToProducts() {
        window.location.href = 'products_list.php';
    }
    </script>
</body>
</html>

