<?php
$a = $_POST["vmname"];
$b = $_POST["vncportnumber"];
$c = $_POST["password"];
exec('/usr/bin/python /var/www/html/scripts/misc/vm-vnc.py --enable '.''.escapeshellarg($a).' '.escapeshellarg($b).' '.escapeshellarg($c).'', $temp);
$result = json_decode($temp[0],true);
if ($result['ERROR'])
	{
		echo $result['ERROR'];
		echo "<br><br>";
		echo "Please click the below link to access $a VM console<br><br>";
		exec('/usr/bin/python /var/www/html/scripts/misc/vm-vnc.py --console '.''.escapeshellarg($a).'', $urldump);
		$result2 = json_decode($urldump[0],true);
		foreach ( $result2 as $key => $value)
                {
			if ( $key == "URL")
				{
					echo "<a href='".$result2[$key]."'>VM CONSOLE</a>";
				}
                }

	}
else
	{
	
	echo "VNC is ENBABLED<br><br>";
	echo "Please click the below link to access $a VM console<br><br>";
                exec('/usr/bin/python /var/www/html/scripts/misc/vm-vnc.py --console '.''.escapeshellarg($a).'', $urldump);
                $result2 = json_decode($urldump[0],true);
                foreach ( $result2 as $key => $value)
                {
                        if ( $key == "URL")
                                {
                                        echo "<a href='".$result2[$key]."'>VM CONSOLE</a>";
                                }
                }



	}	
?>


