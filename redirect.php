<?php
	if (session_id() === "") {
		session_start();
	}
	if(!isset($_COOKIE["user"])){
		die("Dew to security, and for a shitty server its essential for us to set cookie. If you are incognito please dont.");
	}
	else{
		if($_COOKIE["user"] != $_SESSION["user"]){
			die("Dew to security, and for a shitty server its essential for us to set cookie. If you are incognito please dont.");
			session_destroy();
		}
		else{
			
		}
	}
	if($_SESSION["access"] == True){
	}
	else{
		die ("you get the hell out of here");
	}
	if(isset($_GET["post"])){
		header('Location: ../../post.php');
	}
	if(isset($_GET["home"])){
		$_SESSION["currentpost"] = 0;
		header('Location: ../../welcome.php');
	}
	if(isset($_GET["logout"])){
		session_destroy();
		header('Location: ../../index.php');
	}
?>
		<style>
			.fix{
			}
			.head{
				height: 130px;
				background-color: midnightblue;
			}
			.head h1{
				color: white;
				margin-left:10px;
				padding:10px;
			}
			.head h1 a{
				color: white;
				text-decoration:none;
			}
			.head h1 a:hover{
				color: white;
				text-shadow:0px 2px 11px skyblue;
				
			}
			.headform{
				margin-left:10px;
				padding:10px;
			}
			.head h2{
				color: snow;
				margin-right:30px;
				float: right;
				font-size: 40px
			}
		</style>
		<div class = "fix head">
			<h2>HACKBENCHERS</h2>
			<h1><?php echo "<a href = ".$_SESSION["url"]."/".$_COOKIE["user"].">".$_COOKIE["user"]."</a>";?></h1>
			<form method = "get" class = "headform">
				<input type="submit" name="post" value="পোস্ট দিমু">
				<input type="submit" name="home" value="home">
				<input type="submit" name="logout" value="Log Out">
			</form>
			
		</div>
