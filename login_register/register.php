<?php
require_once 'models/User.php';
require_once 'assets/common.php';
require_once 'utilities/Mailer.php';

$user = new User();
$user->name = $_POST['name'] ?? '';
$user->email = $_POST['email'] ?? '';
$user->password = $_POST['password'] ?? '';

if($user->create()) {
    // Get the user data for the email
    $userData = [
        'name' => $user->name,
        'email' => $user->email,
        'date' => date('Y-m-d H:i:s')
    ];

    // Send welcome email
    $mailer = new Mailer();
    if ($mailer->sendWelcomeEmail($userData)) {
        header("Location: index.php?success=1&welcome_email=sent");
    } else {
        // User created but email failed
        header("Location: index.php?success=1&welcome_email=failed");
    }
    exit();
} else {
    if($user->emailExists()) {
        header("Location: index.php?error=email_exists");
    } else {
        header("Location: index.php?error=registration_failed");
    }
    exit();
}