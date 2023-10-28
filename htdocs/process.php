<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "datasave1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$imageData = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedOption = $_POST["number"];
    $enteredDateTime = $_POST["dateTime"];

    if ($selectedOption === "max") {
        $query = "SELECT image_data FROM images ORDER BY num DESC LIMIT 1";
    } else {
        $query = "SELECT image_data FROM images WHERE num = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $selectedOption);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $imageData = base64_encode($row["image_data"]);
        }
    }

    echo $imageData;
}

$conn->close();
?>
