<?php 

$operations = $_POST["dropdownMenu"];

if( $operations == 1)
{
$mem_vmname = $_POST["mem_vmname"];
$mem_size = $_POST["mem_size"];
exec('/usr/bin/python /var/www/html/scripts/misc/resize.py'.' '.escapeshellarg($mem_vmname).' --memory  '.escapeshellarg($mem_size).'', $temp);
$result = json_decode($temp[0],true);
foreach ($result as $key => $value) {
if ( $value == "VM successfully reconfigured")
{
echo "VM successfully reconfigured";
}
elseif ( $value == "Only 8 socket for a VM is supported")
{
echo "ERROR : VM Socket cannot be greater than 8";
}
elseif ( $value == "No of CPU divided by No of Cores per Socket should always be a Integer")
{
echo "No of CPU divided by No of Cores per Socket should always be a Integer";
}
elseif ( $key == "error")
{
echo "Error Configuring VM : $value";
}
}

}


if( $operations == 2)
{
$cpu_vmname = $_POST["cpu_vmname"];
$vcpu = $_POST["vcpu"];
$cores_per_socket = $_POST["cores_per_socket"];
exec('/usr/bin/python /var/www/html/scripts/misc/resize.py'.' '.escapeshellarg($cpu_vmname).' --cpu  '.escapeshellarg($vcpu).' '.escapeshellarg($cores_per_socket).'', $temp);
$result = json_decode($temp[0],true);


foreach ($result as $key => $value) {
if ( $value == "VM successfully reconfigured")
{
echo "VM successfully reconfigured";
}
elseif ( $value == "Only 8 socket for a VM is supported")
{
echo "ERROR : VM Socket cannot be greater than 8";
}
elseif ( $value == "No of CPU divided by No of Cores per Socket should always be a Integer")
{
echo "No of CPU divided by No of Cores per Socket should always be a Integer";
}
elseif ( $key == "error")
{
echo "Error Configuring VM : $value";
}

}


}



if( $operations == 3)
{
$disk_vmname = $_POST["disk_vmname"];
$disk_name = $_POST["disk_name"];
$disk_size = $_POST["disk_size"];
exec('/usr/bin/python /var/www/html/scripts/misc/resize.py'.' '.escapeshellarg($disk_vmname).' --disk  '.escapeshellarg($disk_size).' '.escapeshellarg($disk_name).'', $temp);
$result = json_decode($temp[0],true);


foreach ($result as $key => $value) {
if ( $value == "VM successfully reconfigured")
{
echo "VM successfully reconfigured";
}
elseif ( $value == "Only 8 socket for a VM is supported")
{
echo "ERROR : VM Socket cannot be greater than 8";
}
elseif ( $value == "No of CPU divided by No of Cores per Socket should always be a Integer")
{
echo "No of CPU divided by No of Cores per Socket should always be a Integer";
}
elseif ( $key == "error")
{
echo "Error Configuring VM : $value";
}

}


}



if( $operations == 4)
{

$all_size = $_POST["all_size"];
$all_vcpu = $_POST["all_vcpu"];
$all_cores_per_socket = $_POST["all_cores_per_socket"];
$all_vmname = $_POST["all_vmname"];
$all_disk_name =  $_POST["all_diskname"];
$all_disk_size = $_POST["all_disk_size"];

exec('/usr/bin/python /var/www/html/scripts/misc/resize.py'.' '.escapeshellarg($all_vmname).' --memory '.escapeshellarg($all_size).'  --cpu  '.escapeshellarg($all_vcpu).' '.escapeshellarg($all_cores_per_socket).' --disk  '.escapeshellarg($all_disk_size).' '.escapeshellarg($all_disk_name).'', $temp);
$result = json_decode($temp[0],true);


foreach ($result as $key => $value) {
if ( $value == "VM successfully reconfigured")
{
echo "VM successfully reconfigured";
}
elseif ( $value == "Only 8 socket for a VM is supported")
{
echo "ERROR : VM Socket cannot be greater than 8";
}
elseif ( $value == "No of CPU divided by No of Cores per Socket should always be a Integer")
{
echo "No of CPU divided by No of Cores per Socket should always be a Integer";
}
elseif ( $key == "error")
{
echo "Error Configuring VM : $value";
}

}



}


?>
