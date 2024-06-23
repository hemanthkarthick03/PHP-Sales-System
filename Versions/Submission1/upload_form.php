<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Employee Image | Finecons Sales</title>
</head>
<body>
    <h2>Upload Employee Image | Finecons Sales </h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" required><br><br>
                <label for="company_handling">Company:</label>
        <input type="text" id="company_handling" name="company_handling" required><br><br>
        
        <label for="image">Select Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>
        
        <input type="submit" value="Upload">
    </form>
</body>
</html>
