<?php


exec('python /var/www/html/scripts/misc/pysphere-get-vm-ips.py --all', $temp);
$result = json_decode($temp[0],true);
foreach($result as $key => $value) {
echo "$key : ";
for($i = 0; $i < sizeof($value); $i++) {
echo "$value";
}
echo "<br>";
}

?>

