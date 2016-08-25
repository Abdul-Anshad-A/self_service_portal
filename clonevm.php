<?php
$a = $_POST["name"];
$b = $_POST["templatename"];
$c = $_POST["resourcename"];
$d = $_POST["number"];
$e = $_POST["quantity"];
exec('/usr/bin/python /var/www/html/scripts/misc/pysphere-multi-clone.py'.' -b '.escapeshellarg($a).' -t  '.escapeshellarg($b).' -r '.escapeshellarg($c).' -c '.escapeshellarg($d).' -n '.escapeshellarg($e).'', $temp);
$result = json_decode($temp[0],true);
foreach ($result as $key => $value)
{
if ( $value == "NOT FOUND")
{
echo "$key is not a valid input";
}
elseif ( $value == 1)
{
echo "Clone is created";
}
else
{
echo "A clone with name $key already exists. Please change it and try again";
}
}
?>

