<?php
require_once 'models/User.php';
require_once 'utilities/File.php';
require_once 'assets/common.php';

if (!isset($_SESSION['token'])) {
    header("Location: index.php");
    exit();
}

$user = new User();
$fileHandler = new File($user->conn);

$csv = $fileHandler->exportUsers();

if ($csv) {
    // Set headers for download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="users-'.date('Y-m-d').'.csv"');
    
    echo $csv;
    exit();
} else {
    header("Location: dashboard.php?error=export_failed");
    exit();
}

// Once the headers are set to Content-Type: text/csv, any echo will output CSV data instead of displaying a regular webpage. The browser interprets the response as a downloadable file rather than HTML content.