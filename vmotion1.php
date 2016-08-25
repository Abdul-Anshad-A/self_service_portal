      <form action="vmotion.php" method="post">
	<script>
	function showForm(id) {
    document.getElementById('submitForm').style.display = "block";
    if (id == 0) {
        document.getElementById('submitForm').style.display = "none";
    }
    for (var i = 1; i < 4; i++) {
        if (i == id) {
            document.getElementById(i).style.display = 'block';
        } else {
            document.getElementById(i).style.display = 'none';
        }
    }
}

	</script>



<?php
$temp1 = file_get_contents('/var/www/html/scripts/misc/powerstatus.json');
$result1 = json_decode($temp1,true);
$i=0;
echo "List of VM's";
echo "<br><br>";
foreach ($result1 as $key1 => $value1) {
        echo "VM $i : ";
        echo $key1;
        echo "<br>";
	$i = $i+1;
}
?>





        <p><b> Select the type of migration</b></p>
        <select id="dropdownMenu" name="dropdownMenu"
 onchange="javascript: showForm(document.getElementById('dropdownMenu').value);">
        <option value="0"> Choose an option </option>
        <option value="1">Vmotion</option>
        <option value="2">storagevmotion</option>
	<option value="3">xvmotion</option>
        </select>



        <div id="1" style="display: none;">
        <h2> You selected to perform vmotion </h2>
        <p> VM NAME: <input name="vmotion_vmname"
 size="10" type="text"><br>
	<br>
	<br>
        <br>Target Host name: <input name="vmotion_hostname" size="10" type="text">
	<br>
        </p>
        </div>


        <div id="2" style="display: none;">
        <h2> You selected to perform storage vmotion </h2>
        <p> VMNAME: <input name="svmotion_vmname"
 size="10" type="text"><br>
	<br>
	Target Datastore Name: <input name="svmotion_datastorename"
 size="10" type="text">
        <br>
        </p>
        </div>


	        <div id="3" style="display: none;">
        <h2> You selected to perform xvmotion</h2>
        <p> VMNAME: <input name="xvmotion_vmname"
 size="10" type="text"><br><br>
	Target Datastore Name: <input name="xvmotion_datastorename" size="10" type="text"><br><br>
	Target Host Name: <input name="xvmotion_hostname" size="10" type="text">
	 <br>
        </p>
        </div>


    <div id="submitForm" style="display: none;">
        <p> <br>
        <input value="Submit" type="submit"> </p>

