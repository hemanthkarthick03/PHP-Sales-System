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
        $table_name = preg_replace('/[^a-zA-Z0-9_]/', '_', $name . $employee_id); // Use the employee's ID to ensure unique table name
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

        // Insert a new user with default credentials
        $username = $name . $employee_id;
        $password = 'password'; // Default password "password" (not encrypted as per requirement)
        $role = 'users';
        $insert_user_sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $pdo->prepare($insert_user_sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        // Create a new PHP page for the employee
        $employee_page_content = "<?php
require_once 'dbconfig.php';

\$name = '$name';
\$table_name = '$table_name';

try {
    // Prepare SELECT statement to fetch sales details
    \$stmt = \$pdo->prepare(\"SELECT * FROM \$table_name\");
    \$stmt->execute();
    \$results = \$stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException \$e) {
    die(\"Error fetching sales data: \" . \$e->getMessage());
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?php echo htmlspecialchars(\$name); ?>'s Sales</title>
    <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            padding-top: 60px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 40px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        table th, table td {
            padding: 8px;
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1><?php echo htmlspecialchars(\$name); ?>'s Sales</h1>
        <div class='table-responsive'>
            <table class='table table-striped table-bordered'>
                <thead class='thead-dark'>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Val1</th>
                        <th>Val2</th>
                        <th>Val3</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (\$results as \$row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(\$row['id']); ?></td>
                            <td><?php echo htmlspecialchars(\$row['name']); ?></td>
                            <td><?php echo htmlspecialchars(\$row['category']); ?></td>
                            <td><?php echo htmlspecialchars(\$row['val1']); ?></td>
                            <td><?php echo htmlspecialchars(\$row['val2']); ?></td>
                            <td><?php echo htmlspecialchars(\$row['val3']); ?></td>
                            <td><?php echo htmlspecialchars(\$row['total']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional, for certain components) -->
    <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script>
</body>
</html>
";

        // Create a new PHP dashboard page for the employee
        $employee_dashboard_content = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Employee Dashboard | Finecons Sales</title>
    <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 100px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .button-container {
            margin-top: 30px;
        }
        .button-container a {
            display: block;
            margin-bottom: 10px;
            padding: 15px 20px;
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
        h1 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 40px;
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
    <nav class='navbar navbar-expand-lg navbar-dark fixed-top'>
        <a class='navbar-brand' href='employee_dashboard.php'>Sales Management</a>
        <div class='collapse navbar-collapse' id='navbarNav'>
            <ul class='navbar-nav ml-auto'>
                <li class='nav-item'>
                    <a class='nav-link' href='logout.php'>Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class='container'>
        <center><h2>Welcome to Employee Dashboard</h2></center><br>
        <div class='sales-data'>
            <center>
                <h3>Sales Data</h3><br>
                <p>Display charts, tables, or summaries of sales data here.</p>
            </center>
        </div>
        <div class='button-container'>
            <a href='insert_form.php' class='btn btn-primary btn-lg btn-block mb-3'>Insert Data</a>
            <a href='query_data.php' class='btn btn-primary btn-lg btn-block'>Show Data Table</a>
            <a href='download_excel.php' class='btn btn-primary btn-lg btn-block'>Export Data as Excel</a>
        </div>
    </div>
    <footer>
        &copy; 2024 Sales Management System
    </footer>
    <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script>
</body>
</html>";

        // Create the directory for the employee if it does not exist
        $employee_directory = 'employees/' . $name . $employee_id;
        if (!is_dir($employee_directory)) {
            mkdir($employee_directory, 0777, true);
        }

        // Save the employee sales page
        $employee_sales_page_path = $employee_directory . '/' . $name . $employee_id . '_sales.php';
        file_put_contents($employee_sales_page_path, $employee_page_content);

        // Save the employee dashboard page
        $employee_dashboard_page_path = $employee_directory . '/' . $name . $employee_id . '_Dashboard.php';
        file_put_contents($employee_dashboard_page_path, $employee_dashboard_content);

        echo "Employee added successfully. Employee page created at: <a href='$employee_dashboard_page_path'>$employee_dashboard_page_path</a>";
    } catch (PDOException $e) {
        die("Error adding employee: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Employee Image | Sales Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 100px;
            background-color: #f8f9fa;
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
                <label for="image">Upload Image:</label>
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
