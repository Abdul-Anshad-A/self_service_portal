<?php
$a = $_POST["vmname"];
$b = $_POST["password"];
exec('/usr/bin/python /var/www/html/scripts/misc/vm-vnc.py --changepwd '.''.escapeshellarg($a).' '.escapeshellarg($b).'', $temp);
$result = json_decode($temp[0],true);
foreach ( $result as $key => $value)
	{
		if ( $key == "ERROR" )
		{
			echo "$result[$key]";
		}
		else
		{
			echo "Password changed successfully";
		}
	}
?>


