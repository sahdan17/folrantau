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
        }

        .row1 {
            width: 99%;
            margin-top: 5px;
        }

        #row1 {
            width: 99vw;
            margin-top: 5px;
        }

        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        .chartMenu {
            width: 100%;
            color: rgba(54, 162, 235, 1);
            margin: 0 auto;
        }
        
        .chartCustom {
            width: 100%;
            color: rgba(54, 162, 235, 1);
            margin: 0 auto;
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
            width: 100%;
            padding: 20px;
            border-radius: 20px;
            border: solid 3px rgba(54, 162, 235, 1);
            background: white;
            margin-bottom: 20px;
        }

        .card {
            width: 100%;
        }

        .card-text {
            font-size: 50px;
            font-weight: bold;
        }

        .editButton {
            background-color: black;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        
        .combineButton {
            background-color: black;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .select2-container--default .select2-selection--single {
            background-color: #0b1b36 !important;
            color: white !important;
            border: 1px solid #aaa;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #0b1b36 !important;
            color: white !important;
        }

        .select2-container--default .select2-results > .select2-results__options {
            background-color: #0b1b36 !important;
            color: white !important;
        }
        
        .select2-container--default .select2-selection--multiple {
            background-color: #0b1b36 !important;
            border: 1px solid #aaa !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            color: white !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #1a3d7c !important;
            border: 1px solid #aaa !important;
            color: white !important;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            border-radius: 4px;
            margin-right: 2px;
            opacity: 1;
            filter: invert(1);
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1
        }
        
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 300px;
            max-width: 300px;
            margin: 1em auto;
        }

        table{
            margin: 0 auto;
        }
        
        .battery {
            height: 30px;
            width: 80px;
            border: 2px solid #ffffff;
            margin-left: auto; 
            margin-right: 0;
            border-radius: 5px;
        }

        .bars {
            display: flex;
            gap: 2px;
            padding-left: 3px;
            padding-right: 3px;
            padding-top: 2px;
            padding-bottom: 2px;
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
                width: 120%;
            }

            .chartBox {
                width: 150%;
            }
        }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<!--<body style="background-color: #0b1b36">-->
<body style="background-color: #babdc2">
    @extends('admin.app')

    @section('title', 'FOL Monitoring')

    <div class="row" style="margin-top: 125px">
        <div class="chartMenu col-md-6">
            <div class="mx-3 my-1">
                <label style="color: #0b1b36" for="date">Select Date:</label>
                <input style="background-color: #0b1b36; color: white" type="date" class="form-control" id="date" name="date">
            </div>
            <div class="mx-3 my-1">
                <label style="color: #0b1b36" for="hour">Select Hour:</label>
                <select style="background-color: #0b1b36; color: white" name="hour" id="hour" class="form-control">
                    @foreach([1, 3, 6, 12, 24] as $value)
                    <option value="{{ $value }}">{{ $value }}
                        Hour{{ $value > 1 ? 's' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div style="color: white;" class="mx-3 my-1">
                <label for="spot" style="color: #0b1b36" class="form-label">Select Spot:</label>
                <select id="spot" name="spot" class="form-control" multiple="multiple"></select>
            </div>
        </div>
    
        <div class="col-md-6">
            <div class="chartCustom">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="hidden" id="spot" name="spotId" style="width: 25vw">
                        <label style="color: #0b1b36" for="label">Label Chart:</label><br>
                        <input style="background-color: #0b1b36; color: white" class="form-control" type="text" id="label" name="label" style="width: 25vw">
                    </div>
                    <div class="form-group">
                        <label style="color: #0b1b36" for="labelY">Label Axis Y:</label><br>
                        <input style="background-color: #0b1b36; color: white" class="form-control" type="text" id="labelY" name="labelY" style="width: 25vw">
                    </div>
                    <div class="form-group">
                        <label style="color: #0b1b36" for="minY">Min Axis Y:</label><br>
                        <input style="background-color: #0b1b36; color: white" class="form-control" type="text" id="minY" name="minY" style="width: 25vw">
                    </div>
                    <div class="form-group">
                        <label style="color: #0b1b36" for="maxY">Max Axis Y:</label><br>
                        <input style="background-color: #0b1b36; color: white" class="form-control" type="text" id="maxY" name="maxY" style="width: 25vw">
                    </div>    
                    <button id="customizeChart" type="submit" style="background-color: #0b1b36; border-color: white" class="btn btn-primary d-grid gap-2 col-4 mx-auto my-3">Custom</button>
                </form>
            </div>        
        </div>
    </div>

    <div class="row1"></div>

    <div class="loader"></div>

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
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        let pressuresData = {};
        let spotsName = [];
        let batteryData = [];
        let avg = [];

        let date;
        let spots = [];
        let hour;

        let renderedSpots = [];

        $(document).ready(function(){
            let scrollPosition = 0;

            const dateInput = document.getElementById('date');
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            formattedDate = `${year}-${month}-${day}`;
            dateInput.value = formattedDate;
            date = formattedDate;

            const hourInput = document.getElementById('hour');
            hour = 24;
            hourInput.value = hour;

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

            const spotSelect = $('#spot');

            spotsData.forEach(function(spot) {
                var newOption = new Option(spot.name, spot.id, true, true);
                spotSelect.append(newOption);
            });

            spotSelect.select2({
                placeholder: "",
                allowClear: true
            });

            $('#date').on('change', function() {
                date = $(this).val();
                // console.log(date);
                getTableData();
            });

            $('#hour').on('change', function() {
                hour = parseInt($(this).val(), 10);
                manipulateData(pressuresData, spots, hour, date, spotsName, batteryData);
            });

            $('#spot').on('change', function() {
                spots = $(this).val().map(Number);
                manipulateData(pressuresData, spots, hour, date, spotsName, batteryData);
            });

            setInterval(function() {
                getTableData();
            }, 10000);

            getTableData();
        });

        function getTableData() {
            $.ajax({
                url: 'https://rtuold.findingoillosses.com/getPressureData',
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
                    
                    manipulateData(pressuresData, spots, hour, date, spotsName, batteryData);
                    
                    $.ajax({
                        url: 'https://rtuold.findingoillosses.com/getBatteryData',
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
                            
                            manipulateData(pressuresData, spots, hour, date, spotsName, batteryData);
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

        function manipulateData(pressuresData, spots, hour, date, spotsName, batteryData) {
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
            
            if (pressuresData.length != 0) {
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
                            if (hour == 24){
                                if (!pressures[s]) {
                                    pressures[s] = [];
                                }
                                if (!timestamps[s]) {
                                    timestamps[s] = [];
                                }
                                pressures[s].push(parseFloat(p.psiValue.toFixed(2)));
                                timestamps[s].push(p.timestamp);  
                            } else {
                                if (date != formattedDate){
                                    if (p.timestamp >= endTime){
                                        if (!pressures[s]) {
                                            pressures[s] = [];
                                        }
                                        if (!timestamps[s]) {
                                            timestamps[s] = [];
                                        }
                                        pressures[s].push(parseFloat(p.psiValue.toFixed(2)));
                                        timestamps[s].push(p.timestamp);
                                    }                                    
                                } else {
                                    if (p.timestamp >= endNot24){
                                        if (!pressures[s]) {
                                            pressures[s] = [];
                                        }
                                        if (!timestamps[s]) {
                                            timestamps[s] = [];
                                        }
                                        pressures[s].push(parseFloat(p.psiValue.toFixed(2)));
                                        timestamps[s].push(p.timestamp);
                                    }    
                                }
                            }
                        }                    
                    });
                });
            }

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
                showChart([spotId], pressures, timestamps, spotsName, avgData, batteryData);
            });
        }

        $('#customizeChart').on('click', function() {
            const spotId = $('#spot').val();
            const label = $('#label').val();
            const labelY = $('#labelY').val();
            const minY = parseInt($('#minY').val(), 10);
            const maxY = parseInt($('#maxY').val(), 10);
            const chart = window['myChart-' + spotId];
            if (chart) {
                chart.config.data.datasets[0].label = label;
                chart.config.options.scales.y.title.text = labelY;
                chart.config.options.scales.y.min = minY;
                chart.config.options.scales.y.max = maxY;
                chart.update();
            }
            $('#label').val('');
            $('#labelY').val('');
            $('#minY').val('');
            $('#maxY').val('');
            $('#spotId').val('');
        });
        
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
            
            const batteryTextElement = document.querySelector(`#row-${spotId} .battery-row p`);
            if (batteryTextElement) {
                let battText = "";
                if (!batt) {
                    battText = 'Tidak ada Data';
                } else {
                    battText = batt + "%";
                }
                batteryTextElement.textContent = battText;
            }
        }

        function showChart(spots, pressures, timestampsData, spotsName, avgData, battery) {
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
                var labelY = 'PRESSURE (PSI)';
                var minY = 0;
                var maxY = max[spotId];
                var avg = avgData[spotId];
                var batt = battery[spotId];

                if (window['myChart-' + spotId]) {
                    const chart = window['myChart-' + spotId];
                    chart.config.data.labels = timestamps;
                    chart.config.data.datasets[0].data = psiValues;
                    chart.update();

                    if (timestamps.length!=0){
                        $('#nama' + spotId).text(window['myChart-' + spotId].config.data.datasets[0].label);
                        $('#val' + spotId).text(psiValues[psiValues.length - 1] + ' PSI');
                        var date = new Date(timestamps[timestamps.length - 1]);
                        var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        var dayName = days[date.getDay()];
                        var dateShow = date.toLocaleString("id-ID", {
                                            year: "numeric",
                                            month: "long",
                                            day: "2-digit",
                                            hour: "2-digit",
                                            minute: "2-digit",
                                            second: "2-digit",
                                            hour12: false
                                        });
                        var fullDateShow = dayName + ', ' + dateShow;
                        $('#tgl' + spotId).text(fullDateShow);
                    } else {
                        $('#nama' + spotId).text('');
                        $('#val' + spotId).text('Data Kosong');
                        $('#tgl' + spotId).text('');
                    }
                    
                    const gauge = Highcharts.charts.find(chart => chart.renderTo.id === 'gauge-' + spotId);
                    if (gauge) {
                        gauge.series[0].setData([psiValues.length > 0 ? parseFloat(parseFloat(psiValues[psiValues.length - 1]).toFixed(2)) : 0], true);
                        gauge.redraw();
                    }
                    
                    if (spotId != 1 && spotId != 9){
                        updateBattery(batt, spotId, batt);
                    }
                } else {
                    let battText = "";
                    if (!batt) {
                        battText = 'Tidak ada Data';
                    } else {
                        battText = batt + "%";
                    }
                    
                    var containerHtml = `
                        <div class="row mx-2" id="row-${spotId}">
                            <div class="col-md-4 mt-3">
                                <figure class="highcharts-figure">
                                    <div id="gauge-${spotId}"></div>
                                </figure>
                                <div class="card text-center">
                                    <div style="background: linear-gradient(to left, #0b1b36 0%, #ffffff 100%);" class="card-body">
                                        ${spotId !== 1 && spotId !== 9 ? `
                                        <div class="battery-row" style="display: flex; flex-flow: row wrap; justify-content: flex-end">
                                            <div style="display: flex; flex-flow: row wrap; justify-content: flex-end">
                                                <p style="color: white; margin: 0; padding-top: 5px; padding-right: 10px;">${battText}</p>
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
                                        <p style="color: black" class="card-text my-3" id="val${spotId}"> PSI</p>
                                        <p style="color: black" class="mx-3 my-2" id="tgl${spotId}"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="chartCard">
                                    <div style="background: linear-gradient(to left, #0b1b36 0%, #ffffff 100%); border-color: white; color: white" class="chartBox">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <canvas id="myChart-${spotId}"></canvas>
                                            </div>
                                            <div class="col-md-1 d-flex flex-column">
                                                <button style="background-color: rgba(0, 0, 0, 0);" id="editButton-${spotId}" class="editButton mb-2" data-spot="${spotId}" data-label="${spotName}" data-labelY="${labelY}" data-minY="${minY}" data-maxY="${maxY}">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                <button style="background-color: rgba(0, 0, 0, 0);" id="combineButton-${spotId}" class="combineButton" data-spot="${spotId}" data-date="${date}">
                                                    <i class="fa fa-line-chart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    $('.row1').append(containerHtml);
                    
                    if (spotId != 1 && spotId != 9){
                        updateBattery(batt, spotId, batt);
                    }

                    Highcharts.chart('gauge-' + spotId, {
                        chart: {
                            type: 'gauge',
                            backgroundColor: {
                                radialGradient: { r: 1, cx: 1 },
                                stops: [
                                  [0, '#0b1b36'],
                                  [1, '#ffffff']
                                ]
                            },
                            plotBackgroundImage: null,
                            plotBorderWidth: 0,
                            plotShadow: false,
                            height: '100%',
                            borderRadius: 500,
                            padding: 10,
                        },
                        exporting: {
                            enabled: false
                        },
                        credits: {
                            enabled: false
                        },
                        title: null,
                        pane: {
                            startAngle: -150,
                            endAngle: 150,
                            background: null,
                        },
                        tooltip: {
                            enabled: false
                        },
                        yAxis: {
                            min: 0,
                            max: maxY,
                            tickPixelInterval: 200,
                            tickPosition: 'inside',
                            tickColor: 'black',
                            tickLength: 20,
                            tickWidth: .5,
                            tickInterval: (maxY / 20),
                            minorTicks: false,
                            labels: {
                                distance: 20,
                                style: {
                                    fontSize: '14px',
                                    color: 'black'
                                }
                            },
                            lineWidth: 0,
                        },
                        series: [{
                            name: 'Pressure',
                            data: [psiValues.length > 0 ? parseFloat(parseFloat(psiValues[psiValues.length - 1]).toFixed(2)) : 0],
                            tooltip: {
                                valueSuffix: ' PSI'
                            },
                            dataLabels: {
                                format: "{y} PSI<br/>"+spotName,
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
                            pivot: {
                                backgroundColor: 'white',
                                radius: 6
                            }

                        }]
                    });

                    var ctx = document.getElementById('myChart-' + spotId).getContext('2d');
                    var chartData = {
                        labels: timestamps,
                        datasets: [{
                            label: spotName,
                            data: psiValues,
                            backgroundColor: 'rgba(107, 13, 18, 1)',
                            borderColor: 'rgba(107, 13, 18, 1)',
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
                        // layout: {
                        //     padding: {
                        //         right: 50
                        //     }
                        // },
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'minute',
                                    displayFormats: {
                                        minute: 'HH:mm'
                                    },
                                },
                                title: {
                                    display: true,
                                    text: 'WAKTU (WIB)',
                                    color: 'black',
                                    font: {
                                        size: 14
                                    }
                                    
                                },
                                ticks: {
                                    color: 'black'
                                },
                                grid: {
                                    color: 'rgba(250,250,250,0.25)',
                                }
                            },
                            y: {
                                beginAtZero: true,
                                min: minY,
                                max: maxY,
                                title: {
                                    display: true,
                                    text: labelY,
                                    color: 'black',
                                    font: {
                                        size: 14
                                    }
                                },
                                ticks: {
                                    color: 'black'
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
                                        size: 28,
                                        weight: 'bold' 
                                    },
                                    color: 'black'
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
                            // datalabels: {
                            //     align: 'right',
                            //     display: function(context) {
                            //         return (context.dataIndex === context.dataset.data.length - 1)
                            //     },
                            //     padding: {
                            //         right: '0'
                            //     },
                            //     color: 'white',
                            //     font: {
                            //         size: 14,
                            //     },
                            //     formatter: function(value, context) {
                            //         return context.dataset.data[context.dataset.data.length - 1].toFixed(2) + '\n' + avg.toFixed(2);
                            //     }
                            // }
                        }
                    };
                    window['myChart-' + spotId] = new Chart(ctx, {
                        type: 'line',
                        data: chartData,
                        // plugins: [ChartDataLabels],
                        options: chartOptions
                    });

                    if (timestamps.length!=0){
                        $('#nama' + spotId).text(window['myChart-' + spotId].config.data.datasets[0].label);
                        $('#val' + spotId).text(psiValues[psiValues.length - 1] + ' PSI');
                        var date = new Date(timestamps[timestamps.length - 1]);
                        var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        var dayName = days[date.getDay()];
                        var dateShow = date.toLocaleString("id-ID", {
                                            year: "numeric",
                                            month: "long",
                                            day: "2-digit",
                                            hour: "2-digit",
                                            minute: "2-digit",
                                            second: "2-digit",
                                            hour12: false
                                        });
                        var fullDateShow = dayName + ', ' + dateShow;
                        $('#tgl' + spotId).text(fullDateShow);
                    } else {
                        $('#nama' + spotId).text('');
                        $('#val' + spotId).text('Data Kosong');
                        $('#tgl' + spotId).text('');
                    }

                    document.getElementById('myChart-' + spotId).addEventListener('click', function(event) {
                        const points = window['myChart-' + spotId].getElementsAtEventForMode(event, 'nearest', {
                            intersect: true
                        }, true);
                        if (points.length) {
                            const datasetIndex = points[0].datasetIndex;
                            const index = points[0].index;
                            const nama = window['myChart-' + spotId].data.datasets[0].label;
                            const label = window['myChart-' + spotId].data.labels[index];
                            const value = points[0].element.$context.parsed.y;
                            console.log(value);
                            if (timestamps.length!=0){
                                $('#val' + spotId).text(value + " PSI");
                                var date = new Date(points[0].element.$context.parsed.x);
                                var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                var dayName = days[date.getDay()];
                                var dateShow = date.toLocaleString("id-ID", {
                                                    year: "numeric",
                                                    month: "long",
                                                    day: "2-digit",
                                                    hour: "2-digit",
                                                    minute: "2-digit",
                                                    second: "2-digit",
                                                    hour12: false
                                                });
                                var fullDateShow = dayName + ', ' + dateShow;
                                $('#tgl' + spotId).text(fullDateShow);
                            } else {
                                $('#val' + spotId).text('Data Kosong');
                                $('#tgl' + spotId).text('');
                            }
                        }
                    });
                    $('#editButton-' + spotId).click(function() {
                        var spotId = $(this).data('spot');
                        var label = $(this).data('label');
                        var labelY = $(this).data('labely');
                        var minY = $(this).data('miny');
                        var maxY = $(this).data('maxy');
                        $('#label').val(label);
                        $('#labelY').val(labelY);
                        $('#minY').val(minY);
                        $('#maxY').val(maxY);
                        $('#spot').val(spotId);
                    });
                    
                    $('#combineButton-' + spotId).click(function() {
                        var spotId = $(this).data('spot');
                        var date = $('#date').val();
                        console.log(date);
                        if (!date || !spotId) {
                            alert('Please fill the date and spot fields.');
                            return;
                        }
                        if (spotId && date){
                            window.open('{{ route('admin.combine2') }}' + '?date=' + date + '&spot=' + spotId, '_blank');
                            // console.log(date);
                        }
                    });
                }
                currentCharts[spotId] = true;
                renderedSpots.push(spotId);
            });
        }
    </script>
</body>
</html>
