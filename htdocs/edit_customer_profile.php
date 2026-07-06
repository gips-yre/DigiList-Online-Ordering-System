<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
session_start();

include 'connection.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];  
    $fullname = htmlspecialchars($_POST['fullname']); 
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 
    $user_contact = $_POST['user_contact'];
    $user_address = htmlspecialchars($_POST['user_address']);

     // Check for email and username duplication
    $sql = "SELECT user_id FROM users WHERE (email = ? OR username = ?) AND user_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $email, $username, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = "The username or email is already taken. Please choose another.";
        header("Location: customer_profile.php");
        exit();
    }
    $stmt->close();

    // Validate contact number 
    if (!preg_match("/^09\d{9}$/", $user_contact)) {
        echo "Error: Contact number must start with '09' and be 11 digits.";
        exit();
    }

    // Handle file upload if a new image is provided
    if (!empty($_FILES['user_img']['name'])) {
        $user_img = $_FILES['user_img']['name'];
        $target_dir = "assets/profiles/"; 
        $target_file = $target_dir . basename($user_img);
        
        // Validate image type (allow only .jpg, .jpeg, .png, .gif)
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "Error: Only image files (JPG, JPEG, PNG, GIF) are allowed.";
            exit();
        }
        
        // Move uploaded file to the target directory
        if (!move_uploaded_file($_FILES['user_img']['tmp_name'], $target_file)) {
            echo "Error: Unable to upload image.";
            exit();
        }
    } else {
        // If no new image is uploaded, keep the current image
        $sql = "SELECT user_img FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($current_user_img);
        $stmt->fetch();
        $user_img = $current_user_img; // Keep the existing image if no new one is uploaded
        $stmt->close();
    }

    // Update query
    $sql = "UPDATE users SET fullname=?, username=?, email=?, user_contact=?, user_address=?, user_img=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $fullname, $username, $email, $user_contact, $user_address, $user_img, $user_id);

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
        header("Location: customer_profile.php"); // Redirect back to profile page
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
