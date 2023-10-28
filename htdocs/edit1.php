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

// Assuming you have an 'images' table with an 'image_data' column
$sql = "SELECT image_data FROM images";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $imageDataList = array(); // Create an array to store image data
    while ($row = $result->fetch_assoc()) {
        // Retrieve the image data
        $imageData = $row['image_data'];

        // Check if the image data is not empty
        if (!empty($imageData)) {
            // Store the image data in the array
            $imageDataList[] = $imageData;

            // Display the image directly
            echo '<img src="data:image/jpeg;base64,' . base64_encode($imageData) . '" alt="Image">';
        } else {
            echo "One of the retrieved image data is empty.";
        }
    }

    // Serialize the image data list for passing to dashboards.php
    $serializedImageDataList = urlencode(serialize($imageDataList));

    // Provide a link to dashboards.php with the serialized image data list as a parameter
    echo '<a href="dashboards.php?images=' . $serializedImageDataList . '">View in Dashboard</a>';
} else {
    echo "No results found.";
}

$conn->close();
?>
