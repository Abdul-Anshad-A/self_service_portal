<?php
$a = $_POST["vmname"];
exec('/usr/bin/python /var/www/html/scripts/misc/vm-vnc.py --console '.''.escapeshellarg($a).'', $temp);
$result = json_decode($temp[0],true);
foreach ( $result as $key => $value)
                {
			if ( $key == "URL")
				{
					echo "Please click the below link to access $a VM console<br><br>";
					echo "<a href='".$result[$key]."'>VM CONSOLE</a>";
				}
			elseif ( $key == "ERROR" )
				{
					echo "Enable VNC for this VM";
				}
                }


?>
