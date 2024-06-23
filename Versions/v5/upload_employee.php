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
        $role = 'user'; // Default role "user"
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

$employee_dbconfig_content = "<?php
\$host = 'localhost';
\$db = 'sales';
\$user = 'root';
\$pass = '';
\$charset = 'utf8';

\$dsn = 'mysql:host=' . \$host . ';dbname=' . \$db . ';charset=' . \$charset;
\$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    \$pdo = new PDO(\$dsn, \$user, \$pass, \$options);
} catch (PDOException \$e) {
    throw new PDOException(\$e->getMessage(), (int)\$e->getCode());
}
?>";

$employee_insert_form_content = "
<?php
// Ensure dbconfig.php is included to establish database connection
require_once 'dbconfig.php';

// Initialize variables to hold form data
\$name = \$category = \$val1 = \$val2 = \$val3 = \$total = '';
\$error = '';

// Check if form is submitted
if (\$_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    \$name = htmlspecialchars(\$_POST['name']);
    \$category = htmlspecialchars(\$_POST['category']);
    \$val1 = floatval(\$_POST['val1']);
    \$val2 = floatval(\$_POST['val2']);
    \$val3 = floatval(\$_POST['val3']);
    \$total = floatval(\$_POST['total']);

    // Validate form data (add any additional validation as needed)
    if (empty(\$name) || empty(\$category) || empty(\$val1) || empty(\$val2) || empty(\$val3) || empty(\$total)) {
        \$error = 'All fields are required.';
    } else {
        // Generate the table name dynamically based on employee's name and ID
        \$stmt = \$pdo->prepare('SELECT id FROM employees WHERE name = ?');
        \$stmt->execute([\$name]);
        \$employee = \$stmt->fetch();

        if (!\$employee) {
            \$error = 'Employee not found.';
        } else {
            \$employee_id = \$employee['id'];
            \$table_name = preg_replace('/[^a-zA-Z0-9_]/', '_', \$name . \$employee_id);

            try {
                // Insert data into the dynamically created employee-specific table
                \$insert_sql = 'INSERT INTO ' . \$table_name . ' (name, category, val1, val2, val3, total) 
                               VALUES (?, ?, ?, ?, ?, ?)';
                \$stmt = \$pdo->prepare(\$insert_sql);
                \$stmt->execute([\$name, \$category, \$val1, \$val2, \$val3, \$total]);

                // Display success message
                \$success_message = 'Data inserted successfully!';

                // Connect directly to the newly created database (example)
                \$new_db_name = \$name . '_db';  // Example: Create a new database based on employee's name
                \$new_db_dsn = 'mysql:host=' . \$host . ';dbname=' . \$new_db_name . ';charset=' . \$charset;
                \$new_pdo = new PDO(\$new_db_dsn, \$user, \$pass, \$options);
                // Now you can use \$new_pdo to perform operations on the newly created database

            } catch (PDOException \$e) {
                \$error = 'Error: ' . \$e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Insert Data | Finecons Sales</title>
    <!-- Bootstrap CSS -->
    <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' rel='stylesheet'>
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
    <nav class='navbar navbar-expand-lg navbar-dark fixed-top'>
        <a class='navbar-brand' href='employee_dashboard.php'>Sales Management</a>
        <div class='collapse navbar-collapse' id='navbarNav'>
            <ul class='navbar-nav ml-auto'>
                <li class='nav-item'>
                    <a class='nav-link' href='login.php'>Login</a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link' href='logout.php'>Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class='container mb-5'>
        <h2 class='text-center mb-4'>Add Sales Data | Finecons Sales</h2>
        
        <?php if (!empty(\$error)): ?>
            <div class='alert alert-danger' role='alert'>
                <?php echo \$error; ?>
            </div>
        <?php elseif (!empty(\$success_message)): ?>
            <div class='alert alert-success' role='alert'>
                <?php echo \$success_message; ?>
            </div>
        <?php endif; ?>

        <form action='<?php echo htmlspecialchars(\$_SERVER['PHP_SELF']); ?>' method='post'>
            <div class='form-group'>
                <label for='name'>Name:</label>
                <input type='text' id='name' name='name' class='form-control' required value='<?php echo htmlspecialchars(\$name); ?>'>
            </div>
            <div class='form-group'>
                <label for='category'>Category:</label>
                <select id='category' name='category' class='form-control' required>
                    <option value='Sales1' <?php echo (\$category == 'Sales1') ? 'selected' : ''; ?>>Sales 1</option>
                    <option value='Sales2' <?php echo (\$category == 'Sales2') ? 'selected' : ''; ?>>Sales 2</option>
                    <option value='Sales3' <?php echo (\$category == 'Sales3') ? 'selected' : ''; ?>>Sales 3</option>
                </select>
            </div>
            <div class='form-row'>
                <div class='form-group col-md-4'>
                    <label for='val1'>Value 1:</label>
                    <input type='number' id='val1' name='val1' class='form-control' required value='<?php echo htmlspecialchars(\$val1); ?>'>
                </div>
                <div class='form-group col-md-4'>
                    <label for='val2'>Value 2:</label>
                    <input type='number' id='val2' name='val2' class='form-control' required value='<?php echo htmlspecialchars(\$val2); ?>'>
                </div>
                <div class='form-group col-md-4'>
                    <label for='val3'>Value 3:</label>
                    <input type='number' id='val3' name='val3' class='form-control' required value='<?php echo htmlspecialchars(\$val3); ?>'>
                </div>
            </div>
            <div class='form-group'>
                <label for='total'>Total:</label>
                <input type='number' id='total' name='total' class='form-control' required value='<?php echo htmlspecialchars(\$total); ?>'>
            </div>
            <div class='form-group'>
                <button type='submit' class='btn btn-primary btn-block'>Submit</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        &copy; Finecons | Sales Management System | 2024 
    </footer>

    <!-- Bootstrap JS and dependencies (optional, for certain components) -->
    <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script>
</body>
</html>
";

// Query Data Content

$employee_query_data_content = '<?php
require_once \'dbconfig.php\'; // Adjust path as per your setup

// Initialize variables for filters
$nameFilter = isset($_GET[\'name\']) ? $_GET[\'name\'] : \'\';
$categoryFilter = isset($_GET[\'category\']) ? $_GET[\'category\'] : \'\';

// Build SQL query based on filters
$sql = "SELECT * FROM $name . $employee_id WHERE 1";
$params = [];
if (!empty($nameFilter)) {
    $sql .= " AND name LIKE :name";
    $params[\'name\'] = \'%\' . $nameFilter . \'%\';
}
if (!empty($categoryFilter)) {
    $sql .= " AND category = :category";
    $params[\'category\'] = $categoryFilter;
}

// Prepare and execute SQL query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales | Finecons</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            padding: 20px;
        }
        .table-container {
            margin-top: 20px;
        }
        .print-button {
            margin-top: 20px;
            text-align: right;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 15px;
        }
        .logout-button {
            position: absolute;
            top: 15px;
            right: 15px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar d-flex justify-content-start navbar-expand-lg navbar-dark">
        <!-- <a class="navbar-brand" href="index.php">Sales Management</a> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Container -->
    <div class="container">
        <!-- Back button -->
        <a href="employee_dashboard.php" class="btn btn-secondary back-button">&laquo; Back</a>

        <h2 class="mt-4 s-10 mb-4"><a href="employee_dashboard.php">Sales Data | Finecons Sales</a></h2>

        <!-- Filter form -->
        <form class="form-inline mb-4" method="get" action="">
            <div class="form-group mr-3">
                <label for="name" class="mr-2">Filter by Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($nameFilter); ?>">
            </div>
            <div class="form-group mr-3">
                <label for="category" class="mr-2">Filter by Sales Category:</label>
                <select class="form-control" id="category" name="category">
                    <option value="">All Categories</option>
                    <option value="Sales1" <?php echo ($categoryFilter == \'Sales1\') ? \'selected\' : \'\'; ?>>Sales 1</option>
                    <option value="Sales2" <?php echo ($categoryFilter == \'Sales2\') ? \'selected\' : \'\'; ?>>Sales 2</option>
                    <option value="Sales3" <?php echo ($categoryFilter == \'Sales3\') ? \'selected\' : \'\'; ?>>Sales 3</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </form>

        <!-- Display table -->
        <div class="table-container">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Sales Category</th>
                        <th>Value 1</th>
                        <th>Value 2</th>
                        <th>Value 3</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row[\'id\']); ?></td>
                            <td><?php echo htmlspecialchars($row[\'name\']); ?></td>
                            <td><?php echo isset($row[\'category\']) ? htmlspecialchars($row[\'category\']) : \'\'; ?></td>
                            <td><?php echo htmlspecialchars($row[\'val1\']); ?></td>
                            <td><?php echo htmlspecialchars($row[\'val2\']); ?></td>
                            <td><?php echo htmlspecialchars($row[\'val3\']); ?></td>
                            <td><?php echo htmlspecialchars($row[\'total\']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Print button -->
        <div class="print-button">
            <button class="btn btn-success" onclick="window.print()">Print Table</button>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional, for certain components) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>';

    // Logout page

    $employee_logout_page_content = "<?php
    session_start();
    session_unset();
    session_destroy();
    header('Location: ../../login.php');
    exit();
    ?>";

    // Create the directory for the employee if it does not exist
    $employee_directory = 'Employees/' . $name . $employee_id;
    if (!is_dir($employee_directory)) {
        mkdir($employee_directory, 0777, true);
    }

    // Path to save the employee logout page
    $employee_logout_page_path = $employee_directory . '/logout.php';
    // Save the employee logout page content to a file
    file_put_contents($employee_logout_page_path, $employee_logout_page_content);

    // Save the employee page for insert form
    $employee_insert_form_page_path = $employee_directory . '/' .'insert_form.php';
    file_put_contents($employee_insert_form_page_path, $employee_insert_form_content);

    // // Save the employee sales page
    // $employee_sales_page_path = $employee_directory . '/' . $name . $employee_id . '_sales.php';
    // file_put_contents($employee_sales_page_path, $employee_page_content);

    // Save the employee dbconfig file
    $employee_dbconfig_path = $employee_directory . '/' . 'dbconfig.php';
    file_put_contents($employee_dbconfig_path, $employee_dbconfig_content);

    // Save the employee dashboard page
    $employee_dashboard_page_path = $employee_directory . '/' . $name . $employee_id . '_Dashboard.php';
    file_put_contents($employee_dashboard_page_path, $employee_dashboard_content);

    // Save the employee query data page
    $employee_query_data_page_path = $employee_directory . '/' . 'query_data.php';
    file_put_contents($employee_query_data_page_path, $employee_query_data_content);

    echo "<center>\n";
    echo "<br>\tEmployee added successfully. Employee page created at: <a href='$employee_dashboard_page_path'>$employee_dashboard_page_path</a>\n";
    echo "</center>\n";

    echo "<br>\n"; // Add a line break for separation

    echo "<center>\n";
    echo "<h4>Username: <span style='font-size: 24px;'>$name$employee_id</span></h3>\n";
    echo "</center>\n";

    echo "<center>\n";
    echo "<h4>Password: <span style='font-size: 24px;'>password</span></h3>\n";
    echo "</center>\n";


    } 
    catch (PDOException $e) {
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
                    <a class="nav-link" href="logout.php">Logout</a>                
                </li>
            </ul>
        </div>
    </nav>

<div class="container mb-5">
    <h1>Upload Employee Image</h1>

    <!-- Upload Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:*</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="date_of_birth">Date of Birth:*</label>
            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="company_handling">Company Handling:*</label>
            <input type="text" id="company_handling" name="company_handling" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*" class="form-control-file" required>
        </div>
        <div class="button-container">
            <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
            <a href="admin_dashboard.php" class="btn btn-secondary btn-block">Cancel</a>
        </div>
    </form>
</div>
<br><br><br>

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