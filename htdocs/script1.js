$(document).ready(function() {
    // Set up the Plotly chart
    var data1 = [{
        x: [],
        y: [],
        type: 'scatter',
        mode: 'lines'
    }];
    var layout = {
        title: 'Real-time Temperature and Humidity Graph',
        xaxis: {
            title: 'Time'
        },
        yaxis: {
            title: 'Temperature/Humidity/lightvalue'
        }
    };
    Plotly.newPlot('chart', data1, layout);

    // Fetch data from the server every 5 seconds and update the chart
    setInterval(function() {
        $.ajax({
            url: 'get_data1.php',
            type: 'GET',
            success: function(response) {
                var data1 = JSON.parse(response);
                Plotly.extendTraces('chart', {
                    x: [[data1.time]],
                    y: [[data1.temperature, data1.humidity,data1.lightvalue]]
                }, [0]);
            }
        });
    }, 5000);
});