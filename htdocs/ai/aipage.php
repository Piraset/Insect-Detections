<!DOCTYPE html>
<html>
<head>
    <title>Aipage</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script>
        function displayImages() {
            var enteredDate = document.getElementById("date").value;
            var selectedNum = document.getElementById("num").value;

            // Send the entered date and selected Num to the server for database query
            var formData = new FormData();
            formData.append("date", enteredDate);
            formData.append("num", selectedNum);

            // Use Fetch API to send a POST request to the server
            fetch("get_images.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Display the images based on the response data
                document.getElementById("imageContainer").innerHTML = data;
            })
            .catch(error => console.error("Error:", error));
        }
    </script>
</head>
<body>
    <header>
        <h1>Ai Detection Page</h1>
    </header>

    <nav>
        <ul>
            <li><a href="home.php">Graph</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="left-body">
            <div id="imageContainer"></div> <!-- HTML element for displaying images -->
        </div>

        <div class="right-body">
            <div class="box">
                <form method="post" action="">
                    <label for="date">Select date:</label>
                    <input type="date" id="date" name="date" required><br>
                    <label for="num">Select Num:</label>
                    <select id="num" name="num" required>
                        <?php
                        // Query the database to get the distinct Num values
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "project1";

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        #$query = "SELECT DISTINCT Num FROM detected_blobs";
                        $query = "SELECT DISTINCT num FROM images";#ไว้เช็ครูปที่ถ่ายมา
                        $result = $conn->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                #$num = $row['Num'];
                               $num = $row['num'];#ไว้เช็ครูปที่ถ่ายมา
                                echo "<option value='$num'>$num</option>";
                            }
                        }

                        $conn->close();
                        ?>
                    </select><br>
                    <button type="button" id="submitBtn" onclick="displayImages()">Submit</button><br>
                </form>
            </div>
        </div>
        </div>
            <table border="10">
            <tr>
                <th colspan="3">Top Secret ที่ควรรู้เกี่ยวกับมดและก้นกระดก</th>
            </tr>
           
            <tr>
                <th>ชื่อแมลง/Typeแมลง</th>
                <th>โทษอันตราย</th>
                <th>วิธีป้องกัน</th>
             </tr>
            <tr>
                <td>Ant</td>
                <td>เวลามดวิ่งเข้าไปในอาหารอาจทำให้อาหารเกิดการปนเปื้อนของเชื้อโรคที่ติดมาจากขา
                    เท้าและลำตัวของมดกัดคนเราด้วยปากและกรามของมันถ้ารุมกัดเป็นจำนวนมากก็เจ็บจนทนไม่ไหวเหมือนกัน
มดบางชนิดต่อยด้วยเหล็กไน และสามารถปล่อยน้ำพิษลงในรอยแผลที่ต่อย ทำให้ปวดแสบปวดร้อน
ผลจากการกัดหรือต่อยยังทำให้เกิดการติดเชื้อต่างๆซ้ำในภายหลัง เพราะอวัยวะคนเราที่มักถูกมดกัดต่อยก็คือเท้าซึ่ง     สกปรกง่าย เกิดเป็นแผลหนองเรื้อรัง</td>
                <td>ควรจัดเก็บอาหารให้เป็นระเบียบและหาช็อกหรือที่ฉีดไล่มด มาฉีดหรือเขียนไล่</td>
            </tr>
           
            <tr>
                <td>Rove Beetles</td>
                <td>สารพิษของแมลงชนิดนี้ คือ เพเดอริน (Pederin) ซึ่งมีลักษณะเป็นกรดที่ก่อให้เกิดการระคายเคือง เมื่อถูกผิวหนังจะทำให้เป็นผื่นคันหรือแผลพุพอง ผิวหนังไหม้แดง ปวดแสบปวดร้อน มีไข้ และถ้าถูกพิษบริเวณดวงตา อาจทำให้ตาบอดได้</td>
                <td>ให้รีบล้างน้ำสะอาด หรือทำความสะอาดเช็ดด้วยแอมโมเนียทันที และไม่ควรสัมผัสถูกบริเวณที่ถูกพิษ เพราะอาจเกิดการลุกลามหรือติดเชื้อซ้ำ ควรทายาปฏิชีวนะประเภทครีมที่บริเวณแผล แต่หากมีอาการลุกลามรุนแรงควรรีบไปพบแพทย์</td>
            </tr>
            
        </table>

 

    <footer>
        <div class="footer1">
            <p>&copy; <?php echo date('Y'); ?> All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
