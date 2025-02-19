<?php
// Check if files were uploaded
if (isset($_FILES['files'])) {
    $files = $_FILES['files'];
    $uploadDir = "uploads/"; // Directory to store uploaded files
    $errors = []; // Array to store errors
    $success = []; // Array to store success messages

    // Create the upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Loop through each uploaded file
    for ($i = 0; $i < count($files['name']); $i++) {
        // Get file details for the current file
        $fileName = $files['name'][$i];
        $fileType = $files['type'][$i];
        $fileTmpName = $files['tmp_name'][$i];
        $fileError = $files['error'][$i];
        $fileSize = $files['size'][$i];

        // Define the destination path
        $uploadFile = $uploadDir . basename($fileName);

        // Validate the file
        if ($fileError === UPLOAD_ERR_OK) {
            // Check file size (e.g., limit to 5MB)
            if ($fileSize > 5000000) {
                $errors[] = "File '$fileName' is too large.";
                continue;
            }

            // Check file type (e.g., allow only images)
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "File '$fileName' has an invalid type.";
                continue;
            }

            // Move the file to the upload directory
            if (move_uploaded_file($fileTmpName, $uploadFile)) {
                $success[] = "File '$fileName' uploaded successfully!";
            } else {
                $errors[] = "Failed to move file '$fileName'.";
            }
        } else {
            // Handle upload errors
            switch ($fileError) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = "File '$fileName' exceeds the maximum file size.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors[] = "File '$fileName' was only partially uploaded.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errors[] = "No file was uploaded for index $i.";
                    break;
                default:
                    $errors[] = "File '$fileName' upload failed with error code: $fileError.";
                    break;
            }
        }
    }

    // Display success messages
    if (!empty($success)) {
        echo "<h3>Success:</h3>";
        foreach ($success as $message) {
            echo "$message<br>";
        }
    }

    // Display errors
    if (!empty($errors)) {
        echo "<h3>Errors:</h3>";
        foreach ($errors as $error) {
            echo "$error<br>";
        }
    }
} else {
    echo "No files were uploaded.";
}
?>