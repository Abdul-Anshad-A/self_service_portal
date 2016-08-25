<html>
<head>
<title>VMWARE SELF SERVICE PORTAL</title>
<style type="text/css">
@variables {
  LogoBGColor: #e0e0e0;
}

div.logoContainer {
  background-color: var(LogoBGColor);
  color: red;
}

div.logoContainer1 {
   background-color: var(LogoBGColor);
   color: blue;
}

</style>
</head>

<body>
<div class="logoContainer1">
<h1 class="heading">
<p style="text-align: center;">VMWare Self Service Portal</p>
</h1></div>
<div class="logoContainer">
<?php
echo "<h3>Welcome to VMWare Self Service Portal<h3>";
?>
</div>
<div class="menu">
<?php include 'menu.php';?>
</div>
<?php include 'footer.php';?>
</body>
</html>
