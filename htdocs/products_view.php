<?php
session_start();
include 'connection.php';

// Handle the Add to Cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI']; 
        header("Location: login.php?redirect=true"); 
        exit();
    }

    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    $user_id = $_SESSION['user_id']; 

    // Fetch the products available stock
    $sql_check_stock = "SELECT stock FROM products WHERE product_id = ?";
    $stmt_check_stock = $conn->prepare($sql_check_stock);
    $stmt_check_stock->bind_param("i", $product_id);
    $stmt_check_stock->execute();
    $result_check_stock = $stmt_check_stock->get_result();
    
    if ($result_check_stock->num_rows > 0) {
        $row = $result_check_stock->fetch_assoc();
        $available_stock = $row['stock'];

        // Check if the quantity is greater than available stock
        if ($quantity > $available_stock) {
            $error_message = "The quantity exceeds available stock!";
        } else {
            // Check if the product already exists in the cart for the user
            $sql_check_cart = "SELECT quantity FROM cart WHERE product_id = ? AND user_id = ?";
            $stmt_check_cart = $conn->prepare($sql_check_cart);
            $stmt_check_cart->bind_param("ii", $product_id, $user_id);
            $stmt_check_cart->execute();
            $result_check_cart = $stmt_check_cart->get_result();

            if ($result_check_cart->num_rows > 0) {
                // Product exists in the cart, update the quantity
                $row = $result_check_cart->fetch_assoc();
                $new_quantity = $row['quantity'] + $quantity;

                // Ensure that updated quantity doesn't exceed available stock
                if ($new_quantity > $available_stock) {
                    $error_message = "Updated quantity exceeds available stock!";
                } else {
                    $sql_update_cart = "UPDATE cart SET quantity = ? WHERE product_id = ? AND user_id = ?";
                    $stmt_update_cart = $conn->prepare($sql_update_cart);
                    $stmt_update_cart->bind_param("iii", $new_quantity, $product_id, $user_id);

                    if ($stmt_update_cart->execute()) {
                        $success_message = "Product quantity successfully updated in the cart!";
                    } else {
                        $error_message = "Failed to update the product quantity.";
                    }
                }
            } else {
                // Product doesn't exist in the cart, insert a new entry
                if ($quantity <= $available_stock) {
                    $sql_add_cart = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
                    $stmt_add_cart = $conn->prepare($sql_add_cart);
                    $stmt_add_cart->bind_param("iii", $user_id, $product_id, $quantity);

                    if ($stmt_add_cart->execute()) {
                        $success_message = "Product successfully added to the cart!";
                    } else {
                        $error_message = "Failed to add product to the cart.";
                    }
                } else {
                    $error_message = "Requested quantity exceeds available stock!";
                }
            }
        }
    } else {
        $error_message = "Product not found!";
    }
}

// Get product ID 
$product_id = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;

// Fetch product details
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Fetch random products
$random_products_sql = "SELECT * FROM products WHERE product_id != ? ORDER BY RAND() LIMIT 6";
$random_stmt = $conn->prepare($random_products_sql);
$random_stmt->bind_param("i", $product_id);
$random_stmt->execute();
$random_products_result = $random_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
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
            width: 100%;
            box-sizing: border-box;
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
    justify-content: flex-start; /* Ensure links are aligned to the left */
    gap: 20px; /* Space between each link */
    margin: 0;
    padding: 0;
    flex-grow: 1; /* Make sure the links take up remaining space */
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

        /* Dropdown container */
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

        /* Dropdown menu links */
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

        /* Profile icon styles */
        .profile-icon i {
            margin-left: 5px;
            font-size: 1.1rem;
        }

        /* Show dropdown on hover */
        .navbar .dropdown:hover .dropdown-content {
            display: block;
        }

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
    background-color: #e0e0e0; /* Slightly darker gray on hover */
    color: #007bb5; 
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
            padding-top: 80px;
            overflow-y: auto;
            z-index: 5;
        }

        .sidebar h1 {
            text-align: center;
            margin-bottom: 20px;
            margin-left: 10px;
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

  /* Content Wrapper */
        .content-wrapper {
            margin-left: 250px; /* Account for the sidebar */
            padding: 20px;
        }

        /* Product Details Section */
.product-details {
    display: flex; 
    border: 1px solid #ddd;
    padding: 20px;
    text-align: left; 
    margin: 20px 0;
    border-radius: 8px;
    background: #f5f5f5;
}

.product-details img {
    width: 100%;  
    height: 500px;  
    border-radius: 8px;
    margin-right: 20px;
    object-fit: cover;  
}


.product-details .details {
    flex-grow: 1; 
}

.product-details h1 {
    margin: 10px 0;
    color: #007bb5;
    font-size: 2rem;
}

.product-details p {
    margin: 35px 0; 
    font-size: 1.3rem; 
}

.product-details label {
    font-weight: bold;
    font-size: 1.4rem; 
}

.product-details .product-description,
.product-details .product-stock,
.product-details .product-price {
    font-weight: normal; 
    font-size: 1.2rem; 
}

.product-details input[type="number"] {
    padding: 8px;
    width: 80px; 
    font-size: 1.2rem; 
}

.product-details .add-to-cart {
    background-color: #ff6600;
    color: white;
    border: none;
    padding: 12px 25px;
    font-size: 1.2rem; 
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px; 
}

.product-details .add-to-cart:hover {
    background-color: #ff4500;
}


        /* Related Products Section */
.related-products-section {
    padding: 20px;
    background-color: #fff;
    margin: 20px 0;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.related-products-section h2 {
    text-align: center;
    color: #007bb5;
}

.related-products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.product {
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    transition: transform 0.3s ease;
}

.product img {
    width: 100%;
    height: 250px; 
    object-fit: cover;
    border-bottom: 1px solid #ddd;
}

.product h3 {
    margin: 10px 0;
    font-size: 1.1rem;
    color: #007bb5;
}

.product p {
    margin: 10px 0;
    color: #666;
}

.product:hover {
    transform: translateY(-5px);
}

.product a {
    text-decoration: none;
}
/* General styling for the modal */
    .popup-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #f8f8f8;
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        max-width: 90%;
        width: 400px; 
        text-align: center;
    }

    /* Message text inside the modal */
    .popup-message {
        font-weight: bold;
        margin-bottom: 15px;
    }

    /* Close button styling */
    .popup-close-btn {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    /* Button hover effect */
    .popup-close-btn:hover {
        background-color: #45a049;
    }

    /* Success and Error message styles */
    .success {
        color: #388e3c;
    }
    .error {
        color: #d32f2f; 
    }

    
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.3);
        z-index: 9998; /* Below the modal */
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
                    </div>
                </li>
            </ul>
        </div>
    </header>

    <!-- Sidebar -->
<div class="sidebar">  
    <a href="products_list.php?category=All">All Products</a>       
    <a href="products_list.php?category=Processor">Processor</a>
    <a href="products_list.php?category=Motherboard">Motherboard</a>
    <a href="products_list.php?category=GPU">GPU</a>
    <a href="products_list.php?category=Memory">RAM</a>
    <a href="products_list.php?category=SSD">SSD</a>
    <a href="products_list.php?category=Power Supply">PSU</a>
    <a href="products_list.php?category=AIO Cooling">AIO Cooling</a>
    <a href="products_list.php?category=PC Case">PC Case</a>
    <a href="products_list.php?category=Monitor">Monitor</a>
    <a href="products_list.php?category=UPS/AVR">UPS & AVR</a>
    <a href="products_list.php?category=Peripherals">Peripherals</a>
</div>

<!--for success or error messages -->
<div id="messagePopup" class="popup-modal">
    <div id="popupMessage" class="popup-message"></div>
    <button onclick="closePopup()" class="popup-close-btn">Close</button>
</div>


 <!-- Content Wrapper -->
<div class="content-wrapper">

    <!-- Product Details Section -->
    <div class="product-details">
        <img src="<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
        <div class="details">
            <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
            <p><label>Description:</label> <?php echo htmlspecialchars($product['prod_description']); ?></p>
            <p><label>Stock:</label> <?php echo htmlspecialchars($product['stock']); ?></p>
            <p><label>Price:</label> ₱<?php echo htmlspecialchars($product['price']); ?></p>
            <div>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($product['stock']); ?>">
            </div>
            <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <input type="hidden" name="quantity" id="form-quantity">
                <button type="submit" class="add-to-cart" onclick="submitForm()">Add to Cart</button>
            </form>
        </div>
    </div>

    <!-- Related Products Section -->
    <div class="related-products-section">
        <h2>Related Products</h2>
        <div class="related-products">
            <?php while ($random_product = $random_products_result->fetch_assoc()): ?>
                <div class="product" onclick="window.location.href='products_view.php?product_id=<?php echo htmlspecialchars($random_product['product_id']); ?>'">
                    <img src="<?php echo htmlspecialchars($random_product['product_image']); ?>" alt="<?php echo htmlspecialchars($random_product['product_name']); ?>">
                    <h3><?php echo htmlspecialchars($random_product['product_name']); ?></h3>
                    <p>₱<?php echo htmlspecialchars($random_product['price']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
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

  function submitForm() {
    // Set the form quantity value to match input
    document.getElementById('form-quantity').value = document.getElementById('quantity').value;
     }
       // search input
  function filterProducts() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase(); 
    const products = document.querySelectorAll('.product');

    
    products.forEach(product => {
        const productName = product.querySelector('h2').textContent.toLowerCase(); 
        const productDescription = product.querySelector('p').textContent.toLowerCase();

       
        if (productName.includes(searchInput) || productDescription.includes(searchInput)) {
            product.style.display = ''; 
        } else {
            product.style.display = 'none'; 
        }
    });
}
    
    function showPopup(type, message) {
        // Set the message content
        document.getElementById('popupMessage').innerText = message;
        
        // Change the pop-up 
        var popup = document.getElementById('messagePopup');
        if (type === 'success') {
            popup.classList.add('success');
            popup.classList.remove('error');
        } else {
            popup.classList.add('error');
            popup.classList.remove('success');
        }

        // Show the pop-up
        popup.style.display = 'block';
    }

    function closePopup() {
        // Hide the pop-up 
        document.getElementById('messagePopup').style.display = 'none';
    }
</script>

<?php
    // Check if there's a success or error message
    if (isset($success_message)) {
        echo "<script>window.onload = function() { showPopup('success', '$success_message'); };</script>";
    } elseif (isset($error_message)) {
        echo "<script>window.onload = function() { showPopup('error', '$error_message'); };</script>";
    }
?>

</body>
</html>
