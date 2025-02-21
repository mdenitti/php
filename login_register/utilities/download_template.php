<?php
// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="users-template.csv"');

// Create file pointer for output
$output = fopen('php://output', 'w');

// Add CSV headers with escape parameter
fputcsv($output, ['ID', 'Name', 'Email', 'Status (1=Active, 0=Inactive)', 'Date'], ',', '"', '\\');

// Add sample row with escape parameter
fputcsv($output, ['', 'John Doe', 'john.doe@example.com', '1', ''], ',', '"', '\\');

// Close the file pointer
fclose($output);


// 📝 How it works:
// 	1.	Headers:
// 	•	Content-Type: text/csv: Tells the browser the content is a CSV.
// 	•	Content-Disposition: attachment; filename="users-template.csv": Instructs the browser to download the file with the name users-template.csv.
// 	2.	fopen('php://output', 'w'):
// Opens a stream to the browser output instead of saving to a file on the server.
// 	3.	fputcsv(...):
// Writes CSV data directly to the output stream.
// 	4.	Browser prompts download:
// When the PHP script is accessed (via a link like <a href="export_users.php">Export</a>), the browser detects the headers and triggers the download.