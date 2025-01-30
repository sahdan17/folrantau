<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }
        
        li {
            list-style-type: none;
        }

        .formPred {
            color: blue;
            width: 20vw;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .card {
            min-height: 60px;
            max-height: 60px;
        }

        .row {
            width: 99%;
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
                            <li><a class="dropdown-item" style="color: white" href="/gauge">Gauge Chart</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color: white" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Prediksi Data
                        </a>
                        <ul style="background-color: #265eb5; border-color: white" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" style="color: white" href="predLoc">Predict Location</a></li>
                            <li><a class="dropdown-item disabled" style="color: white" href="predLoss">Predict Loss</a></li>
                        </ul>
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
        <div class="formPred mx-5 mt-4 col-md-3">
            <form action="" method="post">
                <div class="form-group">
                    <label for="nama">Time1:</label><br>
                    <input class="form-control" type="text" id="timeB1" name="timeB1" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Angka Shipping (Bbl):</label><br>
                    <input class="form-control" type="text" id="angkaBJG" name="angkaBJG" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Rate 1:</label><br>
                    <input class="form-control" type="text" id="rateB1" name="rateB1" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Rate 2:</label><br>
                    <input class="form-control" type="text" id="rateB2" name="rateB2" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Rate 3:</label><br>
                    <input class="form-control" type="text" id="rateB3" name="rateB3" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Rate 4:</label><br>
                    <input class="form-control" type="text" id="rateB4" name="rateB4" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Rate 5:</label><br>
                    <input class="form-control" type="text" id="rateB5" name="rateB5" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Rate 6:</label><br>
                    <input class="form-control" type="text" id="rateB6" name="rateB6" style="width: 25vw">
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary d-grid gap-2 col-5 mx-auto my-3">Predict Loss</button>
                </div>
            </form>
        </div>
        <div class="card text-center mt-5 col-md-3" style="width:auto;">
            <div class="card-body">
                <p class="predict-number">Nilai Prediksi</p>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('form').submit(function (event) {
                // Prevent the form from submitting normally
                event.preventDefault();
    
                // Get the form data
                let formData = new FormData();
                formData.append('timeB1', $('#timeB1').val());
                formData.append('angkaBJG', $('#angkaBJG').val());
                formData.append('rateB1', $('#rateB1').val());
                formData.append('rateB2', $('#rateB2').val());
                formData.append('rateB3', $('#rateB3').val());
                formData.append('rateB4', $('#rateB4').val());
                formData.append('rateB5', $('#rateB5').val());
                formData.append('rateB6', $('#rateB6').val());
    
                console.log(formData);
    
                // Send an AJAX request
                $.ajax({
                    type: 'POST',
                    url: '/predictLoss-proc', // Replace with the appropriate URL
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                    },
                    contentType : false,
                    processData : false,
                    success: function (response) {
                        // Handle success response
                        console.log(response);
                        $('.predict-number').text(response.data);

                    },
                    error: function (xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        // Display error message to the user
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
</body>

</html>
