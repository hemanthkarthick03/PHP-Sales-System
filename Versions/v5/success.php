<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Upload Successful</title>
    <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            padding-top: 60px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 40px;
        }
        .alert {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class='container mb-5'>
        <h2>Upload Successful</h2>
        <div class='alert alert-success' role='alert'>
            Employee details uploaded successfully!
        </div>
        <p class='text-center'>
            <a href='upload_employee.php' class='btn btn-primary'>Upload Another Employee</a>
        </p>
    </div>

    <!-- Bootstrap JS and dependencies (optional, for certain components) -->
    <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script>
</body>
</html>
