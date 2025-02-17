<?php
require_once 'models/User.php';
require_once 'assets/common.php';

$user = new User();
$user->email = $_POST['email'] ?? '';
$user->password = $_POST['password'] ?? '';

if($result = $user->login()) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $_SESSION['user_id'] = $result['id'];
    header("Location: dashboard.php");
    exit();
} else {
    if($user->emailExists()) {
        header("Location: index.php?error=invalid_password");
    } else {
        header("Location: index.php?error=email_not_found");
    }
    exit();
}
