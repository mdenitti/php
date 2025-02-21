<?php
class File {
    // create a variable to store the connection instance;
    private $conn;

    // create a variable to store the table name;
    private $table = "users";

    // create a variable to store the allowed mime types;
    private $allowedMimeTypes = ['text/csv', 'application/vnd.ms-excel'];

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function exportUsers(): string {
        try {
            $query = "SELECT id, name, email, status, date FROM " . $this->table;
            $result = $this->conn->query($query);

            if (!$result) {
                throw new Exception("Failed to fetch users");
            }

            // open a temporary memory stream to store the CSV data
            // The php://temp wrapper is a read-write stream that allows 
            // temporary data to be stored in memory.
            $output = fopen('php://temp', 'w');
            if (!$output) {
                // throw new exception if the stream could not be opened
                // a new exception stops the execution of the script
                throw new Exception("Failed to create temporary file");
            }
            
            // Add CSV headers with escape parameter
            fputcsv($output, ['ID', 'Name', 'Email', 'Status', 'Date'], ',', '"', '\\');
            
            // Add data rows with escape parameter
            while ($row = $result->fetch_assoc()) {
                fputcsv($output, $row, ',', '"', '\\');
            }
            
            rewind($output);

            // Let me explain the rewind() function in this context:
            // When you write data to a stream (like our temporary memory stream php://temp), 
            // there's a pointer that keeps track of where you are in the stream. 
            // Think of it like a cursor in a text editor.

            // A simple analogy: It's like rewinding an old cassette tape to the beginning before playing it. 
            // If you don't rewind, you're still at the end of the tape and won't hear anything when you press play.
            
            $csv = stream_get_contents($output);
            fclose($output);
            
            if (!$csv) {
                throw new Exception("Failed to generate CSV content");
            }
            
            return $csv;
        } catch (Exception $e) {
            error_log("Export Error: " . $e->getMessage());
            return false;
        }
    }

    public function importUsers($file): array {
        $messages = ['success' => [], 'errors' => []];
        
        try {
            // Validate file
            if (!isset($file['type']) || !in_array($file['type'], $this->allowedMimeTypes)) {
                throw new Exception("Invalid file type. Please upload a CSV file.");
            }

            if ($file['size'] > 5000000) { // 5MB limit
                throw new Exception("File is too large. Maximum size is 5MB.");
            }

            if (!is_uploaded_file($file['tmp_name'])) {
                throw new Exception("Invalid file upload.");
            }

            if (($handle = fopen($file['tmp_name'], "r")) === FALSE) {
                throw new Exception("Failed to open uploaded file.");
            }

            // Read and validate headers of the csv file with escape parameter
            $headers = fgetcsv($handle, 0, ',', '"', '\\');
            if (!$this->validateHeaders($headers)) {
                throw new Exception("Invalid CSV format. Please use the template provided.");
            }
            
            $row = 1;
            while (($data = fgetcsv($handle, 0, ',', '"', '\\')) !== FALSE) {
                $row++;
                
                if (empty($data[1]) || empty($data[2])) {
                    $messages['errors'][] = "Row $row: Name and Email are required";
                }

                if (!filter_var($data[2], FILTER_VALIDATE_EMAIL)) {
                    $messages['errors'][] = "Row $row: Invalid email format";
                }

                // Map CSV data to columns
                $userData = [
                    'name' => $this->sanitizeInput($data[1]),
                    'email' => $this->sanitizeInput($data[2]),
                    'status' => isset($data[3]) ? (int)$data[3] : 1,
                    'password' => password_hash('default123', PASSWORD_BCRYPT)
                ];

                // Insert user
                $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (name, email, password, status, date) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("sssi", $userData['name'], $userData['email'], $userData['password'], $userData['status']);

                try {
                    if ($stmt->execute()) {
                        $messages['success'][] = "Row $row: User {$userData['email']} imported successfully";
                    }
                } catch (Exception $e) {
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $messages['errors'][] = "Row $row: Email {$userData['email']} already exists";
                    } else {
                        $messages['errors'][] = "Row $row: " . $e->getMessage();
                    }
                }
            }
            fclose($handle);
        } catch (Exception $e) {
            $messages['errors'][] = "Import Error: " . $e->getMessage();
        }
        
        return $messages;
    }

    private function validateHeaders($headers): bool {
        $required = ['ID', 'Name', 'Email', 'Status'];
        foreach ($required as $field) {
            if (!in_array($field, $headers)) {
                return false;
            }
        }
        return true;
    }

    private function sanitizeInput($input): string {
        return htmlspecialchars(strip_tags(trim($input)));
    }
}