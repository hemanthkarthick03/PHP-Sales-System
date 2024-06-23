<?php
require_once 'dbconfig.php'; // Ensure correct path to dbconfig.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $val1 = $_POST['val1'];
    $val2 = $_POST['val2'];
    $val3 = $_POST['val3'];
    $total = $_POST['total'];

    try {
        // Prepare INSERT statement
        $stmt = $pdo->prepare("INSERT INTO salestable (name, val1, val2, val3, total) 
                               VALUES (:name, :val1, :val2, :val3, :total)");

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':val1', $val1);
        $stmt->bindParam(':val2', $val2);
        $stmt->bindParam(':val3', $val3);
        $stmt->bindParam(':total', $total);

        // Execute the INSERT statement
        $stmt->execute();

        echo "Data inserted successfully";
    } catch (PDOException $e) {
        die("Error inserting data: " . $e->getMessage());
    }
} else {
    echo "Invalid request method";
}
?>
