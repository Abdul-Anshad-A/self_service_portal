<?php


exec('python /var/www/html/scripts/misc/test-tmp.py --getvcenterinfo', $temp);
$result = json_decode($temp[0],true);

foreach($result as $key => $value) {
echo "$key : ";
for($i = 0; $i < sizeof($value); $i++) {
echo "$value[$i], ";
}
echo "<br>";
}

?>

