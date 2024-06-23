<?php
require_once 'dbconfig.php';

// Update data if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        // Loop through POST data to update each row
        foreach ($_POST['id'] as $key => $id) {
            $name = $_POST['name'][$key];
            $category = $_POST['category'][$key];
            $val1 = $_POST['val1'][$key];
            $val2 = $_POST['val2'][$key];
            $val3 = $_POST['val3'][$key];
            // Calculate total based on val1, val2, val3
            $total = $val1 + $val2 + $val3;
            
            try {
                // Prepare UPDATE statement
                $stmt = $pdo->prepare("UPDATE salestable SET name = :name, category = :category, val1 = :val1, val2 = :val2, val3 = :val3, total = :total WHERE id = :id");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':category', $category);
                $stmt->bindParam(':val1', $val1);
                $stmt->bindParam(':val2', $val2);
                $stmt->bindParam(':val3', $val3);
                $stmt->bindParam(':total', $total);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            } catch (PDOException $e) {
                die("Error: " . $e->getMessage());
            }
        }
        
        // Redirect to avoid resubmission on refresh
        header("Location: edit_table.php");
        exit();
    }
}

// Fetch all records from the table
try {
    $stmt = $pdo->query("SELECT * FROM salestable");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Table | Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 15px;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .form-control {
            width: 100%;
        }
        .btn-update {
            width: 20%;
            margin-bottom: 2px;
        }
        .btn-container {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mb-5">
        <!-- Back button -->
        <a href="admin_dashboard.php" class="btn btn-secondary back-button">&laquo; Back</a>
        
        <h1>Edit Sales Table</h1>
        <div class="btn-container">
            <button type="button" class="btn btn-primary btn-update" onclick="updateAll()">Update All Rows</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Value 1</th>
                        <th>Value 2</th>
                        <th>Value 3</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><input type="text" class="form-control" name="name[]" value="<?php echo htmlspecialchars($row['name']); ?>"></td>
                        <td><input type="text" class="form-control" name="category[]" value="<?php echo htmlspecialchars($row['category']); ?>"></td>
                        <td><input type="number" class="form-control val1" name="val1[]" value="<?php echo htmlspecialchars($row['val1']); ?>" oninput="calculateTotal(this)"></td>
                        <td><input type="number" class="form-control val2" name="val2[]" value="<?php echo htmlspecialchars($row['val2']); ?>" oninput="calculateTotal(this)"></td>
                        <td><input type="number" class="form-control val3" name="val3[]" value="<?php echo htmlspecialchars($row['val3']); ?>" oninput="calculateTotal(this)"></td>
                        <td><input type="text" class="form-control total" name="total[]" value="<?php echo htmlspecialchars($row['total']); ?>" readonly></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom script for AJAX update and total calculation -->
    <script>
        function calculateTotal(element) {
            let row = element.closest('tr');
            let val1 = parseFloat(row.querySelector('.val1').value) || 0;
            let val2 = parseFloat(row.querySelector('.val2').value) || 0;
            let val3 = parseFloat(row.querySelector('.val3').value) || 0;
            let total = val1 + val2 + val3;
            row.querySelector('.total').value = total.toFixed(2); // Adjust to your formatting needs
        }

        function updateAll() {
            let formData = new FormData();
            let rows = document.querySelectorAll('tbody tr');
            
            rows.forEach((row, index) => {
                formData.append('id[]', row.cells[0].innerText.trim());
                formData.append('name[]', row.querySelector('input[name="name[]"]').value.trim());
                formData.append('category[]', row.querySelector('input[name="category[]"]').value.trim());
                formData.append('val1[]', row.querySelector('input[name="val1[]"]').value.trim());
                formData.append('val2[]', row.querySelector('input[name="val2[]"]').value.trim());
                formData.append('val3[]', row.querySelector('input[name="val3[]"]').value.trim());
                formData.append('total[]', row.querySelector('input[name="total[]"]').value.trim());
            });
            
            formData.append('update', true);
            
            fetch('edit_table.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                alert('All rows updated successfully');
                window.location.reload(); // Reload the page to reflect changes
            })
            .catch(error => {
                console.error('There was an error updating the data:', error);
                alert('Error updating data. Please try again.');
            });
        }
    </script>
</body>
</html>
