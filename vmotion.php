<?php 

$operations = $_POST['dropdownMenu'];


if( $operations == 1)
{
$vmname = $_POST["vmotion_vmname"];
$hostname = $_POST["vmotion_hostname"];
exec('/usr/bin/python /var/www/html/scripts/misc/vmotion_tools.py'.' -m '.escapeshellarg($vmname).' migrate -th '.escapeshellarg($hostname).'', $temp);
$result = json_decode($temp[0],true);
foreach ($result as $key => $value) {
if( $value == "NOT FOUND"){
        echo "$key NOT FOUND";
        }
elseif( $value == "SUCCESS"){
        echo "$vmname has been migrated to host $hostname";
        }
else{
	echo "ERROR MIGRATING VM";
    }
}
}



if( $operations == 2)
{
$vmname = $_POST["svmotion_vmname"];
$datastorename = $_POST["svmotion_datastorename"];
exec('/usr/bin/python /var/www/html/scripts/misc/vmotion_tools.py'.' -m '.escapeshellarg($vmname).' relocate -td '.escapeshellarg($datastorename).'', $temp);
$result = json_decode($temp[0],true);
foreach ($result as $key => $value) {
if( $value == "NOT FOUND"){
	echo "$key NOT FOUND";
	}
elseif( $value == "SUCCESS"){
	echo "$vmname has been migrated to datastore $key";
	}
else {
	echo "ERROR MIGRATING VM";
	}
}
}





if( $operations == 3)
{
$vmname = $_POST["xvmotion_vmname"];
$datastorename = $_POST["xvmotion_datastorename"];
$hostname = $_POST["xvmotion_hostname"];
exec('/usr/bin/python /var/www/html/scripts/misc/vmotion_tools.py'.' -m '.escapeshellarg($vmname).' relocate -td '.escapeshellarg($datastorename).' -th '.escapeshellarg($hostname).'', $temp);
$result = json_decode($temp[0],true);
foreach ($result as $key => $value) {
if ( $value == "NOT FOUND" )
{
echo "$key is not a valid entry";
}
else
{
echo "$key has been migrated\n";
echo "<br>";
}
}
}
?>
