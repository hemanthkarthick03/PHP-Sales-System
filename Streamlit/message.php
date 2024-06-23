<?php
// Simple PHP script to serve a message
$message = "Hello from PHP!";
$response = array('message' => $message);

header('Content-Type: application/json');
echo json_encode($response);
?>
