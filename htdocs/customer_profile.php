<?php

session_start();


include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

// Get the logged-in user's ID 
$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$sql = "SELECT fullname, username, email, user_contact, user_address, user_img FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($fullname, $username, $email, $user_contact, $user_address, $user_img);
$stmt->fetch();
$stmt->close();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
</head>
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

/* Dropdown container */
.navbar .dropdown {
    position: relative;
}

/* Dropdown menu (div instead of ul) */
.navbar .dropdown-content {
    display: none; /* Hide dropdown by default */
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

/* Profile icon styles */
.profile-icon i {
    margin-left: 5px;
    font-size: 1.1rem;
}

/* Additional style to ensure anchor tag behavior */
.navbar .dropdown > a {
    display: flex;
    align-items: center;
    cursor: pointer;
}

        /* Profile Container */
        .profile-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .profile-container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .profile-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .profile-details p {
            font-size: 1.1rem;
            margin: 10px 0;
        }

        .profile-details strong {
            color: #007bb5;
        }

        .edit-profile-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bb5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .edit-profile-btn:hover {
            background-color: #005f8d;
        }

         /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 50px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            position: relative;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .modal-content input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .modal-content button {
            background-color: #007bb5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-content button:hover {
            background-color: #005f8d;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .navbar .search-bar input {
                width: 200px;
            }

            .profile-card {
                width: 90%;
            }

            .navbar .logo h1 {
                font-size: 1.5rem;
            }

            .navbar .nav-links {
                display: none;
            }

            .navbar .nav-links.active {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 60px;
                right: 30px;
                background-color: #007bb5;
                border-radius: 5px;
                padding: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .navbar .nav-links a {
                margin: 5px 0;
                color: white;
            }
        }

        @media (max-width: 480px) {
            .profile-image {
                width: 100px;
                height: 100px;
            }

            .profile-container h2 {
                font-size: 1.6rem;
            }

            .profile-details p {
                font-size: 1rem;
            }

            .profile-card {
                width: 100%;
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
    </style>

<body>
    <!-- Navbar Section -->
    <header class="navbar">
        <div class="logo">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
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

    <main class="profile-container">
        <h2>User Profile</h2>

        <div class="profile-card">
            <img src="assets/profiles/<?php echo htmlspecialchars($user_img); ?>" alt="Profile Image" class="profile-image" />
            <div class="profile-details">
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($fullname); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Contact No:</strong> <?php echo htmlspecialchars($user_contact); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user_address); ?></p>
            </div>

            <!-- Edit Profile Button -->
            <button class="edit-profile-btn" id="edit-btn">Edit Profile</button>
            
        </div>
    </main>
    
     <!-- Modal -->
<div class="modal" id="edit-modal">
    <div class="modal-content">
        <span class="close" id="close-modal">&times;</span>
        <form action="edit_customer_profile.php" method="POST" enctype="multipart/form-data">
            <label for="user_image">Profile Image:</label>
            <input type="file" id="user_image" name="user_img" accept="image/*">

            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">

            <label for="user_contact">Contact No:</label>
            <input type="text" id="user_contact" name="user_contact" value="<?php echo htmlspecialchars($user_contact); ?>">

            <label for="user_address">Address:</label>
            <input type="text" id="user_address" name="user_address" value="<?php echo htmlspecialchars($user_address); ?>">

            <button type="submit">Save Changes</button>
        </form>
    </div>
</div> 
       <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 DigiList. All rights reserved. | <a href="#">Privacy Policy</a></p>
    </footer>
         

    <script>
        const editBtn = document.getElementById('edit-btn');
        const modal = document.getElementById('edit-modal');
        const closeModal = document.getElementById('close-modal');

        // Open modal on button click
        editBtn.addEventListener('click', () => {
            modal.style.display = 'block';
        });

        // Close modal on clicking 'X'
        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Close modal if user clicks outside of modal content
        window.addEventListener('click', (e) => {
            if (e.target == modal) {
                modal.style.display = 'none';
            }
        });

        // Responsive dropdown toggle for smaller screens
        const navLinks = document.querySelector('.navbar .nav-links');
        const dropdown = document.querySelector('.navbar .dropdown');

        // Toggle the dropdown visibility on small screens
        dropdown.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const phoneNumber = document.getElementById('user_contact').value;
        const email = document.getElementById('email').value;
        const username = document.getElementById('username').value;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const phoneRegex = /^09\d{9}$/;

        // Validate phone number (must start with 09 and have 11 digits)
        if (!phoneRegex.test(phoneNumber)) {
            alert("Contact number must start with '09' and have exactly 11 digits!");
            event.preventDefault();  // Prevent form submission
        }

        // Validate email format
        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address!");
            event.preventDefault();
        }

        // Check if username and email are valid and not empty
        if (!username || !email || !phoneNumber) {
            alert("All fields are required!");
            event.preventDefault();  // Prevent form submission
        }
    });
    </script>
</body>
</html>
