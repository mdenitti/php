<?php

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    saveToLogFile($name,$email,$phone);
}

// create a comma seperate value

function saveToLogFile ($name, $email, $phone) {
    //open or ceate file to write of append to it
    $file = fopen('log.txt','a');

    // provide a date object
    $currentdate = date('Y-m-d H:i:s');

    // string
    $string = $currentdate.','.$email.','.$name.','.$phone.'\r\n';
    
    // final write operation
    fwrite ($file,$string);
    fclose ($file);
}



// footer always use this in a footer (c) <?php echo date('Y')