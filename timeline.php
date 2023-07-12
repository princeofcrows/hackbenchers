<?php
	if (session_id() === "") {
		session_start();
	}
?>
<div class = "fix timeline">
	<h3>Timeline (সবার আগে যে পোস্ট)</h3>
	<style>
		.timeline{
			background-color: lightgrey;
			width: 210px;
			float: left;
			padding: 20px;
			height: 600px;
			overflow-y: scroll;
			word-wrap: break-word;
		}
	
	</style>
	<?php
		if($_SESSION["access"] == True){
		}
		else{
			header('Location: ../../index.php');
			die ("you get the hell out of here");
		}
		
		$servername = "localhost";
			$username = "root";
			$password = "****";
			$dbname = "myDB";

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT id, username, headline, file, star FROM posts";	
		$result = mysqli_query($conn, $sql);
		$postTime = array();
		$postfile = array();
		$poster = array();
		$iT = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$poster[$iT] = $row["username"];
				$postTime[$iT] = $row["headline"];
				$postfile[$iT] = $row["file"];
				$iT++;
			}
		}
		$nT = $iT-1;
		if($nT<= 10){
			$countPost = $nT;
		}
		else{
			$countPost = 10;
		}
		$iT = $nT;
		while($countPost >=0 ){
			echo "<h1><a href = ".$_SESSION["url"]."/".$poster[$iT].">".$poster[$iT]."</a></h1><h2><a href = ".$_SESSION["url"]."/".$poster[$iT]."/".$postfile[$iT].">".$postTime[$iT]."</a></h2>";

			$iT--;
			$countPost--;
		}
		$conn->close();
	?></div>