<?php
require 'vendor/autoload.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// Path to the local image
$imagePath = 'logo.jpg';

// Read the image file and encode it to base64
$imageData = base64_encode(file_get_contents($imagePath));

// Format the base64 string for use in HTML
$base64Image = 'data:image/png;base64,' . $imageData;

// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml('
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <img src="' . $base64Image . '" alt="Embedded Image" style="width:200px;height:200px;">
    <p style="color:red; font-family: Helvetica;">I am loving</p> it
</body>
</html>

');

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();