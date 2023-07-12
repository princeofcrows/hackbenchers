<?php 
			$servername = "localhost";
			$username = "root";
			$password = "****";
			$dbname = "myDB";

			$conn = new mysqli($servername, $username, $password, $dbname);
			$sql = "DELETE FROM members";
	if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}
	$conn->close();
?>