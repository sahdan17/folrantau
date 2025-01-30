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
            color: black;
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
    
        table{
            margin: 0 auto;
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
    @extends('admin.app')

    @section('title', 'Loss Prediction')
    
    <div class="row" style="margin-top: 120px">
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
