<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>Register - DigiList</title>
    <style>
        /* Add the styles from your main page to keep consistency */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
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

      .message {
       color: red;
       text-align: center;
       font-weight: bold;
       margin: 20px 0;
    }


        /* Registration Form Styles */
        .register-form {
            width: 600px;
            margin: 100px auto;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            opacity: 0; /* Initially hidden */
            transform: translateY(50px); /* Starts slightly down */
            transition: opacity 1s ease, transform 1s ease; /* Smooth transition */
        }

        .register-form.loaded {
            opacity: 1; /* Fade in */
            transform: translateY(0); /* Move to original position */
        }

        .register-form h2 {
            text-align: center;
            color: #007bb5;
        }

        .register-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .register-form button {
            width: 104%;
            padding: 10px;
            background-color: #007bb5;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .register-form button:hover {
            background-color: #005f8f;
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
    </style>
</head>
<body>

    <!-- Navbar Section -->
    <header class="navbar">
        <div class="logo">
            <h1>DigiList</h1>
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

    <!-- Registration Form Section -->
    <div class="register-form">
        <h2>Create an Account</h2>
        <?php
    
   include 'connection.php';

if (isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if all fields are filled
    if (!empty($fullname) && !empty($email) && !empty($username) && !empty($password)) {
        
        // Validate email format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
            // Check if passwords match
            if ($password === $confirmPassword) {
                
                // Check for duplicate username or email
                $checkStmt = $conn->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
                $checkStmt->bind_param('ss', $username, $email);
                $checkStmt->execute();
                $result = $checkStmt->get_result();

                if ($result->num_rows > 0) {
                    echo '<p class="message" style="color:red; text-align:center;">Username or Email already exists.</p>';
                } else {
                    // If no duplicates, insert the new user
                    $role = 'Customer';
                    $stmt = $conn->prepare('INSERT INTO users (fullname, email, username, password, role) VALUES (?, ?, ?, ?, ?)');
                    $stmt->bind_param('sssss', $fullname, $email, $username, $password, $role);

                    if ($stmt->execute()) {
                        echo '<script>alert("Registered Successfully"); window.location.href="login.php";</script>';
                    } else {
                        echo '<p class="message" style="color:red; text-align:center;">Error: ' . $stmt->error . '</p>';
                    }

                    $stmt->close();
                }

                $checkStmt->close();
            } else {
                echo '<p class="message">Passwords do not match.</p>';
            }

        } else {
            echo '<p class="message">Invalid email format.</p>';
        }

    } else {
        echo '<p class="message">All fields are required.</p>';
    }
}
?>

        <form action="" method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
            <button name="register" type="submit">Sign Up</button>
            
        </form>
        <p style="text-align:center;">Already have an account? <a href="login.php">Log in here</a></p>
    </div>
    

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 DigiList. All rights reserved. | <a href="#">Privacy Policy</a></p>
    </footer>

    <script>
        // Trigger animation on page load
        window.addEventListener('load', () => {
            const registerForm = document.querySelector('.register-form');
            registerForm.classList.add('loaded');
        });
    </script>

</body>
</html>
