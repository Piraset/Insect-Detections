<?php
// Connect to the MySQL database
$conn = mysqli_connect('localhost', 'root', '1234', 'datasave1', 'sensor_data1');

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the latest temperature and humidity readings from the database
$result = mysqli_query($conn, "SELECT temperature, humidity,lightvalue time FROM readings ORDER BY time DESC LIMIT 1");

// Check if the query was executed successfully
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // Return the data as a JSON-encoded object
    echo json_encode($row);
} else {
    echo "No data found.";
}

// Close the database connection
mysqli_close($conn);
?>
