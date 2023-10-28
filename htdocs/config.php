<?php
$json  = file_get_contents('php://input');
$fetch = json_decode($json, true);

$servername = "127.0.0.1";
$username = "root";
$password = "1234";
$dbname = "datasave1";


$temp = $fetch['temp'];
$humi = $fetch['humi'];
$light =$fetch['light'];




// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

 
        $sql = "INSERT INTO `sensor_data` (`temperature`, `humidity`,`lightvalue`)
		VALUES ('$temp', '$humi ','$light')";
		
    	
		if ($conn->query($sql) === TRUE) {
  			echo "New record created successfully";
		} else {
  			echo "Error: " . $sql . "<br>" . $conn->error;
		} 
		

$conn->close();
echo json_encode("OK");
header("HTTP/1.1 200 OK");
?>