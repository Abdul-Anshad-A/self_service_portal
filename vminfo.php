<?php

$a = $_POST["vmname"];
exec('python /var/www/html/scripts/misc/vm_status.py --vmname '.''.escapeshellarg($a).'', $temp);
$result = json_decode($temp[0],true);
echo "VM INFORMATION";
echo "<br><br>";

foreach($result as $key => $value)
	{
		if ( $key == "disks")
		{
			echo "<br>##############################################<br>";
			echo "<br>HARD DISK INFORMATION";
			echo "<br><br>";
			foreach($result[$key] as $key2 => $value2)
			{
				echo "$key2 : ";
				echo "<br>";
				foreach($result[$key][$key2] as $key3 => $value3)
				{
					echo "$key3 :: $value3";
					echo "<br>";
				}
				echo "<br>";
			}
			echo "##############################################<br><br>";
		}


                if ( $key == "net")
                {
			echo "<br>##############################################<br>";
                        echo "<br>NETWORK INFORMATION";
                        echo "<br>";
                        foreach($result[$key] as $key2 => $value2)
                        {
                                echo "<br>";
                                foreach($result[$key][$key2] as $key3 => $value3)
                                {
					if ( $key3 =="ip_addresses")
					{
					echo "IP ADDRESS<br>";
					foreach($result[$key][$key2][$key3] as $key4 => $value4)
					{
						echo "$value4";
						echo "<br>";
					}
					}
					else
					{
					echo "$key3 :: $value3";
					echo "<br>";
					}
                                }
                                echo "<br>";
                        }
			echo "##############################################<br><br>";
                }
		else
		{
			if( $key!="disks"){
			echo "$key :: $value";
			echo "<br>";
			}
		
		}



	}

?>

