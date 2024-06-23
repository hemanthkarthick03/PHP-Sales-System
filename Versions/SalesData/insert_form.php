<?php
// Ensure dbconfig.php is included to establish database connection
require_once 'dbconfig.php';

// Initialize variables to hold form data and error message
$bdm_name = $customer_name = $new_existing = $pl = $specs = $oem = $qty = $price_without_gst = $estimated_order_value = $expected_closure_week = $month = $remarks = '';
$error = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $bdm_name = htmlspecialchars($_POST['bdm_name']);
    $customer_name = htmlspecialchars($_POST['customer_name']);
    $new_existing = htmlspecialchars($_POST['new_existing']);
    $pl = htmlspecialchars($_POST['pl']);
    $specs = htmlspecialchars($_POST['specs']);
    $oem = htmlspecialchars($_POST['oem']);
    $qty = intval($_POST['qty']);
    $price_without_gst = floatval($_POST['price_without_gst']);
    $estimated_order_value = floatval($_POST['estimated_order_value']);
    $expected_closure_week = intval($_POST['expected_closure_week']);
    $month = htmlspecialchars($_POST['month']);
    $remarks = htmlspecialchars($_POST['remarks']);

    // Validate form data
    if (empty($bdm_name) || empty($customer_name) || empty($new_existing) || empty($pl) || empty($specs) || empty($oem) || $qty <= 0 || $price_without_gst <= 0 || $estimated_order_value <= 0 || $expected_closure_week <= 0 || empty($month)) {
        $error = 'All fields are required and must be valid.';
    } else {
        try {
            // Insert data into the SalesData table
            $insert_sql = "INSERT INTO SalesData (BDM_Name, Customer_Name, New_Existing, PL, SPECS, OEM, Qty, Price_Without_GST, Estimated_Order_Value, Expected_Closure_Week, Month, Remarks) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($insert_sql);
            $stmt->execute([$bdm_name, $customer_name, $new_existing, $pl, $specs, $oem, $qty, $price_without_gst, $estimated_order_value, $expected_closure_week, $month, $remarks]);

            // Display success message
            $success_message = 'Data inserted successfully!';
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
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
                <label for='bdm_name'>BDM Name:</label>
                <input type='text' id='bdm_name' name='bdm_name' class='form-control' required value='<?php echo htmlspecialchars($bdm_name); ?>'>
            </div>
            <div class='form-group'>
                <label for='customer_name'>Customer Name:</label>
                <input type='text' id='customer_name' name='customer_name' class='form-control' required value='<?php echo htmlspecialchars($customer_name); ?>'>
            </div>
            <div class='form-group'>
                <label for='new_existing'>New / Existing:</label>
                <input type='text' id='new_existing' name='new_existing' class='form-control' required value='<?php echo htmlspecialchars($new_existing); ?>'>
            </div>
            <div class='form-group'>
                <label for='pl'>PL:</label>
                <input type='text' id='pl' name='pl' class='form-control' required value='<?php echo htmlspecialchars($pl); ?>'>
            </div>
            <div class='form-group'>
                <label for='specs'>SPECS:</label>
                <input type='text' id='specs' name='specs' class='form-control' required value='<?php echo htmlspecialchars($specs); ?>'>
            </div>
            <div class='form-group'>
                <label for='oem'>OEM:</label>
                <input type='text' id='oem' name='oem' class='form-control' required value='<?php echo htmlspecialchars($oem); ?>'>
            </div>
            <div class='form-group'>
                <label for='qty'>Quantity:</label>
                <input type='number' id='qty' name='qty' class='form-control' required value='<?php echo htmlspecialchars($qty); ?>'>
            </div>
            <div class='form-group'>
                <label for='price_without_gst'>Price (Without GST):</label>
                <input type='number' step='0.01' id='price_without_gst' name='price_without_gst' class='form-control' required value='<?php echo htmlspecialchars($price_without_gst); ?>'>
            </div>
            <div class='form-group'>
                <label for='estimated_order_value'>Estimated Order Value (INR Lacs):</label>
                <input type='number' step='0.01' id='estimated_order_value' name='estimated_order_value' class='form-control' required value='<?php echo htmlspecialchars($estimated_order_value); ?>'>
            </div>
            <div class='form-group'>
                <label for='expected_closure_week'>Expected Closure Week:</label>
                <input type='number' id='expected_closure_week' name='expected_closure_week' class='form-control' required value='<?php echo htmlspecialchars($expected_closure_week); ?>'>
            </div>
            <div class='form-group'>
                <label for='month'>Month:</label>
                <input type='text' id='month' name='month' class='form-control' required value='<?php echo htmlspecialchars($month); ?>'>
            </div>
            <div class='form-group'>
                <label for='remarks'>Remarks:</label>
                <textarea id='remarks' name='remarks' class='form-control'><?php echo htmlspecialchars($remarks); ?></textarea>
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
