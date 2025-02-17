<?php
require_once 'assets/common.php';

// destroy the session
session_destroy();
// redirect to login page but add a success message
header("Location: index.php?success=1");
exit();