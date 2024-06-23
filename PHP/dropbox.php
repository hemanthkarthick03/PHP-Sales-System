<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropzone Example</title>
    <!-- Include Dropzone.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Upload Excel File with Dropzone</h2>
        <!-- Dropzone Form Section -->
        <form action="update_excel.php" class="dropzone" id="myDropzone">
            <div class="fallback">
                <input name="file" type="file" multiple />
            </div>
            <div class="dz-message" data-dz-message><span>Drop Excel files here or click to upload.</span></div>
        </form>
    </div>

    <!-- Include Dropzone.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

    <!-- Optional: Add additional JavaScript code to handle Dropzone events or configurations -->
    <script>
        // Customize Dropzone options if needed
        Dropzone.options.myDropzone = {
            paramName: "excel", // Name of the uploaded file parameter
            maxFilesize: 10, // MB
            acceptedFiles: ".xlsx, .xls", // Limit file types to Excel files
            dictDefaultMessage: "Drop Excel files here to upload",
            // Optional: Add more configurations or event handlers as needed
        };
    </script>
</body>
</html>
