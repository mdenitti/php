<?php
if (isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];

    // Basic validation
    if ($file['type'] !== 'text/csv') {
        echo "Invalid file type. Please upload a CSV file.";
        exit;
    } else {
        $content = file_get_contents($file['tmp_name']);
        $lines = explode("\n", $content);

        $users = [];
        // Skip the first line (header)
        foreach(array_slice($lines, 1) as $line) {
            $data = str_getcsv($line, ",", "\"", "\\");
            // Process the data; username,first_name,last_name,email,phone
            $users[] = [
                'username' => $data[0] ?? '',
                'first_name' => $data[1] ?? '',
                'last_name' => $data[2] ?? '',
                'email' => $data[3] ?? '',
                'phone' => $data[4] ?? ''
            ];
        }
        // output the users array into a table
        echo "<table border='0'>";
        echo "<tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th></tr>";
        foreach ($users as $user) {
            // if you want import scenario, you can insert the data into the database here  
            echo "<tr>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$user['first_name']}</td>";
            echo "<td>{$user['last_name']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['phone']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        /*
        $conn = new mysqli($servername, $username, $password, $dbname);

        foreach ($users as $user) {
            $stmt = $conn->prepare("INSERT INTO users (username, first_name, last_name, email, phone) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $user['username'], $user['first_name'], $user['last_name'], $user['email'], $user['phone']);
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();
        */
    }
} else {
    echo "No file uploaded.";
}