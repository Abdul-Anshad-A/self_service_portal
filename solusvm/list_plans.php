<html>
<body>
<form action="list_plans.php" method="post">
Enter the node type:<select name="node">
<option selected="selected">Choose one</option>
<option value="openvz">openvz</option>
<option value="xen">xen</option>
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
$node = $solus->listplans($arg);
$test2 =  json_decode($node, true);
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
