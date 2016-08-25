<?php
$a = $_POST["name"];
$b = $_POST["templatename"];
$c = $_POST["resourcename"];
$d = $_POST["number"];
$e = $_POST["quantity"];
$memory = $_POST["memory"];
$vcpu = $_POST["all_vcpu"];
$cores = $_POST["all_cores_per_socket"];
exec('/usr/bin/python /var/www/html/scripts/misc/pysphere-multi-clone.py'.' -b '.escapeshellarg($a).' -t  '.escapeshellarg($b).' -r '.escapeshellarg($c).' -c '.escapeshellarg($d).' -n '.escapeshellarg($e).'', $temp);
$result = json_decode($temp[0],true);
foreach ($result as $key => $value)
{
if ( $value == "NOT FOUND")
{
echo "$key is not a valid input";
echo "<br><br>";
exit();
}
elseif ( $value == 1)
{
echo "VM $key is deployed";
echo "<br><br>";
}
elseif ( $value == "ALREADY EXISTS" )
	{
		echo "A VM named $key already exists!!";
		echo "<br><br>";
		exit();
	}
}

for ( $i=1; $i <= $e; $i++)
	{
		$vmname = $a."-".$d;
		exec('/usr/bin/python /var/www/html/scripts/misc/resize.py'.' '.escapeshellarg($vmname).' --memory '.escapeshellarg($memory).'  --cpu  '.escapeshellarg($vcpu).' '.escapeshellarg($cores).'', $returnval);
		$result2[$i] = json_decode($returnval[0],true);
		$d = $d + 1;
	}


foreach ($result2 as $value1) 
	{
		foreach ( $value1 as $key2 => $value2 )
			{
				echo "$key2 $value2";
				echo "<br><br>";
			}
	}




?>
