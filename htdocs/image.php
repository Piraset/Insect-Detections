<?php
$servername = "127.0.0.1";
$username = "root";
$password = "1234";
$database = "datasave1";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Assuming you have a 'images' table with a 'image_data' column
$sql = "SELECT image_data FROM images";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Retrieve the image data
        $imageData = $row['image_data'];

        // Convert the image data to a JPG file
        $image = imagecreatefromstring($imageData);
        ob_start();
        imagejpeg($image, null, 80);
        $jpgData = ob_get_clean();

        // Display the image on the dashboard
        echo '<img src="data:image/jpeg;base64,' . base64_encode($jpgData) . '" alt="Image">';
    }
} else {
    echo "No results found.";
}
$conn->close();
?>
