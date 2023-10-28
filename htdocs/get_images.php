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

$imageData = ""; // Initialize image data variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["date"]) && isset($_POST["num"])) {
    $enteredDate = $_POST["date"];
    $selectedNum = intval($_POST["num"]);

    // Query to retrieve the image data based on the entered date (without time) and selected Num
    $query = "SELECT image1 FROM detected_blob WHERE DATE(Date1) = ? AND Num = ?";
    #$query = "SELECT image_data FROM images WHERE DATE(time_data) = ? AND num = ?";#ไว้เช็ครูปที่ถ่ายมา
    // Prepare the query using a parameterized statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $enteredDate, $selectedNum);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imageData .= '<img src="data:image/jpeg;base64,' . base64_encode($row["image1"]) . '" alt="Image" width="400" height="300"><br>';
            #$imageData .= '<img src="data:image/jpeg;base64,' . base64_encode($row["image_data"]) . '" alt="Image" width="400" height="300"><br>';#ไว้เช็ครูปที่ถ่ายมา
        }
    } else {
        $imageData = '<p>No images found for the selected date and Num.</p>';
    }
}

$conn->close();

echo $imageData;
?>
