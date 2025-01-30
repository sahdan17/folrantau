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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
</head>

<body>
    @extends('admin.app')

    @section('title', 'Location Prediction')
    
    <div class="row" style="margin-top: 120px">
        <div class="formPred mx-5 mt-4 col-md-3">
            <!--<form action="" method="post">-->
                <div class="form-group">
                    <label for="nama">Delta Pressure Value 1 (PSI):</label><br>
                    <input class="form-control" type="text" id="psiValue1" name="psiValue1" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Delta Pressure Value 2 (PSI):</label><br>
                    <input class="form-control" type="text" id="psiValue2" name="psiValue2" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Delta Pressure Value 3 (PSI):</label><br>
                    <input class="form-control" type="text" id="psiValue3" name="psiValue3" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Delta Pressure Value 4 (PSI):</label><br>
                    <input class="form-control" type="text" id="psiValue4" name="psiValue4" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Delta Pressure Value 5 (PSI):</label><br>
                    <input class="form-control" type="text" id="psiValue5" name="psiValue5" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Delta Pressure Value 6 (PSI):</label><br>
                    <input class="form-control" type="text" id="psiValue6" name="psiValue6" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Delta Pressure Value 7 (PSI):</label><br>
                    <input class="form-control" type="text" id="psiValue7" name="psiValue7" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Delta Pressure Value 8 (PSI):</label><br>
                    <input class="form-control" type="text" id="psiValue8" name="psiValue8" style="width: 25vw">
                </div>
                <div class="form-group">
                    <label for="nama">Delta Pressure Value 9 (PSI):</label><br>
                    <input class="form-control" type="text" id="psiValue9" name="psiValue9" style="width: 25vw">
                </div>
                <div class="form-group mt-3">
                    <label for="type">Type:</label><br>
                    <select class="form-control" id="type" name="type" style="width: 25vw">
                        <option value="static">Static</option>
                        <option value="dynamic" selected>Dynamic</option>
                    </select>
                </div>

                <div class="form-group mt-4">
                    <button class="btn btn-primary d-grid gap-2 col-5 mx-auto my-3">Predict Loc</button>
                </div>
            <!--</form>-->
        </div>
        <div class="card text-center mt-5 col-md-3" style="width:auto;">
            <div class="card-body">
                <p class="predict-number">Nilai Prediksi</p>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            
            $('.btn.btn-primary').on('click', function(event) {
                let formData = new FormData();
                formData.append('x1', parseFloat($('#psiValue1').val()) || 0);
                formData.append('x2', parseFloat($('#psiValue2').val()) || 0);
                formData.append('x3', parseFloat($('#psiValue3').val()) || 0);
                formData.append('x4', parseFloat($('#psiValue4').val()) || 0);
                formData.append('x5', parseFloat($('#psiValue5').val()) || 0);
                formData.append('x6', parseFloat($('#psiValue6').val()) || 0);
                formData.append('x7', parseFloat($('#psiValue7').val()) || 0);
                formData.append('x8', parseFloat($('#psiValue8').val()) || 0);
                formData.append('x9', parseFloat($('#psiValue9').val()) || 0);
                formData.append('type', $('#type').val() || 0);
    
                $.ajax({
                    type: 'POST',
                    url: '/predictLoc-proc',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    contentType : false,
                    processData : false,
                    success: function (response) {
                        // Handle success response
                        console.log(response);
                        let content = response.data[0];

                        let urlPattern = /(https?:\/\/[^\s]+)/g;
                        
                        let formattedContent = content.replace(urlPattern, function(url) {
                            return '<a href="' + url + '" target="_blank"><button class="btn btn-primary">Klik Disini</button></a>';
                        });
                        
                        $('.predict-number').html(formattedContent);

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
