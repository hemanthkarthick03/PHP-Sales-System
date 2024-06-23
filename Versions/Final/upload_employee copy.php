<?php
require_once 'dbconfig.php'; // Adjust path as per your setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $date_of_birth = $_POST['date_of_birth'];
    $company_handling = $_POST['company_handling'];
    
    // File upload handling
    $image = $_FILES['image'];
    $image_data = file_get_contents($image['tmp_name']); // Read image data as binary

    try {
        // Prepare INSERT statement for employee details
        $stmt = $pdo->prepare("INSERT INTO employees (name, date_of_birth, company_handling, image_data) 
                               VALUES (:name, :date_of_birth, :company_handling, :image_data)");
        
        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':company_handling', $company_handling);
        $stmt->bindParam(':image_data', $image_data, PDO::PARAM_LOB); // Use PDO::PARAM_LOB for blob
        
        $stmt->execute();
        
        // Get the inserted employee's ID
        $employee_id = $pdo->lastInsertId();

        // Create a new table for the employee's sales details
        $table_name = preg_replace('/[^a-zA-Z0-9_]/', '_', $name . $employee_id);; // Use the employee's ID to ensure unique table name
        $create_table_sql = "CREATE TABLE $table_name (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                name VARCHAR(255) NOT NULL,
                                category VARCHAR(255) NOT NULL,
                                val1 DECIMAL(10,2) NOT NULL,
                                val2 DECIMAL(10,2) NOT NULL,
                                val3 DECIMAL(10,2) NOT NULL,
                                total DECIMAL(10,2) NOT NULL
                            )";
        $pdo->exec($create_table_sql);
        
        "Employee details and image uploaded successfully.";
    } catch (PDOException $e) {
        die("Error uploading image: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Employee Image | Finecons Sales</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 60px;
            background-color: #f8f9fa;
            padding-bottom: 60px;

        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        h1 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .button-container {
            margin-top: 30px;
        }
        .button-container a {
            display: block;
            margin-bottom: 10px;
            padding: 5px 5px;
            text-align: center;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button-container a:hover {
            background-color: #0056b3;
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
        <a class="navbar-brand" href="admin_dashboard.php">Sales Management</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Upload Employee Image</h1>

        <!-- Upload Form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="company_handling">Company Handling:</label>
                <input type="text" id="company_handling" name="company_handling" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="image">Select Image:</label>
                <input type="file" id="image" name="image" accept="image/*" class="form-control-file" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary btn-block">Upload Image</button>
                <a href="admin_dashboard.php" class="btn btn-secondary btn-block">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        &copy; Sales Management System | Finecons
    </footer>

    <!-- Bootstrap JS and dependencies (optional, for certain components) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
