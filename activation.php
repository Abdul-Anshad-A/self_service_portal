<?php
$a = $_POST["vmname"];
$b = $_POST["username"];
$c = $_POST["password"];
exec('/usr/bin/python /var/www/html/scripts/misc/winactivate.py --activate '.''.escapeshellarg($a).' '.escapeshellarg($b).' '.escapeshellarg($c).'', $temp);
$result = json_decode($temp[0],true);
if ($result['error'])
	{
		echo $result['error'];
	}
else{
foreach ($result as $key => $value)
{
echo "$key is Activated !!";
}
}
?>
