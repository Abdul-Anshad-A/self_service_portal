<style type="text/css">
table {
    border-collapse: collapse;
    width:800px;  
 }

table, th, td {
   border: 1px solid black;
}
</style>

<script type="text/javascript">
function do_ajax(sid)
{
	var form =  document.forms['theform'];
	var action = document.getElementById('operation_'+sid).value;
	if(action != '')
	{
		addToForm(form,sid,'serverid');
		addToForm(form,action,'action');	
	}
	form.submit();	
}

function submit_form()
{
	var form = document.forms['theform'];
	form.submit();
}

function addToForm(form,key,name)
{
	var input 	= document.createElement('input');
	input.type 	= 'hidden';
	input.name 	= name;
	input.value = key;
	form.appendChild(input);
}
</script>

<?php
require('solus.php');
$solus	= new Solus('https://192.168.1.20:5656/api/admin', 'Pgnakgahdinvatvhc', 'MimvkbaaabclGkma');
if($_POST['serverid'] != '' && $_POST['action'] != '')
{
	$action = strtolower($_POST['action']);
	$sid	= $_POST['serverid'];
	$msg	= '';
	if($action == 'boot')
	{
		$res = $solus->boot($sid);
		$res = json_decode($res);
		$msg = $res->statusmsg;		
	}
	else if($action == "reboot")
	{
		$res = $solus->reboot($sid);
		$res = json_decode($res);
		$msg = $res->statusmsg;
	}
	else if($action == 'shutdown')
	{
		$res = $solus->shutdown($sid);
		$res = json_decode($res);
		$msg = $res->statusmsg;
	}
	else if($action == 'suspend')
	{
		$res = $solus->suspend($sid);
		$res = json_decode($res);
		$msg = $res->statusmsg;
	}
	else if($action == 'unsuspend')
	{
		$res = $solus->unsuspend($sid);
		$res = json_decode($res);
		$msg = $res->statusmsg;
	}
}
$nodes = $solus ->listOpenvzNode();
$nodes = json_decode($nodes);
$nodes_option = "<option value=''>--Select Node---</option>";
if($nodes->status == "success")
{
	$nodes_list = explode(',',$nodes->nodes);
	foreach($nodes_list as $node)
	{
		$node_data = $solus->getNodeDataByName($node);
		$node_data = json_decode($node_data);
		if($node_data->status == "success"){
			if($_POST['node_id'] == $node_data->id)
				$nodes_option.= "<option value='$node_data->id' selected>$node</option>";
			else 
				$nodes_option.= "<option value='$node_data->id'>$node</option>";
		}
	}
}
if($nodes->status == "error"){
	$error = "Error:$nodes->statusmsg <br /> <br />";
}
echo "<form name='theform' method='post' action='power_operations.php'>";
echo "<label>Node</label> : <select name='node_id' onchange='submit_form();'>$nodes_option</select> <br /> <br />";

	
	$node_id 	= $_POST['node_id'];
	$vservers 	= $solus->listServers($node_id);
	$vservers	= json_decode($vservers);
	$tbody		= '';
	foreach($vservers->virtualservers as $server)
	{
		$serverid = $server->vserverid;
		$server_status = $solus->getServerStatus($serverid);
		$server_status = json_decode($server_status);
		if($server_status->status == "success")
		{ 
			$operations = "<option value=''>-- Select Operation ----</option>";
			$status = $server_status->statusmsg;
			if($status == "online"){
				$operations .= "<option value='reboot'>Reboot</option>";
				$operations .= "<option value='shutdown'>Shutdown</option>";
				$operations .= "<option value='suspend'>Suspend</option>";
			}
			else if($status == "offline")
			{
				$operations .= "<option value='boot'>Boot</option>";
				$operations .= "<option value='suspend'>Suspend</option>";
				
			}
			else if($status == "disabled")
			{
				$operations .= "<option value='unsuspend'>Unsuspend</option>";
			}
			$op_list = "<select id='operation_$serverid'>$operations</select>";
			$submit	 = "<button onclick='do_ajax($serverid);'>Go</button>";
			$tbody .= "<tr id=$serverid>
						<td>$server->hostname</td>
						<td>$server->ipaddress</td>
						<td>$server->template</td>
						<td>$server->hdd</td>
						<td>$server->memory</td>
						<td>$status</td>
						<td>$op_list &nbsp; $submit</td>
					</tr>";
		}
	}
	$table 		= "<table cellpadding='0' cellspacing='0'>
						<thead>
							<th>Host Name</th>
							<th>IP Address</th>
							<th>Template</th>
							<th>HDD</th>
							<th>Memory</th>
							<th>Status</th>
							<th>Operations</th>
							$tbody
						</thead>
					</table>";
	if($vservers->status == "error")
	{
		$error 	= "Error : $vservers->statusmsg <br /><br />";
		echo $error;
		echo $table;	
	}
	else 
	{
		echo $table;
	}
echo "</form>";
echo $msg;
?>
