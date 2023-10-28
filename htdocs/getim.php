<?php
// Assuming you have a database connection established
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "datasave1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedOption = $_POST["number"];

    // Prepare the query using a parameterized statement
    $query = "SELECT image_data FROM images WHERE num = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $selectedOption);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imageData = $row["image_data"];

        // Send appropriate headers and display the image
        header("Content-type: image/jpeg");
        echo $imageData;
    } else {
        echo "Image not found.";
    }
    exit(); // Stop further execution after displaying the image
}

$conn->close();
?>
