<!DOCTYPE html>
<html>
<head>
    <style>
        .row {
            width: 99%;
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
    <nav class="navbar navbar-expand-lg navbar-light mx-2">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/">Pressure</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="predLoss">Prediction Loss</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Tambah Data
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                          <li><a class="dropdown-item" href="getField">Tambah Field</a></li>
                          <li><a class="dropdown-item active disabled" href="getLine">Tambah Jalur</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list">Download Data</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="form mx-5 mt-3 col-md-3">
            <form action="/createLine" method="post">
                @csrf
                <div class="form-group">
                    <label for="nama">Field Hulu:</label><br>
                    <select id="spot1" name="spot1" class="form-control">
                        <option value="" selected>Select Field</option>
                        @foreach ($spots as $spot)
                        <option value="{{ $spot->id }}">
                            {{ $spot->namaSpot }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama">Field Hilir:</label><br>
                    <select id="spot2" name="spot2" class="form-control">
                        <option value="" selected>Select Field</option>
                        @foreach ($spots as $spot)
                        <option value="{{ $spot->id }}">
                            {{ $spot->namaSpot }}
                        </option>
                        @endforeach
                    </select>
                </div>
    
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary d-grid gap-2 col-5 mx-auto my-3">Tambah Field</button>
                </div>
            </form>
        </div>
        <div class="col-md-1"></div>
        <table class="table mx-4 my-3 col-md-3" style="width: 25%">
            <tr>
                <th class="col-md-1 text-center">No.</th>
                <th class="col-md-6 text-center">Field Hulu</th>
                <th class="col-md-5 text-center">Field Hilir</th>
            </tr>
            @foreach ($lines as $key => $line)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">{{ $line->spot1 }}</td>
                <td class="text-center">{{ $line->spot2 }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
