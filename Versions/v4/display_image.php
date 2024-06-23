<?php
require_once 'dbconfig.php'; // Adjust path as per your setup

try {
    // Prepare SELECT statement to fetch all employees
    $stmt = $pdo->query("SELECT * FROM employees");
    
    // Display employees and their images
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
        echo "<p><strong>Date of Birth:</strong> " . htmlspecialchars($row['date_of_birth']) . "</p>";
        echo "<p><strong>Company Handling:</strong> " . htmlspecialchars($row['company_handling']) . "</p>";
        
        // Display image
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image_data']) . '" alt="Employee Image"><br><br>';
    }
} catch (PDOException $e) {
    die("Error displaying images: " . $e->getMessage());
}
?>
