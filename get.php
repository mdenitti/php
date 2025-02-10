<?php

// $_GET['name'];
// $_GET['email'];
// $_GET['phone'];

$name = $_GET['name'];
$email = $_GET['email'];
$phone = $_GET['phone'] ?? '00000';
?>

<form action="update.php" method="get">
    <input type="text" name="name" placeholder="Name" value="<?php echo $name ?>">
    <input type="email" name="email" placeholder="Email" value="<?php echo $email ?>">
    <input type="text" name="phone" placeholder="Phone" value="<?php echo $phone ?>">
    <button type="submit">Submit</button>
</form>