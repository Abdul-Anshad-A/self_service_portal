<?php

$vmname = $_POST["vmname"];
exec('python /var/www/html/scripts/misc/vm_status.py --vmname '.''.escapeshellarg($vmname).'', $temp);
$result = json_decode($temp[0],true);

foreach($result as $key => $value)
	{
		if ( $key == "disks")
		{
			$hdcount = 1;
			$hd = array();
			foreach($result[$key] as $key2 => $value2)
			{
				foreach($result[$key][$key2] as $key3 => $value3)
				{
					if ( $key3 == "size")
					{
					$hd[$hdcount] = $value3;
					$hdcount = $hdcount + 1;
					}
				}
			}
		}


                if ( $key == "memory_mb")
                {
			$memory = $result[$key];
		
		}
		if ( $key == "num_cpu")
		{
			$cpu = $result[$key];
		}

	}

$hddata = serialize($hd);
?>
<form action='resize-resources.php' method='POST' name='frm'>
<?php
echo "<input type='hidden' name='".htmlentities("vmname")."' value='".htmlentities($vmname)."'>";
echo "<input type='hidden' name='".htmlentities("memory")."' value='".htmlentities($memory)."'>";
echo "<input type='hidden' name='".htmlentities("cpu")."' value='".htmlentities($cpu)."'>";
echo "<input type='hidden' name='".htmlentities("harddisk")."' value='".htmlentities($hddata)."'>";
?>
</form>
<script language="JavaScript">
document.frm.submit();
</script>
