<?php
session_start();
require('dbconnection.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to cancel your order.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $order_id = $_GET['order_id'];
    $user_id = $_SESSION['user_id'];

    // Delete the order from the pending_orders table
    $sql = "DELETE FROM pending_orders WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $order_id, $user_id);

    if ($stmt->execute()) {

        header("Location: user_dashboard.php");
        echo "Order canceled successfully.";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
