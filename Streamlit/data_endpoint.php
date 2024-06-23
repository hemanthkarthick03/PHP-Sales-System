<?php
// Simulate fetching data from a database
$data = array(
    array('id' => 1, 'name' => 'John Doe', 'age' => 30),
    array('id' => 2, 'name' => 'Jane Smith', 'age' => 25),
    array('id' => 3, 'name' => 'Mike Johnson', 'age' => 35)
);

// Encode data as JSON and send response
header('Content-Type: application/json');
echo json_encode($data);
?>
