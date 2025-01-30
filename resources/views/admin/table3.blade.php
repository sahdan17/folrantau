<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }
        
        .table-container {
            margin-inline: 20px;
        }

        table {
            font-size: 14px;
        }
        
        .table thead th {
            position: sticky;
            top: 115px;
            background-color: white;
            z-index: 10;
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        }
        .hidden-row {
            display: none;
        }

        .interval-row {
            cursor: pointer;
            background-color: #f0f0f0;
        }

        .dropdown-container {
            margin-block: 10px;
            margin-inline: 20px;
            font-size: 16px;
            width: 25%;
        }

        .loader {
            width: 50px;
            aspect-ratio: 1;
            border-radius: 50%;
            background: 
                radial-gradient(farthest-side,#3516ff 94%,#0000) top/8px 8px no-repeat,
                conic-gradient(#0000 30%,#3516ff);
            -webkit-mask: radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);
            animation: l13 1s infinite linear;
            position: absolute;
            left: 50%;
            top: 50%;
            display: none;
        }
        
        @keyframes l13{ 
            100%{transform: rotate(1turn)}
        }

        .loading-active .table-container {
            pointer-events: none;
            opacity: 0.5;
        }

        .content {
            margin-top: 125px;
        }

        label {
            font-weight: 600;
        }  padding-bottom: 5px;
        }
    
        table{
            margin: 0 auto;
        }
        
        .data-summary-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .max-average {
            background-color: #4caf50;
            color: green;
            font-weight: bold;
        }
        .min-average {
            background-color: #f44336;
            color: red;
            font-weight: bold;
        }
    </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="loader"></div>
    
    @extends('admin.app')

    @section('title', 'FOL History')

    <div class="content">
        <div class="dropdown-container">
            <label for="interval-select">Pilih Rentang Data:</label>
            <select name="interval-select" id="interval-select" class="form-control">
                <option value="1">1 menit</option>
                <option value="2">2 menit</option>
                <option value="3">3 menit</option>
                <option value="4">4 menit</option>
                <option value="5">5 menit</option>
                <option value="15">15 menit</option>
                <option value="30">30 menit</option>
                <option value="60" selected>60 menit</option>
                <!--<option value="90">90 menit</option>-->
                <!--<option value="120">120 menit</option>-->
            </select>
        </div>
    
        <div class="dropdown-container">
            <label for="date-select">Pilih Tanggal 1:</label>        
            <input type="date" class="form-control" id="date-select" name="date-select">
        </div>
        
        <div class="dropdown-container">
            <label for="date-select">Pilih Tanggal 2:</label>        
            <input type="date" class="form-control" id="date-select2" name="date-select2">
        </div>
        
        <div class="data-summary-container">
            <div style="width: 1000px; background-color: white; min-width: 0px;">
            <!--<div>-->
                <canvas id="myChart"></canvas>
            </div>
            
            <div>
                <table class="table table-bordered text-center table1"></table>
            </div>
            
            <div class="radio1"></div>
            <div class="radio"></div>
            
            <div class="button1"></div>
            <div>
                <table class="table table-bordered text-center table4"></table>
            </div>
            <div>
                <table class="table table-bordered text-center table3"></table>
            </div>
            
            <div class="button2"></div>
        </div>
    
        <div class="table-container">
            <table class="table table-bordered text-center table2"></table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            const dateInput = document.getElementById('date-select');
            const dateInput2 = document.getElementById('date-select2');
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            dateInput.value = formattedDate;
            dateInput2.value = formattedDate;

            let date = new Date().toISOString().split('T')[0];
            let date2 = new Date().toISOString().split('T')[0];
            let interval = 60;

            getTableData(interval, date, date2);

            $('#interval-select').on('change', function() {
                let selectedInterval = parseInt($(this).val(), 10);
                interval = selectedInterval;
                resetDisplay();
                getTableData(selectedInterval, date, date2);
            });

            $('#date-select').on('change', function() {
                let selectedDate = $(this).val();
                date = selectedDate;
                resetDisplay();
                getTableData(interval, selectedDate, date2);
            });
            
            $('#date-select2').on('change', function() {
                let selectedDate = $(this).val();
                date2 = selectedDate;
                resetDisplay();
                getTableData(interval, date, selectedDate);
            });
        });
        
        let myChart = null;
        let checkedIntervalsNormal = [];
        let checkedIntervalsDrop = [];
        let normal = null;
        let dropConst = null;
        let drop = {};
        
        function resetDisplay() {
            if (myChart !== null) {
                myChart.destroy();
                myChart = null;
            }
                
            $('.table1').html('');
            $('.radio1').html('');
            $('.radio').html('');
            $('.button1').html('');
            $('.table4').html('');
            $('.table3').html('');
            $('.button2').html('');
                        
            checkedIntervalsNormal = [];
            checkedIntervalsDrop = [];
            normal = null;
            dropConst = null;
            drop = {};
        }

        function getTableData(interval, tgl, tgl2) {
            $('.loader').show();
            $('body').addClass('loading-active');

            $.ajax({
                url: 'https://folpertaminafieldrantau.com/getHistoryData2',
                method: 'POST',
                dataType: 'json',
                data: {
                    date1: tgl,
                    date2: tgl2
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    // console.log(response);
                    const date = response.selectedDate;
                    // const date2 = response.selectedDate[1];
                    let press = {};
                    let avg = {};
                    
                    let btn1 = "";
                    let spotName = [];
                    
                    for (let dd = 0; dd < date.length; dd++) {
                        for (let hour = 0; hour < 24; hour++) {
                            for (let minute = 0; minute < 60; minute += interval) {
                                let start = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                                let endMinute = minute + interval;
                                let endHour = hour;
                                if (endMinute >= 60) {
                                    endMinute = 0;
                                    endHour += 1;
                                }
                                let end = `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;
                                press[`${date[dd]} ${start}-${end}`] = [];
    
                                response.spots.forEach(spot => {
                                    press[`${date[dd]} ${start}-${end}`][spot.id] = []; 
                                });
                            }
                        }
                    }
                    // console.log(press);
                    
                    for (let dd = 0; dd < date.length; dd++) {
                        response.pressures.forEach(element => {
                            for (let hour = 0; hour < 24; hour++) {
                                for (let minute = 0; minute < 60; minute += interval) {
                                    let start = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                                    let endMinute = minute + interval;
                                    let endHour = hour;
                                    if (endMinute >= 60) {
                                        endMinute = 0;
                                        endHour += 1;
                                    }
                                    let end = `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;
    
                                    if (element.timestamp >= `${date[dd]} ${start}` && element.timestamp < `${date[dd]} ${end}`) {
                                        let time = element.timestamp.slice(11, 19);
                                        press[`${date[dd]} ${start}-${end}`][element.idSpot].push({
                                            psiValue: parseFloat(element.psiValue).toFixed(2),
                                            timestamp: time
                                        });
                                        break;
                                    }
                                }
                            }
                        })
                    };
                    console.log(press);
                    
                    function createCheckboxes(interval) {
                        return `
                            <label style="display: block; font-size: 12px;">
                                Normal: <input type="checkbox" class="normal-checkbox" data-interval="${interval}" name="normal-${interval}">
                            </label>
                            <label style="display: block; font-size: 12px;">
                                Drop: <input type="checkbox" class="drop-checkbox" data-interval="${interval}" name="drop-${interval}">
                            </label>
                        `;
                    }

                    let table = '<thead><tr><th>Interval</th><th>Check</th>';
                    let tab = '<thead><tr><th></th>';
                    let tabNormal = '<thead><tr id="row-normal"><th>Normal</th>';
                    let tabDrop = '<thead><tr id="row-drop"><th>Drop</th>';

                    response.spots.forEach(spot => {
                        spotName.push(spot.namaSpot);
                        table += `<th colspan="2">${spot.namaSpot}</th>`;
                        tab += `<th>${spot.namaSpot}</th>`;
                        tabNormal += `<td>N/A</td>`;
                        tabDrop += `<td>N/A</td>`;
                    });

                    table += '</tr></thead><tbody>';
                    tab += '</tr></thead><tbody>';

                    let index = 0;
                    let maxPerSpot = {};
                    let minPerSpot = {};
                    
                    for (let interval in press) {
                        response.spots.forEach(spot => {
                            let psiValues = press[interval][spot.id].map(data => parseFloat(data.psiValue));
                            if (psiValues.length > 0) {
                                let sum = psiValues.reduce((a, b) => a + b, 0);
                                let average = sum / psiValues.length;
                    
                                // Cek nilai maksimum per spot
                                if (!maxPerSpot[spot.id] || average > maxPerSpot[spot.id].value) {
                                    maxPerSpot[spot.id] = { value: average, interval: interval };
                                }
                    
                                // Cek nilai minimum per spot
                                if (!minPerSpot[spot.id] || average < minPerSpot[spot.id].value) {
                                    minPerSpot[spot.id] = { value: average, interval: interval };
                                }
                            }
                        });
                    }
                    
                    // Iterasi kedua untuk membangun tabel dengan tanda pada nilai tertinggi dan terendah
                    for (let interval in press) {
                        avg[interval] = {};
                        let maxLength = 0;
                        
                        response.spots.forEach(spot => {
                            maxLength = Math.max(maxLength, press[interval][spot.id].length);
                        });
                    
                        table += `
                            <tr>
                                <th class="interval-row" data-index="${index}">${interval}</th>
                                <td>${createCheckboxes(interval)}</td> <!-- Checkbox Normal/Drop -->
                        `;
                    
                        response.spots.forEach(spot => {
                            let text = '';
                            let psiValues = press[interval][spot.id].map(data => parseFloat(data.psiValue));
                            if (psiValues.length > 0) {
                                let sum = psiValues.reduce((a, b) => a + b, 0);
                                let average = sum / psiValues.length;
                                avg[interval][spot.id] = average;
                    
                                // Tambahkan kelas jika ini adalah nilai tertinggi atau terendah
                                let cellClass = "";
                                if (maxPerSpot[spot.id].interval === interval) {
                                    cellClass = "max-average"; // Tertinggi
                                    text = 'Tertinggi';
                                }
                                if (minPerSpot[spot.id].interval === interval) {
                                    cellClass = "min-average"; // Terendah
                                    text = 'Terendah';
                                }
                    
                                table += `<th colspan="2" class="${cellClass}">${average.toFixed(2)} ${text}</th>`;
                            } else {
                                avg[interval][spot.id] = 0;
                                table += `<th colspan="2">N/A</th>`;
                            }
                        });
                    
                        table += '</tr>';
                        
                        let firstRow = `<tr class="hidden-row hidden-row-${index}"><td></td><td></td>`;
                        response.spots.forEach(spot => {
                            firstRow += `<th>Pressure<br>(PSI)</th><th>Time<br>(WIB)</th>`;
                        });
                        firstRow += '</tr>';                        
                        table += firstRow;
                        
                        for (let i = 0; i < maxLength; i++) {
                            let row = `<tr class="hidden-row hidden-row-${index}"><td></td><td></td>`;
                            response.spots.forEach(spot => {
                                if (i < press[interval][spot.id].length) {
                                    let data = press[interval][spot.id][i];
                                    row += `<td>${data.psiValue}</td><td>${data.timestamp}</td>`;
                                } else {
                                    row += '<td></td><td></td>';
                                }
                            });
                            row += '</tr>';
                            table += row;
                        }
                        index++;
                    }
                    
                    radio = `<label><input type="radio" name="mode" value="static"> Static</label>
                    <label><input type="radio" name="mode" value="dynamic" checked> Dynamic</label>`;
                    radio1 = `<label><input type="checkbox" name="predType" value="single" id="singleCheckbox"> Metode Regresi</label>
                    <label><input type="checkbox" name="predType" value="segmen" id="segmenCheckbox"> Metode Segmen</label>`;
                    btn1 = `<div class="button-container" style="margin: 20px;"><button id="predict-button" class="btn btn-primary">Prediksi</button></div>`;

                    table += '</tbody>';
                    $('.table2').html(table);
                    
                    let responseData;
                    let displayHelpLine = false;
                    let valueHelpLines = [];
                    
                    function chart(normal, drop, dropConst) {
                        if (myChart !== null) {
                            myChart.destroy();
                        }
                        
                        if (normal === null) {
                            normal = [];
                        } else {
                            normal = Object.values(normal);
                        }
                        
                        if (dropConst === null) {
                            dropConst = [];
                        } else {
                            dropConst = Object.values(dropConst);
                        }
                        
                        if (drop === null) {
                            drop = [];
                        } else {
                            drop = Object.values(drop);
                        }
                        
                        // const valueHelpLine = 2.15;
                        
                        const spotsName = ['0 KM', '8.3 KM', '15.9 KM', '24.9 KM', '30.8 KM', '38.9 KM', '44.5 KM', '56.5 KM', '63 KM'];
                        const spotDistances = [0, 8.3, 15.9, 24.9, 30.8, 38.9, 44.5, 56.5, 63];
                        const minDistance = Math.min(...spotDistances);
                        const maxDistance = Math.max(...spotDistances);
                        const stepSize = (maxDistance - minDistance) / (spotDistances.length - 1);
                        const colors = ['rgba(230, 126, 34, 1)', 'rgba(231, 76, 60, 1)', 'rgba(46, 204, 113, 1)', 'rgba(52, 152, 219, 1)'];
                    
                        const normalDataset = {
                            label: 'Normal',
                            data: normal.map((yValue, i) => ({
                                x: spotDistances[i],
                                y: yValue
                            })),
                            backgroundColor: colors[0],
                            borderColor: colors[0],
                            borderWidth: 1,
                            fill: false,
                            tension: 0.5,
                            pointRadius: 3
                        };
                        
                        const dropConstDataset = {
                            label: 'Tolerance Drop',
                            data: dropConst.map((yValue, i) => ({
                                x: spotDistances[i],
                                y: yValue
                            })),
                            backgroundColor: colors[1],
                            borderColor: colors[1],
                            borderWidth: 1,
                            fill: false,
                            tension: 0.5,
                            pointRadius: 3,
                            segment: {
                                borderDash: [5,5]
                            }
                        };
                    
                        const dropDatasets = drop.map((dropSet, index) => {
                            let dropValues = dropSet;
                    
                            dropValues = Object.values(dropValues);
                    
                            return {
                                label: `Drop ${index + 1}`,
                                data: dropValues.map((yValue, i) => ({
                                    x: spotDistances[i],
                                    y: yValue
                                })),
                                backgroundColor: colors[(index + 2) % colors.length],
                                borderColor: colors[(index + 2) % colors.length],
                                borderWidth: 1,
                                fill: false,
                                tension: 0.5,
                                pointRadius: 3
                            };
                        });
                    
                        const datasets = [normalDataset, dropConstDataset, ...dropDatasets];
                    
                        const ctx = document.getElementById('myChart');
                        myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                datasets: datasets
                            },
                            plugins: [{
                                beforeDatasetsDraw(chart, args, options) {
                                    const {
                                        ctx,
                                        chartArea: { top, right, bottom, left, width, height },
                                        scales: { x, y }
                                    } = chart;
                                    
                                    if (!displayHelpLine) {
                                        return;
                                    }
                        
                                    ctx.save();

                                    ctx.save();
                                    ctx.strokeStyle = 'rgba(230, 126, 34, 1)';
                                    ctx.setLineDash([10, 10]);
                                    ctx.font = '12px Arial';
                                    ctx.fillStyle = 'rgba(230, 126, 34, 1)';
                                    ctx.textAlign = 'center';
                                
                                    valueHelpLines.forEach(value => {
                                        const xPosition = x.getPixelForValue(value);
                                
                                        ctx.beginPath();
                                        ctx.moveTo(xPosition, top);
                                        ctx.lineTo(xPosition, bottom);
                                        ctx.stroke();
                                
                                        const text = value + ' KM';
                                        const textX = xPosition;
                                        const textY = bottom + 15;
                                
                                        ctx.fillText(text, textX, textY);
                                    });
                        
                                    ctx.restore();
                                }
                            }],
                            options: {
                                scales: {
                                    x: {
                                        type: 'linear',
                                        title: {
                                            display: true,
                                            text: 'Distance (KM)',
                                            color: 'black'
                                        },
                                        padding: 10,
                                        grid: {
                                            drawTicks: true,
                                            drawOnChartArea: true,
                                            color: (context) => {
                                                const index = spotDistances.indexOf(context.tick.value);
                                                return index !== -1 ? 'rgba(200, 200, 200, 0.8)' : 'rgba(0, 0, 0, 0)'; // Grid for ticks only
                                            }
                                        },
                                        ticks: {
                                            autoSkip: false,
                                            callback: function(value) {
                                                const index = spotDistances.indexOf(value);
                                                return index !== -1 ? spotsName[index] : '';
                                            },
                                            stepSize: .1,
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Pressure (PSI)',
                                            color: 'black'
                                        }
                                    }
                                },
                            }
                        });
                    }
                    
                    let method = '';
                    
                    function mergeValues(single, data) {
                        let mergedArray = [];
                    
                        // Cek apakah single adalah array valid
                        if (Array.isArray(single) && single.length > 0) {
                            single.forEach(value => {
                                if (!isNaN(parseFloat(value))) { // Pastikan nilai bisa di-parse ke angka
                                    mergedArray.push(parseFloat(value));
                                }
                            });
                        }
                    
                        // Cek apakah data adalah array valid
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(rowData => {
                                if (rowData && typeof rowData === "object") {
                                    for (let key in rowData) {
                                        if (rowData.hasOwnProperty(key) && rowData[key] !== "-" && !isNaN(parseFloat(rowData[key]))) {
                                            mergedArray.push(parseFloat(rowData[key]));
                                        }
                                    }
                                }
                            });
                        }
                    
                        return mergedArray;
                    }
                    
                    function predictTable(response) {
                        responseData = response;
                        let predTab = "";
                        let mapsTab = "";
                        let keyTab = [];
                        
                        $('.table4').html('');
                        $('.table3').html('');
                        $('.button2').html('');
                        
                        let single = [];
                        let data = {};
                        
                        if (method == 'single' || method == 'both') {
                            single = response.y['single'];
                            let sMaps = response.maps['single'];
                            
                            console.log(single);
                            
                            mapsTab = '<thead><tr><td></td><th>Metode Regresi</th></tr></thead><tbody>';
                            
                            let idx = 0;
                            single.forEach((rowData, rowIndex) => {
                                mapsTab += `<tr><td>${checkedIntervalsDrop[idx]}</td><td><button class="btn btn-primary" onclick="window.open('${sMaps[rowIndex]}', '_blank')">Maps<br>(${rowData} KM)</button></td></tr>`;
                                idx++;
                            });
                            
                            mapsTab += '</tbody>';
                            
                            $('.table4').html(mapsTab);
                        }
                        
                        if (method == 'segmen' || method == 'both') {
                            data = response.y['segmen'];
                            let maps = response.maps['segmen'];
                            
                            console.log(data);
                            
                            for (let key in data[0]) {
                                if (data[0].hasOwnProperty(key)) {
                                    keyTab.push(key);
                                }
                            }
                            
                            predTab = '<thead><tr><th>Metode Segmen</th>';
                            keyTab.forEach(key => {
                                predTab += `<th>Segmen ${key}</th>`;
                            });
                            predTab += '</tr></thead><tbody>';
                            
                            let idx = 0;
                            data.forEach((rowData, rowIndex) => {
                                predTab += `<tr><td>${checkedIntervalsDrop[idx]}</td>`;
                                keyTab.forEach((key) => {
                                    predTab += `<td>`;
                                    if (maps[rowIndex][key] !== "-") {
                                        predTab += `<button class="btn btn-primary" onclick="window.open('${maps[rowIndex][key]}', '_blank')">Maps<br>(${rowData[key]} KM)</button>`;
                                    } else {
                                        predTab += maps[rowIndex][key];
                                    }
                                    predTab += `</td>`;
                                });
                                predTab += '</tr>';
                                idx++;
                            });
                            
                            predTab += '</tbody>';
                            
                            $('.table3').html(predTab);
                        }
                        
                        valueHelpLines = mergeValues(single, data);
                        
                        displayHelpLine = true;

                        if (myChart) {
                            myChart.update();
                        }
                        
                        let btn2 = `<div class="button-container" style="margin: 20px;">
                                        <button id="wa-button" class="btn btn-primary">Send To WhatsApp</button>
                                    </div>`;
                        $('.button2').html(btn2);
                    }
                    
                    function updateSummaryBoxNormal() {
                        let tabNormal = '';
                        if (normal != null){
                            tabNormal += `<tr id="row-normal"><td style="font-size: 12px">Normal<br>(${checkedIntervalsNormal[0]})</td>`;
                            for (let key in normal) {
                                if (normal.hasOwnProperty(key)) {
                                    let value = parseFloat(normal[key]).toFixed(2);
                                    tabNormal += `<td>${value}</td>`;
                                }
                            }
                            tabNormal += `</tr>`;
                            if ($('#row-normal').length) {
                                $('#row-normal').replaceWith(tabNormal);
                            } else {
                                $('.table1').append(tabNormal);
                            }
                        } else {
                            tabNormal += `<tr id="row-normal"><td>Normal</td>`;
                            for (let key in response.spots) {
                                if (response.spots.hasOwnProperty(key)) {
                                    tabNormal += `<td>N/A</td>`;
                                }
                            }
                            tabNormal += `</tr>`;
                            if ($('#row-normal').length) {
                                $('#row-normal').replaceWith(tabNormal);
                            } else {
                                $('.table1').append(tabNormal);
                            }
                        }
                    }
                    
                    function updateSummaryBoxDrop() {
                        let tabDrop = '';
                        if (Object.keys(drop).length > 0) {
                            let index = 0;
                            for (let dropKey in drop) {
                                if (drop.hasOwnProperty(dropKey)) {
                                    tabDrop += `<tr id="${dropKey}"><td style="font-size: 12px">Drop<br>(${checkedIntervalsDrop[index]})</td>`;
                                    for (let spotKey in drop[dropKey]) {
                                        if (drop[dropKey].hasOwnProperty(spotKey)) {
                                            let value = parseFloat(drop[dropKey][spotKey]).toFixed(2);
                                            tabDrop += `<td>${value}</td>`;
                                        }
                                    }
                                    tabDrop += `</tr>`;
                                    index++;
                                }
                            }
                        
                            $('.table1').find('tr[id^="drop"]').remove();
                            $('.table1').append(tabDrop);
                        } else {
                            tabDrop += `<tr id="row-drop"><td>Drop</td>`;
                            for (let key in response.spots) {
                                if (response.spots.hasOwnProperty(key)) {
                                    tabDrop += `<td>N/A</td>`;
                                }
                            }
                            tabDrop += `</tr>`;
                            
                            if ($('#row-drop').length) {
                                $('#row-drop').replaceWith(tabDrop);
                            } else {
                                $('.table1').append(tabDrop);
                            }
                        }
                    }
                    
                    function checkLimits() {
                        let normalCheckedCount = $('.normal-checkbox:checked').length;
                        let dropCheckedCount = $('.drop-checkbox:checked').length;
        
                        if (normalCheckedCount >= 1) {
                            $('.normal-checkbox:not(:checked)').prop('disabled', true);
                        } else {
                            $('.normal-checkbox').prop('disabled', false);
                        }
                        
                        $('.table1').html(tab);
                        $('.radio1').html(radio1);
                        $('.radio').html(radio);
                        $('.button1').html(btn1);
                        updateSummaryBoxNormal();
                        updateSummaryBoxDrop();
                        
                        chart(normal, drop, dropConst);
                        
                        const segmenCheckbox = document.getElementById('segmenCheckbox');
                        const singleCheckbox = document.getElementById('singleCheckbox');
                        
                        // segmenCheckbox.addEventListener('change', function() {
                        //     if (segmenCheckbox.checked) {
                        //         $('.radio').html(radio);
                        //     } else {
                        //         $('.radio').html('');
                        //     }
                        // });
                    }
                    
                    $('.normal-checkbox').on('change', function() {
                        let intervalClicked = $(this).data('interval');
                    
                        if ($(this).is(':checked')) {
                            normal = avg[intervalClicked];
                            console.log(normal);
                            dropConst = Object.values(normal).map((value) => Math.max(0, value * 0.98));
                            console.log(dropConst);
                            checkedIntervalsNormal.push(intervalClicked);
                        } else {
                            $(`.drop-checkbox[data-interval="${intervalClicked}"]`).prop('disabled', false);
                            
                            const index = checkedIntervalsNormal.indexOf(intervalClicked);
                            if (index > -1) {
                                checkedIntervalsNormal.splice(index, 1);
                            }
                            
                            normal = null;
                            dropConst = null;
                        }
                        
                        checkLimits();
                    });
                    
                    $('.drop-checkbox').on('change', function() {
                        let intervalClicked = $(this).data('interval');
                    
                        if ($(this).is(':checked')) {
                            drop[intervalClicked] = avg[intervalClicked];
                            checkedIntervalsDrop.push(intervalClicked);
                        } else {
                            $(`.normal-checkbox[data-interval="${intervalClicked}"]`).prop('disabled', false);
                            
                            const index = checkedIntervalsDrop.indexOf(intervalClicked);
                            if (index > -1) {
                                checkedIntervalsDrop.splice(index, 1);
                            }

                            delete drop[intervalClicked];
                        }
                        
                    
                        checkLimits();
                    });

                    $('.interval-row').on('click', function() {
                        let index = $(this).data('index');
                        $(`.hidden-row-${index}`).toggle();
                    });
        
                    $(document).on('click', '#predict-button', function() {
                        if (Object.keys(drop).length > 0  && normal != null){
                            let dataDrop = [];
                            let dataNormal = {};
                            let segmen = '';
                            let single = '';
                            let type = '';
                            
                            if (segmenCheckbox.checked) {
                                segmen = 'segmen';
                                type = document.querySelector('input[name="mode"]:checked').value;
                                method = 'segmen';
                            } else {
                                segmen = '';
                                let type = '';
                            }
                            
                            if (singleCheckbox.checked) {
                                single = 'single';
                                type = document.querySelector('input[name="mode"]:checked').value;
                                method = 'single';
                            } else {
                                single = '';
                                let type = '';
                            }
                            
                            if (segmen == 'segmen' && single == 'single') {
                                method = 'both';
                                type = document.querySelector('input[name="mode"]:checked').value;
                            }
                            
                            console.log(method);
                            console.log(type);
                            
                            for (let key in drop) {
                                if (drop.hasOwnProperty(key)) {
                                    let temp = {};
                                    for (let k in drop[key]) {
                                        if (drop[key].hasOwnProperty(k)) {
                                            let value = parseFloat(drop[key][k]).toFixed(2);
                                            temp[k] = value;
                                        }
                                    }
                                    dataDrop.push(temp);
                                }
                            }
                            
                            for (let key in normal) {
                                if (normal.hasOwnProperty(key)) {
                                    let value = parseFloat(normal[key]).toFixed(2);
                                    dataNormal[key] = value;
                                }
                            }
                            
                            $.ajax({
                                url: 'https://folpertaminafieldrantau.com/predictSegmen2',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    normal: dataNormal,
                                    drop: dataDrop,
                                    type: type,
                                    method: method
                                },
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    predictTable(data);
                                },
                                error: function(error) {
                                    console.log('Error:', error);
                                }
                            });
                        }
                    });
                    
                    $(document).on('click', '#wa-button', function() {
                        // console.log(responseData);
                        let segmen = '';
                        let single = '';
                        
                        if (segmenCheckbox.checked) {
                            segmen = 'segmen';
                            method = 'segmen';
                        } else {
                            segmen = '';
                        }
                        
                        if (singleCheckbox.checked) {
                            single = 'single';
                            method = 'single';
                        } else {
                            single = '';
                        }
                        
                        if (segmen == 'segmen' && single == 'single') {
                            method = 'both';
                        }
                        
                        $.ajax({
                            url: 'https://folpertaminafieldrantau.com/sendToWA',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                data: responseData,
                                method: method
                            },
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                // console.log(data);
                            },
                            error: function(error) {
                                console.log('Error:', error);
                            }
                        });
                    });

                    $('.loader').hide();
                    $('body').removeClass('loading-active');
                },
                error: function(error) {
                    console.error('Error fetching data:', error);

                    $('.loader').hide();
                    $('body').removeClass('loading-active');
                }
            });
        }
    </script>
</body>
</html>
