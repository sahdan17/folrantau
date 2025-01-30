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

        li {
            list-style-type: none;
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
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
        }
        
        .combineButton {
            background-color: black;
            border: none;
            color: white;
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .select2-container--default .select2-selection--single {
            background-color: #0b1b36 !important; /* Tambahin !important buat nge-override */
            color: white !important; /* Warna teks biar kelihatan */
            border: 1px solid #aaa; /* Kalau mau tambahin border */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white !important; /* Warna teks dalam select */
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #0b1b36 !important; /* Warna waktu di-hover */
            color: white !important; /* Warna teks waktu di-hover */
        }

        .select2-container--default .select2-results > .select2-results__options {
            background-color: #0b1b36 !important; /* Warna background list option */
            color: white !important; /* Warna teks list option */
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
        
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 300px;
            max-width: 300px;
            margin: 1em auto;
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
                /*max-width: 100%;*/
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
                            <li><a class="dropdown-item active disabled" style="color: white" href="/">Pressure</a></li>
                            <li><a class="dropdown-item" style="color: white" href="/gauge">Gauge Chart</a></li>
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
                <label for="spot-select" style="color: #0b1b36" class="form-label">Select Spot:</label>
                <select style="color;" id="spot-select" name="spot[]" class="form-control" multiple="multiple"></select>
            </div>
    
            <button id="showData" type="submit" style="background-color: #0b1b36; border-color: white" class="btn btn-primary d-grid gap-2 col-4 mx-auto my-3">Show Data</button>
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
    <script type="text/javascript">
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
            $('#spot-select').select2();

            $.ajax({
                url: '{{ route('index') }}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    fetchData(data);
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        });

        $('#showData').click(function() {
            const selectedDate = $('#date').val();
            const selectedHour = $('#hour').val();
            const selectedSpots = $('#spot-select').val();

            if (!selectedDate || !selectedHour || !selectedSpots) {
                alert('Please fill all the fields.');
                return;
            }

            $.ajax({
                url: '{{ route('pressure') }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    date: selectedDate,
                    hour: selectedHour,
                    spot: selectedSpots
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    fetchData(data);
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        });

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

        setInterval(loadData, 10000);
        // setInterval(loadData, 5000);

        function loadData() {
            console.log('berhasil');
            const selectedDate = $('#date').val();
            const selectedHour = $('#hour').val();
            const selectedSpots = $('#spot-select').val();

            if (!selectedDate || !selectedHour || !selectedSpots) {
                alert('Please fill all the fields.');
                return;
            }

            $.ajax({
                url: '{{ route('pressure') }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    date: selectedDate,
                    hour: selectedHour,
                    spot: selectedSpots
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    fetchData(data);
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        function fetchData(data) {
            $('#date').val(data.selectedDate);
            $('#hour').val(data.selectedHour);

            var spotSelect = $('#spot-select');
            spotSelect.empty();
            $.each(data.spots, function(index, spot) {
                spotSelect.append(new Option(spot.namaSpot, spot.id));
            });

            spotSelect.val(Object.keys(data.selectedName)).trigger('change');

            var displayFormats;
            let hour;
            if (data.selectedHour == 1 || data.selectedHour == 3 || data.selectedHour == 6) {
                hour = 'minute';
                displayFormats = {
                    minute: 'H:mm'
                };
            } else {
                hour = 'hour';
                displayFormats = {
                    hour: 'H:mm'
                };
            }

            const currentCharts = {};

            $('.row1 .row').each(function() {
                const spotId = this.id.split('-')[1];
                if (spotId !== undefined) {
                    if (!data.selectedSpot.includes(spotId)) {
                        $(this).remove();
                        if (window['myChart-' + spotId]) {
                            window['myChart-' + spotId].destroy();
                            delete window['myChart-' + spotId];
                        }
                    }
                }
            });
            
            var max = [null, 200, 400, 200, 100, 500, 500, 300];

            data.selectedSpot.forEach(function(spotId) {
                var spotName = data.selectedName[spotId];
                var psiValues = data.psiValues[spotId];
                var timestamps = data.timestamps[spotId];
                var labelY = 'Pressure (PSI)';
                var minY = 0;
                var maxY = max[spotId];


                if (window['myChart-' + spotId]) {
                    // Update existing chart
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
                        gauge.series[0].setData([psiValues.length > 0 ? psiValues[psiValues.length - 1] : 0], true);
                    }
                    
                } else {
                    var containerHtml = `
                        <div class="row mx-2" id="row-${spotId}">
                            <div class="col-md-4 mt-3">
                                <figure class="highcharts-figure">
                                    <div id="gauge-${spotId}"></div>
                                </figure>
                                <div class="card text-center">
                                    <div style="background: linear-gradient(to left, #0b1b36 0%, #ffffff 100%);" class="card-body">
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
                                            <div class="col-md-1">
                                                <button style="background-color: rgba(0, 0, 0, 0);" id="editButton-${spotId}" class="editButton" data-spot="${spotId}" data-label="${spotName}" data-labelY="${labelY}" data-minY="${minY}" data-maxY="${maxY}">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                <button style="background-color: rgba(0, 0, 0, 0);" id="combineButton-${spotId}" class="combineButton" data-spot="${spotId}" data-date="${data.selectedDate}">
                                                    <i class="fa fa-line-chart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    $('.row1').append(containerHtml);
                    
                    Highcharts.chart('gauge-' + spotId, {
                        chart: {
                            type: 'gauge',
                            // backgroundColor: '#0b1b36',
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
                            // tickInterval: (maxY > 200 ? maxY == 250 ? 25 : 20 : maxY <= 50 ? 5 : 10),
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
                            data: [psiValues.length > 0 ? psiValues[psiValues.length - 1] : 0],
                            tooltip: {
                                valueSuffix: ' PSI'
                            },
                            dataLabels: {
                                format: "{y} PSI<br/>"+spotName,
                                borderWidth: 0,
                                color: 'white',
                                style: {
                                    fontSize: '16px',
                                    color: 'white'
                                }
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
                            backgroundColor: 'rgba(38, 94, 181, 0)',
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
                                    text: 'Waktu (WIB)',
                                    // color: 'rgba(250,250,250,1)',
                                    color: 'black',
                                },
                                ticks: {
                                    // color: 'rgba(250,250,250,1)',
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
                                    // color: 'rgba(250,250,250,1)',
                                    color: 'black'
                                },
                                ticks: {
                                    // color: 'rgba(250,250,250,1)',
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
                                        size: 50,
                                        weight: 'bold' 
                                    },
                                    // color: 'rgba(250,250,250,1)',
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
                            }
                        }
                    };

                    window['myChart-' + spotId] = new Chart(ctx, {
                        type: 'line',
                        data: chartData,
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
                            const value = window['myChart-' + spotId].data.datasets[datasetIndex].data[index];

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
                        var date = $(this).data('date');

                        if (!date || !spotId) {
                            alert('Please fill the date and spot fields.');
                            return;
                        }

                        if (spotId && date){
                            // window.location.href = '{{ route('admin.combine') }}' + '?date=' + date + '&spot=' + spotId;
                            window.open('{{ route('admin.combine') }}' + '?date=' + date + '&spot=' + spotId, '_blank');
                        }
                    });
                }

                currentCharts[spotId] = true;
            });
        }
    </script>
</body>
</html>
