$(document).ready(function() {
    // Set up the Plotly chart
    var data = [{
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
            title: 'Temperature/Humidity'
        }
    };
    Plotly.newPlot('chart', data, layout);

    // Fetch data from the server every 5 seconds and update the chart
    setInterval(function() {
        $.ajax({
            url: 'get_data.php',
            type: 'GET',
            success: function(response) {
                var data = JSON.parse(response);
                Plotly.extendTraces('chart', {
                    x: [[data.time]],
                    y: [[data.temperature, data.humidity]]
                }, [0]);
            }
        });
    }, 5000);
});