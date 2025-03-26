@php
    use Carbon\Carbon;
    $formattedDate = Carbon::parse($selectedDate)->locale('id')->translatedFormat('d F Y');
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
    
        table{
            margin: 0 auto;
        }

        @media (max-width: 999px) {
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
    @extends('admin.app')

    @section('title', 'FOL Combine')
    
    <div style="margin-top: 150px;">
        <div class="row justify-content-center">
            <div class="col-md-1" style="min-width: 150px;">
                <label for="startTime" class="form-label">Start Time:</label>
                <input type="time" id="startTime" name="startTime" class="form-control" value="00:00" required>
            </div>
            <div class="col-md-1" style="min-width: 150px;">
                <label for="endTime" class="form-label">End Time:</label>
                <input type="time" id="endTime" name="endTime" class="form-control" value="23:59" required>
            </div>
            <div class="col-md-1" style="min-width: 150px;">
                <button id="submitButton" class="btn mt-4" style="background-color: #0b1b36; color: white;">
                    Submit
                </button>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-inline: 350px; margin-top: 20px; background-color: #0b1b36; border-color: white; color: white; padding: 20px">
        <p style="font-size: 20px; padding: 0; margin: 0">{{ $selectedName }}</p>
        <p style="font-size: 16px; padding: 0; margin: 0">{{ $formattedDate }}</p>
        <div id="chartContainer">
    <canvas id="myChart"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var start = "";
        var end = "";
        var awal = "";
        var akhir = "";
        var selectedDate = "";
        var respon = {};
        var myChart = null;
        
        $('#submitButton').click(function() {
            start = $('#startTime').val();
            end = $('#endTime').val();
            
            var startTime = new Date("1970-01-01T" + start + "Z");
            var endTime = new Date("1970-01-01T" + end + "Z");
            
            selectedDate = "{{ $selectedDate }}";
            if (end == "23:59"){
                end = "24:00";
            }
            awal = selectedDate + " " + start;
            akhir = selectedDate + " " + end;
            
            // console.log(awal);
            // console.log(akhir);
        
            if (startTime >= endTime) {
                alert('Start time cannot be later than or equal to end time.');
                return;
            }
            
            processData(respon);
        });
        
        $(document).ready(function(){
            start = $('#startTime').val();
            end = $('#endTime').val();
            selectedDate = "{{ $selectedDate }}";
            if (end = "23:59"){
                end = "24:00";
            }
            awal = selectedDate + " " + start;
            akhir = selectedDate + " " + end;
            
            $.ajax({
                url: 'https://folrantauold.findingoillosses.com/getHistoryData',
                method: 'POST',
                dataType: 'json',
                data: {
                    date: selectedDate
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    respon = response;
                    processData(respon);
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
        
        function processData(respon){
            var spots = [];
            var spotsName = [];
            let psiValues = {};
            let timestamps = {};
            // console.log(respon);
            
            respon.spots.forEach(spot => {
                spots.push(spot['id']);
                spotsName[spot['id']] = spot['namaSpot'];
                psiValues[spot['id']] = [];
                timestamps[spot['id']] = [];
            });
            
            respon.pressures.forEach(p => {
                spots.forEach(s => {
                    if (p.idSpot == s && p.timestamp >= awal && p.timestamp <= akhir) {
                        psiValues[s] = psiValues[s] || [];
                        timestamps[s] = timestamps[s] || [];
        
                        psiValues[s].push(parseFloat(p.psiValue.toFixed(2)));
                        timestamps[s].push(p.timestamp);
                    }
                });
            });
            
            // console.log(spots);
            // console.log(spotsName);
            // console.log(psiValues);
            // console.log(timestamps);
            displayChart(psiValues, timestamps, spots, spotsName);
        }
        
        // const ctx = document.getElementById('myChart').getContext('2d');
        
        // if (myChart !== null) {
        //     myChart.destroy();
        // }
        
        // displayChart();

        function displayChart(psiValues, timestamps, spots, spotsName){
            if (myChart !== null) {
        myChart.destroy();
        }
        
        // Hapus elemen canvas lama
        $('#myChart').remove(); 
        // Tambahkan elemen canvas baru
        $('#chartContainer').append('<canvas id="myChart"></canvas>'); 
        const ctx = document.getElementById('myChart').getContext('2d');
                
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
        
            const datasets = spots.map((spotId, index) => ({
                label: spotsName[spotId],
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
                plugins: [ChartDataLabels],
                options: {
                    elements: {
                        point:{
                        radius: 0
                        }
                    },
                    layout: {
                        padding: {
                            right: 50
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'PRESSURE (PSI)',
                                color: 'rgba(250,250,250,1)',
                                font: {
                                    size: 12
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
                                    size: 12
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
                            labels: {                            
                                boxHeight: 4,
                                boxWidth: 15,
                                color: 'rgba(250,250,250,1)',
                                font: {
                                    size: 10
                                }
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
                        datalabels: {
                            align: 'right',
                            display: function(context) {
                                // console.log(context.dataset.data[context.dataset.data.length - 1]);
                                return (context.dataIndex === context.dataset.data.length - 1);
                            },
                            padding: {
                                right: '0'
                            },
                            color: function(context) {
                                return colors[context.datasetIndex % colors.length];  // Memilih warna sesuai dengan indeks dataset
                            },
                            font: {
                                size: 10,
                            },
                            formatter: function(value, context) {
                                // console.log(context.dataset.data[context.dataset.data.length - 1]);
                                // return context.dataset.data[context.dataset.data.length - 1].toFixed(2);
                                return value.y;
                            }
                        }
                    }
                },
                
            });
        }
    </script>
      
</body>
</html>