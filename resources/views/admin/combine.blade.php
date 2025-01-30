@php
    use Carbon\Carbon;
    $formattedDate = Carbon::parse($selectedDate)->locale('id')->translatedFormat('d F Y');
    
    if (count($selectedSpot) > 1) {
        $combinedLabel = $selectedName[$selectedSpot[0]] . ' - ' . $selectedName[$selectedSpot[count($selectedSpot) - 1]];
    } else {
        $combinedLabel = $selectedName[$selectedSpot[0]];
    }
@endphp

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

        li {
            list-style-type: none;
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body style="background-color: white">
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
                                <li><a class="dropdown-item" style="color: black" href="/viewTable">History Data</a></li>
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
                                <p style="color: black; margin: 0; text-align: center; font-weight: bold; font-size: 12px;">Field Jambi</p>
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
                        <p style="color: black; margin: 0; text-align: center; font-weight: bold; font-size: 12px;">Field Jambi</p>
                    </div>
                </div>                    
            </div>            
        </div>
    </nav>

    <div style="text-align: center; margin-inline: 350px; margin-top: 150px; background-color: #0b1b36; border-color: white; color: white; padding: 20px">
        <p style="font-size: 24px; padding: 0; margin: 0">{{ $combinedLabel }}</p>
        <p style="font-size: 20px; padding: 0; margin: 0">{{ $formattedDate }}</p>
        <canvas id="myChart"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
        
        const ctx = document.getElementById('myChart').getContext('2d');
    
        const selectedSpot = @json($selectedSpot);
        const selectedName = @json($selectedName);
        const psiValues = @json($psiValues);
        const timestamps = @json($timestamps);
        // timestamps.forEach(function(d, p) {
        //     // p adalah index (posisi)
        //     // d adalah nilai data
        //     if ($d > '2024-09-27 10:00'){
        //         console.log($d);
        //     }
        // });
        
        displayChart();

        function displayChart(){
            const colors = [
              'rgba(230, 126, 34, 1)',  // Orange
              'rgba(52, 152, 219, 1)',  // Blue
              'rgba(46, 204, 113, 1)',  // Green
              'rgba(155, 89, 182, 1)',  // Purple
              'rgba(241, 196, 15, 1)',  // Yellow
              'rgba(231, 76, 60, 1)',   // Red
              'rgba(26, 188, 156, 1)',  // Turquoise
              'rgba(192, 57, 43, 1)',   // Dark Red
              'rgba(236, 240, 241, 1)'  // Light Gray
            ];
            
            const combinedLabel = selectedSpot.map(spotId => selectedName[spotId]).join(' - ');
        
            const datasets = selectedSpot.map((spotId, index) => ({
                label: `${selectedName[spotId]}`,
                data: timestamps[spotId].map((timestamp, idx) => ({
                    x: timestamp,
                    y: psiValues[spotId][idx]
                })),
                spanGaps: true,
                backgroundColor: colors[index % colors.length],
                borderColor: colors[index % colors.length],
                borderWidth: 1,
                tension: 1,
                pointRadius: 0,
            }));
        
            new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: datasets
                },
                // plugins: [ChartDataLabels],
                options: {
                    elements: {
                        point:{
                        radius: 0
                        }
                    },
                    // layout: {
                    //     padding: {
                    //         right: 50
                    //     }
                    // },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'PRESSURE (PSI)',
                                color: 'rgba(250,250,250,1)',
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                color: 'rgba(250,250,250,1)'
                            },
                            grid: {
                                color: 'rgba(250,250,250,0.25)'
                            }
                        },
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
                                color: 'rgba(250,250,250,1)',
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                color: 'rgba(250,250,250,1)'
                            },
                            grid: {
                                color: 'rgba(250,250,250,0.25)'
                            }
                        },
                    },
                    plugins: {
                        legend: {
                            // title: {
                            //     display: true,
                            //     text: `Title Line 1\n${combinedLabel}`,
                            //     font: {
                            //         size: 24
                            //     },
                            //     color: 'rgba(250,250,250,1)'
                            // },
                            // position: 'right',
                            labels: {                            
                                boxHeight: 4,
                                boxWidth: 15,
                                color: 'rgba(250,250,250,1)'
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
                                
                        //         return (context.dataIndex == parseFloat(parseFloat(context.dataset.data.length - 1).toFixed(2)))
                        //     },
                        //     padding: {
                        //         right: '0'
                        //     },
                        //     color: 'white',
                        //     font: {
                        //         size: 14,
                        //     },
                        //     formatter: function(value, context) {
                        //         var spotId = selectedSpot[context.datasetIndex];
                        //         // return value.y + '\n' + avg[spotId].toFixed(2);
                        //         return value.y;
                        //     }
                        // }
                    }
                }
            });
        }
    </script>
      
</body>
</html>