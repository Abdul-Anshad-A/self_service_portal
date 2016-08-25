<?php 


$temp = file_get_contents ('/var/www/html/scripts/misc/powerstatus.json');
$result = json_decode($temp,true);
foreach ($result as $key => $value) {
    echo "<p>$key | $value</p>";
}

?>
