<?php
// Connect to the MySQL database
$conn = mysqli_connect('localhost', 'root', '1234', 'datasave1','sensor_data');

// Fetch the latest temperature and humidity readings from the database
$result = mysqli_query($conn, "SELECT temperature, humidity, time FROM readings ORDER BY time DESC LIMIT 1");
$row = mysqli_fetch_assoc($result);

// Return the data as a JSON-encoded object
echo json_encode($row);
?>