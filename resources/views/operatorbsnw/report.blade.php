@php
use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html>
<head>
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
                    <li class="nav-item">
                        <a class="nav-link" style="color: white" aria-current="page" href="/">Input BS&W</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active disabled" style="color: white" aria-current="page" href="/indexBSNW">Report BS&W</a>
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

    <div style="margin-top: 110px">
        <div class="row align-items-end" style="max-width: 50%">
            <div class="col-md-9">
                <div class="mx-3 my-1">
                    <label style="color: black; font-weight: bold" for="date">Select Date:</label>
                    <input style="background-color: white; color: black;" type="date" class="form-control" id="date" name="date"
                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
            </div>            
            <div class="col-md-3">
                <div class="submit">
                    <button style="background-color: #265eb5; border-color: white;" type="submit" id="rekapButton" class="btn btn-primary">Report Data BS&W</button>
                </div>
            </div>            
        </div>
        <div id="dataTabel"></div>
    </div>
</body>

<script>
    $(document).ready(function() {
        fetchData();

        $('#date').change(function() {
            fetchData();
        });

        $('#rekapButton').click(function() {
            var date = $('#date').val();

            if (date) {
                $.ajax({
                    url: '{{ route('report') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { date: date },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        alert('Report berhasil dikirim!');
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            } else {
                alert('Tanggal atau line tidak valid.');
            }
        });
    });

    function fetchData() {
        var date = $('#date').val();
        if (date) {
            $.ajax({
                url: '{{ route('indexBSNW') }}',
                type: 'POST',
                dataType: 'json',
                data: { date: date },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    var tbody = $('#dataTabel');
                    tbody.empty();
                    
                    data.data.forEach((lineData, index) => {
                        var section = `<div style="margin-top: 20px">
                            <div class="mx-4">
                                <p style= "font-size: 20px; font-weight: bold;">MGS ${lineData.line}</p>
                            </div>
                            
                            <table class="table mx-4 col-md-3" style="width: 50%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Waktu Pengukuran (WIB)</th>
                                        <th class="text-center">BS&W (%)</th>
                                        <th class="text-center">Asal Tangki</th>
                                        <th class="text-center">Asal Struktur</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                        
                        lineData.data.forEach((item, idx) => {
                            section += `<tr>
                                <td class="text-center">${idx + 1}</td>
                                <td class="text-center">${item.time}</td>
                                <td class="text-center">${item.percent}</td>
                                <td class="text-center">${item.tank}</td>
                                <td class="text-center">${item.structure}</td>
                            </tr>`;
                        });

                        section += `</tbody>
                            </table>
                            <div class="mx-4" style="margin-bottom: 40px">
                                <p><strong>Average: ${lineData.average}</strong></p>
                            </div>
                        </div>`;

                        tbody.append(section);
                    });
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
    }
</script>
</html>
