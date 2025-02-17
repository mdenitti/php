<?php
require_once 'models/User.php';
require_once 'assets/common.php';

$user = new User();
$user->name = $_POST['name'] ?? '';
$user->email = $_POST['email'] ?? '';
$user->password = $_POST['password'] ?? '';

if($user->create()) {
    header("Location: index.php?success=1");
    exit();
} else {
    if($user->emailExists()) {
        header("Location: index.php?error=email_exists");
    } else {
        header("Location: index.php?error=registration_failed");
    }
    exit();
}