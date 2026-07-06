<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>About Us - DigiList</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        
        body {
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
        .navbar, .footer {
            background-color: #007bb5;
            color: white;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
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

    .search-bar {
    display: flex;
    align-items: center;
    gap: 10px;
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

        .about-section, .vision-mission {
            text-align: center;
            padding: 30px 30px;
            margin-top: 5px;
            color: #333;
        }

        .about-section h1, .vision-mission h2 {
            font-size: 2.5rem;
            color: #007bb5;
            margin-bottom: 20px;
        }

        .about-section p, .vision-mission p {
            font-size: 1.1rem;
            line-height: 1.8;
            max-width: 800px;
            margin: auto;
        }

        /* Animation */
        .about-section, .vision-mission {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1s ease, transform 1s ease;
        }

        .about-section.loaded, .vision-mission.loaded {
            opacity: 1;
            transform: translateY(0);
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
        <nav class="nav-links">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="login.php">Log In</a></li>
            </ul>
        </nav>
    </header>

    <!-- About Us Section -->
    <section class="about-section">
        <h1>About Us</h1>
        <p>
            DigiList is one of the leading online suppliers of IT and electronic gadgets in the Philippines. 
            We aim to provide a seamless shopping experience by offering a wide range of high-quality tech products and exceptional customer service. 
            Through our easy-to-use website and mobile apps, we ensure convenience, secure payment options, reliable brand warranties, and efficient delivery services across the nation.
        </p>
        <p>
            Our commitment to innovation and quality ensures that every customer enjoys satisfaction with their purchases. At DigiList, we’re shaping the future of online tech shopping.
        </p>
    </section>

    <!-- Vision and Mission Section -->
    <section class="vision-mission">
        <h2>Our Vision</h2>
        <p>
            To become the most trusted and innovative online destination for technology products in the Philippines, empowering every Filipino with cutting-edge solutions for their digital needs.
        </p>

        <h2>Our Mission</h2>
        <p>
            Our mission is to make premium technology accessible to everyone by delivering a top-tier online shopping experience. 
            We are dedicated to providing exceptional service, fast and secure delivery, and unmatched quality while promoting trust and innovation in all aspects of our business.
        </p>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 DigiList. All rights reserved. | <a href="#">Privacy Policy</a></p>
    </footer>

    <script>
        // Trigger animations on page load
        window.addEventListener('load', () => {
            document.querySelector('.about-section').classList.add('loaded');
            document.querySelector('.vision-mission').classList.add('loaded');
        });
    </script>

</body>
</html>
