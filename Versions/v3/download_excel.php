<?php
require 'dbconfig.php';
require 'vendor/autoload.php'; // Path to PhpSpreadsheet autoload.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Prepare SELECT statement
    $stmt = $pdo->query("SELECT * FROM salestable");

    // Create a new PhpSpreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers for Excel columns
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Name');
    $sheet->setCellValue('C1', 'Val1');
    $sheet->setCellValue('D1', 'Val2');
    $sheet->setCellValue('E1', 'Val3');
    $sheet->setCellValue('F1', 'Total');
    $sheet->setCellValue('G1', 'Category');

    // Fetching data and populating the Excel sheet
    $row = 2;
    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sheet->setCellValue('A' . $row, $data['id']);
        $sheet->setCellValue('B' . $row, $data['name']);
        $sheet->setCellValue('C' . $row, $data['val1']);
        $sheet->setCellValue('D' . $row, $data['val2']);
        $sheet->setCellValue('E' . $row, $data['val3']);
        $sheet->setCellValue('F' . $row, $data['total']);
        $sheet->setCellValue('G' . $row, $data['category']);
        $row++;
    }

    // Set header for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="sales.xlsx"');
    header('Cache-Control: max-age=0');

    // Save Excel file to PHP output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    exit();

} catch (PDOException $e) {
    die("Error querying data: " . $e->getMessage());
}
?>
