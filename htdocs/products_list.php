<?php
include 'connection.php';

// Get the search, category, and current page
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'All';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$productsPerPage = 20; // Limit products 
$offset = ($page - 1) * $productsPerPage; // Calculate the offset

//  based on category
if ($category == 'All') {
    // don't filter by category
    $sql = "SELECT * FROM products WHERE product_name LIKE ? LIMIT ?, ?";
} else {
    // filter by category
    $sql = "SELECT * FROM products WHERE category = ? AND product_name LIKE ? LIMIT ?, ?";
}

$stmt = $conn->prepare($sql);

//  based on the filters
if ($category == 'All') {
    //  only bind the search term and offset
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("sii", $searchTerm, $offset, $productsPerPage);
} else {
    //  bind both category and search term, along with offset
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ssii", $category, $searchTerm, $offset, $productsPerPage);
}
$stmt->execute();
$result = $stmt->get_result();

// Get the total number of products for pagination calculation
$totalSql = $category == 'All' ? "SELECT COUNT(*) FROM products WHERE product_name LIKE ?" : "SELECT COUNT(*) FROM products WHERE category = ? AND product_name LIKE ?";
$totalStmt = $conn->prepare($totalSql);
if ($category == 'All') {
    $totalStmt->bind_param("s", $searchTerm);
} else {
    $totalStmt->bind_param("ss", $category, $searchTerm);
}
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_row();
$totalProducts = $totalRow[0];
$totalPages = ceil($totalProducts / $productsPerPage); // Calculate total pages
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
            border: 1px solid #ccc;
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

        /* Logout Button */
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

        /* Main content */
        .main-content {
            margin-left: 270px;
            padding: 20px;
            flex-grow: 1;
            transition: margin-left 0.3s ease-in-out;
        }

        .main-content h1 {
            color: black;
            margin-left: 15px;
        }

.product-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.product {
    background-color: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.product img {
    width: 100%;
    height: 200px;
    object-fit: contain;
    margin-bottom: 15px;
}

.product h2 {
    font-size: 1.2rem;
    margin-bottom: 10px;
    color: #333;
}

.product p {
    font-size: 1rem;
    color: #666;
    margin-bottom: 15px;
}

.product .price {
    font-size: 1.4rem;
    font-weight: bold;
    color: #007bb5;
}

.product .add-to-cart {
    background-color: #ff6600;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 1rem;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.product .add-to-cart:hover {
    background-color: #ff4500;
}

/* Hover effect for products */
.product:hover {
    transform: translateY(-5px);
}

        .section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            color: #007bb5;
        }
        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 10px 15px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #007bb5;
            color: white;
            border-radius: 5px;
            font-size: 1rem;
        }

        .pagination a:hover {
            background-color: #005f8f;
        }

        .pagination .active {
            background-color: #ffd700;
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
    <input type="text" id="searchInput" placeholder="Search for products" oninput="filterProducts()">
    <button type="submit" class="search-button">Search <i class="fas fa-search"></i></button>
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

    !-- Sidebar -->
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

<!-- Main Content -->
<div class="main-content">
    <h1><?php echo htmlspecialchars($category); ?> Products</h1>

    <div class="product-list">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                <h2><?php echo htmlspecialchars($row['product_name']); ?></h2>
                <p class="price">₱<?php echo htmlspecialchars($row['price']); ?></p>
                <a href="products_view.php?product_id=<?php echo htmlspecialchars($row['product_id']); ?>">
                 <button class="add-to-cart">View Details</button>
                 </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>
<!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>
<script>
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
</script>

</body>
</html>
