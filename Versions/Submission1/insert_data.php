<?php
require_once 'dbconfig.php'; // Ensure correct path to dbconfig.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data and sanitize inputs
    $name = htmlspecialchars($_POST['name']);
    $val1 = floatval($_POST['val1']);
    $val2 = floatval($_POST['val2']);
    $val3 = floatval($_POST['val3']);
    $category = $_POST['category']; // Assuming category is validated at the form level

    // Validate form data (add more validation as needed)
    if (empty($name) || $val1 <= 0 || $val2 <= 0 || $val3 <= 0) {
        die("All fields are required and must be valid numbers greater than zero.");
    }

    // Compute total
    $total = $val1 + $val2 + $val3;

    try {
        // Prepare INSERT statement
        $stmt = $pdo->prepare("INSERT INTO salestable (name, category, val1, val2, val3, total) 
                               VALUES (:name, :category, :val1, :val2, :val3, :total)");

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':val1', $val1);
        $stmt->bindParam(':val2', $val2);
        $stmt->bindParam(':val3', $val3);
        $stmt->bindParam(':total', $total);

        // Execute the INSERT statement
        $stmt->execute();

        // Redirect to success page or show success message
        header("Location: success.php");
        exit();

    } catch (PDOException $e) {
        die("Error inserting data: " . $e->getMessage());
    }
} else {
    echo "Invalid request method";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data | Finecons Sales</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding-top: 60px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }
        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#">Sales Management</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center mb-4">Add Sales Data | Finecons Sales</h2>
        <form action="insert_data.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="Sales1">Sales 1</option>
                    <option value="Sales2">Sales 2</option>
                    <option value="Sales3">Sales 3</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="val1">Value 1:</label>
                    <input type="number" id="val1" name="val1" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="val2">Value 2:</label>
                    <input type="number" id="val2" name="val2" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="val3">Value 3:</label>
                    <input type="number" id="val3" name="val3" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="total">Total:</label>
                <input type="number" id="total" name="total" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        &copy; Finecons | Sales Management System | 2024 
    </footer>

    <!-- Bootstrap JS and dependencies (optional, for certain components) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
