<?php
$json  = file_get_contents('php://input');
$fetch = json_decode($json, true);

$servername = "127.0.0.1";
$username = "root";
$password = "1234";
$dbname = "datasave1";


$temp1 = $fetch['temp1'];
$humi1 = $fetch['humi1'];
$light1 =$fetch['light1'];



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

 
        $sql = "INSERT INTO `sensor_data1` (`temperature`, `humidity`,`lightvalue`)
		VALUES ('$temp1', '$humi1','$light1')";
		
		
    	
		if ($conn->query($sql) === TRUE) {
  			echo "New record created successfully";
		} else {
  			echo "Error: " . $sql . "<br>" . $conn->error;
		} 
		

$conn->close();
echo json_encode("OK");
header("HTTP/1.1 200 OK");
?>