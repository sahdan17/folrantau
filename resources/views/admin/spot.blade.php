<!DOCTYPE html>
<html>
<head>
    <style>
        .row {
            width: 99%;
        }
        
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
                            <li><a class="dropdown-item" style="color: white" href="/gauge">Gauge</a></li>
                            <li><a class="dropdown-item" style="color: white" href="/viewTable">History Data</a></li>
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color: white" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Tambah Data
                        </a>
                        <ul style="background-color: #265eb5; border-color: white" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item disabled" style="color: white" href="getField">Tambah Field</a></li>
                            <li><a class="dropdown-item" style="color: white" href="getLine">Tambah Jalur</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: white" href="list">Download Data</a>
                    </li>
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
    
    <div class="row" style="margin-top: 100px">
        <div class="form mx-5 mt-3 col-md-3">
            <form action="/createField" method="post">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama Field:</label><br>
                    <input class="form-control" type="text" id="namaSpot" name="namaSpot" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Lokasi Field:</label><br>
                    <input class="form-control" type="text" id="lokasiSpot" name="lokasiSpot" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Kode Field:</label><br>
                    <input class="form-control" type="text" id="codeSpot" name="codeSpot" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Minimal Value Pressure:</label><br>
                    <input class="form-control" type="text" id="minValue" name="minValue" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Stop Value Pressure:</label><br>
                    <input class="form-control" type="text" id="stopValue" name="stopValue" style="width: 25vw">
                </div>
    
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary d-grid gap-2 col-5 mx-auto my-3">Tambah Field</button>
                </div>
            </form>
        </div>
        <div class="col-md-1"></div>
        <table class="table mx-4 my-3 col-md-3" style="width: 50%">
            <tr>
                <th class="col-md-1 text-center">No.</th>
                <th class="col-md-4 text-center">Nama Field</th>
                <th class="col-md-3 text-center">Kode Field</th>
                <th class="col-md-2 text-center">Min Value</th>
                <th class="col-md-2 text-center">Stop Value</th>
            </tr>
            @foreach ($spots as $key => $spot)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">{{ $spot->namaSpot }}</td>
                <td class="text-center">{{ $spot->codeSpot }}</td>
                <td class="text-center">{{ $spot->minValue }}</td>
                <td class="text-center">{{ $spot->stopValue }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
