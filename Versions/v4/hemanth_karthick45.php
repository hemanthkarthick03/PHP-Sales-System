<?php
require_once 'dbconfig.php';

// Assuming $name and $employee_id are already defined
$table_name = 'Hemanth_Karthick45';

try {
    // Prepare SELECT statement to fetch sales details
    $stmt = $pdo->prepare("SELECT * FROM $table_name");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching sales data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?php echo htmlspecialchars($name); ?>'s Sales</title>
    <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
    <div class='container'>
        <h1><?php echo htmlspecialchars($name); ?>'s Sales</h1>
        <table class='table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Val1</th>
                    <th>Val2</th>
                    <th>Val3</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['val1']); ?></td>
                        <td><?php echo htmlspecialchars($row['val2']); ?></td>
                        <td><?php echo htmlspecialchars($row['val3']); ?></td>
                        <td><?php echo htmlspecialchars($row['total']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
