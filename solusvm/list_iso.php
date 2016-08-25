<html>
<body>
<form action="list_iso.php" method="post">
Enter the Node:<select name="node">
<option selected="selected">Choose one</option>
<option value="xen hvm">xen hvm</option>
<option value="kvm">kvm</option></select>
<input type="submit" value="Submit">
</form>
</body>
</html>

<?php
$arg =$_POST['node'];
require('solus.php');
$solus = new Solus('https://192.168.1.20:5656/api/admin', 'Pgnakgahdinvatvhc', 'MimvkbaaabclGkma');
$iso = $solus->listISO($arg);
$test2 =  json_decode($iso, true);
foreach ( $test2 as $key => $value)
{
echo "$key : ";
if ($value == "")
{
echo "NULL";
echo "<br>";
}
else
{
echo $value;
echo "<br>";
}
}
echo "<br>";
?>
