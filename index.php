<?php
	session_start();
	$_SESSION["access"] = False;
	$_SESSION["url"] = "http://localhost:8080";
	$Log_email = $Log_pass = "";
	$Login_emailErr = $Login_passErr = "";
	$happen = False;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST["Log_email"])) {
			$Login_emailErr = "Email required";
			$Ld = False;
		}
		else{
			$Log_email = test_input($_POST["Log_email"]);
			$Ld = True;
			$happen = True;
		}
		if (empty($_POST["Log_pass"])) {
			$Login_passErr = "Password required";
			$Ld = False;
		}
		else{
			$Log_pass = test_input($_POST["Log_pass"]);
			$Ld = True;
			$happen = True;
		}
		if($Ld == True){
			$emailExistance = false;
			$servername = "localhost";
			$username = "root";
			$password = "****";
			$dbname = "myDB";

			$conn = new mysqli($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			else{
				$sql = "SELECT id, name, email, password, roll FROM members";	
				$result = mysqli_query($conn, $sql);
				if($result->num_rows > 0){
					$Login_emailErr = $Login_passErr = "";
					while($row = $result->fetch_assoc()) {
						$Datemail = $row["email"];
						$Datpass = $row["password"];
						if($Log_email == $Datemail){
							$emailExistance = true;
							if( $Datpass == $Log_pass){
								$_SESSION["access"] = True;
								$_SESSION["user"] = $row["name"];
								$_SESSION["roll"] = $row["roll"];
								if(!isset($_COOKIE["user"])){
									setcookie("user", $row["name"], time() + (86400 * 30), "/"); // 86400 = 1 day
								}
								else{
									if($_COOKIE["user"] == $_SESSION["user"]){
			
									}
									else{
										setcookie("user", $row["name"], time() + (86400 * 30), "/"); // 86400 = 1 day
									}
								}
								$_SESSION["currentpost"] = 0;
								$conn->close();
								break;
							}
							else{
								$Login_passErr = "Worng pass";
							}
						}
					}
				}
				if($_SESSION["access"] == True){
					header('Location: welcome.php');
				}
				else if($emailExistance == false){
					$Login_emailErr = "email doesn't exists";
				}
			}
		}
	}
	
	if(isset($_GET["signup"])){
		header('Location: signup.php');
	}
	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
?>
<!DOCTYPE html>
<html>
	<head>
	<title>হ্যাকবেঞ্চার</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css"/>
	</head>
	
	<body>
		<style>
			body{
			}
			.main{
				margin:5px auto;
				width: 960px;
				height:650px;
				background-color: wheat;
			}
			.hackbenchers{
				text-align: center;
				color: midnightblue;
				font-size:54px;
				font-family: fantasy;
				padding: 7px;
			}
			.sign{
				margin-left: 95px;
				font-family: cursive;
				color: darkblue;
				font-size: 20px;
			}
			.sign h1{
				font-size: 40px;
				color: teal;
			}
		</style>
		<div class = "main">
		<div class = "hackbenchers"><h1>HACKBENCHERS</h1></div>
		<div class ="sign"><h1>Sign in</h1>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			E-mail: <input type="text" name="Log_email" value="<?php echo $Log_email;?>">
		    <span class="error">* <?php echo $Login_emailErr;?></span>
		    <br><br>
			Password: <input type="password" name="Log_pass" value="<?php echo $Log_pass;?>">
		    <span class="error">* <?php echo $Login_passErr;?></span>
		    <br><br> 
			<input type="submit" name="Log_submit" value="Submit"> <br><br>
		</form>
		<p style = "display:inline;">আইডি নাই?</p><form style = "display:inline; margin-left:5px;" method="get" action="<?php ($_SERVER["PHP_SELF"]);?>"> 
			<input type="submit" name="signup" value="Signup">
		</form></div>
		</div>
	</body>
</html>