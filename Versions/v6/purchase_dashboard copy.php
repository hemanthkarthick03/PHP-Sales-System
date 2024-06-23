<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Dashboard | Finecons Sales</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Dropzone CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">
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
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="purchase_dashboard.php">Sales Management</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Welcome to Purchase Dashboard</h1>

        <!-- Dropzone Form Section -->
        <form action="update_excel.php" class="dropzone" id="myDropzone">
            <div class="fallback">
                <input name="file" type="file" multiple />
            </div>
            <div class="dz-message" data-dz-message><span>Drop files here or click to upload.</span></div>
        </form>

        <!-- Sales Data Section -->
        <div class="sales-data mt-4">
            <h3>Sales Data Summary</h3>
            <div id="salesSummary"></div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date('Y'); ?> | Finecons Sales 
    </footer>

    <!-- Bootstrap JS and dependencies (optional, for certain components) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Dropzone JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <!-- Custom JavaScript for Dropzone -->
    <script>
        Dropzone.options.myDropzone = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 10, // MB
            acceptedFiles: ".xlsx, .xls",
            init: function() {
                this.on("success", function(file, response) {
                    document.getElementById('salesSummary').innerHTML = response;
                });
                this.on("error", function(file, errorMessage) {
                    alert("File upload error: " + errorMessage);
                });
            }
        };
    </script>
</body>
</html>
