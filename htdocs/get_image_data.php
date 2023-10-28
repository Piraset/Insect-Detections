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
    $selectedDateTime = $_POST["datetime"];

    if ($selectedOption === "max") {
        // Query to find the image with the maximum insect and its date-time
        $query = "SELECT image_data, time_data FROM images ORDER BY num DESC LIMIT 1";
        $stmt = $conn->prepare($query);
    } else {
        // Query to retrieve the image data and date-time for the selected number and date-time
        $query = "SELECT image_data, time_data FROM images WHERE num = ? AND time_data = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $selectedOption, $selectedDateTime);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imageData = base64_encode($row["image_data"]);
        $imageDateTime = $row["time_data"];

        $response = array(
            "imageData" => $imageData,
            "imageDateTime" => $imageDateTime
        );

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

$conn->close();
?>
