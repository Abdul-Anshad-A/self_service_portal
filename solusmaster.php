<html>
<head>
<title>SOLUS VM PORTAL</title>
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
<p style="text-align: center;">Solus VM Portal</p>
</h1></div>
<div class="logoContainer">
<?php
echo "<h3>Welcome to Solus VM Portal<h3>";
?>
</div>
<div class="menu">
<?php include 'solusmenu.php';?>
</div>
</body>
</html>
