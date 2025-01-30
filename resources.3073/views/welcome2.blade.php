{{-- @php
    foreach ($selectedSpot as $spot) {
        ${"val".$spot} = end($psiValues[$spot]);
        ${"tgl".$spot} = end($timestamps[$spot]);
        ${"nama".$spot} = $selectedName[$spot];
    }
    foreach ($selectedSpot as $idSpot) {
        $label[$idSpot] = "Field {$selectedName[$idSpot]}";
        $labelY[$idSpot] = "Pressure (PSI)";
        $minY[$idSpot] = 0;
        $maxY[$idSpot] = 300;
    }
    
    $hour = '';
    if ($selectedHour == 24 or $selectedHour == 12){
        $hour = 'hour';
    } else {
        $hour = 'minute';
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
            width: 30vw;
            color: rgba(54, 162, 235, 1);
        }
        
        .chartCustom {
            width: 25vw;
            color: rgba(54, 162, 235, 1);
        }

        .chartCard {
            width: 30vw;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .chartBox {
            width: 800px;
            padding: 20px;
            border-radius: 20px;
            border: solid 3px rgba(54, 162, 235, 1);
            background: white;
            margin-bottom: 20px;
        }

        .card {
            width: 28vw;
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

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light mx-2">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active disabled" aria-current="page" href="/">Pressure</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="list">Download Data</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-6">
            <div class="chartMenu">
                <form action="/pressure" method="POST">
                    @csrf
                    <div class="mx-3 my-1">
                        <label for="date">Select Date:</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $selectedDate }}">
                    </div>    
                    <div class="mx-3 my-1">
                        <label for="hour">Select Hour:</label>
                        <select name="hour" id="hour" class="form-control">
                            @foreach([1, 3, 6, 12, 24] as $value)
                            <option value="{{ $value }}" {{ $value == $selectedHour ? 'selected' : '' }}>{{ $value }}
                                Hour{{ $value > 1 ? 's' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mx-3 my-1">
                        <label for="spot-select" class="form-label">Select Spot</label>
                        <select id="spot-select" name="spot[]" class="form-control" multiple="multiple">
                            @foreach ($spots as $spot)
                            <option value="{{ $spot->id }}" {{ in_array($spot->id, $selectedSpot) ? 'selected' : '' }}>
                                {{ $spot->namaSpot }}
                            </option>
                            @endforeach
                        </select>
                    </div>
    
                    <button type="submit" class="btn btn-primary d-grid gap-2 col-4 mx-auto my-3">Show Data</button>
                </form>
            </div>        
        </div>
        <div class="col-md-6">
            <div class="chartCustom">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="hidden" id="spotId" name="spotId" style="width: 25vw">
                        <label for="label">Label Chart:</label><br>
                        <input class="form-control" type="text" id="label" name="label" style="width: 25vw">
                    </div>
                    <div class="form-group">
                        <label for="labelY">Label Axis Y:</label><br>
                        <input class="form-control" type="text" id="labelY" name="labelY" style="width: 25vw">
                    </div>
                    <div class="form-group">
                        <label for="minY">Min Axis Y:</label><br>
                        <input class="form-control" type="text" id="minY" name="minY" style="width: 25vw">
                    </div>
                    <div class="form-group">
                        <label for="maxY">Max Axis Y:</label><br>
                        <input class="form-control" type="text" id="maxY" name="maxY" style="width: 25vw">
                    </div>
    
                    <button id="customizeChart" type="submit" class="btn btn-primary d-grid gap-2 col-4 mx-auto my-3">Custom</button>
                </form>
            </div>        
        </div>
    </div>
    @foreach ($selectedSpot as $spot)
        <div class="row" id="row1">
            <div class="col-md-6 mt-3">
                <div class="card mx-3 text-center">
                    <div class="card-body">
                        <p class="mx-2 my-2" id="nama{{ $spot }}"></p>
                        <p class="card-text my-3" id="val{{ $spot }}">{{ ${"val".$spot} }} PSI</p>
                        <p class="mx-3 my-2" id="tgl{{ $spot }}">{{ ${"tgl".$spot} }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chartCard">
                    <div class="chartBox">
                        <div class="row">
                            <div class="col-md-11">
                                <canvas id="myChart{{ $spot }}"></canvas>
                            </div>
                            <div class="col-md-1">
                                <button class="editButton"
                                    data-spot="{{ $spot }}" 
                                    data-label="{{ $label[$spot] }}" 
                                    data-labelY="{{ $labelY[$spot] }}" 
                                    data-minY="{{ $minY[$spot] }}" 
                                    data-maxY="{{ $maxY[$spot] }}"><i class="fa fa-pencil"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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
    
        <script>
            $(document).ready(function () {
                $('#spot-select').select2();
        
                @foreach($selectedSpot as $spot)
                const data{{ $spot }} = {
                    labels: {!!json_encode($timestamps[$spot]) !!},
                    datasets: [{
                        label: '{{ $label[$spot] }}',
                        data: {!!json_encode($psiValues[$spot]) !!},
                        backgroundColor: [
                            'rgba(0, 0, 0, 0.5)'
                        ],
                        borderColor: [
                            'rgba(255, 26, 104, 0.8)',
                        ],
                        borderWidth: 1,
                        tension: 0.5,
                    }]
                };
        
                const config{{ $spot }} = {
                    type: 'line',
                    data: data{{ $spot }},
                    options: {
                        scales: {
                            x: {
                                type: 'time',
                                min: '0:00',
                                max: '24:00',
                                time: {
                                    unit: '{{ $hour }}',
                                    displayFormats: {
                                        {{ $hour }}: 'H:mm'
                                    },
                                },
                            },
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: 300,
                                title: {
                                    display: true,
                                    text: '{{ $labelY[$spot] }}'
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    title: context => {
                                        console.log(context[0].parsed.x);
                                        const d = new Date(context[0].parsed.x);
                                        const formattedDate = d.toLocaleDateString("id-ID", {
                                            year: "numeric",
                                            day: "2-digit",
                                            month: "2-digit",                                            
                                            hour: "2-digit",
                                            minute: "2-digit",
                                            second: "2-digit",
                                            hour12: false,
                                        });
                                        return formattedDate;
                                    }
                                }
                            }
                        }
                    }
                };

                window['myChart' + {{ $spot }}] = new Chart(
                    document.getElementById('myChart{{ $spot }}'),
                    config{{ $spot }}
                );

                $('#nama{{ $spot }}').text(myChart{{ $spot }}.data.datasets[0].label);
        
                document.getElementById('myChart{{ $spot }}').addEventListener('click', function (event) {
                    const points = window['myChart' + {{ $spot }}].getElementsAtEventForMode(event, 'nearest', {
                        intersect: true
                    }, true);
        
                    if (points.length) {
                        const datasetIndex = points[0].datasetIndex;
                        const index = points[0].index;
                        const nama = window['myChart' + {{ $spot }}].data.datasets[0].label;
                        const label = window['myChart' + {{ $spot }}].data.labels[index];
                        const value = window['myChart' + {{ $spot }}].data.datasets[datasetIndex].data[index];
        
                        document.querySelector('#val{{ $spot }}').textContent = value + " PSI";
                        document.querySelector('#nama{{ $spot }}').textContent = nama;

                        const timestamp = new Date(label);
                        const formattedDate = timestamp.toISOString().replace('T', ' ').substring(0, 19);
                        document.querySelector('#tgl{{ $spot }}').textContent = formattedDate;
                    }
                });
                @endforeach

                $('.editButton').on('click', function () {
                    const spot = $(this).data('spot');
                    const label = $(this).data('label');
                    const labelY = $(this).data('labely');
                    const minY = $(this).data('miny');
                    const maxY = $(this).data('maxy');

                    $('#label').val(label);
                    $('#labelY').val(labelY);
                    $('#minY').val(minY);
                    $('#maxY').val(maxY);
                    $('#spotId').val(spot);
                });

                $('#customizeChart').on('click', function () {
                    const spotId = $('#spotId').val();
                    const label = $('#label').val();
                    const labelY = $('#labelY').val();
                    const minY = parseInt($('#minY').val(), 10);
                    const maxY = parseInt($('#maxY').val(), 10);

                    const chart = window['myChart' + spotId];
                    console.log(window['myChart' + spotId]);
                    chart.data.datasets[0].label = label;
                    chart.options.scales.y.title.text = labelY;
                    chart.options.scales.y.min = minY;
                    chart.options.scales.y.max = maxY;
                    chart.update();

                    $('#nama' + spotId).text(label);

                    $('#label').val('');
                    $('#labelY').val('');
                    $('#minY').val('');
                    $('#maxY').val('');
                    $('#spotId').val('');
                });
            });
        </script>
    </body>
</html> --}}

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .row {
            width: 99%;
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

        .chartMenu {
            width: 30%;
            color: rgba(54, 162, 235, 1);
        }
        
        .chartCustom {
            width: 25vw;
            color: rgba(54, 162, 235, 1);
        }

        .chartCard {
            width: 30vw;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .chartBox {
            width: 800px;
            padding: 20px;
            border-radius: 20px;
            border: solid 3px rgba(54, 162, 235, 1);
            background: white;
            margin-bottom: 20px;
        }

        .card {
            width: 28vw;
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

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light mx-2">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active disabled" aria-current="page" href="/">Pressure</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="list">Download Data</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="row">
        <div class="chartMenu col-md-4">
            <div class="mx-3 my-1">
                <label for="date">Select Date:</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="mx-3 my-1">
                <label for="hour">Select Hour:</label>
                <select name="hour" id="hour" class="form-control">
                    @foreach([1, 3, 6, 12, 24] as $value)
                    <option value="{{ $value }}">{{ $value }}
                        Hour{{ $value > 1 ? 's' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mx-3 my-1">
                <label for="spot-select" class="form-label">Select Spot</label>
                <select id="spot-select" name="spot[]" class="form-control" multiple="multiple">
                </select>
            </div>
    
            <button id="showData" type="submit" class="btn btn-primary d-grid gap-2 col-4 mx-auto my-3">Show Data</button>
        </div>

        <div class="col-md-2"></div>
    
        <div class="col-md-6">
            <div class="chartCustom">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="hidden" id="spot" name="spotId" style="width: 25vw">
                        <label for="label">Label Chart:</label><br>
                        <input class="form-control" type="text" id="label" name="label" style="width: 25vw">
                    </div>
                    <div class="form-group">
                        <label for="labelY">Label Axis Y:</label><br>
                        <input class="form-control" type="text" id="labelY" name="labelY" style="width: 25vw">
                    </div>
                    <div class="form-group">
                        <label for="minY">Min Axis Y:</label><br>
                        <input class="form-control" type="text" id="minY" name="minY" style="width: 25vw">
                    </div>
                    <div class="form-group">
                        <label for="maxY">Max Axis Y:</label><br>
                        <input class="form-control" type="text" id="maxY" name="maxY" style="width: 25vw">
                    </div>
    
                    <button id="customizeChart" type="submit" class="btn btn-primary d-grid gap-2 col-4 mx-auto my-3">Custom</button>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#spot-select').select2();

            $.ajax({
                url: '{{ route('index') }}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#date').val(data.selectedDate);
                    $('#hour').val(data.selectedHour);

                    var spotSelect = $('#spot-select');
                    spotSelect.empty();
                    $.each(data.spots, function(index, spot) {
                        spotSelect.append(new Option(spot.namaSpot, spot.id));
                    });

                    spotSelect.val(Object.keys(data.selectedName)).trigger('change');

                    $('.row1').empty();

                    let hour;
                    if (data.selectedHour == 1 || data.selectedHour == 3 || data.selectedHour == 6) {
                        hour = 'minute';
                    } else {
                        hour = 'hour';
                    }

                    data.selectedSpot.forEach(function(spotId) {
                        var spotName = 'Field '+data.selectedName[spotId];
                        var psiValues = data.psiValues[spotId];
                        var timestamps = data.timestamps[spotId];
                        var labelY = 'Pressure (PSI)';
                        var minY = 0;
                        var maxY = 300;

                        var containerHtml = '<div class="row"><div class="col-md-6 mt-3"><div class="card mx-3 text-center"><div class="card-body"><p class="mx-2 my-2" id="nama' + spotId + '"></p><p class="card-text my-3" id="val' + spotId + '"> PSI</p><p class="mx-3 my-2" id="tgl' + spotId + '"></p></div></div></div><div class="col-md-6"><div class="chartCard"><div class="chartBox"><div class="row"><div class="col-md-11"><canvas id="myChart-' + spotId + '"></canvas></div><div class="col-md-1"><button id="editButton-' + spotId + '" class="editButton" data-spot= "' + spotId + '" data-label="' + spotName + '" data-labelY="' + labelY + '" data-minY="' + minY + '" data-maxY="' + maxY + '"><i class="fa fa-pencil"></i></button></div></div></div></div></div></div>';
                        $('.row1').append(containerHtml);

                        var ctx = document.getElementById('myChart-' + spotId).getContext('2d');

                        var chartData = {
                            labels: timestamps,
                            datasets: [{
                                label: spotName,
                                data: psiValues,
                                backgroundColor: 'rgba(0, 0, 0, 0.5)',
                                borderColor: 'rgba(255, 26, 104, 0.8)',
                                borderWidth: 1,
                                tension: 0.5,
                            }]
                        };

                        var chartOptions = {
                            scales: {
                                x: {
                                    type: 'time',
                                    time: {
                                        unit: hour,
                                        displayFormats: {
                                            hour: 'H:mm'
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Waktu (WIB)'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    min: minY,
                                    max: maxY,
                                    title: {
                                        display: true,
                                        text: labelY
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        title: function(context) {
                                            var date = new Date(context[0].parsed.x);
                                            return date.toLocaleString("id-ID", {
                                                year: "numeric",
                                                month: "2-digit",
                                                day: "2-digit",
                                                hour: "2-digit",
                                                minute: "2-digit",
                                                second: "2-digit",
                                                hour12: false
                                            });
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

                        $('#nama' + spotId).text(spotName);
                        $('#val' + spotId).text(psiValues[psiValues.length - 1] + ' PSI');
                        $('#tgl' + spotId).text(timestamps[timestamps.length - 1]);

                        document.getElementById('myChart-' + spotId).addEventListener('click', function (event) {
                            const points = window['myChart-' + spotId].getElementsAtEventForMode(event, 'nearest', {
                                intersect: true
                            }, true);
                
                            if (points.length) {
                                const datasetIndex = points[0].datasetIndex;
                                const index = points[0].index;
                                const nama = window['myChart-' + spotId].data.datasets[0].label;
                                const label = window['myChart-' + spotId].data.labels[index];
                                const value = window['myChart-' + spotId].data.datasets[datasetIndex].data[index];
                
                                $('#val' + spotId).text(value + " PSI");
                                $('#nama' + spotId).text(nama);

                                const timestamp = new Date(label);
                                const formattedDate = timestamp.toISOString().replace('T', ' ').substring(0, 19);
                                $('#tgl' + spotId).text(formattedDate);
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
                    });
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
                    'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#date').val(data.selectedDate);
                    $('#hour').val(data.selectedHour);

                    var spotSelect = $('#spot-select');
                    spotSelect.empty();
                    $.each(data.spots, function(index, spot) {
                        spotSelect.append(new Option(spot.namaSpot, spot.id));
                    });

                    spotSelect.val(Object.keys(data.selectedName)).trigger('change');

                    $('.row1').empty();

                    let hour;
                    if (data.selectedHour == 1 || data.selectedHour == 3 || data.selectedHour == 6) {
                        hour = 'minute';
                    } else {
                        hour = 'hour';
                    }

                    data.selectedSpot.forEach(function(spotId) {
                        var spotName = 'Field '+data.selectedName[spotId];
                        var psiValues = data.psiValues[spotId];
                        var timestamps = data.timestamps[spotId];
                        var labelY = 'Pressure (PSI)';
                        var minY = 0;
                        var maxY = 300;

                        var containerHtml = '<div class="row"><div class="col-md-6 mt-3"><div class="card mx-3 text-center"><div class="card-body"><p class="mx-2 my-2" id="nama' + spotId + '"></p><p class="card-text my-3" id="val' + spotId + '"> PSI</p><p class="mx-3 my-2" id="tgl' + spotId + '"></p></div></div></div><div class="col-md-6"><div class="chartCard"><div class="chartBox"><div class="row"><div class="col-md-11"><canvas id="myChart-' + spotId + '"></canvas></div><div class="col-md-1"><button id="editButton-' + spotId + '" class="editButton" data-spot= "' + spotId + '" data-label="' + spotName + '" data-labelY="' + labelY + '" data-minY="' + minY + '" data-maxY="' + maxY + '"><i class="fa fa-pencil"></i></button></div></div></div></div></div></div>';
                        $('.row1').append(containerHtml);

                        var ctx = document.getElementById('myChart-' + spotId).getContext('2d');

                        var chartData = {
                            labels: timestamps,
                            datasets: [{
                                label: spotName,
                                data: psiValues,
                                backgroundColor: 'rgba(0, 0, 0, 0.5)',
                                borderColor: 'rgba(255, 26, 104, 0.8)',
                                borderWidth: 1,
                                tension: 0.5,
                            }]
                        };

                        var chartOptions = {
                            scales: {
                                x: {
                                    type: 'time',
                                    time: {
                                        unit: hour,
                                        displayFormats: {
                                            hour: 'H:mm'
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Waktu (WIB)'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    min: minY,
                                    max: maxY,
                                    title: {
                                        display: true,
                                        text: labelY
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        title: function(context) {
                                            var date = new Date(context[0].parsed.x);
                                            return date.toLocaleString("id-ID", {
                                                year: "numeric",
                                                month: "2-digit",
                                                day: "2-digit",
                                                hour: "2-digit",
                                                minute: "2-digit",
                                                second: "2-digit",
                                                hour12: false
                                            });
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

                        $('#nama' + spotId).text(spotName);
                        $('#val' + spotId).text(psiValues[psiValues.length - 1] + ' PSI');
                        $('#tgl' + spotId).text(timestamps[timestamps.length - 1]);

                        document.getElementById('myChart-' + spotId).addEventListener('click', function (event) {
                            const points = window['myChart-' + spotId].getElementsAtEventForMode(event, 'nearest', {
                                intersect: true
                            }, true);
                
                            if (points.length) {
                                const datasetIndex = points[0].datasetIndex;
                                const index = points[0].index;
                                const nama = window['myChart-' + spotId].data.datasets[0].label;
                                const label = window['myChart-' + spotId].data.labels[index];
                                const value = window['myChart-' + spotId].data.datasets[datasetIndex].data[index];
                
                                $('#val' + spotId).text(value + " PSI");
                                $('#nama' + spotId).text(nama);

                                const timestamp = new Date(label);
                                const formattedDate = timestamp.toISOString().replace('T', ' ').substring(0, 19);
                                $('#tgl' + spotId).text(formattedDate);
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
                    });
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        });

        $('#customizeChart').on('click', function () {
            const spotId = $('#spot').val();
            const label = $('#label').val();
            const labelY = $('#labelY').val();
            const minY = parseInt($('#minY').val(), 10);
            const maxY = parseInt($('#maxY').val(), 10);

            const chart = window['myChart-' + spotId];
            chart.config.data.datasets[0].label = label;
            chart.config.options.scales.y.title.text = labelY;
            chart.config.options.scales.y.min = minY;
            chart.config.options.scales.y.max = maxY;
            chart.update();

            $('#label').val('');
            $('#labelY').val('');
            $('#minY').val('');
            $('#maxY').val('');
            $('#spotId').val('');
        });    
    </script>
</body>
</html>