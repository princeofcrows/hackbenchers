<?php
	if (session_id() === "") {
		session_start();
	}
?>
<html>
	<head>
	<title>হ্যাকবেঞ্চার</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css"/>
	</head>
<?php
	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
	$headlineEmpty = $newsempty =  "";
	$headline = $news = "";
	$ditector = True;
	if($_SESSION["access"] == True){
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			if (isset($_POST["submit"])) {
				if (empty($_POST["headline"])) {
					$headlineEmpty =  "Headline bar empty";
					$ditector = False;
				}
				else{
					$headline = $_POST["headline"];
					$headline = test_input($headline);
				}
				if(empty($_POST["news"])) {
					$newsempty =  "News bar empty";
					$ditector = False;
				}
				else{
					$news = $_POST["news"];
					$news = test_input($news);
				}
				if($ditector){
					include "filewrite.php";
					$name = $_SESSION["user"];
					$foldername = time();
					newsfile($foldername, $name, $news, $headline);
					$_SESSION["imgupload"] = $name."/".$foldername."/image/";
					header('Location:image.php');
				}
			}
			else{
				die ("you get the hell out of here");
			}
		}
	}
?>
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
	<div class = "main"><div><?php include_once "redirect.php";?></div>
		<div class = "bar"><form method = "post" >
		<h1>হেডলাইন</h1><textarea name="headline" rows="4" cols="80" value = "<?php echo $headline;?>"></textarea><span><?php echo $headlineEmpty;?></span><br><br>
		<h1>খবর</h1><textarea name="news" rows="10" cols="80" value = "<?php echo $news;?>"></textarea><span><?php echo $newsempty;?></span><br><br>
		<input type="submit" name="submit" value="Submit"> 
	</form></div></div>
</body>
</html>