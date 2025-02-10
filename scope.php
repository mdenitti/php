<?php
$message = "hello world";

function sayHello() {
    global $message; // pulling the global var $message into this function
    // $_GLOBALS['message'] is an alternative
    echo $message;
}

// without using the global keyword: Undefined variable $message error!
sayHello();

