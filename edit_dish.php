<?php
session_start();
require('dbconnection.php');

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Initialize an empty array to store the dish data
$dish = [];

// Fetch dish details based on the ID provided in the URL
if (isset($_GET['id'])) {
    $dish_id = $_GET['id'];
    $query = "SELECT * FROM popular_dishes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $dish_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If dish is found, fetch its data
    if ($result->num_rows > 0) {
        $dish = $result->fetch_assoc();
    } else {
        echo "Dish not found.";
        exit();
    }
}

// Update dish in the database if the form is submitted
if (isset($_POST['update_dish'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // Validate inputs
    if (!empty($name) && !empty($description) && !empty($price) && !empty($image)) {
        // Update query
        $update_query = "UPDATE popular_dishes SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssdsi", $name, $description, $price, $image, $dish_id);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Dish updated successfully!</p>";
            // Optionally redirect to admin dashboard after successful update
            // header('Location: admin_dashboard.php#manage-dishes');
        } else {
            echo "<p style='color: red;'>Failed to update the dish.</p>";
        }
    } else {
        echo "<p style='color: red;'>All fields are required!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dish - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            padding: 2rem;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 1.5rem;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            border-radius: 10px;
        }
        label {
            font-weight: bold;
            margin-bottom: 0.5rem;
            display: block;
        }
        input[type="text"], textarea, input[type="number"] {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #ff7800;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #ff8800;
        }
        a {
            text-decoration: none;
            color: #ff7800;
            display: inline-block;
            margin-top: 1rem;
        }
        a:hover {
            color: #ff8800;
        }
    </style>
</head>
<body>

<h2>Edit Dish</h2>

<form method="post">
    <label for="name">Dish Name</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($dish['name']); ?>" required>

    <label for="description">Description</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($dish['description']); ?></textarea>

    <label for="price">Price</label>
    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($dish['price']); ?>" step="0.01" required>

    <label for="image">Image URL</label>
    <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($dish['image']); ?>" required>

    <button type="submit" name="update_dish">Update Dish</button>
</form>

<a href="admin_dashboard.php#manage-dishes">Back to Dashboard</a>

</body>
</html>
