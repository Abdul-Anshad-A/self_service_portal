<?php
require('solus.php');
$solus = new Solus('https://192.168.1.20:5656/api/admin', 'Pgnakgahdinvatvhc', 'MimvkbaaabclGkma');
$templates = $solus->listTemplates();
$test =  json_decode($templates, true);
foreach ( $test as $key => $value )
{
if(( $key == "templates" ) || ( $key == "templateshvm" ) || ( $key == "templateskvm" ))
{
echo "$key: ";
echo $value;
echo "<br>";
}
}
echo "<br>";
?>
