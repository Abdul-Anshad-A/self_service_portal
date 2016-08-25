<html>
<body>

<form action="clonevm.php" method="POST">
Specify a name for the VM after clone:<input type="text" name="name"><br><br>
Name of the VM from which the clone should be taken:<input type="text" name="templatename"><br><br>

<?php
$temp1 = file_get_contents('/var/www/html/scripts/misc/powerstatus.json');
$result1 = json_decode($temp1,true);
$i=0;
foreach ($result1 as $key1 => $value1) {
        echo "VM $i : ";
        echo $key1;
        echo "<br>";
        $i=$i+1;
}
echo "<br><br>";
?>

Specify the resource pool:<input type="text" name="resourcename"><br><br>
Subscript of the VM should start from:<input type="number" name="number" min="1" max="5"><br><br>
Number of clones to be created:<input type="number" name="quantity" min="1" max="5"><br><br>
<input type="submit" value="Submit">
</form>

</body>
</html> 


