<html>
    <form action="check.php" method="post">
        <input type="text" name="name" placeholder="Name">
        <input type="number" name="age" placeholder="Age">
        <button type="submit">Submit</button>
    </form>
</html>

<?php 
// date access methods
echo date('Y');
echo date('m');
echo date ('l');
echo date ('i');
echo date ('n');
echo date ('j');
?>