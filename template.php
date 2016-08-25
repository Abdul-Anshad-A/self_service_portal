<html>
<body>

<form action="deployvm.php" method="POST">
Specify a name for the new VM:<input type="text" name="name"><br><br>
Name of the template:<input type="text" name="templatename"><br><br>


<?php
echo "List of Available Templates";
echo "<br><br>";
exec('python /var/www/html/scripts/misc/test-tmp.py --getalltemplates', $temp1);
$result1 = json_decode($temp1[0],true);
$i=0;
foreach ($result1 as $key1 => $value1) {
    if ( $value1 == "POWERED OFF"){
	echo "VM $i:";
	echo $key1;
	echo "<br>";
	$arg1[$i]=$key1;
	$i = $i + 1;
}
}
echo "<br><br>";
?>


Specify the resource pool:<input type="text" name="resourcename"><br><br>
Subscript of the VM should start from:<input type="number" name="number" min="1" max="5"><br><br>
Number of VM's to be created:<input type="number" name="quantity" min="1" max="5"><br><br>
Memory: <input type="number" name="memory" min="1"><br><br>
No of Vcpu: <input type="number" name="all_vcpu" min="1" max="8"><br><br>
No of Cores per Socket: <input type="number" name="all_cores_per_socket" min="1" max="8"><br><br>
<input type="submit" value="Submit">
</form>

</body>
</html> 
