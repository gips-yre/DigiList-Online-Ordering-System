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
            text-align: center;
            padding: 100px;
        }
.confirmation-message {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .confirmation-message h2 {
            font-size: 2.5rem;
            color: #007bb5;
            margin-bottom: 20px;
        }

        .confirmation-message p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 30px;
        }

        .confirmation-message .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .confirmation-message .button-container a {
            text-decoration: none;
            background-color: #007bb5;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .confirmation-message .button-container a:hover {
            background-color: #005f87;
        }

        .confirmation-message .email-notice {
            font-size: 0.9rem;
            margin-top: 10px;
            color: #999;
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
    <div class="confirmation-message">
        <h2>Thank you for your purchase!</h2>
        <p>A confirmation has been sent to your email. Stay put and relax while we process your order!</p>

        <div class="button-container">
            <a href="customer_orders.php">Check Your Orders</a>
        </div>

    </div>
</div>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 DigiList. All rights reserved. | <a href="#">Privacy Policy</a></p>
    </footer>


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

