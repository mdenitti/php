<?php 
include 'assets/header.php';
include 'common.php';
?>

<h1>Display our courses</h1>


<?php
// print_r($conn); // or var_dump -> personal preference
$query = "SELECT * FROM courses";

$results = mysqli_query($conn,$query);
print_r($results);

if (mysqli_num_rows($result) > 0) {
    foreach ($results as $result){
        echo $result['name'].' - '.$result['price'].' € <hr>';
    }
} else {
    echo "No results found in the table...";
}


?>

<?php include 'assets/footer.php'?>