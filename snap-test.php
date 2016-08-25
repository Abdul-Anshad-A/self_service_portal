<?php 

$operations = $_POST["dropdownMenu"];

if( $operations == 1)
{
$vmname = $_POST["create_vmname"];
$snapshotname = $_POST["create_snapshotname"];
$snapdesc = $_POST["create_snapshotdesc"];
exec('/usr/bin/python /var/www/html/scripts/misc/snapshot.py'.' -m '.escapeshellarg($vmname).' create  -sn '.escapeshellarg($snapshotname).' -sd '.escapeshellarg($snapdesc).'', $temp);
$result = json_decode($temp[0],true);
foreach ($result as $key => $value) {
if ( $value == "BACKGROUND")
{
echo "The snapshot for the VM $key has completed";
}
elseif ( $value == "NOT FOUND")
{
echo "The VM $key is not found in the inventory";
}
else
{
echo "Please enter valid details";
}
}

}


if( $operations == 2)
{
$vmname = $_POST["delete_vmname"];
$snapshotname = $_POST["delete_snapshotname"];
exec('/usr/bin/python /var/www/html/scripts/misc/snapshot.py'.' -m '.escapeshellarg($vmname).' delete  -sn '.escapeshellarg($snapshotname).'', $temp);
$result = json_decode($temp[0],true);

foreach ($result as $key => $value) {
if ( $value == "BACKGROUND")
{
echo "The current Snapshot has been deleted";
}
elseif ( $value == "NOT FOUND")
{
echo "The VM or Snapshot $key is not found in the inventory";
}
else
{
echo "Please enter valid details";
}
}


}



if( $operations == 3)
{
$vmname = $_POST["list_vmname"];
exec('/usr/bin/python /var/www/html/scripts/misc/snapshot.py'.' -m '.escapeshellarg($vmname).' list', $temp);
$result = json_decode($temp[0],true);
foreach ($result as $key => $value) {
if ( $value == "" )
{
echo "No snapshots exists for the VM $key";
}
else
{
echo "$key $value\n";
echo "<br>";
}
}

}



if( $operations == 4)
{
$vmname = $_POST["revert_vmname"];
$snapshotname = $_POST["revert_snapshotname"];
exec('/usr/bin/python /var/www/html/scripts/misc/snapshot.py'.' -m '.escapeshellarg($vmname).' revert  -sn '.escapeshellarg($snapshotname).'', $temp);
$result = json_decode($temp[0],true);


foreach ($result as $key => $value) {
if ( $value == "BACKGROUND")
{
echo "Reverting to snapshot $key is completed";
}
elseif ( $value == "NOT FOUND")
{
echo "The VM or Snapshot $key is not found in the inventory";
}
elseif ( $value == "ERROR" )
{
echo "ERROR executing task - Please enter valid details";
}
}


}


?>
