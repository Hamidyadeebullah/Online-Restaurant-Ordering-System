<?php
session_start();
require('dbconnection.php');

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to add items to your order.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $dish_id = intval($_POST['dish_id']);
    $quantity = intval($_POST['quantity']);

    if ($dish_id > 0 && $quantity > 0) {
        $stmt = $conn->prepare("INSERT INTO pending_orders (user_id, dish_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $dish_id, $quantity);

        if ($stmt->execute()) {
            $_SESSION['id'] = $stmt->insert_id;  // Store the order ID in the session
            echo "Order added successfully!";
        } else {
            echo "Failed to add order. Please try again.";
        }
    } else {
        echo "Invalid dish or quantity.";
    }
} else {
    echo "Invalid request method.";
}
