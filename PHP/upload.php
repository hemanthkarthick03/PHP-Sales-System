<?php
require_once 'dbconfig.php'; // Adjust path as per your setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $date_of_birth = $_POST['date_of_birth'];
    $company_handling = $_POST['company_handling'];
    
    // File upload handling
    $image = $_FILES['image'];

    // Validate image file
    $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
    if (!in_array($image['type'], $allowed_types)) {
        die("Invalid file type. Only JPG, PNG, and GIF files are allowed.");
    }

    // Fixed dimensions for resizing
    $target_width = 641;
    $target_height = 641;

    // Create a temporary image resource from the uploaded file
    $source = imagecreatefromstring(file_get_contents($image['tmp_name']));

    // Get original dimensions
    $width = imagesx($source);
    $height = imagesy($source);

    // Calculate aspect ratios
    $source_aspect_ratio = $width / $height;
    $target_aspect_ratio = $target_width / $target_height;

    // Determine resize dimensions based on aspect ratios
    if ($source_aspect_ratio > $target_aspect_ratio) {
        // Source image is wider
        $new_width = $target_width;
        $new_height = (int) ($target_width / $source_aspect_ratio);
    } else {
        // Source image is taller or equal aspect ratio
        $new_height = $target_height;
        $new_width = (int) ($target_height * $source_aspect_ratio);
    }

    // Create a new resized image resource
    $resized = imagescale($source, $new_width, $new_height);

    // Create a blank canvas of the target dimensions
    $final_image = imagecreatetruecolor($target_width, $target_height);

    // Calculate positioning for centering the resized image on the canvas
    $x_offset = ($target_width - $new_width) / 2;
    $y_offset = ($target_height - $new_height) / 2;

    // Copy the resized image onto the final canvas
    imagecopy($final_image, $resized, $x_offset, $y_offset, 0, 0, $new_width, $new_height);

    // Save the final image to a temporary file
    ob_start();
    imagejpeg($final_image, null, 100);
    $image_data = ob_get_clean();

    // Free up memory
    imagedestroy($source);
    imagedestroy($resized);
    imagedestroy($final_image);

    try {
        // Prepare INSERT statement
        $stmt = $pdo->prepare("INSERT INTO employees (name, date_of_birth, company_handling, image_data) 
                               VALUES (:name, :date_of_birth, :company_handling, :image_data)");
        
        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':company_handling', $company_handling);
        $stmt->bindParam(':image_data', $image_data, PDO::PARAM_LOB); // Use PDO::PARAM_LOB for blob
        
        $stmt->execute();
        
        echo "Employee details and image uploaded successfully.";
    } catch (PDOException $e) {
        die("Error uploading image: " . $e->getMessage());
    }
}
?>
