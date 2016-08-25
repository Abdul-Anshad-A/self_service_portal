<?php
require('solus.php');
$solus = new Solus('https://192.168.1.20:5656/api/admin', 'Pgnakgahdinvatvhc', 'MimvkbaaabclGkma');
$clients = $solus->listClients();
$test =  json_decode($clients, true);
foreach ( $test['clients'] as $test2) {
foreach ( $test2 as $key => $value)
{
echo "$key : ";
echo $value;
echo "<br>";
}
echo "<br>";
}
?>
