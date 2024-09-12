<?php
session_start();
require('dbconnection.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to modify your order.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $new_quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    // Validate the quantity
    if (empty($new_quantity) || $new_quantity <= 0) {
        echo "Please enter a valid quantity.";
        exit();
    }

    // Update the order quantity in the pending_orders table
    $sql = "UPDATE pending_orders SET quantity = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $new_quantity, $order_id, $user_id);

    if ($stmt->execute()) {
        // Redirect to the dashboard
        header("Location: user_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
