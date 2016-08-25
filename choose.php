<html>
<head>
<title>SELF SERVICE PORTAL</title>
<style type="text/css">
@variables {
  LogoBGColor: #337777;
}

div.logoContainer {
  background-color: var(LogoBGColor);
  color: blue;
}

div.logoContainer1 {
   background-color: var(LogoBGColor);
   color: red;
}

</style>
</head>

<body>
<div class="logoContainer1">
<h1 class="heading">
<p style="text-align: center;">Self Service Portal</p>
</h1></div>
<div class="logoContainer">
<?php
echo "<h3>Welcome to Self Service Portal<h3>";
?>
</div>
<div class="menu">
<?php include 'menu1.php';?>
</div>
</body>
</html>
