<?php
$message = "hello world";

function sayHello() {
    global $message; // pulling the global var $message into this function
    // $_GLOBALS['message'] is an alternative
    echo $message;
}

// without using the global keyword: Undefined variable $message error!
sayHello();

print_r(date('Y-m-d H:i:s'));

// print_r date object

echo "Today is " . date("Y/m/d") . "<br>";
echo "Today is " . date("Y.m.d") . "<br>";
echo "Today is " . date("Y-m-d") . "<br>";
echo "Today is " . date("d");

echo date('j'); // Output: 9   (if today is the 9th of the month)
echo date('d'); // Output: 09  (if today is the 9th of the month)