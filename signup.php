<?php
	
	function save_data($name, $email, $password, $roll){
		$servername = "localhost";
		$username = "root";
		$servpassword = "****";
		$dbname = "myDB";

		$conn = new mysqli($servername, $username, $servpassword, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT id, name, email, password, roll FROM members";	
		$result = mysqli_query($conn, $sql);
		
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$exname = $row["name"];
				$exemail = $row["email"];
				$exroll = $row["roll"];
				if($name == $exname){
					die("name exists");
				}
				else if($email == $exemail){
					die("email exists");
				}
				else if($roll == $exroll){
					die("roll exists");
				}
			}
		}
		
		$sql = $conn->prepare("INSERT INTO members(name, email, password, roll)VALUES ( ?, ?, ?, ?)");
		$sql->bind_param("ssss", $name, $email, $password, $roll);
		$sql->execute();
					
		echo "New records created successfully";
		$sql->close();

		$sql = "CREATE TABLE ".$name." (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
		username VARCHAR(30) NOT NULL,
		headline VARCHAR(200) NOT NULL,
		file VARCHAR(30) NOT NULL,
		star INT(6) NOT NULL,
		reg_date TIMESTAMP
		)";

		if ($conn->query($sql) === TRUE) {
			echo "Table MyGuests created successfully";
		} else {
			echo "Error creating table: " . $conn->error;
		}
		$conn->close();
		mkdir($name, 0777);
		$profile = fopen($name."/index.php", "w");
		fwrite($profile, '<html>
			<?php
			if (session_id() === "") {
				session_start();
				}
			?>
			<head>
				<title>হ্যাকবেঞ্চার</title>
				<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
			</head>
			<body>
				<style>
					.userprofile{
						background-color:lightgrey;
					}
				</style>
				<div class = "userprofile">
				<?php include_once $_SERVER["DOCUMENT_ROOT"]."/redirect.php";?>
				<?php
					
					if($_SESSION["access"] == False){
						header("Location: ../../index.php");
						die("you get the hell out of here");
					}
					$name = "'.$name.'";
					include_once ($_SERVER["DOCUMENT_ROOT"]."/postterms.php");
					$conn = getconn();
					if ($conn->connect_error) {
						die("Connection failed: " . $conn->connect_error);
					}
					$sql = "SELECT id, username, headline, file, star FROM posts";	
					$result = mysqli_query($conn, $sql);
					$post = array();
					$i = 0;
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							if($name == $row["username"]){
								$post[$i] = $row["file"];
								$i++;
							}
						}
					}
					$i--;
					if($i>5){
						$countPost = 5;
					}
					else{
						$countPost = $i;
					}
					while($countPost >=0 ){
						include $post[$i]."/index.php";
						$countPost--;
						$i--;
					}
					?>
					</div>
					</body>
					</html>');
		fclose($profile);
		header('Location: index.php');
	}
	$nameErr = $emailErr = $genderErr = $passErr = "";
	$name = $email = $gender = $comment = $password = "";
	$ditector = True;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST["email"])) {
			$emailErr = "Email is required";
			$ditector = False;
		} 
		else {
			$email = test_input($_POST["email"]);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$emailErr = "Invalid email format";
				$ditector = False;
			}
			else{
				
			}
		}
		if (empty($_POST["name"])) {
			$nameErr = "Name is required";
			$ditector = False;
		}
		else {
			$name = test_input($_POST["name"]);
			$name = str_replace(' ', '', $name);
			if (!preg_match("/^[a-zA-Z ]*$/",$name)){
			   $nameErr = "Only letters and white space allowed"; 
			   $ditector = False;
			}
			else{
			}
	    }
		if (empty($_POST["password"])) {
			$passErr = "password is required";
			$ditector = False;
		}
		else {
			$password = test_input($_POST["password"]);
	    }
		if (empty($_POST["conf_pass"])) {
			$passErr = "password is required";
			$ditector = False;
		}
		else {
			$conf_password = test_input($_POST["conf_pass"]);
			if($conf_password == $password){
			}
			else{
				$passErr = "password doesn't match";
				$ditector = False;
			}
	    }
		if (empty($_POST["roll"])) {
			$ditector = False;
		}
		else{
			$roll = $_POST["roll"];
			$roll = test_input($_POST["roll"]);
		}
		if($ditector == True){
			save_data($name, $email, $password, $roll);
		}
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
				color: teal;
				font-size: 20px;
			}
			.sign h1{
				font-family: cursive;
				color: darkblue;
				font-size: 20px;
			}
			.sign h1 a{
				color: darkblue;
				text-decoration:none;
			}
			.sign a:hover{
				color: midnightblue;
				text-decoration:none;
				text-shadow:0px 1px 11px skyblue;
			}
		</style>
		<div class = "main">
		<div class = "hackbenchers"><h1>HACKBENCHERS</h1></div>
		<div class ="sign"><form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
			Name: <input type="text" name="name" value="<?php echo $name;?>">
		    <span class="error">* <?php echo $nameErr;?></span>
		    <br><br>
		    E-mail: <input type="text" name="email" value="<?php echo $email;?>">
		    <span class="error">* <?php echo $emailErr;?></span>
		    <br><br>
			Password: <input type="password" name="password" value="<?php echo $password;?>">
		    <span class="error">* <?php echo $passErr;?></span>
		    <br><br>
			Confirm Password: <input type="password" name="conf_pass" value="">
		    <span class="error">* <?php echo $passErr;?></span>
		    <br><br>
			<select name= "roll" size="1">
				<option value="1306066">1306066</option>
				<option value="1306067">1306067</option>
				<option value="1306068">1306068</option>
				<option value="1306069">1306069</option>
				<option value="1306070">1306070</option>
				<option value="1306071">1306071</option>
				<option value="1306072">1306072</option>
				<option value="1306073">1306073</option>
				<option value="1306074">1306074</option>
				<option value="1306075">1306075</option>
				<option value="1306076">1306076</option>
				<option value="1306077">1306077</option>
				<option value="1306078">1306078</option>
				<option value="1306079">1306079</option>
				<option value="1306080">1306080</option>
				<option value="1306081">1306081</option>
				<option value="1306082">1306082</option>
				<option value="1306083">1306083</option>
				<option value="1306084">1306084</option>
				<option value="1306085">1306085</option>
				<option value="1306086">1306086</option>
				<option value="1306088">1306088</option>
				<option value="1306089">1306089</option>
				<option value="1306090">1306090</option>
				<option value="1306092">1306092</option>
				<option value="1306093">1306093</option>
				<option value="1306094">1306094</option>
				<option value="1306095">1306095</option>
				<option value="1306096">1306096</option>
				<option value="1306097">1306097</option>
				<option value="1306098">1306098</option>
				<option value="1306099">1306099</option>
				<option value="1306100">1306100</option>
				<option value="1306101">1306101</option>
				<option value="1306102">1306102</option>
				<option value="1306103">1306103</option>
				<option value="1306104">1306104</option>
				<option value="1306105">1306105</option>
				<option value="1306106">1306106</option>
				<option value="1306107">1306107</option>
				<option value="1306108">1306108</option>
				<option value="1306109">1306109</option>
				<option value="1306110">1306110</option>
				<option value="1306111">1306111</option>
				<option value="1306112">1306112</option>
				<option value="1306113">1306113</option>
				<option value="1306114">1306114</option>
				<option value="1306115">1306115</option>
				<option value="1306116">1306116</option>
				<option value="1306117">1306117</option>
				<option value="1306118">1306118</option>
				<option value="1306119">1306119</option>
				<option value="1306120">1306120</option>
				<option value="1306121">1306121</option>
				<option value="1306122">1306122</option>
				<option value="1306123">1306123</option>
				<option value="1306124">1306124</option>
				<option value="1306125">1306125</option>
				<option value="1306126">1306126</option>
				<option value="1306127">1306127</option>
				<option value="1306128">1306128</option>
				<option value="1306129">1306129</option>
				<option value="1306130">1306130</option>
			</select><br><br>
			<input type="submit" name="submit" value="Submit">
			<h1><a href = "http://localhost:8080">Log in</a></h1>
		</form>
		</div>
	</body>
</html>