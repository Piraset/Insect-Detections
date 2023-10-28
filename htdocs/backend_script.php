<?php
// Replace with your MySQL database credentials
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "datasave1";
//$dbname = "datasave1";
// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve temperature and humidity data from the table
$sql = "SELECT timestamp, temperature, humidity FROM sensor_data ORDER BY timestamp DESC LIMIT 10";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    // Fetch rows from the result set
    while ($row = $result->fetch_assoc()) {
        // Add each row to the data array
        $data[] = $row;
    }
}

// Close the database connection
$conn->close();

// Set the response header to JSON
header('Content-Type: application/json');

// Convert the data array to JSON and echo it
echo json_encode($data);
?>
