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

            li {
                list-style-type: none;
            }

            * {
                margin: 0;
                padding: 0;
                font-family: sans-serif;
            }
            
            .ratio-container {
                display: flex;
                width: 100%;
            }

            .ratio-item {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .ratio-item-5 {
                flex: 4;
                margin-right: 20px;
            }

            .ratio-item-7 {
                flex: 8;
            }
            
            .digital{
                color: white;
                padding-left: 20px;
                padding-right: 20px;
                text-align: center;
            }

            #Date{
                font-size: 16px !important;
                padding-bottom: 5px;
            }

            table{
                margin: 0 auto;
            }

            .digital tr{
                list-style: none;
                font-size: 20px;
            }
            
            .containerclock{
                position: relative;
            }

            .clock{
                width: 100px;
                height: 100px;
                border-radius: 50%;
                border: 1px solid white;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .clock span{
                position: absolute;
                transform: rotate(calc(30deg * var(--i))); 
                inset: -6px;
                text-align: center;
            }

            .clock span b{
                transform: rotate(calc(-30deg * var(--i)));
                display: inline-block;
                font-size: 12px;
                font-weight: 200;
                color: white;
            }

            .clock::before{
                content: '';
                position: absolute;
                width: 4px;
                height: 4px;
                border-radius: 50%;
                background-color: #fff;
                z-index: 2;
            }

            .hand{
                position: absolute;
                display: flex;
                justify-content: center;
                align-items: flex-end;
            }

            .hand i{
                position: absolute;
                background-color: var(--clr);
                width: 2px;
                height: var(--h);
                border-radius: 8px;
            }

            @media (max-width: 999px) {
                .navbar-nav {
                    display: none;
                }

                .navbar-toggler {
                    display: block;
                }

                .ratio-item-5 {
                    flex: 6;
                    margin-right: 20px;
                }

                .ratio-item-7 {
                    flex: 6;
                }
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>
        <div style="margin-top: 150px" id="charts-container" class="charts-container"></div>

        <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #265eb5">
            <div class="container-fluid">
                <li class="nav-item dropdown">
                    <a class="navbar-brand dropdown-toggle" style="color: white" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $user->email }}
                    </a>
                    <ul style="background-color: #265eb5; border-color: white" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" style="color: white" href="logout">Logout</a></li>
                    </ul>
                </li>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse align-items-center" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex align-items-center">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" style="color: white" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Monitoring
                            </a>
                            <ul style="background-color: #265eb5; border-color: white" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" style="color: white" href="/">Pressure</a></li>
                                <li><a class="dropdown-item active disabled" style="color: white" href="/gauge">Gauge Chart</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" style="color: white" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Prediksi Data
                            </a>
                            <ul style="background-color: #265eb5; border-color: white" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" style="color: white" href="predLoc">Predict Location</a></li>
                                <li><a class="dropdown-item" style="color: white" href="predLoss">Predict Loss</a></li>
                            </ul>
                        </li>
                        <div class="digital">
                            <div id="Date">Senin, 1 Januari 2024</div>
                            <table>
                                <tr>
                                    <td id="hours">00</td>
                                    <td id="point1"> : </td>
                                    <td id="min">00</td>
                                    <td id="point2"> : </td>
                                    <td id="sec">00</td>
                                </tr>
                            </table>
                        </div>
                        <div class="containerclock">
                            <div class="clock">
                                <div style="--clr: #ff3d58; --h: 36px" id="h" class="hand">
                                    <i></i>
                                </div>
                                <div style="--clr: #00a6ff; --h: 45px" id="m" class="hand">
                                    <i></i>
                                </div>
                                <div style="--clr: #ffffff; --h: 48px" id="s" class="hand">
                                    <i></i>
                                </div>
                        
                                <span style="--i: 1"><b>1</b></span>
                                <span style="--i: 2"><b>2</b></span>
                                <span style="--i: 3"><b>3</b></span>
                                <span style="--i: 4"><b>4</b></span>
                                <span style="--i: 5"><b>5</b></span>
                                <span style="--i: 6"><b>6</b></span>
                                <span style="--i: 7"><b>7</b></span>
                                <span style="--i: 8"><b>8</b></span>
                                <span style="--i: 9"><b>9</b></span>
                                <span style="--i: 10"><b>10</b></span>
                                <span style="--i: 11"><b>11</b></span>
                                <span style="--i: 12"><b>12</b></span>
                            </div>
                        </div>
                    </ul>
                    <div class="align-items-center ms-auto hide-on-small-screen">
                        <div class="ratio-container">
                            <div class="ratio-item ratio-item-5">
                                <img src="{{ asset('images/gambar11.png') }}" alt="Gambar 1" style="height: 50px;">
                                <p style="color: white; margin: 0; text-align: center; font-weight: bold">SKK Migas</p>
                            </div>
                            <div class="ratio-item ratio-item-7">
                                <img src="{{ asset('images/gambar2.png') }}" alt="Gambar 2" style="height: 50px;">
                                <p style="color: white; margin: 0; text-align: center; font-weight: bold">Zona 1 Field Jambi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
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
            var max = [null, 200, 400, 200, 100, 500, 500, 300];

            function clock(){                
                var monthNames = [ "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember" ]; 
                var dayNames= ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"]

                var today = new Date();

                document.getElementById('Date').innerHTML = (dayNames[today.getDay()] + ", " + 
                today.getDate() + ' ' + monthNames[today.getMonth()] + ' ' +today.getFullYear());
                    
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();

                h = h<10? '0'+h: h;
                m = m<10? '0'+m: m;
                s = s<10? '0'+s: s;               

                document.getElementById('hours').innerHTML = h;
                document.getElementById('min').innerHTML = m;
                document.getElementById('sec').innerHTML = s;

            }
            var inter = setInterval(clock, 400);
            
            let hr = document.getElementById('h');
            let min = document.getElementById('m');
            let sec = document.getElementById('s');

            function displayTime(){
                let date = new Date();

                let hh = date.getHours();
                let mm = date.getMinutes();
                let ss = date.getSeconds();

                let hRotation = 30*hh + mm/2;
                let mRotation = 6*mm;
                let sRotation = 6*ss;

                hr.style.transform = `rotate(${hRotation}deg)`;
                min.style.transform = `rotate(${mRotation}deg)`;
                sec.style.transform = `rotate(${sRotation}deg)`;

            }

            setInterval(displayTime, 1000);

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

                            if (!charts[chartId]) {
                                createGaugeChart(chartId, item.pressure, item.spotName, time, item.spot_id);
                            } else {
                                const chart = charts[chartId];
                                chart.series[0].setData([item.pressure], true, {
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
                        plotBands: [
                            {
                                from: spotId == 1 ? 100 : null,
                                to: spotId == 1 ? 131 : null,
                                color: 'red',
                                thickness: 20,
                            },
                            {
                                from: spotId == 1 ? 131 : null,
                                to: spotId == 1 ? 140 : null,
                                color: 'green',
                                thickness: 20,
                            },
                            {
                                from: spotId == 2 ? 51 : null,
                                to: spotId == 2 ? 77 : null,
                                color: 'red',
                                thickness: 20,
                            },
                            {
                                from: spotId == 2 ? 77 : null,
                                to: spotId == 2 ? 99 : null,
                                color: 'green',
                                thickness: 20,
                            },
                            {
                                from: spotId == 3 ? 110 : null,
                                to: spotId == 3 ? 125 : null,
                                color: 'red',
                                thickness: 20,
                            },
                            {
                                from: spotId == 3 ? 125 : null,
                                to: spotId == 3 ? 150 : null,
                                color: 'green',
                                thickness: 20,
                            },
                            {
                                from: spotId == 4 ? 10 : null,
                                to: spotId == 4 ? 14 : null,
                                color: 'red',
                                thickness: 20,
                            },
                            {
                                from: spotId == 4 ? 14 : null,
                                to: spotId == 4 ? 38 : null,
                                color: 'green',
                                thickness: 20,
                            },
                            {
                                from: spotId == 5 ? 90 : null,
                                to: spotId == 5 ? 103 : null,
                                color: 'red',
                                thickness: 20,
                            },
                            {
                                from: spotId == 5 ? 103 : null,
                                to: spotId == 5 ? 130 : null,
                                color: 'green',
                                thickness: 20,
                            },
                            {
                                from: spotId == 6 ? 180 : null,
                                to: spotId == 6 ? 235 : null,
                                color: 'red',
                                thickness: 20,
                            },
                            {
                                from: spotId == 6 ? 235 : null,
                                to: spotId == 6 ? 270 : null,
                                color: 'green',
                                thickness: 20,
                            },
                            {
                                from: spotId == 7 ? 10 : null,
                                to: spotId == 7 ? 18 : null,
                                color: 'red',
                                thickness: 20,
                            },
                            {
                                from: spotId == 7 ? 18 : null,
                                to: spotId == 7 ? 45 : null,
                                color: 'green',
                                thickness: 20,
                            },
                        ]
                    },
                    series: [{
                        name: 'Pressure',
                        data: [data],
                        tooltip: { valueSuffix: ' PSI' },
                        dataLabels: {
                            format: "{y} PSI<br>" + spotName + "<br>" + time,
                            borderWidth: 0,
                            color: 'white',
                            style: { fontSize: '16px', color: 'white' }
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