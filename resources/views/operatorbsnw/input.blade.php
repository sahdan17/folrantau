@php
use Carbon\Carbon;
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        li {
            list-style-type: none;
        }

        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
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

        .input {
            margin-top: 120px;
            margin-inline: 40px;
            width: 25vw;
        }

        .form-group {
            margin-top: 10px;
        }

        .form-control {
            margin-top: 5px;
        }

        .submit {
            margin-top: 20px;
        }

        .custom {
            margin-top: 5px;
            font-size: 14px;
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body style="background-color: white">
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
                    <li class="nav-item">
                        <a class="nav-link active disabled" style="color: white" aria-current="page" href="/inputBSNW">Input BS&W</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: white" aria-current="page" href="/getBSNW">Report BS&W</a>
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

    <div class="input">
        <form id="bswForm" class="forminput">
            @csrf
            <div class="form-group">
                <label for="date">Tanggal:</label>
                <input type="date" class="form-control" id="date" name="date" style="width: 25vw" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
            </div>
            <div class="form-group">
                <label for="nama">Jam:</label><br>
                <input class="form-control" type="time" id="time" name="time" style="width: 25vw" value="{{ \Carbon\Carbon::now()->format('H:i') }}">
            </div>
            <div class="form-group">
                <label for="nama">Asal Trunkline:</label><br>
                <select id="line" name="line" class="form-control" style="width: 25vw">
                    <option value="" disabled selected>Pilih asal trunkline</option>
                    <option value="KAS">KAS</option>
                    <option value="BJG">BJG</option>
                    <option value="TPN">TPN</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nama">Persentase BS&W:</label><br>
                <input class="form-control" type="text" id="percent" name="percent" style="width: 25vw" placeholder="cth: 0.5">
            </div>
            <div class="form-group">
                <label for="nama">Asal Tangki:</label><br>
                <select id="tank" name="tank" class="form-control" style="width: 25vw">
                    <option value="" disabled selected>Pilih asal tangki</option>
                    <option value="T-001">T-001</option>
                    <option value="T-002">T-002</option>
                    <option value="T-003">T-003</option>
                    <option value="T-004">T-004</option>
                    <option value="T-005">T-005</option>
                    <option value="T-006">T-006</option>
                    <option value="T-007">T-007</option>
                    <option value="T-008">T-008</option>
                    <option value="T-009">T-009</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nama">Asal Struktur:</label><br>
                <input type="text" id="structure" name="structure" class="form-control" style="width: 25vw; margin-top: 5px;" placeholder="cth: PPS, KTT">
            </div>

            <div class="submit text-center">
                <button style="background-color: #0b1b36; border-color: white;" type="submit" class="btn btn-primary">Input Data BS&W</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            $('#bswForm').submit(function(e) {
                e.preventDefault();
                
                console.log($(this).serialize());

                $.ajax({
                    type: 'POST',
                    url: '/inputBSNW',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert('Data berhasil dikirim!');
                        $('#percent').val('');
                    },
                    error: function(error) {
                        console.log(error);
                        alert('Terjadi kesalahan saat mengirim data');
                    }
                });
            });
        });
    </script>
</body>
</html>