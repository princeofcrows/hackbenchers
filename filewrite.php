<?php
	function newsfile($folder, $name, $news, $headline){
			$servername = "localhost";
			$username = "root";
			$password = "***";
			$dbname = "myDB";
		settype($folder, "string");

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT id, username, headline FROM posts";	
		$result = mysqli_query($conn, $sql);
		
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$exname = $row["username"];
				$expost = $row["headline"];
				if($name == $exname && $headline == $expost){
					die("post exists");
				}
			}
		}
		$i = 0;
		$j = 1;
		$sql = $conn->prepare("INSERT INTO posts(username, headline, file, star, comment, person) VALUES ( ?, ?, ?, ?, ?, ?)");
		$sql->bind_param("ssssss", $name, $headline, $folder, $i, $i, $i);
		$sql->execute();
					
		$conn->close();
		if(mkdir($name."/".$folder, 0777)){
			$file = fopen($name."/".$folder."/index.php", "w");
		fwrite($file, 
		'<?php
			if (session_id() === "") {
				session_start();
			}
		?>
			<html>
			<head>
				<title>হ্যাকবেঞ্চার</title>
				<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
			</head>
				<?php
					if($_SESSION["access"] == False){
						header("Location: ../../index.php");
						die("you get the hell out of here");
					}
					$headline = "'.$headline.'";
					$name = "'.$name.'";
					$filename = "'.$folder.'";
					
					include_once ($_SERVER["DOCUMENT_ROOT"]."/postterms.php");
					$info =array();
					
					$info = verifypost($name, $headline);
					$comnum = $info[0];
					$strnum = $info[1];
					$id =$info[2];
					$person = $info[3];
					
					$indistar = starcond($name, $headline);
					
					if($indistar){
						$starBut = "Unstar";
					}
					else {
						$starBut = "Star";
					}
					
					if ($_SERVER["REQUEST_METHOD"] == "GET") {
						if(isset($_GET["comsub'.str_replace(".php", "", $folder).'"])){
							$comnum = commentprocess("comsub'.str_replace(".php", "", $folder).'", "comment'.str_replace(".php", "", $folder).'", $comnum, $filename, $name, $id, $person);
						}
						else if(isset($_GET["onestar'.str_replace(".php", "", $folder).'"])&& $starBut == "Star"){
							$strnum = starprocess($name, $headline, $filename, $strnum, $id, 1, $person);
							$starBut = "Unstar";
							$person ++;
						}
						else if(isset($_GET["twostar'.str_replace(".php", "", $folder).'"])&& $starBut == "Star"){
							$strnum = starprocess($name, $headline, $filename, $strnum, $id, 2, $person);
							$starBut = "Unstar";
							$person ++;
						}
						else if(isset($_GET["threestar'.str_replace(".php", "", $folder).'"])&& $starBut == "Star"){
							$strnum = starprocess($name, $headline, $filename, $strnum, $id, 3, $person);
							$starBut = "Unstar";
							$person ++;
						}
						else if(isset($_GET["fourstar'.str_replace(".php", "", $folder).'"])&& $starBut == "Star"){
							$strnum = starprocess($name, $headline, $filename, $strnum, $id, 4, $person);
							$starBut = "Unstar";
							$person ++;
						}
					}
					$indistar = starcond($name, $headline);
				?>
			<body>
				<style>
					body{
						margin:0px auto;
						width:960px;
					}
					 h1{
						color: darkblue;
					}
					h1 a{
						color: darkblue;
						text-decoration:none;
					}
					h1 a:hover{
						color: midnightblue;
						text-decoration:none;
						text-shadow:0px 1px 11px skyblue;
					}
					h2{
						color: teal;
					}
					h2 a{
						color: teal;
						text-decoration:none;
					}
					h2 a:hover{
						color: teal;
						text-decoration:none;
						text-shadow:0px 1px 11px skyblue;
					}
					h3{
						color: red;
					}
					div{
						background-color:wheat;
					}
					.inpo{
						padding: 0px 20px 20px 20px;
						word-wrap: break-word;
					}
					.comment{
						word-wrap: break-word;
					}
					img{
						padding-bottom: 10px;
						width:550px;
					}
				</style>
				<div><?php include_once $_SERVER["DOCUMENT_ROOT"]."/redirect.php";?>
				<div class = "inpo"><h1><a href = "'.$_SESSION["url"].'/'.$name.'/index.php">'.$name.'</a></h1><h2><a href = "'.$_SESSION["url"].'/'.$name.'/'.$folder.'/index.php">'.$headline.'</a></h2>
				<p>'.$news.'</p>
				<?php if(file_exists($name."/".$filename."/image")){
					$file = glob($name."/".$filename."/image/*");
					if(count($file) === 0){}
					else{echo "<img src = ".$file[0].">";}
				}
				else if(file_exists($filename."/image")){
					$file = glob($filename."/image/*");
					if(count($file) === 0){}
					else{echo "<img src = ".$file[0].">";}
				}
				else if(file_exists("image")){
					$file = glob("image/*");
					if(count($file) === 0){}
					else{echo "<img src = ".$file[0].">";}
				}?>
				<form method ="get" action="<?php echo $_SERVER["PHP_SELF"];?>">
					<textarea name="comment'.str_replace(".php", "", $folder).'" rows="2" cols="40" placeholder = "comment" value = "<?php echo $comment;?>"></textarea><br><br>
					<input type="submit" name="comsub'.str_replace(".php", "", $folder).'" value="comment" > 
					<?php if ($starBut=="Star"){ echo'."'".'<input type="submit" name="onestar'.str_replace(".php", "", $folder).'" value="1 Star">'."';".'
					echo'."'".'<input type="submit" name="twostar'.str_replace(".php", "", $folder).'" value="2 Star">'."';".'
					echo'."'".'<input type="submit" name="threestar'.str_replace(".php", "", $folder).'" value="3 Star">'."';".'
					echo'."'".'<input type="submit" name="fourstar'.str_replace(".php", "", $folder).'" value="4 Star">'."';".'}?>
				</form>
				<p><?php if($comnum != 0) echo "Comments: ".$comnum;?></p>
				<h2><?php if($person != 0) echo "Post CG is ".$strnum/$person;?></h2>
				<h3><?php if($indistar) echo "You have marked ".$indistar;?></h3>
				<div><?php include "comments.php";?></div></div></div>
			</body>
		</html>');
		fclose($file);
		}
		else{
			die ("not created");
		}
		$file = fopen($name."/".$folder."/comments.php", "w");
		flock($file,LOCK_EX);
		fwrite($file,'
		<?php
			if (session_id() === "") {
				session_start();
			}
			if($_SESSION["access"] == False){
				header("Location: ../../index.php");
				die("you get the hell out of here");
			}
		?>');
		 flock($file,LOCK_UN);
	}
	
	function commentwrite($filename, $commenter, $comment){
		$file = fopen($_SERVER['DOCUMENT_ROOT']."/".$commenter."/".$filename."/comments.php", "a");
		flock($file,LOCK_EX);
		fwrite($file, '<h1 style = "display:inline;"><a href = "'.$_SESSION["url"].'/'.$_SESSION["user"].'">'.$_SESSION["user"].'</a></h1> <p style = "display:inline;">'.$comment.'</p><br>');
		flock($file,LOCK_UN);
		fclose($file);
		
	}
	
?>