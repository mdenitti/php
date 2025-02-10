<?php
session_start();

if(isset($_POST['name']) && isset($_POST['age'])){
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['age'] = $_POST['age'];
    CheckAge();
}

function CheckAge(){
    $drinkType = "";
    $age = (int)$_SESSION['age'];
    if ($age < 16) {
        $drinkType = "Soda";
        echo "Only Soda.";
    } elseif ($age >= 16 && $age < 18) {
        $drinkType = "Beer";
        echo "Only beer.";
    } else {
        $drinkType = "Liquor & beer";
        echo "Beer and Liquor.";
    }
    $_SESSION['drinkType'] = $drinkType;
}

?>

<a href="print.php">print</a>