<?php
// mysqli_(host, username, password, database);
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'schooldb';

$conn = mysqli_connect($host,$user,$password,$database);
if (!$conn) {
    echo "Error connecting to database";
    exit;
}

// $conn2 // other database for example

function exampleFunction() {
    return "Hello, this is an example function.";
}

function showBeDate() {
    $months = ['januari','februari','maart','april','mei','juni','juli','augustus','september','oktober','november','december'];
    $days = ['zondag','maandag','dinsdag','woensdag','donderdag','vrijdag','zaterdag'];

    // access the values from the array using the index

    $day = date('j');
    $month = $months[date('m')-1];
    $dayofweek = $days[date('w')];
    $year = date('Y');

    return $dayofweek.' '.$day.' '.$month.' '.$year;
    
}