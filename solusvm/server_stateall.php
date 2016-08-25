<html>
<body>
<form action="server_stateall.php" method="post">
Enter the Server ID:<input type="number" name="name">
<br>
<input type="submit" value="Submit">
</form>
</body>
</html>

<?php
$arg = $_POST['name'];
if($arg)
{
require('solus.php');
$solus = new Solus('https://192.168.1.20:5656/api/admin', 'Pgnakgahdinvatvhc', 'MimvkbaaabclGkma');
$state = $solus->getServerState($arg);
$test2 =  json_decode($state, true);
foreach ( $test2 as $key => $value)
{
echo "$key : ";
if ($value == "")
{
echo "NULL";
}

echo $value;

echo "<br>";
}
}
?>
