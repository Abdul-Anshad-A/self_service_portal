<html>
<body>
<form action="list_nodes.php" method="post">
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
$nodename = $solus->listNodes($arg);
$node = $solus->listNodesID($arg);
$test2 =  json_decode($node, true);
$test = json_decode($nodename, true);
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
foreach ( $test as $key1 => $value1)
{
echo "$key1 : ";
if ($value1 == "")
{
echo "NULL";
echo "<br>";
}
else
{
echo $value1;
echo "<br>";
}
}
echo "<br>";

?>
