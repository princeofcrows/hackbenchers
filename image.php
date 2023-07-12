<!DOCTYPE html>
<html>
	<head>
	<title>হ্যাকবেঞ্চার</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css"/>
	</head>
	<body>
	<style>
		.main{
			width:1080px;
			height:600px;
			margin:5px auto;
			display:block;
			background-color: wheat;
		}
		.bar{
			padding-left: 20px;
			color: teal;
		}
		.bar h1{
			color: darkblue;
			font-size: 29px;
			
		}
		.bar span{
			margin: 48px;
			color: teal;
			font-size: 21px;
		}
	</style>
	<div class = "main"><div><?php include "redirect.php";?></div>
	<?php
		if (isset($_POST["No"])){
			$_SESSION["imgupload"] = "";
			header('Location:welcome.php');
		}
	?>
	<div class = "bar"><h1>পোস্টের সাথে ছবি</h1><form  action="" method="post" >
		<h2>If you dont want to upload image click this;</h2>
		<input type="submit" value="No" name="No">
	</form>
	<form  action="upload.php" method="post" enctype="multipart/form-data">
		<h2>Select image to upload:</h2>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Upload Image" name="Imgsubmit">
	</form></div></div>
	</body>
</html>
