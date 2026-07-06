<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

// Fetch monthly sales data
$query = "
    SELECT MONTH(o.order_date) AS month, SUM(o.total_amount) AS total_sales
    FROM orders o
    WHERE o.status = 'Delivered'
    GROUP BY MONTH(o.order_date)
    ORDER BY MONTH(o.order_date) DESC
    LIMIT 6;
";
$result = $conn->query($query);

$salesLabels = [];
$salesData = [];
while ($row = $result->fetch_assoc()) {
    $salesLabels[] = date('F', mktime(0, 0, 0, $row['month'], 1)); // month names
    $salesData[] = (int)$row['total_sales'];  // data is numeric
}

// Fetch yearly sales data
$queryYearly = "
    SELECT YEAR(o.order_date) AS year, SUM(o.total_amount) AS total_sales
    FROM orders o
    WHERE o.status = 'Delivered'
    GROUP BY YEAR(o.order_date)
    ORDER BY YEAR(o.order_date) DESC
    LIMIT 1;
";
$resultYearly = $conn->query($queryYearly);
$yearlySalesLabels = [];
$yearlySalesData = [];
while ($row = $resultYearly->fetch_assoc()) {
    $yearlySalesLabels[] = $row['year'];
    $yearlySalesData[] = (int)$row['total_sales'];  // data is numeric
}

// Fetch order statistics
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orders";
$totalOrdersResult = $conn->query($totalOrdersQuery);
$totalOrders = $totalOrdersResult->fetch_assoc()['total_orders'];

$deliveredOrdersQuery = "SELECT COUNT(*) AS delivered_orders FROM orders WHERE status = 'Delivered'";
$deliveredOrdersResult = $conn->query($deliveredOrdersQuery);
$deliveredOrders = $deliveredOrdersResult->fetch_assoc()['delivered_orders'];

$shippedOrdersQuery = "SELECT COUNT(*) AS shipped_orders FROM orders WHERE status = 'Shipped'";
$shippedOrdersResult = $conn->query($shippedOrdersQuery);
$shippedOrders = $shippedOrdersResult->fetch_assoc()['shipped_orders'];

$pendingOrdersQuery = "SELECT COUNT(*) AS pending_orders FROM orders WHERE status = 'Pending'";
$pendingOrdersResult = $conn->query($pendingOrdersQuery);
$pendingOrders = $pendingOrdersResult->fetch_assoc()['pending_orders'];

$cancelledOrdersQuery = "SELECT COUNT(*) AS cancelled_orders FROM orders WHERE status = 'Cancelled'";
$cancelledOrdersResult = $conn->query($cancelledOrdersQuery);
$cancelledOrders = $cancelledOrdersResult->fetch_assoc()['cancelled_orders'];

// Fetch top-selling products
$topProductsQuery = "SELECT p.product_name, SUM(od.quantity) AS total_quantity
                     FROM order_details od
                     JOIN products p ON od.product_id = p.product_id
                     GROUP BY p.product_name
                     ORDER BY total_quantity DESC
                     LIMIT 5";
$topProductsResult = $conn->query($topProductsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/digilist logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/digilist logo.png">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
        }

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
            margin-left: 10px;
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

       .main-content {
    margin-left: 270px;
    padding: 20px;
    flex-grow: 1;
    display: grid;
    grid-template-areas:
        "welcome welcome"
        "sales-chart sales-chart"
        "top-products order-stats";
    grid-template-columns: 1fr 1fr;
    grid-gap: 20px;
}

.main-content h1 {
    color: black;
    margin-left: 15px;
    grid-area: welcome;
    margin-bottom: 10px; /* Add spacing below the header */
}


        .sales-chart {
       grid-area: sales-chart;
       background: white;
       padding: 20px;
       border-radius: 8px;
       box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
       }

      .sales-chart h2 {
       margin-bottom: 10px; 
       color: #007bb5;
      }

      .chart-container {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      margin-top: 20px; 
      }


        .top-products {
            grid-area: top-products;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .order-stats {
            grid-area: order-stats;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            color: #007bb5;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px; 
            }

            .main-content {
                margin-left: 210px;
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                width: 100%; 
                position: relative;
            }

            .main-content {
                margin-left: 0; 
                grid-template-columns: 1fr;
                grid-template-areas:
                    "sales-chart"
                    "top-products"
                    "order-stats";
            }

            .sidebar a {
                font-size: 14px; 
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px 12px;
            text-align: center;
        }

        th {
            background-color: #007bb5;
            color: white;
        }

        @media (max-width: 600px) {
            table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h1>Dashboard</h1>
        <a href="admin_manage_products.php">View & Manage Products</a>
        <a href="admin_manage_orders.php">View & Manage Orders</a>
        <form method="POST" action="logout.php">
            <button class="logout-btn" type="submit">Log Out</button>
        </form>
    </div>

    <div class="main-content">
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
     
    <div class="sales-chart section">
    <h2>Sales Analytics</h2>
    <p>Monthly and Yearly sales performance:</p>
    <div class="chart-container">
        <div style="width: 48%; height: 400px;">
            <canvas id="monthlySalesChart"></canvas>
        </div>
        <div style="width: 48%; height: 400px;">
            <canvas id="yearlySalesChart"></canvas>
        </div>
    </div>
</div>


    <div class="top-products section">
        <h2>Top Products</h2>
        <p>Here are the top-selling products:</p>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity Sold</th>
                    <th>Total Sales (PHP)</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT p.product_name, SUM(od.quantity) AS quantity_sold, SUM(od.quantity * p.price) AS total_sales, p.product_image
                        FROM order_details od
                        JOIN products p ON od.product_id = p.product_id
                        GROUP BY p.product_name
                        ORDER BY quantity_sold DESC LIMIT 5";

                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                    echo "<td>" . $row['quantity_sold'] . "</td>";
                    echo "<td>" . number_format($row['total_sales'], 2) . "</td>";
                    echo "<td><img src='" . $row['product_image'] . "' alt='" . htmlspecialchars($row['product_name']) . "' style='width: 50px; height: 50px;'></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="order-stats section">
        <h2>Order Statistics</h2>
        <p>Overview of order statuses:</p>
        <table>
            <thead>
                <tr>
                    <th>Order Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Orders</td>
                    <td><?php echo htmlspecialchars($totalOrders); ?></td>
                </tr>
                <tr>
                    <td>Delivered Orders</td>
                    <td><?php echo htmlspecialchars($deliveredOrders); ?></td>
                </tr>
                <tr>
                    <td>Shipped Orders</td>
                    <td><?php echo htmlspecialchars($shippedOrders); ?></td>
                </tr>
                <tr>
                    <td>Pending Orders</td>
                    <td><?php echo htmlspecialchars($pendingOrders); ?></td>
                </tr>
                <tr>
                    <td>Cancelled Orders</td>
                    <td><?php echo htmlspecialchars($cancelledOrders); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>


    <script>
        const monthlySalesLabels = <?php echo json_encode($salesLabels); ?>;
        const monthlySalesData = <?php echo json_encode($salesData); ?>;

        const yearlySalesLabels = <?php echo json_encode($yearlySalesLabels); ?>;
        const yearlySalesData = <?php echo json_encode($yearlySalesData); ?>;

        const monthlyCtx = document.getElementById('monthlySalesChart').getContext('2d');
        const yearlyCtx = document.getElementById('yearlySalesChart').getContext('2d');

        const monthlySalesChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlySalesLabels,
                datasets: [{
                    label: 'Total Sales (PHP) - Monthly',
                    data: monthlySalesData,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const yearlySalesChart = new Chart(yearlyCtx, {
            type: 'bar',
            data: {
                labels: yearlySalesLabels,
                datasets: [{
                    label: 'Total Sales (PHP) - Yearly',
                    data: yearlySalesData,
                    backgroundColor: 'rgba(0, 255, 123, 0.5)',
                    borderColor: 'rgba(0, 255, 123, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
