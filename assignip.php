<html>
<body>

<form action="assignipmain.php" method="POST">
Name of the VM for which the IP should be assigned:

<?php
$temp1 = file_get_contents('/var/www/html/scripts/misc/powerstatus.json');
$result1 = json_decode($temp1,true);
$count = 1;
foreach($result1 as $key1 => $value1) {
	if ( $value1 == "POWERED ON"){
		$vm[$count] = $key1;
		$count = $count +1;
	}	
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
Enter the IP Address:<input type="text" name="ipaddress"><br><br>
Enter the default gateway:<input type="text" name="gateway"><br><br>
Enter the Subnet mask:<input type="text" name="subnet"><br><br>
Enter the DNS server IP:<input type="text" name="dns"><br><br>
Enter the username of the machine:<input type="text" name="username"><br><br>
Enter the password of the machine:<input type="password" name="password"><br><br>
Select the Guest OS TYPE:
<select id="os" name="os">  
  <option value="linux">linux</option>
  <option value="windows">windows</option>
</select>
<br><br>
<input type="submit" value="Submit">
</form>

</body>
</html> 


