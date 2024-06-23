<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet library autoload

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

// Function to handle PHP memory limit increase
function increase_memory_limit() {
    ini_set('memory_limit', '512M'); // Adjust as needed
}

// Check if file was uploaded and no error occurred
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $tmpFilePath = $_FILES['file']['tmp_name'];

    try {
        // Increase memory limit (optional, adjust as needed)
        increase_memory_limit();

        // Initialize PhpSpreadsheet reader
        $reader = IOFactory::createReaderForFile($tmpFilePath);

        // Set read filter for chunk reading
        $reader->setReadFilter(new class implements IReadFilter {
            public function readCell(string $column, int $row, string $worksheetName = ''): bool {
                return true; // Read all cells
            }
        });

        // Set chunk size (number of rows to process at a time)
        $chunkSize = 100; // Adjust as needed

        // Initialize variables for calculations
        $totalSales = 0;
        $totalMargin = 0;
        $totalNetProfit = 0;
        $target = 1000000; // Target of 10 lakhs

        // Load the first sheet only
        $spreadsheet = $reader->load($tmpFilePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Get the highest row number
        $highestRow = $sheet->getHighestRow();

        // Process rows in chunks
        for ($startRow = 2; $startRow <= $highestRow; $startRow += $chunkSize) {
            $endRow = min($startRow + $chunkSize - 1, $highestRow);

            // Iterate through rows in the current chunk
            for ($currentRow = $startRow; $currentRow <= $endRow; ++$currentRow) {
                // Get cell values (assuming column indexes based on your previous description)
                $totalAmount = $sheet->getCell('F' . $currentRow)->getValue();
                $receivedAmount = $sheet->getCell('G' . $currentRow)->getValue();

                // Calculate margin and net profit for each row
                $margin = $totalAmount - $receivedAmount;
                $netProfit = $margin - $target;

                // Aggregate totals
                $totalSales += $totalAmount;
                $totalMargin += $margin;
                $totalNetProfit += $netProfit;
            }
        }

        // Build response HTML
        $response = '<h3>Sales Data Summary</h3>';
        $response .= '<div class="table-responsive">';
        $response .= '<table class="table table-bordered">';
        $response .= '<thead>';
        $response .= '<tr><th>Total Sales</th><th>Total Margin</th><th>Total Net Profit</th></tr>';
        $response .= '</thead>';
        $response .= '<tbody>';
        $response .= '<tr>';
        $response .= '<td>' . number_format($totalSales, 2) . '</td>';
        $response .= '<td>' . number_format($totalMargin, 2) . '</td>';
        $response .= '<td>' . number_format($totalNetProfit, 2) . '</td>';
        $response .= '</tr>';
        $response .= '</tbody>';
        $response .= '</table>';
        $response .= '</div>';

        // Output response
        echo $response;
    } catch (Exception $e) {
        echo 'Error processing Excel file: ' . $e->getMessage();
    }
} else {
    echo 'File upload error.';
}
?>
