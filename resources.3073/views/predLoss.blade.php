<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
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
                        <a class="nav-link  active disabled" href="predLoss">Prediction Loss</a>
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
        <div class="formPred mx-5 mt-4 col-md-3">
            <form action="" method="post">
                <div class="form-group">
                    <label for="nama">Rate1:</label><br>
                    <input class="form-control" type="text" id="Rate1" name="rate1" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Pressure1:</label><br>
                    <input class="form-control" type="text" id="Pressure1" name="pressure1" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Pressure2:</label><br>
                    <input class="form-control" type="text" id="Pressure2" name="pressure2" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Durasi:</label><br>
                    <input class="form-control" type="text" id="Durasi" name="durasi" style="width: 25vw">
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
                formData.append('Rate1', $('#Rate1').val());
                formData.append('Pressure1', $('#Pressure1').val());
                formData.append('Pressure2', $('#Pressure2').val());
                formData.append('Durasi', $('#Durasi').val());
    
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
