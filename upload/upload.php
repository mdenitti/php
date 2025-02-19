<?php
if (isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];


    // check if upload folder exists and if not create it
    // if (!file_exists('uploads')) {
    //     mkdir('uploads', 0777, true);
    // }

    // Define upload directory
    $uploadDir = "uploads/";
    $uploadFile = $uploadDir . basename($file['name']);

     // Validate file
     $errors = [];
    
     // Check for upload errors
     if ($file['error'] !== UPLOAD_ERR_OK) {
         $errors[] = "Upload failed with error code: " . $file['error'];
     }
 
     // Check file size (e.g., limit to 5MB)
     if ($file['size'] > 5000000) {
         $errors[] = "File is too large.";
     }

     // Check file type (e.g., allow only images)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        $errors[] = "Invalid file type.";
    }



    // If no errors, move the file to the upload directory
    if (empty($errors)) {
       
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            echo "File uploaded successfully!";
        } else {
            echo "Failed to move the uploaded file.";
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>