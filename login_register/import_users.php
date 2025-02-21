<?php
require_once 'models/User.php';
require_once 'utilities/File.php';
require_once 'assets/common.php';

if (!isset($_SESSION['token'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_FILES['csv_file'])) {
    header("Location: dashboard.php?error=no_file");
    exit();
}

$user = new User();
$fileHandler = new File($user->conn);

$result = $fileHandler->importUsers($_FILES['csv_file']);

$_SESSION['import_results'] = $result;
header("Location: dashboard.php?import=done");
exit();