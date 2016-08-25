      <form action="resize-main.php" method="post">
	<script>
	function showForm(id) {
    document.getElementById('submitForm').style.display = "block";
    if (id == 0) {
        document.getElementById('submitForm').style.display = "none";
    }
    for (var i = 1; i < 5; i++) {
        if (i == id) {
            document.getElementById(i).style.display = 'block';
        } else {
            document.getElementById(i).style.display = 'none';
        }
    }
}

	</script>



<?php
$vmname = $_POST["vmname"];
$memory = $_POST["memory"];
$cpu = $_POST["cpu"];
$harddisk = $_POST["harddisk"];
$harddisk = unserialize($harddisk);
echo "$vmname has $memory MB of memory and  $cpu VCPU";
echo "<br><br>";
echo "$vmname has the following hardisks";
echo "<br><br>";
$count = 1;
$harddisk_name = array();
foreach ($harddisk as $key)
{
$key = ($key/1024)/1024;
echo "Hard disk $count has $key GB total space<br>";
$harddisk_name[$count] = "Hard disk ".$count;
$count = $count + 1;
}
?>





        <p><b> Select the Option for VM resource resize</b></p>
        <select id="dropdownMenu" name="dropdownMenu"
 onchange="javascript: showForm(document.getElementById('dropdownMenu').value);">
        <option value="0"> Choose an option </option>
        <option value="1">MEMORY</option>
        <option value="2">CPU</option>
        <option value="3">DISK</option>
        <option value="4">MEMORY, CPU AND DISK</option>
        </select>



        <div id="1" style="display: none;">
        <h2> You selected to resize memory </h2>
        <p> VM NAME: <input name="mem_vmname"
 size="10" type="text" value=<?php echo "$vmname"; ?>><br>
	<br>
	Memory Size in MB: <input name="mem_size"
 size="10" type="text">
	<br>
        </p>
        </div>


        <div id="2" style="display: none;">
        <h2> You selected to resize CPU </h2>
        <p> VMNAME: <input name="cpu_vmname"
 size="10" type="text" value=<?php echo "$vmname"; ?>><br>
	<br>
	No of Vcpu: <input type="number" name="vcpu" min="1" max="8">
        <br>
        <br>
        No of Cores per Socket: <input type="number" name="cores_per_socket" min="1" max="8">
        <br>

        </p>
        </div>


	        <div id="3" style="display: none;">
        <h2> You selected to resize disk </h2>
        <p> VMNAME: <input name="disk_vmname"
 size="10" type="text" value=<?php echo "$vmname"; ?>><br><br>

Virtual Hardisk:
<select name="disk_name" id="disk_name">
  <option selected="selected">Choose one</option>
  <?php
    foreach($harddisk_name as $key) { ?>
      <option value="<?php echo $key ?>"><?php echo $key ?></option>
  <?php
    } ?>
</select>
<br><br>


Total size of the Virtual disk in GB : <input type="number" name="disk_size" min="1">
	 <br>
        </p>
        </div>



	                <div id="4" style="display: none;">
        <h2> You selected to resize MEMORY, CPU AND DISK</h2>
        <p> VMNAME: <input name="all_vmname"
 size="10" type="text" value=<?php echo "$vmname"; ?>><br>
	 <br>
	        Memory Size in MB: <input name="all_size"
 size="10" type="text">
        <br><br>
        No of Vcpu: <input type="number" name="all_vcpu" min="1" max="8">
        <br> <br>
        No of Cores per Socket: <input type="number" name="all_cores_per_socket" min="1" max="8">
        <br> <br>

Virtual Hardisk:
<select name="all_diskname" id="all_diskname">
  <option selected="selected">Choose one</option>
  <?php
    foreach($harddisk_name as $key) { ?>
      <option value="<?php echo $key ?>"><?php echo $key ?></option>
  <?php
    } ?>
</select>
<br><br>


Total size of the Virtual disk in GB : <input type="number" name="all_disk_size" min="1">
         <br>
        </p>
        </div>




        <div id="submitForm" style="display: none;">
        <p> <br>
        <input value="Submit" type="submit"> </p>

