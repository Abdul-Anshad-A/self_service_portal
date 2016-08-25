<html>
<body>

<form action="suspend.php" method="post">
Name of the VM to be powered on:<input type="text" name="name"><br>
<input type="submit" value="Submit">
</form>

<?php
$temp1 = file_get_contents('/var/www/html/scripts/misc/powerstatus.json');
$result1 = json_decode($temp1,true);
$i=0;
echo "List of powered off VM's";
echo "<br><br>";
foreach ($result1 as $key1 => $value1) {
    if ( $value1 == "POWERED OFF"){
        echo "VM $i : ";
        echo $key1;
        echo "<br>";
	$i=$i+1;
}
}
?>


<?php
$arg =$_POST['name'];
if($arg){
exec('python /var/www/html/scripts/misc/test-tmp.py --suspend'.' '.EscapeShellArg("$arg").'', $temp);
$result = json_decode($temp[0],true);
echo "<br>";
foreach ($result as $key => $value) {
if ( $value == -1)
{
echo "$key is already powered on";
}
elseif ( $value == 1)
{
echo "$key has been powered on";
echo "<br>";
}
else
{
echo "VM not found in the inventory";
}
}
}
?>
</body>
</html>


