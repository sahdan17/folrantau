<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            .charts-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }

            .highcharts-figure {
                min-width: 200px;
                max-width: 300px;
                flex: 1 1 300px;
                margin: 1em;
                box-sizing: border-box;
            }

            @media (max-width: 800px) {
                .charts-container {
                    flex-direction: column;
                    align-items: center;
                }
            
                .highcharts-figure {
                    max-width: 100%;
                }
            }

            * {
                margin: 0;
                padding: 0;
                font-family: sans-serif;
            }
    
            table{
                margin: 0 auto;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>
        @extends('admin.app')

        @section('title', 'FOL Gauge Chart')
        
        <div style="margin-top: 120px" id="charts-container" class="charts-container"></div>
        
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
            integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
            crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
            crossorigin="anonymous">
        </script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-more.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-more.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script type="text/javascript">
            var max = [null, 600, 600, 500, 500, 400, 400, 300, 200, 100];

            let previousData = [];
            let charts = {};

            function loadDataAndUpdateCharts() {
                $.ajax({
                    url: 'getLastData',
                    method: 'GET',
                    success: function(response) {
                        response.forEach((item, index) => {
                            const time = new Date(item.timestamp).toTimeString().split(' ')[0];
                            const chartId = `gauge-${index + 1}`;
                            
                            if (!$(`#${chartId}`).length) {
                                const chartDiv = document.createElement('div');
                                chartDiv.id = chartId;
                                chartDiv.className = 'highcharts-figure';
                                document.getElementById('charts-container').appendChild(chartDiv);
                            }
                            
                            // let psiValue: parseFloat(item.pressure).toFixed(2),

                            if (!charts[chartId]) {
                                createGaugeChart(chartId, parseFloat(parseFloat(item.pressure).toFixed(2)), item.spotName, time, item.spot_id);
                            } else {
                                const chart = charts[chartId];
                                chart.series[0].setData([parseFloat(parseFloat(item.pressure).toFixed(2))], true, {
                                    duration: 1000
                                });
                                chart.series[0].update({
                                    dataLabels: {
                                        format: "{y} PSI<br>" + item.spotName + "<br>" + time,
                                    }
                                });
                            }
                        });

                        previousData = response;
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            setInterval(loadDataAndUpdateCharts, 5000);

            $(document).ready(function() {
                loadDataAndUpdateCharts();
            });

            function createGaugeChart(containerId, data, spotName, time, spotId) {
                const chart = Highcharts.chart(containerId, {
                    chart: {
                        type: 'gauge',
                        backgroundColor: {
                            radialGradient: { r: 1, cx: 1 },
                            stops: [
                                [0, '#0b1b36'],
                                [1, '#ffffff']
                            ]
                        },
                        plotBorderWidth: 0,
                        plotShadow: false,
                        height: '100%',
                        borderRadius: 500,
                        padding: 10,
                    },
                    exporting: { enabled: false },
                    credits: { enabled: false },
                    title: null,
                    pane: {
                        startAngle: -150,
                        endAngle: 150,
                        background: null,
                    },
                    tooltip: { enabled: false },
                    yAxis: {
                        min: 0,
                        max: max[spotId],
                        tickPixelInterval: 200,
                        tickPosition: 'inside',
                        tickColor: 'black',
                        tickLength: 20,
                        tickWidth: 0.5,
                        tickInterval: max[spotId]/20,
                        minorTicks: false,
                        labels: {
                            distance: 20,
                            style: { fontSize: '14px', color: 'black' }
                        },
                        lineWidth: 0,
                        // plotBands: [
                        //     {
                        //         from: spotId == 1 ? 100 : null,
                        //         to: spotId == 1 ? 131 : null,
                        //         color: 'red',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 1 ? 131 : null,
                        //         to: spotId == 1 ? 140 : null,
                        //         color: 'green',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 2 ? 51 : null,
                        //         to: spotId == 2 ? 77 : null,
                        //         color: 'red',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 2 ? 77 : null,
                        //         to: spotId == 2 ? 99 : null,
                        //         color: 'green',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 3 ? 110 : null,
                        //         to: spotId == 3 ? 125 : null,
                        //         color: 'red',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 3 ? 125 : null,
                        //         to: spotId == 3 ? 150 : null,
                        //         color: 'green',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 4 ? 10 : null,
                        //         to: spotId == 4 ? 14 : null,
                        //         color: 'red',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 4 ? 14 : null,
                        //         to: spotId == 4 ? 38 : null,
                        //         color: 'green',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 5 ? 150 : null,
                        //         to: spotId == 5 ? 180 : null,
                        //         color: 'red',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 5 ? 180 : null,
                        //         to: spotId == 5 ? 250 : null,
                        //         color: 'green',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 6 ? 180 : null,
                        //         to: spotId == 6 ? 235 : null,
                        //         color: 'red',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 6 ? 235 : null,
                        //         to: spotId == 6 ? 270 : null,
                        //         color: 'green',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 7 ? 10 : null,
                        //         to: spotId == 7 ? 18 : null,
                        //         color: 'red',
                        //         thickness: 20,
                        //     },
                        //     {
                        //         from: spotId == 7 ? 18 : null,
                        //         to: spotId == 7 ? 45 : null,
                        //         color: 'green',
                        //         thickness: 20,
                        //     },
                        // ]
                    },
                    series: [{
                        name: 'Pressure',
                        data: [data],
                        tooltip: { valueSuffix: ' PSI' },
                        dataLabels: {
                            format: "{y} PSI<br>" + spotName + "<br>" + time,
                            borderWidth: 0,
                            color: 'white',
                            style: {
                                fontSize: '16px',
                                color: 'white',
                                textAlign: 'center',
                            },
                            useHTML: true,
                        },
                        dial: {
                            radius: '80%',
                            backgroundColor: 'white',
                            baseWidth: 12,
                            baseLength: '0%',
                            rearLength: '0%'
                        },
                        pivot: { backgroundColor: 'white', radius: 6 }
                    }]
                });

                charts[containerId] = chart;
            }
        </script>
    </body>
</html>