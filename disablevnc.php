<html>
<body>

<form action="disablevnc-main.php" method="POST">
Name of the VM for which the VNC should be disabled:

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

</body>
</html> 


