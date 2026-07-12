
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>Forgot Password - DigiList</title>
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

    <!-- main Section -->
    <main>
        <div class="login-form loaded">
            <h2>Forgot Password</h2>

<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);          
include 'connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); // Get the email from the form

    if (!empty($email)) {
        // Validate email format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Check if the email exists in the database
            $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Fetch the user's email
                $row = $result->fetch_assoc();
                $user_email = $row['email'];

                // Generate a unique reset token
                $token = bin2hex(random_bytes(16)); 

                // Set expiration time (60 minutes from now)
                $expiry_time = date('Y-m-d H:i:s', strtotime('+60 minutes'));

                // Store the token and expiry time in the database
                $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
                $stmt->bind_param("sss", $token, $expiry_time, $user_email);
                $stmt->execute();

                // Create the reset link
                $resetLink = "https://tinyurl.com/4yz59p4u?token=" . $token;
                $resetLinkHtml = "<a href='" . $resetLink . "'>Click here to reset your password</a>";
                
                require 'PHPMailer/PHPMailerAutoload.php';
                // Set up PHPMailer
                
                $mail = new PHPMailer;
                
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'contact@gmail.com'; 
                $mail->Password = 'contact'; 
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Set email 
                $mail->setFrom('contact@gmail.com', 'DigiList');
                $mail->addAddress($user_email); 
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "
                    <html>
                    <head>
                        <title>Password Reset</title>
                    </head>
                    <body>
                        <p>You requested a password reset. Click the link to reset your password: $resetLinkHtml</p>
                        
                    </body>
                    </html>
                ";

                // Send email
                if ($mail->send()) {
                    echo '<script>alert("A password reset link has been sent to your email address."); window.location.href="login.php";</script>';
                    
                } else {
                    echo '<p class="message" style="color: red; text-align: center;">Failed to send reset email. Please try again later.</p>';
                }
            } else {
                echo '<p class="message" style="color: red; text-align: center;">Email not found in our records.</p>';
            }

            $stmt->close();
        } else {
            echo '<p class="message" style="color: red; text-align: center;">Invalid email format.</p>';
        }
    } else {
        echo '<p class="message" style="color: red; text-align: center;">Please enter your email address.</p>';
    }
}

$conn->close();
?>


            <form action="" method="POST">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit">Submit</button>
            </form>
        </div>
    </main>

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
