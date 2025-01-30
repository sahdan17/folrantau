<!DOCTYPE html>
<html>
<head>
    <style>
        li {
            list-style-type: none;
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
        
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
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
        }
    </style>
    
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
</head>
<body>
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
                            <a class="nav-link active disabled" style="color: black" href="list">Download Data</a>
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
    
    <div style="margin-top: 130px">
        <table class="table mx-4 my-3 col-md-3" style="width: 95%;">
            <tr>
                <th class="text-center">Tanggal</th>
                @foreach ($spots as $key => $spot)
                    <th class="text-center">{{ $spot->namaSpot }}</th>
                @endforeach
                <th class="text-center">Jumlah</th>
                <th class="text-center">Action</th>
            </tr>
            @foreach ($press as $key => $pr)
            <tr>
                <td class="text-center" id="tanggal" style="font-weight: bold">{{ $pr->tanggal }}</td>
                @foreach ($spots as $key => $spot)
                    <td class="text-center col-md-1">{{ $pr->{'idSpot' . $spot->id} }}</td>
                @endforeach
                <td class="text-center"  style="font-weight: bold">{{ $pr->jumlah }}</td>
                <td class="text-center">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('download', $pr->tanggal) }}" method="post" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i></button>
                            </form>
                            <form action="{{ route('delete', $pr->tanggal) }}" method="post" class="d-inline ml-2">
                                @csrf
                                <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    </div>                    
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    
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
    </script>
</body>
</html>
