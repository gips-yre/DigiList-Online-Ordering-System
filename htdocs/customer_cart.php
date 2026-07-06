<?php
session_start();

include 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Customer') {
    header('Location: login.php');
    exit();
}


$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch cart items for the logged-in user
$sql = "SELECT cart.cart_id, cart.quantity, products.product_name, products.product_image, products.price, products.stock 
        FROM cart 
        INNER JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();

// Fetch user details (address, contact, etc.)
$sql_user = "SELECT fullname, email, user_contact, user_address FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_details = $stmt_user->get_result()->fetch_assoc();

if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $cart_id => $new_quantity) {
        // Update the cart quantity if the new quantity is valid
        if ($new_quantity > 0) {
            $update_sql = "UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("iii", $new_quantity, $cart_id, $user_id);
            $update_stmt->execute();
        }
    }
    header("Location: customer_cart.php"); 
    exit();
}
// Removal
if (isset($_POST['remove_item'])) {
    $cart_id_to_remove = $_POST['remove_item'];
    
    $delete_sql = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $cart_id_to_remove, $user_id);
    $delete_stmt->execute();
    
    header("Location: customer_cart.php");
    exit();
}
   
if (isset($_POST['checkout_selected'])) {
    // Check if there are selected items in the cart
    if (!empty($_POST['selected_items'])) {
        // Fetch user contact and address from the database
        $select_user_details_sql = "SELECT user_contact, user_address FROM users WHERE user_id = ?";
        $select_user_details_stmt = $conn->prepare($select_user_details_sql);
        $select_user_details_stmt->bind_param("i", $user_id);
        $select_user_details_stmt->execute();
        $select_user_details_stmt->bind_result($user_contact, $user_address);
        $select_user_details_stmt->fetch();
        $select_user_details_stmt->close();

        // Check if contact and address are provided
        if (empty($user_contact) || empty($user_address)) {
            echo "<script>
                    alert('Please complete your contact information and shipping address before checking out.');
                    window.location.href = 'customer_cart.php'; 
                  </script>";
            return;           
        }
        $selected_items = $_POST['selected_items']; // Array 
        $grand_total = 0; 
        
        // order insert
        $insert_order_sql = "INSERT INTO orders (user_id, status, payment_method, total_amount, order_date) 
                             VALUES (?, 'Pending', ?, ?, NOW())";
        $order_stmt = $conn->prepare($insert_order_sql);
        $order_stmt->bind_param("isd", $user_id, $_POST['payment_method'], $_POST['grand_total']);
        $order_stmt->execute();
        $order_id = $order_stmt->insert_id; 
        $order_stmt->close();
        
        $order_summary = '';
        // Loop through selected items
        foreach ($_POST['selected_items'] as $cart_id => $value) {
            // Fetch product details for the cart_id
            $select_item_sql = "SELECT product_id, quantity FROM cart WHERE cart_id = ? AND user_id = ?";
            $select_item_stmt = $conn->prepare($select_item_sql);
            $select_item_stmt->bind_param("ii", $cart_id, $user_id);
            $select_item_stmt->execute();
            $select_item_stmt->store_result();

            // Check if the item exists in the cart
            if ($select_item_stmt->num_rows > 0) {
                $select_item_stmt->bind_result($product_id, $quantity);
                $select_item_stmt->fetch(); 

                // Fetch the price from the products table
                $select_price_sql = "SELECT product_name, price, stock FROM products WHERE product_id = ?";
                $select_price_stmt = $conn->prepare($select_price_sql);
                $select_price_stmt->bind_param("i", $product_id);
                $select_price_stmt->execute();
                $select_price_stmt->bind_result($product_name, $price, $stock);
                $select_price_stmt->fetch(); // Fetch the price and stock for the product
                $select_price_stmt->close();

                // Calculate total price for the item (price * quantity)
                $item_total = $price * $quantity;
                $grand_total += $item_total; // Add item total 

                // Now insert into the order_details table
                $orderItemQuery = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($orderItemQuery);
                $stmt->bind_param("iiis", $order_id, $product_id, $quantity, $price);
                $stmt->execute();
                $stmt->close();

                // Subtract ordered quantity from product stock
                $new_stock = $stock - $quantity;
                if ($new_stock < 0) {
                    $new_stock = 0; // Ensure stock doesn't go negative
                }

                $update_stock_query = "UPDATE products SET stock = ? WHERE product_id = ?";
                $update_stock_stmt = $conn->prepare($update_stock_query);
                $update_stock_stmt->bind_param("ii", $new_stock, $product_id);
                $update_stock_stmt->execute();
                $update_stock_stmt->close();

                // Delete the item from the cart after inserting into orders
                $deleteCartQuery = "DELETE FROM cart WHERE product_id = ? AND user_id = ?";
                $deleteCartStmt = $conn->prepare($deleteCartQuery);
                $deleteCartStmt->bind_param("ii", $product_id, $user_id);
                $deleteCartStmt->execute();
                $deleteCartStmt->close();

                $order_summary .= "<tr>
                            <td>{$product_name}</td>
                            <td>{$quantity}</td>
                            <td>₱" . number_format($price, 2) . "</td>
                            <td>₱" . number_format($item_total, 2) . "</td>
                        </tr>";
            }

            $select_item_stmt->close();
        }

        // update the order's total_amount with the grand total
        // Add VAT and shipping fee
        $vat_rate = 0.10; 
        $shipping_fee = 100; // Shipping fee 
        $vat = $grand_total * $vat_rate;
        $total_with_vat_and_shipping = $grand_total + $vat + $shipping_fee;

        // Update the total amount in the orders table
        $update_order_sql = "UPDATE orders SET total_amount = ? WHERE order_id = ?";
        $update_order_stmt = $conn->prepare($update_order_sql);
        $update_order_stmt->bind_param("di", $total_with_vat_and_shipping, $order_id);
        $update_order_stmt->execute();
        $update_order_stmt->close();

        $sql = "SELECT email FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);  
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
         // Fetch the email
        $row = $result->fetch_assoc();
        $user_email = $row['email'];
        
        //Email Sending
        require 'PHPMailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'evangelista.yhuri.bsit@gmail.com';  
        $mail->Password = 'ofszwltxbxtowinw';           
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        
        $mail->setFrom('evangelista.yhuri.bsit@gmail.com', 'DigiList');
        $mail->addAddress($user_email);  
        $mail->isHTML(true);
        $mail->Subject = 'Order Confirmation - Order #' . $order_id;
        
        $mail->Body = "
    <h1>Thank you for your order, {$username}!</h1>
    
    
    <h2>Order Summary:</h2>
    <table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            {$order_summary}
        </tbody>
    </table>

    <p><strong>Shipping:</strong> ₱" . number_format($shipping_fee, 2) . "</p>
    <p><strong>Taxes:</strong> ₱" . number_format($vat, 2) . "</p>
    <p><strong>Order Total:</strong> ₱" . number_format($total_with_vat_and_shipping, 2) . "</p>

    <h2>Shipping Information:</h2>
    <p><strong>Shipping Address:</strong> {$user_details['user_address']}</p>

    <p><strong>Your Order Number:</strong> #{$order_id}</p>
    <p>Payment Method: <strong>{$_POST['payment_method']}</strong></p>

    <h2>If you have any questions or need assistance, feel free to contact our customer support team at:</h2>
    <p>Email: evangelista.yhuri.bsit@gmail.com | Phone: 09612962786</p>

    <p>We hope you enjoy your purchase!</p>

    <p>Best regards,</p>
    <p>The DigiList Team</p>

    <hr>
    <p><a href=''>Return Policy</a> | <a href=''>Terms & Conditions</a></p>
";

        $mail->AltBody = "Thank you for your order!\nOrder ID: {$order_id}\nTotal Amount: ₱" . number_format($total_with_vat_and_shipping, 2);

        if (!$mail->send()) {
            echo 'Message could not be sent. Error Occured: ' . $mail->ErrorInfo;
        } else {
            echo 'Order confirmation email sent successfully.';
          }
        }
        // Redirect to order confirmation page
        header("Location: checkout_success.php");
        exit;
    } else {
        echo "<script>alert('No items selected for checkout.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>Customer Cart - DigiList</title>
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

.main-content {
    flex-grow: 1; 
    overflow-y: auto; 
    padding-bottom: 100px; 
}

    /* Cart Table Styles */
.cart-table {
    width: 80%;
    margin: 40px auto;
    border-collapse: collapse;
    text-align: center;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.cart-table th, .cart-table td {
    border: 1px solid #ddd;
    padding: 10px;
}

.cart-table th {
    background-color: #f4f4f4;
    font-size: 18px;
}

.cart-table img {
    width: 120px;
    height: auto;
    object-fit: contain;
}

.total-price {
    text-align: right; 
    font-size: 18px;
    font-weight: bold;
    width: 85%;
    margin-bottom: 20px; 
}

.update-button {
    display: block;
    margin: 0 auto; 
    padding: 10px 20px;
    background-color: #d9534f;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.update-button:hover {
    background-color: #c9302c;
}
.remove-button {
    display: block;
    margin: 0 auto; 
    padding: 10px 20px;
    background-color: #d9534f;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.remove-button:hover {
    background-color: #c9302c;
}

/* Customer Details Section */
.customer-details-section {
    width: 80%;
    margin: 40px auto;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.customer-details-section h3 {
    font-size: 24px;
    margin-bottom: 20px;
}

.customer-details-section p {
    font-size: 18px;
    margin: 10px 0;
}

.payment-dropdown {
    font-size: 18px;
    padding: 5px 10px;
    margin-top: 10px;
}

/* Checkout Button Container */
.checkout-button-container {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.checkout-button {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    font-size: 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.checkout-button:hover {
    background-color: #218838;
}

.required-field-warning {
    color: red;
    font-size: 18px;
    margin-top: 10px;
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

   <!-- Main Section -->
<div class="main-content">
    <h2 style="text-align:center; margin-top: 40px;">Your Cart</h2>
    <form action="" method="POST">
    <table class="cart-table">
        <thead>
            <tr>
                <th>Select</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Stock</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_price = 0;
            while ($row = $cart_result->fetch_assoc()) {
                $total_item_price = $row['price'] * $row['quantity'];
                $total_price += $total_item_price;
                ?>
                <tr>
                    <td><input type="checkbox" name="selected_items[<?php echo $row['cart_id']; ?>]" value="1"></td>
                    <td><img src="<?php echo $row['product_image']; ?>" alt="Product Image"></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td>₱<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <input type="number" name="quantities[<?php echo $row['cart_id']; ?>]" value="<?php echo $row['quantity']; ?>" min="1" max="<?php echo $row['stock']; ?>" class="quantity-input">
                    </td>
                    <td><?php echo $row['stock']; ?></td>
                    <td>₱<?php echo number_format($total_item_price, 2); ?></td>
                    <td><button class="remove-button" type="submit" name="remove_item" value="<?php echo $row['cart_id']; ?>">Remove</button></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="total-price">
      <?php
      $vat = $total_price * 0.10; 
      $shipping_fee = 100; 
      $grand_total = $total_price + $vat + $shipping_fee;
      ?>
      <p><strong>VAT: ₱<?php echo number_format($vat, 2); ?></strong></p>
      <p><strong>Shipping Fee: ₱<?php echo number_format($shipping_fee, 2); ?></strong></p>
      <p><strong>Grand Total: ₱<?php echo number_format($grand_total, 2); ?></strong></p>
    </div>

    <button type="submit" class="update-button" name="update_cart" value="update_cart">Update Cart</button>

    <!-- Customer Details -->
    <div class="customer-details-section">
       <h3>Customer Details</h3>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user_details['fullname']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user_details['email']); ?></p>
      <p><strong>Contact:</strong> <?php echo htmlspecialchars($user_details['user_contact']); ?></p>
      <p><strong>Address:</strong> <?php echo htmlspecialchars($user_details['user_address']); ?></p>

      <label for="payment_method">Payment Method:</label>
      <select name="payment_method" id="payment_method" class="payment-dropdown">
          <option value="COD">Cash on Delivery</option>
          <option value="Gcash">Gcash</option>
          <option value="Bank Transfer">Bank Transfer</option>
      </select>

      <input type="hidden" name="grand_total" value="<?php echo $grand_total; ?>">
      
      <div class="checkout-button-container">
         <button type="submit" class="checkout-button" name="checkout_selected" value="checkout_selected">Checkout</button>
      </div>
    </div>
</form>
</div>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 DigiList. All rights reserved. | <a href="#">Privacy Policy</a></p>
    </footer>


    <script>
    function updateTotal() {
        let grandTotal = 0;
        let vatRate = 0.10;
        let shippingFee = 100;

        // Loop through each row and check if it is selected
        document.querySelectorAll('.cart-table tbody tr').forEach(function(row) {
            let checkbox = row.querySelector('.select-item');
            let quantityInput = row.querySelector('.quantity-input');
            let itemTotalCell = row.querySelector('.item-total');
            let price = parseFloat(row.getAttribute('data-price'));
            let stock = parseInt(row.getAttribute('data-stock'));
            let totalItemPrice = parseFloat(row.getAttribute('data-total-price'));

            // Only include selected items in the total calculation
            if (checkbox.checked) {
                let quantity = parseInt(quantityInput.value);
                if (quantity > stock) {
                    quantityInput.value = stock; // Prevent exceeding stock
                }
                totalItemPrice = price * quantity;
                itemTotalCell.textContent = '₱' + totalItemPrice.toFixed(2);
                grandTotal += totalItemPrice;
            } else {
                itemTotalCell.textContent = '₱0.00'; // Set to 0 if not selected
            }
        });

        // Add VAT and Shipping Fee
        let vat = grandTotal * vatRate;
        let totalWithVatAndShipping = grandTotal + vat + shippingFee;

        // Update the grand total on the page
        document.getElementById('grand-total').textContent = totalWithVatAndShipping.toFixed(2);

        // Update hidden grand total field for form submission
        document.querySelector('input[name="grand_total"]').value = totalWithVatAndShipping.toFixed(2);
    }

    // Initialize total when the page loads
    window.onload = updateTotal;

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

