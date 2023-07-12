<?php

	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
	
	function getconn(){
		$servername = "localhost";
			$username = "root";
			$password = "***";
			$dbname = "myDB";
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		return $conn;
	}
	
	function verifypost($name, $headline){
		$conn = getconn();
		$sql = "SELECT id, username, headline, file, star, comment, person FROM posts";	
		$result = mysqli_query($conn, $sql);
		$info = array();
		if ($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$exname = $row["username"];
				$expost = $row["headline"];
				if($name == $exname && $headline == $expost){
					$info[0] = $row["comment"];
					$info[1] = $row["star"];
					$info[2] =$row["id"];
					$info[3] =$row["person"];
					break;
				}	
			}
		}
		$conn->close();
		return $info;
	}
	
	function commentprocess($combut, $combar, $comnum, $filename, $name, $id){
		$comment = "";
		if(empty($_GET[$combar])){	
		}
		else{
			$comment = $_GET[$combar];
			$comment = test_input($comment);
			if (!isset($_SESSION["lastcomment"])){
				include "filewrite.php";
				$_SESSION["lastcomment"] = $comment;
				$comnum++;
				commentwrite($filename, $name, $comment);
				$conn = getconn();
				$sql = "UPDATE posts SET comment = ".$comnum." WHERE id=".$id;
				$conn->query($sql);
				$conn->close();
			}
			else if($_SESSION["lastcomment"] != $comment){
				include "filewrite.php";
				$_SESSION["lastcomment"] = $comment;
				$comnum++;
				commentwrite($filename, $name, $comment);
				$conn = getconn();
				$sql = "UPDATE posts SET comment = ".$comnum." WHERE id=".$id;
				$conn->query($sql);
				$conn->close();
			}	
		}
		return $comnum;
	}
	
	function starcond($name, $headline){
		$conn = getconn();
		$sql = "SELECT id, username, headline, file, star FROM ".$_SESSION["user"];	
		$result = mysqli_query($conn, $sql);
		if ($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$exname = $row["username"];
				$expost = $row["headline"];
				if($name == $exname && $headline == $expost){
					$infostar = $row["star"];
					return $infostar;
				}
			}
		}
		
		return False;
	}
	
	function starprocess($name, $headline, $filename, $strnum, $id, $n, $person){
		$conn = getconn();
		$sql = $conn->prepare("INSERT INTO ".$_SESSION["user"]." (username, headline, file, star)VALUES ( ?, ?, ?, ?)");
		$sql->bind_param("ssss", $name, $headline, $filename, $n);
		$sql->execute();
		$person++;
		$strnum = $strnum + $n;
		$sql = "UPDATE posts SET star = ".$strnum." WHERE id=".$id;
		$conn->query($sql);
		$sql = "UPDATE posts SET person = ".$person." WHERE id=".$id;
		$conn->query($sql);
		$conn->close();
		return $strnum;
	}
?>
