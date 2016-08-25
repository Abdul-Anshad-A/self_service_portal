<html>
<body>

<form action="vmconsole.php" method="POST">
Name of the VM for which the console should be launched:

<?php
$temp1 = file_get_contents('/var/www/html/scripts/misc/powerstatus.json');
$result1 = json_decode($temp1,true);
$count = 1;
foreach($result1 as $key1 => $value1) 
{
		$vm[$count] = $key1;
		$count = $count +1;

}
?>


<select name="vmname" id="vmname">
  <option selected="selected">Choose one</option>
  <?php
    foreach($vm as $key) { ?>
      <option value="<?php echo $key ?>"><?php echo $key ?></option>
  <?php
    } ?>
</select> 

<br><br>
<input type="submit" value="Submit">
</form>


<?php
$a = $_POST["vmname"];
if ( $a ) {
exec('/usr/bin/python /var/www/html/scripts/misc/vm-vnc.py --console '.''.escapeshellarg($a).'', $temp);
$result = json_decode($temp[0],true);
foreach ( $result as $key => $value)
                {
                        if ( $key == "URL")
                                {
                                        echo "Please click the below link to access $a VM console<br><br>";
                                        echo "<a href='".$result[$key]."'>VM CONSOLE</a>";
                                }
                        elseif ( $key == "ERROR" )
                                {
                                        echo "Enable VNC for this VM";
                                }
                }
}


?>



</body>
</html> 


