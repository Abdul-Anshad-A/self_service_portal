<html>
<body>
<form action="server_terminate.php" method="post">
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
$state = $solus->Terminate($arg);
$test2 =  json_decode($state, true);
foreach ( $test2 as $key => $value)
{
echo "$key : ";
echo $value;
echo "<br>";
}
}
?>
