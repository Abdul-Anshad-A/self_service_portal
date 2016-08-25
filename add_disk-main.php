<?php
$a = $_POST["vmname"];
$b = $_POST["disk_type"];
if ( $b == "thin")
	{
		$c = "None";
	}
else
	{
	
		$c = $_POST["disk_provision"];
	}
$d = $_POST["disk_mode"];
$e = $_POST["size"];

exec('/usr/bin/python /var/www/html/scripts/misc/adddisk-test.py --adddisk '.''.escapeshellarg($a).' '.escapeshellarg($e).' '.escapeshellarg($b).' '.escapeshellarg($c).' '.escapeshellarg($d).'', $temp);
$result = json_decode($temp[0],true);
if ($result['ERROR'])
	{
		echo $result['error'];
	}
else{
foreach ($result as $key => $value)
{
echo "Added hard disk to $key";
}
}
?>
