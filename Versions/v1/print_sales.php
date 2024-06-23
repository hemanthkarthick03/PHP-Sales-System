<?php
require 'vendor/autoload.php'; // Include PHPExcel or PHPSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;

$excel_file = "uploads/your_excel_file.xlsx"; // Update with your actual file path

if (isset($_POST["salesman_name"])) {
    $salesman_name = $_POST["salesman_name"];

    $spreadsheet = IOFactory::load($excel_file);
    $sheet = $spreadsheet->getActiveSheet();

    // Filter and print data for the selected salesman
    echo "<h2>Sales Data for Salesman: $salesman_name</h2>";
    echo "<table border='1'>";
    foreach ($sheet->getRowIterator() as $row) {
        $cellValue = $sheet->getCell('A' . $row->getRowIndex())->getValue(); // Assuming salesman name is in column A
        if ($cellValue == $salesman_name) {
            echo "<tr>";
            echo "<td>" . $sheet->getCell('A' . $row->getRowIndex())->getValue() . "</td>";
            echo "<td>" . $sheet->getCell('B' . $row->getRowIndex())->getValue() . "</td>"; // Assuming sales amount is in column B
            echo "</tr>";
        }
    }
    echo "</table>";
}
?>
