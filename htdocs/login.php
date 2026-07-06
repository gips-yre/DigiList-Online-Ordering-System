<?php
session_start(); 
include 'connection.php'; 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    if (!empty($username) && !empty($password)) {
      
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

       
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username']; 
            $_SESSION['user_img'] = $user['user_img'];
            $_SESSION['role'] = $user['role']; 

          
            if ($user['role'] == 'Customer') {
                header('Location: customer_dashboard.php');
            } elseif ($user['role'] == 'Admin') {
                header('Location: admin_dashboard.php');
            } else {
                echo '<script>alert("Invalid role assigned. Contact administrator.");</script>';
            }
        } else {
            echo '<script>alert("Invalid username or password.");</script>';
        }

        $stmt->close();
    } else {
        echo '<script>alert("All fields are required.");</script>';
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
    <title>Login - DigiList</title>
    <style>
        /* Add the styles from your main page to keep consistency */
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

        /* Login Form Styles */
        .login-form {
            width: 600px;
            margin: 100px auto;
            background-color: white;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1s ease, transform 1s ease;
        }

        .login-form.loaded {
            opacity: 1;
            transform: translateY(0);
        }

        .login-form h2 {
            text-align: center;
            color: #007bb5;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .login-form button {
            width: 104%;
            padding: 10px;
            background-color: #007bb5;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .login-form button:hover {
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

    <!-- Login Form Section -->
    <div class="login-form">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <p style="text-align: left;margin-top: -3px;">
    <a href="forgot_password.php" style="color: black; text-decoration: none; font-size: 14px;">Forgot Password?</a> </p>
            <button type="submit">Log In</button>
        </form>
        <p style="text-align:center;">Don't have an account? <a href="register.php">Sign up here</a></p>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 DigiList. All rights reserved. | <a href="#">Privacy Policy</a></p>
    </footer>

    <script>
        // Trigger animation on page load
        window.addEventListener('load', () => {
            const loginForm = document.querySelector('.login-form');
            loginForm.classList.add('loaded');
        });
    </script>

</body>
</html>
