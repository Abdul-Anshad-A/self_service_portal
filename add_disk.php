<html>
<body>

<form action="add_disk-main.php" method="POST">
Name of the VM for which the IP should be assigned:

<?php
$temp1 = file_get_contents('/var/www/html/scripts/misc/powerstatus.json');
$result1 = json_decode($temp1,true);
$count = 1;
foreach($result1 as $key1 => $value1) {
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
Select the DISK TYPE:
<select id="disk_type" name="disk_type">  
  <option value="thin">Thin</option>
  <option value="thick">Thick</option>
</select>
<br><br>
Select Provision Type:
<select id="disk_provision" name="disk_provision">
  <option value="lazy">Lazy Zeroed</option>
  <option value="eager">Eager zeroed</option>
</select>
<br><br>
Select Disk Mode:
<select id="disk_mode" name="disk_mode">
  <option value="persistent">Persistent</option>
  <option value="independent_persistent">Independent_persistent</option>
  <option value="independent_nonpersistent">Independent_nonpersistent</option>
</select>
<br><br>

Size of the disk in GB:<input type="number" name="size" min="1">
<br><br>

<input type="submit" value="Submit">
</form>

</body>
</html> 


