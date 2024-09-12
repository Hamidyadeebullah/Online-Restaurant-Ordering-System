<?php
session_start();
require('dbconnection.php');

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your dashboard.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the pending (unpaid) orders from the pending_orders table
$pending_sql = "SELECT po.id, po.dish_id, po.quantity, po.status, po.order_date, pd.name AS dish_name, pd.price AS dish_price 
                FROM pending_orders po
                JOIN popular_dishes pd ON po.dish_id = pd.id
                WHERE po.user_id = ? AND po.status = 'pending'";
$pending_stmt = $conn->prepare($pending_sql);
$pending_stmt->bind_param("i", $user_id);
$pending_stmt->execute();
$pending_result = $pending_stmt->get_result();
$pending_orders = $pending_result->fetch_all(MYSQLI_ASSOC);

// Fetch the paid orders from the orders table
$paid_sql = "SELECT o.id, o.food_name AS dish_name, o.quantity, o.status, o.created_at AS order_date, o.total_price AS dish_price 
             FROM orders o
             WHERE o.user_id = ? AND o.status = 'paid'";
$paid_stmt = $conn->prepare($paid_sql);
$paid_stmt->bind_param("i", $user_id);
$paid_stmt->execute();
$paid_result = $paid_stmt->get_result();
$paid_orders = $paid_result->fetch_all(MYSQLI_ASSOC);

$grandTotal = 0;
foreach ($pending_orders as $order) {
    $grandTotal += $order['dish_price'] * $order['quantity'];
}
$_SESSION['grand_total'] = $grandTotal;

// Store the most recent unpaid order ID for payment processing
if (count($pending_orders) > 0) {
    $_SESSION['order_id'] = $pending_orders[0]['id'];
}

$pending_stmt->close();
$paid_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="user_dashboard_css.css">
</head>
<body>
    <div class="container">
        <h1>Your Orders</h1>

        <!-- Unpaid Orders Section -->
        <div class="unpaid-orders">
            <h2>Unpaid Orders</h2>
            <?php if (count($pending_orders) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Dish Name</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th>Price (Each)</th>
                            <th>Total Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['dish_name']); ?></td>
                                <td>
                                    <form action="modify_order.php" method="post" class="inline-form">
                                        <input type="number" name="quantity" value="<?php echo $order['quantity']; ?>" min="1" required>
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="btn-modify">Modify</button>
                                    </form>
                                </td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                <td>&euro;<?php echo htmlspecialchars(number_format($order['dish_price'], 2)); ?></td>
                                <td>&euro;<?php echo htmlspecialchars(number_format($order['dish_price'] * $order['quantity'], 2)); ?></td>
                                <td>
                                    <a href="cancel_order.php?order_id=<?php echo $order['id']; ?>" class="btn-cancel">Cancel</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Payment Form -->
                <form action="payment.php" method="post" class="payment-form">
                    <!-- The ID of the most recent pending order is stored in the session -->
                    <input type="hidden" name="order_id" value="<?php echo $_SESSION['order_id']; ?>">
                    <input type="hidden" name="grand_total" value="<?php echo $_SESSION['grand_total']; ?>">

                    <label for="delivery_time">Select Delivery Date and Time:</label>
                    <input type="datetime-local" name="delivery_time" required>
                    
                    <button type="submit" class="pay-button">Pay Now</button>
                </form>
            <?php else: ?>
                <p>You have no unpaid orders.</p>
            <?php endif; ?>
        </div>

        <!-- Paid Orders Section -->
        <div class="paid-orders">
            <h2>Paid Orders</h2>
            <?php if (count($paid_orders) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Dish Name</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th>Price (Each)</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paid_orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['dish_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                <td>&euro;<?php echo htmlspecialchars(number_format($order['dish_price'], 2)); ?></td>
                                <td>&euro;<?php echo htmlspecialchars(number_format($order['dish_price'] * $order['quantity'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have no paid orders.</p>
            <?php endif; ?>
        </div>

        <!-- Back to Dashboard Button -->
        <a href="user_logged.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
