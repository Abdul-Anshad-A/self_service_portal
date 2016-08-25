<html>
<body>
<form action="list_servers.php" method="post">
Enter the Node Type ID:<input type="number" name="name">
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
$state = $solus->listServers($arg);
$test2 =  json_decode($state, true);
foreach ( $test2['virtualservers'] as $test) {
foreach ( $test as $key => $value)
{
echo "$key : ";
echo $value;
echo "<br>";
}
echo "<br>";
}
foreach ($test2 as $key => $value){
for($i = 0; $i < sizeof($value); $i++) {
echo "$key: ";
echo "$value";
}
echo "<br>";
}
}
?>
