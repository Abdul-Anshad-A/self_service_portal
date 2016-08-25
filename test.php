<?php 


exec('python /var/www/html/scripts/misc/pysphere-multi-clone.py -s 172.16.1.239 -u Administrator -b DSL-CLONE-JSON-AGAIN -t DSL-4.4.10', $temp);
$result = json_decode($temp[0],true);
print_r($result);

?>
