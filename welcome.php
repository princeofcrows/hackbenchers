	<?php
		if (session_id() === "") {
			session_start();
		}
		if($_SESSION["access"] == True){
		}
		else{
			header('Location: ../../index.php');
			die ("you get the hell out of here");
			
		}
		
		$servername = "localhost";
			$username = "root";
			$password = "*****";
			$dbname = "myDB";

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT id, username, headline, file, star FROM posts";	
		$result = mysqli_query($conn, $sql);
		function setcurPost($n){
			$_SESSION["currentpost"] = $n;
		}
		$poststar = array();
		$posthead = array();
		$postfile = array();
		$poster = array();
		$_SESSION['posts'] = array();
		$i = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$post = $row["file"];
				$poster[$i] = $row["username"];
				array_push($_SESSION['posts'], $row["username"]."/".$post."/index.php");
				$poststar[$i] = $row["star"];
				$posthead[$i] = $row["headline"];
				$postfile[$i] = $row["file"];
				$i++;
			}
		}
		$i--;
		$n = $i;
	?>

<html>
	<head>
	<title>হ্যাকবেঞ্চার</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	</head>
	<body>
	<style>
			.fix{
			}
			.main{
				width:1080px;
				height:600px;
				margin:5px auto;
				display:block;
				background-color: wheat;
			}
			.main h1{
				color: darkblue;
			}
			.main h1 a{
				color: darkblue;
				text-decoration:none;
			}
			.main h1 a:hover{
				color: midnightblue;
				text-decoration:none;
				text-shadow:0px 1px 11px skyblue;
			}
			.main h2{
				color: teal;
			}
			.main h2 a{
				color: teal;
				text-decoration:none;
			}
			.main h2 a:hover{
				color: teal;
				text-decoration:none;
				text-shadow:0px 1px 11px skyblue;
			}
			.main h3{
				color: red;
			}
			.time{
				width:550px;
				height:640px;
				float:left;
				overflow-y: scroll;
				background-color: wheat;
			}
			.frontback{
				padding: 20px 20px 0px 20px;
			}
			.star{
				width:240px;
				height:600px;
				float:right;
				overflow-y: scroll;
				background-color: wheat;
				padding: 20px;
			}
			img{
				padding-bottom: 10px;
				width:250px;
			}
		</style>
		<div class = "fix main">
			<?php include_once "redirect.php";?>
			<?php include_once "timeline.php";?>
			<div class = "fix time" >
			<form method = "get" class = "frontback">
				<input type="submit" name="back" value="আগেরটা">
				<input type="submit" name="next" value="পরেরটা">
			</form>
				<?php
					
					if($_SESSION["currentpost"]  == 0){
						setcurPost($n);
						include $_SESSION['posts'][$_SESSION["currentpost"]];
					}
					else if(isset($_GET["back"]) && $_SESSION["currentpost"]<$n){
						$_SESSION["currentpost"]++;
						include $_SESSION['posts'][$_SESSION["currentpost"]];
					}
					else if(isset($_GET["next"]) && $_SESSION["currentpost"]>0){
						$_SESSION["currentpost"]--;
						include $_SESSION['posts'][$_SESSION["currentpost"]];
					}
					else{
						include $_SESSION['posts'][$_SESSION["currentpost"]];
					}
				?>
			</div>
			<div class = "fix star">
			<h3>Star line (সবচেয়ে বেশি স্টার কোথায় পড়ছে)</h3>
			<?php
				$count = 0;
				while($count <= $n){
					$i = 0;
					while($i < $n - $count){
						if($poststar[$i]>=$poststar[$i+1]){
							$temp = $poststar[$i+1];
							$tempost = $posthead[$i+1];
							$tempmember = $poster[$i+1];
							$tempfile = $postfile[$i+1];
							$posthead[$i+1] = $posthead[$i];
							$poststar[$i+1] = $poststar[$i];
							$poster[$i+1] = $poster[$i];
							$poststar[$i] = $temp;
							$posthead[$i] = $tempost;
							$poster[$i] = $tempmember;
							$postfile[$i] = $tempfile;
						}
						$i++;
					}
					$count++;
				}
				
				if($n<=5){
					$countPost = $n;
				}
				else{
					$countPost = 5;
				}
				$i = $n;
				while($countPost >=0 ){
					echo "<h1><a href = ".$_SESSION["url"]."/".$poster[$i].">".$poster[$i]."</a></h1><h2><a href = ".$_SESSION["url"]."/".$poster[$i]."/".$postfile[$i].">".$posthead[$i]."</a></h2>";
					echo "<p>".$poststar[$i]." stars</p>";
					$i--;
					$countPost--;
				}
			?>
			</div>
			<?php include "footer.php";?>
		</div>
	</body>
</html>
