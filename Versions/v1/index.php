<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sales Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .button-container {
            margin-top: 20px;
        }
        .button-container a {
            display: block;
            width: 200px;
            padding: 10px;
            margin: 10px;
            text-align: center;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .button-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Sales Data</h1>
        <div class="button-container">
            <a href="insert_form.php">Insert Data</a>
            <a href="query_data.php">Export Data as Excel</a>
        </div>
    </div>
</body>
</html>
