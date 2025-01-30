<!DOCTYPE html>
<html>
<head>    
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
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Tambah Data
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                          <li><a class="dropdown-item" href="getField">Tambah Field</a></li>
                          <li><a class="dropdown-item" href="getLine">Tambah Jalur</a></li>
                        </ul>
                    </li>                    
                    <li><a class="nav-link active disabled" href="list">Download Data</a></li>
                </ul>
            </div>
        </div>
    </nav>
        <table class="table mx-4 my-3 col-md-3" style="width: 95%">
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
</body>
</html>
