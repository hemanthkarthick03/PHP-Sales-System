<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin's Dashboard | Finecons Sales</title>
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
        .sales-data {
            margin-top: 30px;
        }
        .profile-section {
            margin-top: 50px;
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

    <div class="container mb-5">
        <h1>Welcome to the Admin Dashboard</h1>

        <!-- Sales Data Section -->
        <div class="sales-data">
            <center>
                <h2>Sales Data</h2><br>
                <p>Display charts, tables, or summaries of sales data here.</p>
            </center>
            
            <div class="button-container">
                <!-- <a href="insert_form.php" class="btn btn-primary btn-lg btn-block mb-3">Insert Data</a> -->
                <a href="employee_list.php" class="btn btn-primary btn-lg btn-block">Employee List</a> 
                <a href="upload_employee.php" class="btn btn-primary btn-lg btn-block">Upload Employee Data</a>
                <a href="edit_table.php" class="btn btn-primary btn-lg btn-block">Edit Table Data</a> 
                <a href="download_excel.php" class="btn btn-primary btn-lg btn-block">Export Data as Excel</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; Sales Management System | Finecons Sales
    </footer>

    <!-- Bootstrap JS and dependencies (optional, for certain components) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
