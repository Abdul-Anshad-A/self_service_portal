<?php
$a = $_POST["vmname"];
$b = $_POST["ipaddress"];
$c = $_POST["gateway"];
$d = $_POST["subnet"];
$e = $_POST["dns"];
$f = $_POST["username"];
$g = $_POST["password"];
$h = $_POST["os"];
exec('/usr/bin/python /var/www/html/scripts/misc/setip.py --setip '.''.escapeshellarg($a).' '.escapeshellarg($b).' '.escapeshellarg($c).' '.escapeshellarg($d).' '.escapeshellarg($e).' '.escapeshellarg($f).' '.escapeshellarg($g).' '.escapeshellarg($h).'', $temp);
$result = json_decode($temp[0],true);
if ($result['error'])
	{
		echo $result['error'];
	}
else{
foreach ($result as $key => $value)
{
echo "Assigned ip $b to $key";
}
}
?>


