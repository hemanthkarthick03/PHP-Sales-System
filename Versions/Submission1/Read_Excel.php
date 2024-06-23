<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// Function to read Excel file in chunks and display in a table
function readExcelInChunks($filePath) {
    $reader = new Xlsx();
    $spreadsheet = $reader->load($filePath);

    // Get the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Start creating the Bootstrap table
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-striped">';

    // Display table header (Excel sheet headers)
    echo '<thead class="thead-dark"><tr>';
    foreach ($sheet->getRowIterator() as $row) {
        foreach ($row->getCellIterator() as $cell) {
            echo '<th>' . htmlspecialchars($cell->getValue()) . '</th>';
        }
        break; // Only need headers from the first row
    }
    echo '</tr></thead>';

    // Display table body (Excel data rows)
    echo '<tbody>';
    $dataStartRow = 2; // Assuming data starts from row 2 (adjust as needed)
    $chunkSize = 10; // Number of rows to read at once
    $rowCount = $sheet->getHighestRow();

    for ($startRow = $dataStartRow; $startRow <= $rowCount; $startRow += $chunkSize) {
        echo '<!-- Start Chunk -->';
        // Read rows in chunk
        $chunk = [];
        for ($rowIndex = $startRow; $rowIndex < $startRow + $chunkSize; $rowIndex++) {
            if ($rowIndex > $rowCount) break;

            $rowData = $sheet->rangeToArray('A' . $rowIndex . ':H' . $rowIndex, NULL, TRUE, FALSE);
            $chunk[] = $rowData[0];
        }

        // Display rows in the chunk
        foreach ($chunk as $row) {
            echo '<tr>';
            foreach ($row as $cellData) {
                echo '<td>' . htmlspecialchars($cellData) . '</td>';
            }
            echo '</tr>';
        }
        echo '<!-- End Chunk -->';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // Close table-responsive
}

// Check if file is uploaded and process it
if ($_FILES && isset($_FILES['excel']['tmp_name'])) {
    $excelFile = $_FILES['excel']['tmp_name'];

    try {
        // Display the uploaded file in a Bootstrap table
        echo '<div class="container mt-5">';
        echo '<h3 class="mb-4">Excel Data (Chunked Reading)</h3>';
        readExcelInChunks($excelFile);

        // Get categories (headers) using PHPSpreadsheet
        $reader = new Xlsx();
        $spreadsheet = $reader->load($excelFile);
        $sheet = $spreadsheet->getActiveSheet();
        $headers = [];
        foreach ($sheet->getRowIterator(1, 1) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $headers[] = htmlspecialchars($cell->getValue());
            }
            break;
        }
        echo '<p class="mt-4"><strong>Categories:</strong> ' . implode(', ', $headers) . '</p>';
        echo '</div>'; // Close container
    } catch (Exception $e) {
        echo '<div class="container mt-5">';
        echo '<div class="alert alert-danger" role="alert">';
        echo 'Error: ' . $e->getMessage();
        echo '</div>';
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload and Display Excel</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Upload and Display Excel</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="excel">Choose Excel file:</label>
            <input type="file" class="form-control-file" name="excel" id="excel" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload and Display</button>
    </form>

    <?php if ($_FILES && isset($_FILES['excel']['tmp_name'])): ?>
        <hr>
        <h3 class="mt-5 mb-4">Excel Data (Full Reading)</h3>
        <?php displayExcelInTable($excelFile); ?>
    <?php endif; ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
