
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>Reset Password - DigiList</title>
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

        /* Reset Password Form Styles */
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

    <!-- main Section -->
    <div class="login-form">
        <h2>Reset Your Password</h2>
 <?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include 'connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token']; 

    // Validate token and check expiration
    $stmt = $conn->prepare('SELECT user_id FROM users WHERE reset_token = ? AND token_expiry > NOW()');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        if (isset($_POST['reset'])) {
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            if ($newPassword === $confirmPassword) {
                // Update password
                $stmt = $conn->prepare('UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?');
                $stmt->bind_param('ss', $newPassword, $token);
                $stmt->execute();

                echo '<script>
                        alert("Password reset successfully.");
                        window.location.href = "login.php";
                      </script>';
            } else {
                echo '<p class="message" style="text-align:center; color:red;">Passwords do not match. Please re-enter the passwords.</p>';
                //  the form fields with the user's input
                $passwordFieldValue = htmlspecialchars($newPassword);
                $confirmPasswordFieldValue = htmlspecialchars($confirmPassword);
            }
        } else {
            // Default form values
            $passwordFieldValue = '';
            $confirmPasswordFieldValue = '';
        }
        ?>
        <form method="POST" action="">
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" value="<?php echo $passwordFieldValue; ?>" required>
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" name="confirmPassword" id="confirmPassword" value="<?php echo $confirmPasswordFieldValue; ?>" required>
            <button type="submit" name="reset">Reset Password</button>
        </form>
        <?php
    } else {
        echo '<p class="message" style="text-align:center; color:red;">Invalid or expired token.</p>';
    }
    $stmt->close();
}
?>
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
