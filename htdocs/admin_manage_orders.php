<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include 'connection.php';

$sql = "SELECT o.order_id, u.fullname, p.product_name, od.quantity, o.total_amount, o.status 
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        JOIN order_details od ON o.order_id = od.order_id
        JOIN products p ON od.product_id = p.product_id"; 
$result = $conn->query($sql);

if (isset($_POST['update_status'])) {
    $order_id = $_POST['update_status']; 
    $new_status = $_POST['status']; 

    // Update the status
    $update_sql = "UPDATE orders SET status = ? WHERE order_id = ?";
    if ($stmt = $conn->prepare($update_sql)) {
        $stmt->bind_param("si", $new_status, $order_id); 
        if ($stmt->execute()) {
            echo "<script>alert('Order status updated successfully!');</script>";

            // Check if the status for email sending
            if ($new_status == 'Shipped' || $new_status == 'Delivered') {
                // Get customer email
                $email_sql = "SELECT u.email, u.fullname, o.order_id FROM orders o
                            JOIN users u ON o.user_id = u.user_id WHERE o.order_id = ?";
                $email_stmt = $conn->prepare($email_sql);
                $email_stmt->bind_param("i", $order_id);
                $email_stmt->execute();
                $email_stmt->store_result();
                $email_stmt->bind_result($user_email, $customer_name, $order_id);
                $email_stmt->fetch();

                if ($user_email) {
                    // Email Sending
                    require 'PHPMailer/PHPMailerAutoload.php';

                    $mail = new PHPMailer;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';  
                    $mail->SMTPAuth = true;
                    $mail->Username = 'evangelista.yhuri.bsit@gmail.com';  
                    $mail->Password = 'ofszwltxbxtowinw';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('evangelista.yhuri.bsit@gmail.com', 'DigiList');
                    $mail->addAddress($user_email);  
                    $mail->isHTML(true);
                    $mail->Subject = 'Order Status Update - Order #' . $order_id;

                    
                    $mail->Body = "
                        <html>
                        <head>
                            <title>Order Status Update</title>
                        </head>
                        <body>
                            <h2>Hi " . htmlspecialchars($customer_name) . ",</h2>
                            <p>Your order (Order ID: #$order_id) has been <strong>$new_status</strong>.</p>
                            <p>Thank you for your patience. We will notify you when the status changes again.</p>
                            <p>If you have any questions, feel free to reach out to our customer support.</p>
                            <footer>
                                <p>Best Regards,</p>
                                <p><strong>DigiList Team</strong></p>
                            </footer>
                        </body>
                        </html>
                    ";

                    // Send the email
                    if (!$mail->send()) {
                        echo "<script>alert('Failed to send email notification.');</script>";
                    } else {
                        echo "<script>alert('Email notification sent to the customer.');</script>";
                    }
                } else {
                    echo "<script>alert('Customer email not found.');</script>";
                }

            }

            echo "<script>window.location.href = 'admin_manage_orders.php';</script>";
        } else {
            echo "<script>alert('Error updating status.');</script>";
        }
    }
}

// Get the selected status
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status_filter']) ? trim($_GET['status_filter']) : '';

$sql = "
    SELECT o.order_id, u.fullname, p.product_name, od.quantity, o.total_amount, o.status 
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    JOIN order_details od ON o.order_id = od.order_id
    JOIN products p ON od.product_id = p.product_id
    WHERE 1=1"; // Always true

// Apply status filter
if (!empty($status_filter)) {
    $sql .= " AND o.status = ?";
}

// Apply search filter
if (!empty($search_query)) {
    $sql .= " AND (u.fullname LIKE ? OR p.product_name LIKE ? OR o.order_id LIKE ?)";
}

$stmt = $conn->prepare($sql);

// Bind parameters
if (!empty($status_filter) && !empty($search_query)) {
    $search_param = '%' . $search_query . '%';
    $stmt->bind_param("ssss", $status_filter, $search_param, $search_param, $search_param);
} elseif (!empty($status_filter)) {
    $stmt->bind_param("s", $status_filter);
} elseif (!empty($search_query)) {
    $search_param = '%' . $search_query . '%';
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>View & Manage Orders</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
        }

        /* Fixed Sidebar */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #007bb5;
            color: white;
            padding-top: 20px;
            overflow-y: auto;
        }

        .sidebar h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }

        .sidebar a:hover {
            background-color: #005f8f;
        }

        .logout-btn {
            position: absolute;
            bottom: 50px;
            width: 90%;
            left: 5%;
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #cc0000;
        }

        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 20px;
            flex-grow: 1;
        }

        .main-content h1 {
            color: #007bb5;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-bar {
            padding: 8px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .status-bar {
            padding: 8px;
            width: 180px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-left: 10px;
        }

        .add-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            margin-left: auto;
        }

        .add-btn:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #007bb5;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .btn-group a {
            text-decoration: none;
            padding: 8px 12px;
            margin: 0 5px;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-group .update-btn {
            background-color: #ffc107;
        }

        .btn-group .update-btn:hover {
            background-color: #e0a800;
        }

        select {
            padding: 8px;
            font-size: 16px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .update-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
        font-weight: bold;
        width: 100%;
    }

    .update-btn:hover {
        background-color: #0056b3;
    }

    td {
        vertical-align: middle;
    }
    </style>
</head>
<body>

<div class="sidebar">
    <h1>Admin</h1>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_manage_products.php">View and Manage Products</a>
    <a href="admin_manage_orders.php"> View and Manage Orders</a>
    <form method="POST" action="logout.php">
        <button class="logout-btn" type="submit">Log Out</button>
    </form>
</div>

<div class="main-content">
    <h1>View and Manage Orders</h1>

    <div class="actions">
    <input type="text" class="search-bar" id="search-bar" placeholder="Search orders..." onkeyup="filterTable()">
    <select id="status-filter" class="status-bar" onchange="filterTable()">
        <option value="">All Status</option>
        <option value="Pending" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="Processing" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
        <option value="Shipped" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
        <option value="Delivered" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
        <option value="Cancelled" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
    </select>
</div>

   <table id="order-table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Order Details</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
        error_reporting(E_ALL); 
        ini_set('display_errors', 1);

        if ($result->num_rows > 0) {
            // Group orders by order_id
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                $order_id = $row['order_id'];
                if (!isset($orders[$order_id])) {
                    $orders[$order_id] = [
                        'order_id' => $order_id,
                        'customer_name' => $row['fullname'],
                        'details' => [],
                        'total_amount' => 0,
                        'status' => $row['status']
                    ];
                }

                // Add product details and quantity to the order
                $orders[$order_id]['details'][] = [
                    'product_name' => $row['product_name'],
                    'quantity' => $row['quantity'],
                    'total_amount' => $row['quantity'] * $row['total_amount']
                ];

                // Update the total amount for this order
                $orders[$order_id]['total_amount'] += $row['quantity'] * $row['total_amount'];
            }

            foreach ($orders as $order) {
    echo "<tr>";
    echo "<td>" . $order['order_id'] . "</td>";
    echo "<td>" . htmlspecialchars($order['customer_name']) . "</td>";
    echo "<td>";
    foreach ($order['details'] as $item) {
        echo htmlspecialchars($item['product_name']) . " (x" . $item['quantity'] . ")<br>";
    }
    echo "</td>";
    echo "<td>₱" . number_format($order['total_amount'], 2) . "</td>";

    echo "<td style='text-align: center;'>
        <form action='' method='POST'>
            <select name='status' required style='padding: 5px; border-radius: 4px;'>
                <option value='Pending' " . ($order['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                <option value='Processing' " . ($order['status'] == 'Processing' ? 'selected' : '') . ">Processing</option>
                <option value='Shipped' " . ($order['status'] == 'Shipped' ? 'selected' : '') . ">Shipped</option>
                <option value='Delivered' " . ($order['status'] == 'Delivered' ? 'selected' : '') . ">Delivered</option>
                <option value='Cancelled' " . ($order['status'] == 'Cancelled' ? 'selected' : '') . ">Cancelled</option>
            </select>
      </td>";

echo "<td style='text-align: center;'>
        <button type='submit' name='update_status' value='" . $order['order_id'] . "' class='update-btn'>Update Status</button>
        <input type='hidden' name='order_id' value='" . $order['order_id'] . "'>
      </form>
      </td>";

}  

        } else {
            echo "<tr><td colspan='6'>No orders found</td></tr>";
        }
        ?>
    </tbody>
</table>
</div>

<script>
    let debounceTimeout;
function filterTable() {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        var statusFilter = document.getElementById("status-filter").value;
        var searchQuery = document.getElementById("search-bar").value;
        var url = new URL(window.location.href);

        if (statusFilter) {
            url.searchParams.set('status_filter', statusFilter);
        } else {
            url.searchParams.delete('status_filter');
        }

        if (searchQuery) {
            url.searchParams.set('search', searchQuery);
        } else {
            url.searchParams.delete('search');
        }

        window.location.href = url.toString();
    }, 300); // delay 
}

</script>

</body>
</html>
