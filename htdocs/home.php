<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">



<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>Graph</title>
<!-- Include the Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Include the gauge.js library -->
<script src="https://cdn.jsdelivr.net/npm/gaugeJS/dist/gauge.min.js"></script>


</head>
<body>
<div>
    <header>
    <h1>Real-Time Graph</h1>
</header>
    <link rel="stylesheet" type="text/css" href="styles.css">
</div>
<nav>
        <ul>
            <li><a href="aipage.php">Ai Page</a></li>
            

           
        </ul>
    </nav>
<div class="container1">
    <div class="line-chart">
        <canvas id="myChart"></canvas>
        <p class="text1">Graph</p>
    </div>
    
    <div class="line-chart">
        <canvas id="lineChartLight"></canvas>
        <p class="text1">Light Value</p>
    </div>

    <div class="gauge-chart">
        <canvas id="gaugeChartTemperature"></canvas>
        <p class="text1">Temperature</p>
        <p id="temperatureValue"></p>
    </div>
    <div class="gauge-chart">
        <canvas id="gaugeChartHumidity"></canvas>
        <p class="text1">Humidity</p>
        <p id="humidityValue"></p>
    </div> 
    <div class="label1">
    <p class="text1">ลักษณะสภาพอากาศตอนนี้</p>
    <label id="statusLabel" style="text-align:center"></label>
</div>
</div> 


<script>
setInterval(() => {
    const now = new Date().toISOString();
    console.log(now);
}, 1000);

// Function to fetch temperature and humidity data from the server
function fetchData() {
    // Make an AJAX request to the backend PHP script to retrieve the data
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "backend_script.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Parse the JSON response
                var data = JSON.parse(xhr.responseText);
                // Call the function to update the chart
                updateChart(data);
                // Call the function to update the gauge charts
                
            } else {
                console.error("Failed to fetch data:", xhr.status, xhr.statusText);
            }
        }
    };
    xhr.send();
}

function fetchData1() {
    // Make an AJAX request to the backend PHP script to retrieve the data
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "backend_script1.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Parse the JSON response
                var data1 = JSON.parse(xhr.responseText);
                // Call the function to update the chart
             
                updateGaugeCharts(data1);
                updateLineChartLight(data1);
            } else {
                console.error("Failed to fetch data:", xhr.status, xhr.statusText);
            }
        }
    };
    xhr.send();
}


// Function to update the line chart with new data
function updateChart(data) {
    var labels = [];
    var temperatureData = [];
    var humidityData = [];
   
    // Extract the labels, temperature, and humidity values from the data
    for (var i = 0; i < data.length; i++) {
        labels.push(data[i].timestamp);
        temperatureData.push(data[i].temperature);
        humidityData.push(data[i].humidity);
    }

    // Destroy existing Chart instance to reuse the <canvas> element
    var chartStatus = Chart.getChart("myChart"); // <canvas> id
    if (chartStatus !== undefined) {
        chartStatus.destroy();
    }

    // Get the canvas element
    var ctx = document.getElementById("myChart").getContext("2d");
    // Create a new Chart object
    var chart = new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Temperature (℃)",
                    data: temperatureData,
                    borderColor: "red",
                    backgroundColor: "rgba(255, 0, 0, 0.1)",
                    fill: false
                },
                {
                    label: "Humidity (%)",
                    data: humidityData,
                    borderColor: "blue",
                    backgroundColor: "rgba(0, 0, 255, 0.1)",
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: "Timestamp"
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: "Value"
                    }
                }
            }
        }
    });
}

setInterval(fetchData, 5000, true); // Update every 5 seconds

// Function to update the gauge charts with new data
function updateGaugeCharts(data1) {
    var latestTemperature = data1[data1.length - 10].temperature;
    var latestHumidity = data1[data1.length - 10].humidity;
    var statusLabel = document.getElementById("statusLabel");

    // Check if it's cold and humid
    if (latestTemperature <= 10  && latestHumidity >= 30) {
        // It's cold and humid, set the message in the Label
        statusLabel.textContent = "สภาพอากาศหนาวเย็นและแห้ง";
    }
    else if (latestTemperature <= 10  && latestHumidity >= 60) {
        // It's cold and humid, set the message in the Label
        statusLabel.textContent = "สภาพอากาศหนาวเย็นและชื้น";
    }
    else if (latestTemperature >= 10 && latestTemperature <= 20 && latestHumidity >= 30) {
        // It's cold and humid, set the message in the Label
        statusLabel.textContent = "สภาพอากาศหนาวและแห้ง";
    }
    else if (latestTemperature >= 10 && latestTemperature <= 20 && latestHumidity >= 60) {
        // It's cold and humid, set the message in the Label
        statusLabel.textContent = "สภาพอากาศหนาวและชื้น";
    }
    else if (latestTemperature >= 21 && latestTemperature <= 30 && latestHumidity >= 30) {
        // It's cold and humid, set the message in the Label
        statusLabel.textContent = "สภาพอากาศเย็นและแห้ง";
    }
    else if (latestTemperature >= 21 && latestTemperature <= 30 && latestHumidity >= 60) {
        // It's cold and humid, set the message in the Label
        statusLabel.textContent = "สภาพอากาศเย็นและชื้น";
    }
   
    else if (latestTemperature >= 31 && latestTemperature <= 34 && latestHumidity >= 60) {
        // It's cold and humid, set the message in the Label
        statusLabel.textContent = "สภาพอากาศปกติ";
    }
    else if (latestTemperature >= 35 && latestHumidity >= 30) {
        // It's cold and humid, set the message in the Label
        statusLabel.textContent = "สภาพอากาศร้อนและแห้ง";
    }
    else if (latestTemperature >= 35 && latestHumidity >= 60) {
        // It's cold and humid, set the message in the Label
        statusLabel.textContent = "สภาพอากศร้อนและชื้น ";
    }
    else {
        // Clear the Label if the condition is not met
        statusLabel.textContent = "";
    }
    // Get the canvas elements for the gauge charts
    var gaugeCanvasTemperature = document.getElementById("gaugeChartTemperature");
    var gaugeCanvasHumidity = document.getElementById("gaugeChartHumidity");
 
    // Create the gauge chart objects for temperature and humidity
    var gaugeChartTemperature = new Gauge(gaugeCanvasTemperature).setOptions({
        // options for temperature gauge...
        staticLabels: {
            font: "10px sans-serif",
            labels: [0, 25, 50, 75, 100],
            color: "#000000",
            fractionDigits: 0,
        },
        staticZones: [
            { strokeStyle: "#30B32D", min: 0, max: latestTemperature },
            { strokeStyle: "#FFDD00", min: latestTemperature, max: 100 },
        ],
    });

    var gaugeChartHumidity = new Gauge(gaugeCanvasHumidity).setOptions({
        // options for humidity gauge...
        staticLabels: {
            font: "10px sans-serif",
            labels: [0, 25, 50, 75, 100],
            color: "#000000",
            fractionDigits: 0,
        },
        staticZones: [
            { strokeStyle: "#FFDD00", min: 0, max: latestHumidity },
            { strokeStyle: "#FF0000", min: latestHumidity, max: 100 },
        ],
    });

    // Set the values of the gauge charts to the latest temperature and humidity
    gaugeChartTemperature.maxValue = 100;
    gaugeChartTemperature.setMinValue(0);
    gaugeChartTemperature.animationSpeed = 32;
    gaugeChartTemperature.set(latestTemperature);

    gaugeChartHumidity.maxValue = 100;
    gaugeChartHumidity.setMinValue(0);
    gaugeChartHumidity.animationSpeed = 32;
    gaugeChartHumidity.set(latestHumidity);

   // Update the value elements with latest temperature and humidity
   document.getElementById("temperatureValue").textContent = latestTemperature + "℃";
   document.getElementById("humidityValue").textContent = latestHumidity + "%";   
}
function showValueOnChart(canvas, text, value, labelText) {
  var ctx = canvas.getContext("2d");
  ctx.font = "12px Arial";
  ctx.fillStyle = "#000000";
  ctx.textAlign = "center";
  ctx.fillText(text, canvas.width / 2, canvas.height / 2 - 15);
  ctx.fillText(value + " %", canvas.width / 2, canvas.height / 2);
  ctx.fillText(labelText, canvas.width / 2, canvas.height / 2 + 15);
}

// Function to update the Line Chart with Light Value data
function updateLineChartLight(data1) {
    var labels = [];
    var lightData = [];

    // Extract the labels and light value data from the data
    for (var i = 0; i < data1.length; i++) {
        labels.push(data1[i].timestamp);
        lightData.push(data1[i].lightvalue);
    }

    // Destroy existing Chart instance to reuse the <canvas> element
    var chartStatus = Chart.getChart("lineChartLight"); // <canvas> id
    if (chartStatus !== undefined) {
        chartStatus.destroy();
    }

    // Get the canvas element
    var ctx = document.getElementById("lineChartLight").getContext("2d");
    // Create a new Line Chart object
    var chart = new Chart(ctx, {
    type: "line",
    data: {
        labels: labels,
        datasets: [
            {
                label: "Light Value",
                data: lightData,
                borderColor: "green",
                backgroundColor: "rgba(0, 255, 0, 0.1)",
                fill: true,
                pointRadius: 4, // Set the point radius here
                pointBackgroundColor: "green",
                pointBorderColor: "white"
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: "Timestamp"
                }
            },
            y: {
                display: true,
                title: {
                    display: true,
                    text: "Light Value"
                },
                beginAtZero: true, // เริ่มแกน Y ที่ค่า 0
                suggestedMax: 2000, // ค่าสูงสุดบนแกน Y
            }
        },
        plugins: {
            legend: {
                display: true, // แสดงคำอธิบายกราฟ
                labels: {
                    color: "black", // สีข้อความคำอธิบาย
                    boxWidth: 20, // ความกว้างของกล่องคำอธิบาย
                }
            }
        }
    }
});
}

// Fetch data initially and then set an interval to update it every few seconds
setInterval(fetchData1, 5000, true); // Update every 5 seconds
</script>
</div>
<div class="footer1">
    <div class="container" id="clock">
    <script src="clock.js"></script>
    </div>
</div>
</body>
</html>
