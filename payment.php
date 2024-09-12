<?php
session_start();
require('dbconnection.php');

// Simulate payment processing
function process_payment() {
    // Simulate payment success
    return true; // Payment successful
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to proceed with the payment.";
    exit();
}

// Ensure the grand total and order ID are available
if (!isset($_SESSION['grand_total']) || !isset($_SESSION['order_id'])) {
    echo "No payment information found. Please place an order first.";
    exit();
}

$grand_total = $_SESSION['grand_total'];
$order_id = $_SESSION['order_id'];
$user_id = $_SESSION['user_id'];

// Retrieve user information
$query = "SELECT firstname, middlename, lastname, address, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_info = $stmt->get_result()->fetch_assoc();

if (!$user_info) {
    echo "User information could not be retrieved.";
    exit();
}

$full_name = trim($user_info['firstname'] . ' ' . $user_info['middlename'] . ' ' . $user_info['lastname']);
$address = $user_info['address'];
$phone_number = $user_info['phone'];

// Retrieve order details
$query = "SELECT dish_id, quantity FROM pending_orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order_info = $stmt->get_result()->fetch_assoc();

if (!$order_info) {
    echo "Order information could not be retrieved.";
    exit();
}

$dish_id = $order_info['dish_id'];
$quantity = $order_info['quantity'];

// Retrieve dish name
$query = "SELECT name FROM popular_dishes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $dish_id);
$stmt->execute();
$dish_name = $stmt->get_result()->fetch_assoc()['name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate POST data
    if (empty($_POST['delivery_time']) || empty($grand_total) || empty($order_id)) {
        echo "Please fill in all required fields.";
        exit();
    }

    $delivery_time = $_POST['delivery_time'];

    // Process payment
    if (process_payment()) {
        // Insert order into `orders` table
        $order_details = "Order for $quantity x $dish_name";
        $insert_query = "INSERT INTO orders (full_name, food_name, order_details, address, phone_number, quantity, delivery_time, status, total_price, user_id, dishe_id)
                         VALUES (?, ?, ?, ?, ?, ?, ?, 'paid', ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("sssssisiii", $full_name, $dish_name, $order_details, $address, $phone_number, $quantity, $delivery_time, $grand_total, $user_id, $dish_id);
        $insert_stmt->execute();

        // Update status in `pending_orders` table to 'paid'
        $update_query = "UPDATE pending_orders SET status = 'paid' WHERE id = ? AND user_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $order_id, $user_id);

        if ($update_stmt->execute()) {
            // Clear session variables
            unset($_SESSION['order_id']);
            unset($_SESSION['grand_total']);

            // Output the effect and refresh the dashboard
            echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Payment Confirmation</title>
                <style>
                    body {
                        font-family: 'Dosis', sans-serif;
                        background-color: rgba(0, 0, 0, 0.8);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        color: #fff;
                    }
                    .confirmation-message {
                        font-size: 3rem;
                        text-align: center;
                        animation: fadeIn 1s ease forwards, fadeOut 1s ease 2s forwards;
                    }
                    @keyframes fadeIn {
                        from { opacity: 0; transform: scale(0.9); }
                        to { opacity: 1; transform: scale(1); }
                    }
                    @keyframes fadeOut {
                        from { opacity: 1; transform: scale(1); }
                        to { opacity: 0; transform: scale(1.1); }
                    }
                </style>
            </head>
            <body>
                <div class='confirmation-message'>
                    Payment Successful! Redirecting...
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'user_dashboard.php';
                    }, 3000);
                </script>
            </body>
            </html>";
        } else {
            // Handle update failure
            echo "Error updating order status: " . $conn->error;
        }
    } else {
        echo "Payment failed. Please try again.";
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>
