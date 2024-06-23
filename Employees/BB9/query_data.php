<?php
require_once 'dbconfig.php'; // Adjust path as per your setup

// Initialize variables for filters
$nameFilter = isset($_GET['name']) ? $_GET['name'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

// Build SQL query based on filters
$sql = "SELECT * FROM BB9 WHERE 1";
$params = [];
if (!empty($nameFilter)) {
    $sql .= " AND name LIKE :name";
    $params['name'] = '%' . $nameFilter . '%';
}
if (!empty($categoryFilter)) {
    $sql .= " AND category = :category";
    $params['category'] = $categoryFilter;
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
                    <option value="Sales1" <?php echo ($categoryFilter == 'Sales1') ? 'selected' : ''; ?>>Sales 1</option>
                    <option value="Sales2" <?php echo ($categoryFilter == 'Sales2') ? 'selected' : ''; ?>>Sales 2</option>
                    <option value="Sales3" <?php echo ($categoryFilter == 'Sales3') ? 'selected' : ''; ?>>Sales 3</option>
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
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo isset($row['category']) ? htmlspecialchars($row['category']) : ''; ?></td>
                            <td><?php echo htmlspecialchars($row['val1']); ?></td>
                            <td><?php echo htmlspecialchars($row['val2']); ?></td>
                            <td><?php echo htmlspecialchars($row['val3']); ?></td>
                            <td><?php echo htmlspecialchars($row['total']); ?></td>
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
</html>