<?php
// Only start session if one hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// method to check if SESSION['token'] exists otherwise redirect to login
function checkLogin() {
    if (!isset($_SESSION['token'])) {
        header("Location: index.php");
        exit();
    }
}