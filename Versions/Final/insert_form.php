<?php
require_once 'dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $val1 = $_POST['val1'];
    $val2 = $_POST['val2'];
    $val3 = $_POST['val3'];
    $total = $_POST['total'];
    $category = $_POST['category'];

    try {
        // Prepare INSERT statement
        $stmt = $pdo->prepare("INSERT INTO salestable (name, val1, val2, val3, total, category) 
                               VALUES (:name, :val1, :val2, :val3, :total, :category)");

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':val1', $val1);
        $stmt->bindParam(':val2', $val2);
        $stmt->bindParam(':val3', $val3);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':category', $category);

        $stmt->execute();

        echo "Data inserted successfully";
    } catch (PDOException $e) {
        die("Error inserting data: " . $e->getMessage());
    }
} else {
    echo "Invalid request method";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        form {
            width: 400px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group select {
            width: calc(100% - 12px);
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="submit"] {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center">Insert Data Form</h2><br><br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" class="form-control" required>
                <option value="Sales1">Sales 1</option>
                <option value="Sales2">Sales 2</option>
                <option value="Sales3">Sales 3</option>
            </select>
        </div>
        <div class="form-group">
            <label for="val1">Value 1:</label>
            <input type="number" id="val1" name="val1" required>
        </div>
        <div class="form-group">
            <label for="val2">Value 2:</label>
            <input type="number" id="val2" name="val2" required>
        </div>
        <div class="form-group">
            <label for="val3">Value 3:</label>
            <input type="number" id="val3" name="val3" required>
        </div>
        <div class="form-group">
            <label for="total">Total:</label>
            <input type="number" id="total" name="total" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Submit">
        </div>
    </form>
</body>
</html>
