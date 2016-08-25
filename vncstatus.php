<?php
exec('python /var/www/html/scripts/misc/vm-vnc.py --vncstatus all', $temp);
$result = json_decode($temp[0],true);
if ( $result == NULL )
{
echo "NO VM has VNC enabled with it";
}
else
{
echo "VNC INFORMATION";
echo "<br><br>";
foreach ($result as $key1) {
$count=0;
echo "VM[$count]";
echo "<br><br>";
foreach ($key1 as $key2)
{
$count = $count + 1;
if ( $count == 2 ){
   echo "VMNAME -  $result[$key1][$key2]";
   echo "<br>";
  }
elseif ( $count == 5 ){
	echo "PORT NUMBER -  $result[$key1][$key2]";
	echo "<br>";
	}
elseif ( $count == 4 ){
        echo "HOST NAME -  $result[$key1][$key2]";
echo "<br>";

        }

}
echo "<br><br>";
}
}
?>

