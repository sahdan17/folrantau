<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>    
        .row {
            width: 99%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        .row1 {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            width: 99%;
            margin-top: 5px;
        }

        #row1 {
            width: 99vw;
            margin-top: 5px;
        }
        
        .dropdown-container {
            margin-block: 10px;
            margin-inline: 20px;
            font-size: 16px;
            width: 25%;
        }

        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        .chartCard {
            width: 100%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .chartBox {
            /* width: 100%; */
            width: 25%;
            min-width: 250px;
            /* padding: 20px; */
            border-radius: 20px;
            border: solid 3px rgba(54, 162, 235, 1);
            background: white;
            margin-bottom: 10px;
            /* margin-inline: 5px; */
        }

        canvas {
            width: 100%;
            height: auto;
        }

        .card {
            width: 100%;
        }

        .card-text {
            font-size: 50px;
            font-weight: bold;
        }

        table{
            margin: 0 auto;
        }
        
        .battery {
            height: 15px;
            width: 30px;
            border: 1px solid #ffffff;
            margin-left: auto; 
            margin-right: 10px;
            margin-top: 5px;
            border-radius: 3px;
        }

        .bars {
            display: flex;
            gap: 1px;
            padding-left: 2px;
            padding-right: 2px;
            padding-top: 1px;
            padding-bottom: 1px;
            height: 100%;
            align-items: center;
        }

        .bar {
            height: 100%;
            width: 100%;
            background-color: transparent;
            border-radius: 2px;
        }

        .bar.active {
            background-color: #ffffff;
        }

        @media (max-width: 999px) {
            .chartMenu,
            .chartCustom {
                width: 100%;
            }

            .row {
                flex-direction: column;
                align-items: center;
            }

            .chartMenu,
            .chartCustom {
                width: 100%;
                max-width: 600px;
            }

            .col-md-4,
            .col-md-6 {
                margin-top: 20px;
            }

            .row {
                flex-direction: column;
            }

            .col-md-4, .col-md-8 {
                width: 100%;
                max-width: 100%;
            }

            .chartBox {
                width: 100%;
            }
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body style="background-color: white">
    @extends('admin.app')

    @section('title', 'FOL Line Chart')
    
    <div class="dropdown-container" style="margin-top: 130px">
        <label for="date-select">Pilih Tanggal:</label>        
        <input type="date" class="form-control" id="date-select" name="date-select">
    </div>

    <div class="row1"></div>    

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let pressuresData = {};
        let spotsName = [];
        let batteryData = [];
        let maxData = [];
        let avg = [];

        let date;
        let spots = [];
        let hour;

        let renderedSpots = [];

        $(document).ready(function(){
            $('#date-select').on('change', function() {
                let selectedDate = $(this).val();
                date = selectedDate;
                getTableData();
            });
            
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            formattedDate = `${year}-${month}-${day}`;
            date = formattedDate;
            
            const dateInput = document.getElementById('date-select');
            dateInput.value = formattedDate;

            var spotsData = [
                @foreach($spots as $spot)
                    {
                        id: {{ $spot->id }},
                        name: "{{ $spot->namaSpot }}"
                    },
                @endforeach
            ];

            spots = [
                @foreach($spots as $spot)
                    {{ $spot->id }},
                @endforeach
            ].map(Number);

            setInterval(function() {
                getTableData();
            }, 10000);

            getTableData();
        });

        function getTableData() {
            $.ajax({
                url: 'https://folpertaminafieldrantau.com/getPressureData',
                method: 'POST',
                dataType: 'json',
                data: {
                    date: date
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    pressuresData = response.pressures;
                    response.spots.forEach(s => {
                        spotsName[s.id] = s.namaSpot;
                    });
                    
                    manipulateData(pressuresData, spots, hour, date, spotsName, batteryData, maxData);
                    
                    $.ajax({
                        url: 'https://folpertaminafieldrantau.com/getBatteryData',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            date: date
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            let batteryDump = response.batterys;
                            batteryDump.forEach(b => {
                                if (b.isActive) {
                                    batteryData[b.idSpot] = b.batt;
                                }
                            });
                            
                            manipulateData(pressuresData, spots, hour, date, spotsName, batteryData, maxData);
                        },
                        error: function(error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                    
                    $.ajax({
                        url: 'https://folpertaminafieldrantau.com/getMaxData',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            date: date
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            let max = response.max;
                            max.forEach(m => {
                               maxData[m.idSpot] = m.max; 
                            });
                            
                            manipulateData(pressuresData, spots, hour, date, spotsName, batteryData, maxData);
                        },
                        error: function(error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        }

        function manipulateData(pressuresData, spots, hour, date, spotsName, batteryData, maxData) {
            let pressures = {};
            let timestamps = {};
            let avgData = {};
            let total = {};
            let avgNumber = {};
            let end = 24 - hour;
            let endTime = date + " " + end + ":00:00";
            let now = new Date();
            let hours = now.getHours();
            let endMinute = now.getMinutes();
            let endSecond = now.getSeconds();
            let endHour = hours - hour;

            if (endHour < 0) {
                endHour = hours;
            }
            if (endHour.toString().length === 1) {
                endHour = '0' + endHour;
            }
            if (endMinute.toString().length === 1) {
                endMinute = '0' + endMinute;
            }
            if (endSecond.toString().length === 1) {
                endSecond = '0' + endSecond;
            }
            let endNot24 = date + " " + endHour + ":" + endMinute + ":" + endSecond;
            pressuresData.forEach(p => {
                spots.forEach(s => {
                    if (p.idSpot == s) {
                        avgNumber[s] = avgNumber[s] || [];
                        if (s == 1 && p.psiValue >= 128 && p.psiValue <= 150){
                            avgNumber[s].push(parseFloat(p.psiValue.toFixed(2)));
                        } else if (s == 2 && p.psiValue >= 70 && p.psiValue <= 100){
                            avgNumber[s].push(parseFloat(p.psiValue.toFixed(2)));
                        } else if (s == 3 && p.psiValue >= 120 && p.psiValue <= 150){
                            avgNumber[s].push(parseFloat(p.psiValue.toFixed(2)));
                        } else if (s == 4 && p.psiValue >= 16 && p.psiValue <= 38){
                            avgNumber[s].push(parseFloat(p.psiValue.toFixed(2)));
                        } else if (s == 5 && p.psiValue >= 92 && p.psiValue <= 185){
                            avgNumber[s].push(parseFloat(p.psiValue.toFixed(2)));
                        } else if (s == 6 && p.psiValue >= 225 && p.psiValue <= 250){
                            avgNumber[s].push(parseFloat(p.psiValue.toFixed(2)));
                        } else if (s == 7 && p.psiValue >= 38 && p.psiValue <= 50){
                            avgNumber[s].push(parseFloat(p.psiValue.toFixed(2)));
                        }
                        pressures[s] = pressures[s] || [];
                        timestamps[s] = timestamps[s] || [];
                        if (!pressures[s]) {
                            pressures[s] = [];
                        }
                        if (!timestamps[s]) {
                            timestamps[s] = [];
                        }
                        pressures[s].push(parseFloat(p.psiValue.toFixed(2)));
                        timestamps[s].push(p.timestamp);
                    }                    
                });
            });

            spots.forEach(s => {
                if (typeof timestamps[s] == 'undefined') {
                    timestamps[s] = [];
                    pressures[s] = [];
                }
                if (typeof avgNumber[s] != 'undefined') {
                    total[s] = avgNumber[s].reduce((acc, number) => acc + number, 0);
                    avgData[s] = total[s] / avgNumber[s].length;
                    if (!avgData[s]) {
                        avgData[s] = 0;
                    }
                } else {
                    avgData[s] = 0;
                }
            });

            Object.keys(window).forEach(function(key) {
                if (key.startsWith('myChart-')) {
                    let spotId = parseInt(key.split('-')[1], 10);
                    if (!spots.includes(spotId)) {
                        if (window[key]) {
                            window[key].destroy();
                            delete window[key];
                        }
                        $('#row-' + spotId).remove();
                    }
                }
            });

            spots.forEach(function(spotId) {
                showChart([spotId], pressures, timestamps, spotsName, avgData, batteryData, maxData);
            });
        }
        
        function updateBattery(level, spotId, batt) {
            if (level > 0 && level <= 25) {
                level = 1;
            } else if (level > 25 && level <= 50) {
                level = 2;
            } else if (level > 50 && level <= 75) {
                level = 3;
            } else if (level > 75 && level <= 100) {
                level = 4;
            }
            for (let i = 1; i <= 4; i++) {
                let bar = document.getElementById('bar' + i + '-' + spotId);
                if (i <= level) {
                    bar.classList.add('active');
                } else {
                    bar.classList.remove('active');
                }
            }
            
            let battText = "";
            if (!batt) {
                battText = '-';
            } else {
                battText = batt + "%";
            }
            $(`#battery-text-${spotId}`).text(battText);
        }

        function showChart(spots, pressures, timestampsData, spotsName, avgData, battery, maxValue) {
            const currentCharts = {};
            $('.row1 .row').each(function() {
                const spotId = this.id.split('-')[1];
                if (spotId !== undefined && spots.includes(Number(spotId))) {
                    currentCharts[spotId] = true;
                }
            });

            var max = [null, 600, 600, 500, 500, 400, 400, 300, 200, 100];
            
            spots.forEach(function(spotId) {
                var spotName = spotsName[spotId];
                var psiValues = pressures[spotId];
                var timestamps = timestampsData[spotId];
                var labelY = 'Pressure (PSI)';
                var minY = 0;
                var maxY = max[spotId];
                var avg = avgData[spotId];
                var batt = battery[spotId];
                var maxV = maxValue[spotId];

                if (window['myChart-' + spotId]) {
                    const chart = window['myChart-' + spotId];
                    chart.config.data.labels = timestamps;
                    chart.config.data.datasets[0].data = psiValues;
                    chart.options.plugins.datalabels.formatter = function(value, context) {
                        if (maxV) {
                            const lastValue = context.dataset.data[context.dataset.data.length - 1];
                            const percentageChange = ((lastValue - maxV) / maxV) * 100;
    
                            return lastValue.toFixed(2) + '\n' + (percentageChange >= 0 ? '+' : '') + percentageChange.toFixed(1) + '%';
                        } else {
                            return context.dataset.data[context.dataset.data.length - 1].toFixed(2);
                        }
                    };
                    chart.update();
                    
                    if (spotId != 1 && spotId != 9){
                        updateBattery(batt, spotId, batt);
                    }
                } else {
                    let battText = "";
                    if (!batt) {
                        battText = '-';
                    } else {
                        battText = batt + "%";
                    }

                    var containerHtml = `
                        <div style="background: linear-gradient(to right, #20a1ad 0%, #021738 100%); border-color: white; color: white" class="chartBox">
                            ${spotId !== 1 && spotId !== 9 ? `
                                <div class="battery-row">
                                    <div style="display: flex; align-items: right;">
                                        <p class="battery-text" id="battery-text-${spotId}" style="color: white; margin: 0; padding-left: 10px;">${battText}</p>
                                        <div class="battery">
                                            <div class="bars">
                                                <div class="bar" id="bar1-${spotId}"></div>
                                                <div class="bar" id="bar2-${spotId}"></div>
                                                <div class="bar" id="bar3-${spotId}"></div>
                                                <div class="bar" id="bar4-${spotId}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ` : ''}
                            <canvas id="myChart-${spotId}"></canvas>
                        </div>`;
                    $('.row1').append(containerHtml);

                    if (spotId != 1 && spotId != 9){
                        updateBattery(batt, spotId, batt);
                    }

                    var ctx = document.getElementById('myChart-' + spotId).getContext('2d');
                    var chartData = {
                        labels: timestamps,
                        datasets: [{
                            label: spotName,
                            data: psiValues,
                            backgroundColor: 'rgba(38, 94, 181, 0)',
                            borderColor: 'white',
                            borderWidth: 1,
                            tension: 1,
                            pointRadius: 0,
                        }]
                    };
                    var chartOptions = {
                        options: {
                            elements: {
                                point:{
                                    radius: 0
                                }
                            }
                        },
                        layout: {
                            padding: {
                                right: 40
                            }
                        },
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'minute',
                                    displayFormats: {
                                        minute: 'HH:mm'
                                    },
                                },
                                // title: {
                                //     display: true,
                                //     text: 'Waktu (WIB)',
                                //     color: 'black',
                                // },
                                ticks: {
                                    color: 'white',
                                    font: {
                                        size: 9
                                    }
                                },
                                grid: {
                                    color: 'rgba(250,250,250,0.25)',
                                }
                            },
                            y: {
                                beginAtZero: true,
                                min: minY,
                                max: maxY,
                                // title: {
                                //     display: true,
                                //     text: labelY,
                                //     color: 'black'
                                // },
                                ticks: {
                                    color: 'white',
                                    font: {
                                        size: 9
                                    }
                                },
                                grid: {
                                    color: 'rgba(250,250,250,0.25)'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    boxWidth: 0,
                                    font: {
                                        size: 16,
                                        weight: 'bold' 
                                    },
                                    color: 'white'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        var date = new Date(context[0].parsed.x);
                                        var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                        var dayName = days[date.getDay()];
                                        dateShow = date.toLocaleString("id-ID", {
                                            year: "numeric",
                                            month: "long",
                                            day: "2-digit",
                                            hour: "2-digit",
                                            minute: "2-digit",
                                            second: "2-digit",
                                            hour12: false
                                        });
                                        var fullDateShow = dayName + ', ' + dateShow;
                                        return fullDateShow;
                                    },
                                    label: function(context) {
                                        var psi = context.parsed.y + " PSI";
                                        var label = context.dataset.label;
                                        var show = label + ": " + psi;
                                        return show;
                                    }
                                }
                            },
                            datalabels: {
                                align: 'right',
                                display: function(context) {
                                    return (context.dataIndex === context.dataset.data.length - 1)
                                },
                                padding: {
                                    right: '0'
                                },
                                color: 'white',
                                font: {
                                    size: 10,
                                },
                                formatter: function(value, context) {
                                    // return context.dataset.data[context.dataset.data.length - 1].toFixed(2) + '\n' + avg.toFixed(2);
                                    if (maxV) {
                                        return context.dataset.data[context.dataset.data.length - 1].toFixed(2) + '\n' + maxV;
                                    } else {
                                        return context.dataset.data[context.dataset.data.length - 1].toFixed(2);
                                    }
                                }
                            }
                        }
                    };
                    window['myChart-' + spotId] = new Chart(ctx, {
                        type: 'line',
                        data: chartData,
                        plugins: [ChartDataLabels],
                        options: chartOptions
                    });
                }
                currentCharts[spotId] = true;
                renderedSpots.push(spotId);
            });
        }
    </script>
      
</body>
</html>