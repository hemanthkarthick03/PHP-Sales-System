<?php
require_once 'dbconfig.php'; // Adjust path as per your setup

// Function to delete employee by ID
if (isset($_POST['delete_employee'])) {
    $employee_id = $_POST['employee_id'];
    
    try {
        // Prepare DELETE statement
        $stmt_delete = $pdo->prepare("DELETE FROM employees WHERE id = :employee_id");
        $stmt_delete->bindParam(':employee_id', $employee_id);
        $stmt_delete->execute();
        
        // Redirect to avoid resubmission on refresh
        header("Location: employee_list.php");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

try {
    // Prepare SELECT statement to fetch all employees
    $sql = "SELECT * FROM employees";
    
    // Initialize where clause array and parameter array
    $whereClause = [];
    $params = [];
    
    // Handle filtering by employee name if selected
    if (isset($_GET['employee_name']) && !empty($_GET['employee_name'])) {
        $employee_name = $_GET['employee_name'];
        $whereClause[] = "name = :employee_name";
        $params[':employee_name'] = $employee_name;
    }

    // Build final query with where clause if necessary
    if (!empty($whereClause)) {
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }

    // Prepare and execute statement with parameters
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Fetch distinct employee names for filter dropdown
    $sql_employee_names = "SELECT DISTINCT name FROM employees";
    $stmt_employee_names = $pdo->query($sql_employee_names);
    $employee_names = $stmt_employee_names->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List | Finecons Sales</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 60px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
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
        .card {
            margin-bottom: 20px;
            position: relative; /* Ensure relative positioning for absolute delete button */
        }
        .card-body {
            text-align: center;
        }
        .card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .delete-btn-container {
            text-align: center;
            padding-top: 10px; /* Add padding to separate from card content */
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .delete-btn:hover {
            background-color: #c82333;
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
        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-row {
            align-items: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <!-- Back button -->
        <a href="admin_dashboard.php" class="btn btn-secondary back-button">&laquo; Back</a>
        
        <!-- <a class="navbar-brand" href="admin_dashboard.php">Sales Management</a> -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mb-5">
        <h1>Employee List</h1>

        <!-- Filter form -->
        <form action="employee_list.php" method="get">
            <div class="form-row">
                <div class="col-md-9">
                    <div class="form-group">
                        <select class="form-control" name="employee_name">
                            <option value="">All Employees</option>
                            <?php foreach ($employee_names as $name): ?>
                                <option value="<?php echo htmlspecialchars($name); ?>" <?php echo ($_GET['employee_name'] == $name) ? 'selected' : ''; ?>><?php echo htmlspecialchars($name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col mb-4">
                    <div class="card">
                        <?php if (!empty($row['image_data'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_data']); ?>" class="card-img-top" alt="Employee Image">
                        <?php else: ?>
                            <img src="placeholder.jpg" class="card-img-top" alt="Placeholder Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text">Date of Birth: <?php echo htmlspecialchars($row['date_of_birth']); ?></p>
                            <p class="card-text">Company: <?php echo htmlspecialchars($row['company_handling']); ?></p>
                        </div>
                        <!-- Delete button -->
                        <div class="delete-btn-container">
                            <form method="post" class="delete-form">
                                <input type="hidden" name="employee_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="delete-btn" name="delete_employee" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
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

<?php
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
