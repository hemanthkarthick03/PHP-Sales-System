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
