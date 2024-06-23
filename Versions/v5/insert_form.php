
<?php
// Ensure dbconfig.php is included to establish database connection
require_once 'dbconfig.php';

// Initialize variables to hold form data
$name = $category = $val1 = $val2 = $val3 = $total = '';
$error = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $name = htmlspecialchars($_POST['name']);
    $category = htmlspecialchars($_POST['category']);
    $val1 = floatval($_POST['val1']);
    $val2 = floatval($_POST['val2']);
    $val3 = floatval($_POST['val3']);
    $total = floatval($_POST['total']);

    // Validate form data (add any additional validation as needed)
    if (empty($name) || empty($category) || empty($val1) || empty($val2) || empty($val3) || empty($total)) {
        $error = 'All fields are required.';
    } else {
        // Generate the table name dynamically based on employee's name and ID
        $stmt = $pdo->prepare('SELECT id FROM employees WHERE name = ?');
        $stmt->execute([$name]);
        $employee = $stmt->fetch();

        if (!$employee) {
            $error = 'Employee not found.';
        } else {
            $employee_id = $employee['id'];
            $table_name = preg_replace('/[^a-zA-Z0-9_]/', '_', $name . $employee_id);

            try {
                // Insert data into the dynamically created employee-specific table
                $insert_sql = 'INSERT INTO $table_name (name, category, val1, val2, val3, total) 
                               VALUES (?, ?, ?, ?, ?, ?)';
                $stmt = $pdo->prepare($insert_sql);
                $stmt->execute([$name, $category, $val1, $val2, $val3, $total]);

                // Display success message
                $success_message = 'Data inserted successfully!';
            } catch (PDOException $e) {
                $error = 'Error: ' . $e->getMessage();
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
        
        <?php if (!empty($error)): ?>
            <div class='alert alert-danger' role='alert'>
                <?php echo $error; ?>
            </div>
        <?php elseif (!empty($success_message)): ?>
            <div class='alert alert-success' role='alert'>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action='insert_data.php' method='post'>
            <div class='form-group'>
                <label for='name'>Name:</label>
                <input type='text' id='name' name='name' class='form-control' required value='<?php echo htmlspecialchars($name); ?>'>
            </div>
            <div class='form-group'>
                <label for='category'>Category:</label>
                <select id='category' name='category' class='form-control' required>
                    <option value='Sales1' <?php echo ($category == 'Sales1') ? 'selected' : ''; ?>>Sales 1</option>
                    <option value='Sales2' <?php echo ($category == 'Sales2') ? 'selected' : ''; ?>>Sales 2</option>
                    <option value='Sales3' <?php echo ($category == 'Sales3') ? 'selected' : ''; ?>>Sales 3</option>
                </select>
            </div>
            <div class='form-row'>
                <div class='form-group col-md-4'>
                    <label for='val1'>Value 1:</label>
                    <input type='number' id='val1' name='val1' class='form-control' required value='<?php echo htmlspecialchars($val1); ?>'>
                </div>
                <div class='form-group col-md-4'>
                    <label for='val2'>Value 2:</label>
                    <input type='number' id='val2' name='val2' class='form-control' required value='<?php echo htmlspecialchars($val2); ?>'>
                </div>
                <div class='form-group col-md-4'>
                    <label for='val3'>Value 3:</label>
                    <input type='number' id='val3' name='val3' class='form-control' required value='<?php echo htmlspecialchars($val3); ?>'>
                </div>
            </div>
            <div class='form-group'>
                <label for='total'>Total:</label>
                <input type='number' id='total' name='total' class='form-control' required value='<?php echo htmlspecialchars($total); ?>'>
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
