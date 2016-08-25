<?php
$a = $_POST["vmname"];
exec('/usr/bin/python /var/www/html/scripts/misc/vm-vnc.py --disable '.''.escapeshellarg($a).'', $temp);
$result = json_decode($temp[0],true);
if ($result['ERROR'])
	{
		echo $result['ERROR'];
	}
else
	{
	echo "VNC is DISABLED";
	}
?>


