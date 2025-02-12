<?php
session_start();
// method to check if SESSION['token'] exists otherwise redirect to login
function checkLogin () {
    if (!isset($_SESSION['token'])) {
        header("Location: index.php");
        exit();
    }
}

//database connection string
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// method to check if email exists in the database
function checkEmail ($email) {
    global $conn;
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

// Get all users
function getAllUsers() {
    global $conn;
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Get single user
function getUser($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT id, name, email, status, date FROM users WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

// Update user
function updateUser($id, $name, $email, $status) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $status = mysqli_real_escape_string($conn, $status);
    
    $sql = "UPDATE users SET name = '$name', email = '$email', status = '$status' WHERE id = '$id'";
    return mysqli_query($conn, $sql);
}

// Delete user
function deleteUser($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "DELETE FROM users WHERE id = '$id'";
    return mysqli_query($conn, $sql);
}