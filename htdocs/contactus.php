<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>Contact Us - DigiList</title>
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

        /* Search Bar with Categories */
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
            padding: 20px;
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

        /* Main Content Styles */
        .contact-section {
            text-align: center;
            padding: 30px;
            margin-top: 20px;
            color: #333;
        }

        .contact-section h1 {
            font-size: 2.5rem;
            color: #007bb5;
            margin-bottom: 20px;
        }

        .contact-section p {
            font-size: 1.1rem;
            line-height: 1.8;
            max-width: 800px;
            margin: auto;
        }

        .contact-section .contact-details {
            margin-top: 30px;
        }

        .contact-details p {
            font-size: 1.2rem;
            line-height: 1.6;
        }

        .contact-details a {
            color: #007bb5;
            text-decoration: none;
        }

        .contact-details a:hover {
            color: #ffd700;
        }

        .contact-section {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1s ease, transform 1s ease;
        }

        .contact-section.loaded {
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

    <!-- Contact Us Section -->
    <section class="contact-section">
        <h1>Contact Us</h1>
        <p>For any inquiries, support requests, or feedback, please don't hesitate to get in touch with us. We're here to assist you!</p>

        <div class="contact-details">
            <p>Email: <a href="mailto:evangelista.yhuri.bsit@gmail.com">evangelista.yhuri.bsit@gmail.com</a></p>
            <p>Phone: <a href="tel:+63212345678">+63 9612962786</a></p>
            <p>Address: DigiList HQ, Malolos City, Philippines</p>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 DigiList. All rights reserved. | <a href="#">Privacy Policy</a></p>
    </footer>

    <script>
        // Trigger animations on page load
        window.addEventListener('load', () => {
            document.querySelector('.contact-section').classList.add('loaded');
        });
    </script>

</body>
</html>
