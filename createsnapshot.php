      <form action="snap-test.php" method="post">
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
$temp1 = file_get_contents('/var/www/html/scripts/misc/powerstatus.json');
$result1 = json_decode($temp1,true);
$i=0;
echo "List of VM's";
echo "<br><br>";
foreach ($result1 as $key1 => $value1) {
        echo "VM $i : ";
        echo $key1;
        echo "<br>";
	$i = $i + 1;
}
?>





        <p><b> Select the Option for snapshot</b></p>
        <select id="dropdownMenu" name="dropdownMenu"
 onchange="javascript: showForm(document.getElementById('dropdownMenu').value);">
        <option value="0"> Choose an option </option>
        <option value="1">Create</option>
        <option value="2">Delete</option>
        <option value="3">list</option>
        <option value="4">revert</option>
        </select>



        <div id="1" style="display: none;">
        <h2> You selected to create a snapshot </h2>
        <p> VM NAME: <input name="create_vmname"
 size="10" type="text"><br>
	<br>
	Snapshot name: <input name="create_snapshotname"
 size="10" type="text">
	<br>
        <br>Snapshot Description:
	<textarea name="create_snapshotdesc" rows="3" cols="40"></textarea>
	<br>
        </p>
        </div>


        <div id="2" style="display: none;">
        <h2> You selected to Delete a snapshot </h2>
        <p> VMNAME: <input name="delete_vmname"
 size="10" type="text"><br>
	<br>
	Snapshot Name: <input name="delete_snapshotname"
 size="10" type="text">
        <br>
        </p>
        </div>


	        <div id="3" style="display: none;">
        <h2> You selected to list snapshots </h2>
        <p> VMNAME: <input name="list_vmname"
 size="10" type="text"><br>
	 <br>
        </p>
        </div>



	                <div id="4" style="display: none;">
        <h2> You selected to revert to a snapshot</h2>
        <p> VMNAME: <input name="revert_vmname"
 size="10" type="text"><br>
	 <br>
	Snapshot name: <input name="revert_snapshotname"
 size="10" type="text"><br>
        </p>
        </div>




        <div id="submitForm" style="display: none;">
        <p> <br>
        <input value="Submit" type="submit"> </p>

