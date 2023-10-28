<?php
if ($_FILES['image']['error'] === UPLOAD_ERR_OK && isset($_POST['image_data'])) {
    // Handle image upload using file upload

    // Check if an image file was uploaded
    $tempFile = $_FILES['image']['tmp_name'];

    // Specify the destination directory to save the image
    $uploadDir = 'C:\xampp\htdocs\images'; // Replace with the actual destination directory

    // Create the destination directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique filename for the uploaded image
    $filename = uniqid() . '.jpg';

    // Move the uploaded file to the destination directory
    $targetFile = $uploadDir . '/' . $filename;
    if (move_uploaded_file($tempFile, $targetFile)) {
        // File moved successfully

        // Insert the image into the database
        $imageData = file_get_contents($targetFile);
        $imageData = addslashes($imageData); // Escape special characters
        $query = "INSERT INTO images (image_data) VALUES ('$imageData')";

        // Execute the INSERT query
        $connection = mysqli_connect("127.0.0.1", "root", "1234", "datasave1");
        mysqli_query($connection, $query);
        mysqli_close($connection);

        echo "Image uploaded successfully.";
    } else {
        // Failed to move file
        echo "Failed to upload image.";
    }
} elseif (isset($_POST['image_data'])) {
    // Handle image upload using base64 encoded data

    // Get the base64 encoded image data
    $imageData = $_POST['image_data'];

    // Decode the base64 image data
    $decodedImage = base64_decode($imageData);

    // Specify the destination directory to save the image
    $uploadDir = 'C:\xampp\htdocs\images'; // Replace with the actual destination directory

    // Create the destination directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique filename for the uploaded image
    $filename = uniqid() . '.jpg';

    // Save the decoded image data to a file
    if (file_put_contents($uploadDir . '/' . $filename, $decodedImage)) {
        // File saved successfully

        // Insert the image into the database
        $imageData = addslashes($decodedImage); // Escape special characters
        $query = "INSERT INTO images (image_data) VALUES ('$imageData')";

        // Execute the INSERT query
        $connection = mysqli_connect("127.0.0.1", "root", "", "project1");
        mysqli_query($connection, $query);
        mysqli_close($connection);

        echo "Image uploaded successfully.";
    } else {
        // Failed to save file
        echo "Failed to upload image.";
    }
} else {
    // No image data received
    echo "No image data received.";
}
?>
