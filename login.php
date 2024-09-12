<?php
session_start();
require('dbconnection.php');

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = sanitize($_POST['email']);
        $password = $_POST['password'];

        // Check credentials in the admin table
        $admin_query = "SELECT * FROM admin WHERE email = ?";
        if ($admin_stmt = $conn->prepare($admin_query)) {
            $admin_stmt->bind_param("s", $email);
            $admin_stmt->execute();
            $admin_result = $admin_stmt->get_result();

            if ($admin_result->num_rows > 0) {
                $admin_row = $admin_result->fetch_assoc();
                if (password_verify($password, $admin_row['password'])) {
                    $_SESSION['admin_id'] = $admin_row['id'];
                    $_SESSION['admin_username'] = $admin_row['username'];
                    $_SESSION['admin_email'] = $admin_row['email'];
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    echo "Incorrect admin password. <a href='index.php'>Back to Login</a>";
                }
            } else {
                // Check credentials in the users table
                $user_query = "SELECT * FROM users WHERE email=?";
                if ($user_stmt = $conn->prepare($user_query)) {
                    $user_stmt->bind_param("s", $email);
                    $user_stmt->execute();
                    $user_result = $user_stmt->get_result();

                    if ($user_result->num_rows > 0) {
                        $user_row = $user_result->fetch_assoc();
                        if (password_verify($password, $user_row['password'])) {
                            $_SESSION['user_id'] = $user_row['id'];
                            header("Location: user_logged.php");
                            exit();
                        } else {
                            echo "Incorrect user password. <a href='index.php'>Back to Login</a>";
                        }
                    } else {
                        echo "No account found with this email. <a href='index.php'>Back to Login</a>";
                    }
                    $user_stmt->close();
                } else {
                    echo "Error preparing user statement: " . $conn->error;
                }
            }
            $admin_stmt->close();
        } else {
            echo "Error preparing admin statement: " . $conn->error;
        }
    } else {
        echo "Email and password cannot be empty. <a href='index.php'>Back to Login</a>";
    }
} else {
    echo "Invalid request method. <a href='index.php'>Back to Login</a>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

</html>
