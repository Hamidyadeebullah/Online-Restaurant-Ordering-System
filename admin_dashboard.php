<?php
session_start();
require('dbconnection.php');

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch pending and served orders
$pending_orders_query = "SELECT * FROM orders WHERE service='pending'";
$pending_orders_result = mysqli_query($conn, $pending_orders_query);

$served_orders_query = "SELECT * FROM orders WHERE service='served'";
$served_orders_result = mysqli_query($conn, $served_orders_query);

// Fetch all dishes for management
$dishes_query = "SELECT * FROM popular_dishes";
$dishes_result = mysqli_query($conn, $dishes_query);

// Fetch all users for management
$users_query = "SELECT * FROM users";
$users_result = mysqli_query($conn, $users_query);

if (!$pending_orders_result || !$served_orders_result || !$dishes_result || !$users_result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Adeeb Online Restaurant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<!-- Header section starts -->
<header>
    <a href="admin_dashboard.php" class="logo"><span>A</span>dmin</a>
    <nav class="navbar">
        <a href="#orders">Orders</a>
        <a href="#served-orders">Served Orders</a>
        <a href="#manage-dishes">Manage Dishes</a>
        <a href="#Users">Users</a>
    </nav>
    <div class="icons">
        <a href="logout.php" class="logout-icon"><i class="fas fa-sign-out-alt"></i></a>
    </div>
</header>

<!-- Pending Orders Section -->
<section class="admin-dashboard" id="orders">
    <h2>Pending Orders</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Full Name</th>
                    <th>Food Name</th>
                    <th>Quantity</th>
                    <th>Address</th>
                    <th>Delivery Time</th>
                    <th>Phone Number</th>
                    <th>Order Details</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($pending_orders_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['food_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['delivery_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['order_details']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="serve_order" class="serve-btn">Serve Order</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Served Orders Section -->
<section class="admin-dashboard" id="served-orders">
    <h2>Served Orders</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Full Name</th>
                    <th>Food Name</th>
                    <th>Quantity</th>
                    <th>Address</th>
                    <th>Delivery Time</th>
                    <th>Phone Number</th>
                    <th>Order Details</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($served_orders_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['food_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['delivery_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['order_details']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="undo_serve_order" class="undo-btn">Undo Serve</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Manage Dishes Section -->
<section class="admin-dashboard" id="manage-dishes">
    <h2>Manage Dishes</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Dish ID</th>
                    <th>Dish Name</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($dish = mysqli_fetch_assoc($dishes_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($dish['id']); ?></td>
                    <td><?php echo htmlspecialchars($dish['name']); ?></td>
                    <td><?php echo htmlspecialchars($dish['price']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($dish['image']); ?>" alt="<?php echo htmlspecialchars($dish['name']); ?>" width="50"></td>
                    <td>
                        <a href="edit_dish.php?id=<?php echo $dish['id']; ?>" class="edit-btn">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Manage Users Section -->
<section class="admin-dashboard" id="Users">
    <h2>Users</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($user['middlename']); ?></td>
                    <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($user['gender']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    <td><?php echo htmlspecialchars($user['address']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

</body>
</html>
