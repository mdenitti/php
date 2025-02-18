<?php
require_once 'vendor/autoload.php';
require_once 'models/User.php';
require_once 'assets/common.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Check if user is logged in
if (!isset($_SESSION['token'])) {
    header("Location: index.php");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$user = new User();
$user->id = $_GET['id'];
$userData = $user->getOne();


if (!$userData) {
    header("Location: dashboard.php");
    exit();
}

// Initialize DomPDF
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);

// Prepare HTML content
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Details</title>
    <style>
        body {
            font-family: Helvetica, sans-serif;
            margin: 40px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 20px;
        }
        h1 {
            color: #e74c3c;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }
        .user-info {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .info-row {
            margin: 15px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .label {
            color: #2c3e50;
            font-weight: bold;
            width: 100px;
            display: inline-block;
        }
        .value {
            color: #34495e;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-active {
            background-color: #2ecc71;
            color: white;
        }
        .status-inactive {
            background-color: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>User Details Report</h1>
        <div class="subtitle">Generated on ' . date('Y-m-d H:i:s') . '</div>
    </div>

    <div class="user-info">
        <div class="info-row">
            <span class="label">Name:</span>
            <span class="value">' . htmlspecialchars($userData['name']) . '</span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">' . htmlspecialchars($userData['email']) . '</span>
        </div>
        <div class="info-row">
            <span class="label">Status:</span>
            <span class="status ' . ($userData['status'] == 1 ? 'status-active' : 'status-inactive') . '">
                ' . ($userData['status'] == 1 ? 'Active' : 'Inactive') . '
            </span>
        </div>
        <div class="info-row">
            <span class="label">Join Date:</span>
            <span class="value">' . $userData['date'] . '</span>
        </div>
    </div>
</body>
</html>';

// Load HTML content
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output PDF with user's name in filename
$filename = "user-" . preg_replace('/[^a-z0-9]+/', '-', strtolower($userData['name'])) . ".pdf";
$dompdf->stream($filename, array("Attachment" => true));