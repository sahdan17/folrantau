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

        li {
            list-style-type: none;
        }

        .content {
            margin-top: 125px;
        }

        label {
            font-weight: 600;
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
            color: black;
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
            border: 1px solid black;
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
            color: black;
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
            
        .navFix {
            display: flex;
            align-items: center;
        }
    
        .small {
            display: none;
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

        @media (max-width: 999px) {
            .navbar-nav {
                display: none;
            }
    
            .hide-on-small-screen {
                display: none;
            }

            .navFix {
                display: block;
                justify-content: space-between;
                width: 100%;
                padding: 0 15px;                
            }

            .navToggler {
                display: flex;
                justify-content: space-between;
                width: 100%;
                padding: 0 15px;
            }
    
            .small {
                display: flex;
                justify-content: center;
                align-items: center;
            }
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

    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #e6e4df; border: 5px solid blue;">
        <div class="container-fluid">
            <div class="align-items-center hide-on-small-screen">
                <div class="ratio-container" style="display: flex; justify-content: center; align-items: center;">
                    <div class="ratio-item ratio-item-5" style="margin-right: 20px;">
                        <img src="{{ asset('images/bumn.png') }}" alt="Gambar 1" style="height: 30px;">
                    </div>
                    <div class="ratio-item ratio-item-5" style="margin-right: 20px;">
                        <img src="{{ asset('images/iog.png') }}" alt="Gambar 1" style="height: 70px;">
                    </div>
                </div>                    
            </div>
            <div class="navFix">
                <div class="navToggler">
                    <li class="nav-item dropdown">
                        <a class="navbar-brand dropdown-toggle" style="color: black" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $user->email }}
                        </a>
                        <ul style="background-color: white; border-color: black" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" style="color: black" href="logout">Logout</a></li>
                        </ul>
                    </li>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse align-items-center" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex align-items-center">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" style="color: black" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Monitoring
                            </a>
                            <ul style="background-color: white; border-color: black" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" style="color: black" href="/">Pressure</a></li>
                                <li><a class="dropdown-item" style="color: black" href="/gauge">Gauge</a></li>
                                <li><a class="dropdown-item" style="color: black" href="/chart">Line Chart</a></li>
                                <li><a class="dropdown-item active disabled" style="color: black" href="/viewTable">History Data</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" style="color: black" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Prediksi Data
                            </a>
                            <ul style="background-color: white; border-color: black" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" style="color: black" href="predLoc">Predict Location</a></li>
                                <li><a class="dropdown-item" style="color: black" href="predLoss">Predict Loss</a></li>
                            </ul>
                        </li>
                        <!--<li class="nav-item dropdown">-->
                        <!--    <a class="nav-link dropdown-toggle" style="color: black" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">-->
                        <!--        Tambah Data-->
                        <!--    </a>-->
                        <!--    <ul style="background-color: white; border-color: black" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">-->
                        <!--        <li><a class="dropdown-item" style="color: black" href="getField">Tambah Field</a></li>-->
                        <!--        <li><a class="dropdown-item" style="color: black" href="getLine">Tambah Jalur</a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->
                        <li class="nav-item">
                            <a class="nav-link" style="color: black" href="list">Download Data</a>
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
                                <div style="--clr: black; --h: 48px" id="s" class="hand">
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
                    <div class="align-items-center small">
                        <div class="ratio-container" style="display: flex; justify-content: center; align-items: center;">
                            <div class="ratio-item" style="margin-right: 20px;">
                                <img src="{{ asset('images/bumn.png') }}" alt="Gambar 1" style="height: 30px;">
                            </div>
                            <div class="ratio-item" style="margin-right: 20px;">
                                <img src="{{ asset('images/iog.png') }}" alt="Gambar 1" style="height: 70px;">
                            </div>
                            <div class="ratio-item" style="margin-right: 20px;">
                                <img src="{{ asset('images/skk.png') }}" alt="Gambar 1" style="height: 70px;">
                            </div>
                            <div class="ratio-item">
                                <img src="{{ asset('images/gambar2.png') }}" alt="Gambar 2" style="height: 50px;">
                                <p style="color: black; margin: 0; text-align: center; font-weight: bold; font-size: 12px;">Zona 1</p>
                                <p style="color: black; margin: 0; text-align: center; font-weight: bold; font-size: 12px;">Field Rantau</p>
                            </div>
                        </div>                    
                    </div>
                </div>
            </div>
            <div class="align-items-center hide-on-small-screen">
                <div class="ratio-container" style="display: flex; justify-content: center; align-items: center;">
                    <div class="ratio-item ratio-item-5" style="margin-right: 20px;">
                        <img src="{{ asset('images/skk.png') }}" alt="Gambar 1" style="height: 70px;">
                    </div>
                    <div class="ratio-item ratio-item-7">
                        <img src="{{ asset('images/gambar2.png') }}" alt="Gambar 2" style="height: 50px;">
                        <p style="color: black; margin: 0; text-align: center; font-weight: bold; font-size: 12px;">Zona 1</p>
                        <p style="color: black; margin: 0; text-align: center; font-weight: bold; font-size: 12px;">Field Rantau</p>
                    </div>
                </div>                    
            </div>            
        </div>
    </nav>

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
            <label for="date-select">Pilih Tanggal:</label>        
            <input type="date" class="form-control" id="date-select" name="date-select">
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

    <script>
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

        $(document).ready(function() {
            const dateInput = document.getElementById('date-select');
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            dateInput.value = formattedDate;

            let date = new Date().toISOString().split('T')[0];
            let interval = 60;

            getTableData(interval, date);

            $('#interval-select').on('change', function() {
                let selectedInterval = parseInt($(this).val(), 10);
                interval = selectedInterval;
                resetDisplay();
                getTableData(selectedInterval, date);
            });

            $('#date-select').on('change', function() {
                let selectedDate = $(this).val();
                date = selectedDate;
                resetDisplay();
                getTableData(interval, selectedDate);
            });
        });
        
        let myChart = null;
        let checkedIntervalsNormal = [];
        let checkedIntervalsDrop = [];
        let normal = null;
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
            drop = {};
        }

        function getTableData(interval, tgl) {
            $('.loader').show();
            $('body').addClass('loading-active');

            $.ajax({
                url: 'https://folrantauold.findingoillosses.com/getHistoryData',
                method: 'POST',
                dataType: 'json',
                data: {
                    date: tgl
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    const date = response.selectedDate;
                    let press = {};
                    let avg = {};
                    
                    let btn1 = "";
                    let spotName = [];

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
                            press[`${start}-${end}`] = [];

                            response.spots.forEach(spot => {
                                press[`${start}-${end}`][spot.id] = []; 
                            });
                        }
                    }

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

                                if (element.timestamp >= `${date} ${start}` && element.timestamp < `${date} ${end}`) {
                                    let time = element.timestamp.slice(11, 19);
                                    press[`${start}-${end}`][element.idSpot].push({
                                        psiValue: parseFloat(element.psiValue).toFixed(2),
                                        timestamp: time
                                    });
                                    break;
                                }
                            }
                        }
                    });
                    
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
                    let maxPerSpot = {}; // { spotId: { value: 0, interval: '' } }
                    let minPerSpot = {}; // { spotId: { value: Infinity, interval: '' } }
                    // let text = '';
                    
                    // Iterasi awal untuk menentukan nilai tertinggi dan terendah per spot
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
                    
                    function chart(normal, drop) {
                        if (myChart !== null) {
                            myChart.destroy();
                        }
                        
                        if (normal === null) {
                            normal = [];
                        } else {
                            normal = Object.values(normal);
                        }
                        
                        if (drop === null) {
                            drop = [];
                        } else {
                            drop = Object.values(drop);
                        }
                        const spotsName = ['0 KM', '8.3 KM', '15.9 KM', '24.9 KM', '30.8 KM', '38.9 KM', '44.5 KM', '56.5 KM', '63 KM'];
                        const colors = ['rgba(230, 126, 34, 1)', 'rgba(52, 152, 219, 1)', 'rgba(46, 204, 113, 1)', 'rgba(231, 76, 60, 1)'];
                    
                        const normalDataset = {
                            label: 'Normal',
                            data: normal.map((yValue, i) => ({
                                x: spotsName[i],
                                y: yValue
                            })),
                            backgroundColor: colors[0],
                            borderColor: colors[0],
                            borderWidth: 1,
                            fill: false,
                            tension: 0.5,
                            pointRadius: 3
                        };
                    
                        const dropDatasets = drop.map((dropSet, index) => {
                            let dropValues = dropSet;
                    
                            dropValues = Object.values(dropValues);
                    
                            return {
                                label: `Drop ${index + 1}`,
                                data: dropValues.map((yValue, i) => ({
                                    x: spotsName[i],
                                    y: yValue
                                })),
                                backgroundColor: colors[(index + 1) % colors.length],
                                borderColor: colors[(index + 1) % colors.length],
                                borderWidth: 1,
                                fill: false,
                                tension: 0.5,
                                pointRadius: 3
                            };
                        });
                    
                        const datasets = [normalDataset, ...dropDatasets];
                    
                        const ctx = document.getElementById('myChart');
                        myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                datasets: datasets
                            },
                            options: {
                                scales: {
                                    x: {
                                        type: 'category',
                                        labels: spotsName,
                                        title: {
                                            display: true,
                                            text: 'Distance (KM)',
                                            color: 'black'
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
                                }
                            }
                        });
                    }
                    
                    let method = '';
                    
                    function predictTable(response) {
                        responseData = response;
                        let predTab = "";
                        let mapsTab = "";
                        let keyTab = [];
                        
                        $('.table4').html('');
                        $('.table3').html('');
                        $('.button2').html('');
                        
                        if (method == 'single' || method == 'both') {
                            let single = response.y['single'];
                            let sMaps = response.maps['single'];
                            
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
                            let data = response.y['segmen'];
                            let maps = response.maps['segmen'];
                            
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
                        
                        chart(normal, drop);
                        
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
                            checkedIntervalsNormal.push(intervalClicked);
                        } else {
                            $(`.drop-checkbox[data-interval="${intervalClicked}"]`).prop('disabled', false);
                            
                            const index = checkedIntervalsNormal.indexOf(intervalClicked);
                            if (index > -1) {
                                checkedIntervalsNormal.splice(index, 1);
                            }
                            
                            normal = null;
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
                                url: 'https://folrantauold.findingoillosses.com/predictSegmen2',
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
                            url: 'https://folrantauold.findingoillosses.com/sendToWA',
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
