<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Customer') {
    header('Location: login.php');
    exit();
}


$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>Customer Dashboard - DigiList</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
<style>

body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    overflow-x: hidden;
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
    background-color: #e0e0e0; /* Slightly darker gray on hover */
    color: #007bb5; 
}

/* Product Showcase Section */
.product-showcase {
    text-align: center;
    margin: 50px 0;
    opacity: 0;
    transform: translateY(50px);
    transition: opacity 1s ease, transform 1s ease;
}

.product-showcase.loaded {
    opacity: 1;
    transform: translateY(0);
}

.product-showcase h2 {
    font-size: 2.0rem;
    color: #007bb5;
    margin-bottom: 20px;
}

.carousel {
    position: relative;
    width: 90%;
    max-width: 1200px;
    margin: auto;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.carousel-images {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.carousel-item {
    min-width: 100%;
    height: auto;
    object-fit: cover;
}

.carousel-controls button {
    position: absolute;
    top: 50%;
    z-index: 1;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    font-size: 2rem;
    transform: translateY(-50%);
}

.carousel-controls .prev {
    left: 10px;
}

.carousel-controls .next {
    right: 10px;
}

.carousel-indicators {
    position: absolute;
    bottom: 10px;
    width: 100%;
    text-align: center;
}

.carousel-indicators .dot {
    height: 15px;
    width: 15px;
    margin: 0 5px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block;
    cursor: pointer;
}

.carousel-indicators .dot.active {
    background-color: #717171;
}

/* Categories Section */
.categories {
    text-align: center;
    margin-bottom: 20px;
    opacity: 0;
    transform: translateY(50px);
    transition: opacity 1s ease, transform 1s ease;
}

.categories.loaded {
    opacity: 1;
    transform: translateY(0);
}

.categories h2 {
    font-size: 2.0rem;
    color: #007bb5;
    margin-bottom: 20px;
}

.categories-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    max-width: 1500px;
    margin: 0 auto;
    padding: 0 10px;
    opacity: 1;
    transform: translateY(0);
}

.category-list-wrapper {
    overflow: hidden;
    width: 100%;
}

.category-list {
    display: flex;
    gap: 20px;
    transition: transform 0.5s ease;
}

.category-item {
    background-color: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: calc(33.333% - 20px);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.category-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 10px;
}

.arrow {
    font-size: 2rem;
    background: white;
    color: #007bb5;
    border: none;
    cursor: pointer;
    padding: 10px;
    border-radius: 50%;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    position: absolute;
    z-index: 10;
    top: 50%;
    transform: translateY(-50%);
}

.arrow.left {
    left: 0;
}

.arrow.right {
    right: 0;
}

.arrow:hover {
    background-color: #007bb5;
    color: white;
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
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
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


     <!-- Product Showcase Section -->
    <section class="product-showcase">
    <h2>Featured Products</h2>
    <div class="carousel">
        <div class="carousel-images">
            <img src="assets/rakk_mouse_poster.webp" alt="Product 1" class="carousel-item" onclick="redirectToProducts()">
            <img src="assets/rakk_kb_poster.webp" alt="Product 2" class="carousel-item" onclick="redirectToProducts()">
            <img src="assets/rakk_case_poster.webp" alt="Product 3" class="carousel-item" onclick="redirectToProducts()">
            <img src="assets/ryzen_poster.webp" alt="Product 4" class="carousel-item" onclick="redirectToProducts()">
            <img src="assets/nvidia_poster.webp" alt="Product 5" class="carousel-item" onclick="redirectToProducts()">
        </div>
        <div class="carousel-controls">
            <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
            <button class="next" onclick="changeSlide(1)">&#10095;</button>
        </div>
        <div class="carousel-indicators">
            <span class="dot active" onclick="setSlide(0)"></span>
            <span class="dot" onclick="setSlide(1)"></span>
            <span class="dot" onclick="setSlide(2)"></span>
            <span class="dot" onclick="setSlide(3)"></span>
            <span class="dot" onclick="setSlide(4)"></span>
        </div>
    </div>
</section>


   <!-- Categories Section -->
<section class="categories">
    <h2>Categories</h2>
    <div class="categories-container">
        
        <button class="arrow left" onclick="scrollCategories(-1)">&#10094;</button>

       
        <div class="category-list-wrapper">
            <div class="category-list">
                <!-- Category Items -->
                <div class="category-item" onclick="redirectToCategoryPage('Processor')">
                    <img src="assets/processor.png" alt="Processor">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('Motherboard')">
                    <img src="assets/mobo.png" alt="Motherboard">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('GPU')">
                    <img src="assets/gpu.png" alt="GPU">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('Memory')">
                    <img src="assets/ram.png" alt="RAM">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('SSD')">
                    <img src="assets/ssd.png" alt="SSD">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('Power Supply')">
                    <img src="assets/psu.png" alt="PSU">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('PC Case')">
                    <img src="assets/case.png" alt="PC Case">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('Monitor')">
                    <img src="assets/monitor.png" alt="Monitor">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('Peripherals')">
                    <img src="assets/keyboard.png" alt="Keyboard">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('Peripherals')">
                    <img src="assets/mouse.png" alt="Mouse">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('Peripherals')">
                    <img src="assets/headset.png" alt="Headphones">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('Peripherals')">
                    <img src="assets/speaker.png" alt="Speaker">
                </div>
                <div class="category-item" onclick="redirectToCategoryPage('UPS/AVR')">
                    <img src="assets/ups.png" alt="UPS & AVR">
                </div>
            </div>
        </div>

        <button class="arrow right" onclick="scrollCategories(1)">&#10095;</button>
    </div>
</section>


    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 DigiList. All rights reserved. | <a href="#">Privacy Policy</a></p>
    </footer>

    <script>
        let currentIndex = 0;
        const images = document.querySelectorAll('.carousel-item');
        const dots = document.querySelectorAll('.dot');
        const showcase = document.querySelector('.product-showcase');
        const categories = document.querySelector('.categories');

        function showSlide(index) {
            if (index >= images.length) currentIndex = 0;
            if (index < 0) currentIndex = images.length - 1;
            images.forEach((img, i) => {
                img.style.display = i === currentIndex ? 'block' : 'none';
            });
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentIndex].classList.add('active');
        }

        function changeSlide(n) {
            showSlide(currentIndex += n);
        }

        function setSlide(index) {
            currentIndex = index;
            showSlide(index);
        }
        
          // Trigger animations on load
        window.addEventListener('load', () => {
            const showcase = document.querySelector('.product-showcase');
            const categoriesHead = document.querySelector('.categories');
            const categories = document.querySelector('.categories-container');  
            if (showcase) {
                showcase.classList.add('loaded');
            }
            if (categoriesHead) {
                categoriesHead.classList.add('loaded');
            }
            if (categories) {
                categories.classList.add('loaded');
            }
        });

        showSlide(currentIndex);

        // JavaScript for scrolling categories
       let scrollPosition = 0;
       const categoryList = document.querySelector('.category-list');
       const categoryItemWidth = 170; 
       const visibleItems = 6;

       function scrollCategories(direction) {
     const maxScroll =
        (categoryList.children.length - visibleItems) * categoryItemWidth;

     scrollPosition += direction * categoryItemWidth * visibleItems;
     if (scrollPosition < 0) scrollPosition = 0;
     if (scrollPosition > maxScroll) scrollPosition = maxScroll;

    categoryList.style.transform = `translateX(-${scrollPosition}px)`;
       }

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
