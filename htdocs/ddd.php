<!DOCTYPE html>
<html>
<head>
    <title>Image Viewer</title>
    <script>
        // Function to send an AJAX request to image.php
        function fetchImages() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "image.php", true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Retrieve the image data response
                    var imageDataResponse = xhr.responseText;

                    // Display the image on the page
                    document.getElementById("imageContainer").innerHTML = imageDataResponse;
                }
            };

            xhr.send();
        }

        // Call the fetchImages function when the page loads
        window.onload = fetchImages;
    </script>
</head>
<body>
    <div id="imageContainer"></div>
</body>
</html>
