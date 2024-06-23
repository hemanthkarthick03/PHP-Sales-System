<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// Function to read Excel file in chunks and display in a table
function readExcelInChunks($filePath) {
    $reader = new Xlsx();
    $spreadsheet = $reader->load($filePath);

    // Get the active sheet
    $sheet = $spreadsheet->getActiveSheet();
    echo '<h2></h2>';

    // Calculate total price and net margin
    $totalPrice = 0;
    $netMargin = 0;


    // Start creating the Bootstrap table
    echo '<div class="table-responsive" id="table-container">';
    echo '<table class="table table-bordered table-striped table-hover table-normal-spacing">';

    // Display table header (Excel sheet headers)
    echo '<thead class="thead-dark"><tr>';
    $headers = [];
    foreach ($sheet->getRowIterator() as $row) {
        foreach ($row->getCellIterator() as $cell) {
            $headers[] = htmlspecialchars($cell->getValue());
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
    $priceColumnIndex = array_search('Price(Without GST)', $headers);
    $orderValueColumnIndex = array_search('Estimated Order Value (INR Lacs)', $headers);

    for ($startRow = $dataStartRow; $startRow <= $rowCount; $startRow += $chunkSize) {
        echo '<!-- Start Chunk -->';
        // Read rows in chunk
        $chunk = [];
        for ($rowIndex = $startRow; $rowIndex < $startRow + $chunkSize; $rowIndex++) {
            if ($rowIndex > $rowCount) break;

            $rowData = $sheet->rangeToArray('A' . $rowIndex . ':O' . $rowIndex, NULL, TRUE, FALSE);
            $chunk[] = $rowData[0];
        }

        // Display rows in the chunk
        foreach ($chunk as $row) {
            echo '<tr>';
            foreach ($row as $cellIndex => $cellData) {
                echo '<td>' . htmlspecialchars($cellData) . '</td>';
                if ($cellIndex == $priceColumnIndex) {
                    $totalPrice += floatval($cellData);
                }
                if ($cellIndex == $orderValueColumnIndex) {
                    $netMargin += (floatval($cellData) - (isset($row[$priceColumnIndex]) ? floatval($row[$priceColumnIndex]) : 0));
                }
            }
            echo '</tr>';
        }
        echo '<!-- End Chunk -->';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // Close table-responsive

    // Display total price and net margin
    echo '<div class="totals mt-3 mb-5">';
    echo '<h4>Total Price (Without GST): ' . number_format($totalPrice, 2) . '</h4>';
    echo '<h4>Net Margin: ' . number_format($netMargin, 2) . '</h4>';
    echo '</div>';

    echo '<br> <br>';
}

// Check if file is uploaded and process it
if ($_FILES && isset($_FILES['excel']['tmp_name'])) {
    $excelFile = $_FILES['excel']['tmp_name'];

    try {
        // Display the uploaded file in a Bootstrap table
        echo '<div class="container pt-1">';
        // readExcelInChunks($excelFile);
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
    <title>Finecons Sales</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .table-normal-spacing {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        .container-padding {
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-group label {
            font-weight: bold;
        }
        .totals {
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
    </style>
    <script>
        function printTable() {
            var divToPrint = document.getElementById("table-container");
            var newWin = window.open("");
            newWin.document.write('<html><head><title>Print Table</title>');
            newWin.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
            newWin.document.write('</head><body>');
            newWin.document.write(divToPrint.outerHTML);
            newWin.document.write('</body></html>');
            newWin.print();
            newWin.close();
        }
    </script>
</head>
<body>
    <div class="header bg-primary py-4">
        <div class="container">
        <h2 class="text-white">Finecons Sales</h2>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <!-- <h2 class="text-white">Finecons Sales</h2> -->
                </div>
                <div class="col-md-6 text-md-right">
                    <button onclick="printTable()" class="btn btn-light">Print</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-1">
        <div class="row">
            <div class="col">
                <h2 class="mb-4">Upload and Display Excel</h2>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="excel">Choose Excel file:</label>
                        <input type="file" class="form-control-file" name="excel" id="excel" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload and Display</button>
                </form>
            </div>
        </div>

        <?php if ($_FILES && isset($_FILES['excel']['tmp_name'])): ?>
            <hr>
            <?php readExcelInChunks($excelFile); ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
