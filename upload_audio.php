<?php

// Start the session
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['audio']) && $_FILES['audio']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }



        $uploadFile = $uploadDir . basename($_FILES['audio']['name']);
        $newName = 'uploads/' . date("Ymd-his") . '.mp3';
        $_SESSION['audioName'] = $newName;
        if (move_uploaded_file($_FILES['audio']['tmp_name'], $newName)) {
            $value = 'File is valid, and was successfully uploaded.'; 
            echo "<div class=\"problem\">$value</div>"; 
        } else {
            echo 'Possible file upload attack!';
        }
    } else {
        echo 'No file uploaded or upload error!';
    }
} else {
    echo 'Invalid request method!';
}


?>
