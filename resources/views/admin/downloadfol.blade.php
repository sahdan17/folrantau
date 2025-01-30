<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .load {
            cursor: pointer;
        }
        
        .load:hover {
            color: blue;
        }
        
        table {
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }
        
        td, th {
            padding: 10px;
            text-align: left;
        }
        
        .tableList {
            margin: 20px auto;
            padding: 10px;
            max-width: 90%;
            overflow-x: auto;
            border-radius: 10px;
        }
        
        .loader {
            width: 50px;
            aspect-ratio: 1;
            border-radius: 50%;
            background: 
                radial-gradient(farthest-side,#3516ff 94%,#0000) top/8px 8px no-repeat,
                conic-gradient(#0000 30%,#3516ff);
            -webkit-mask: radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);
            animation: l13 1s infinite linear;
            position: fixed;
            left: 50%;
            top: 50%;
            display: none;
            z-index: 1000;
            aspect-ratio: 1;
        }
        
        @keyframes l13{ 
            100%{transform: rotate(1turn)}
        }

        .loading-active .table-container {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body style="background-color: white; padding-top: 130px">
    <div class="loader"></div>
    
    @extends('admin.app')

    @section('title', 'FOL Download')
    
    <table class="tableList" style="border-radius: 10px;"></table>
    
    <div class="button1"></div>
    
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data tanggal <span id="tanggalToDelete" class="fw-bold"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                </div>
            </div>
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
    <script>
        let tanggal = {};
        let indexPage = 0;
        let spots = {};
        let tbl = '';
        
        function chunkArray(array, chunkSize) {
            let result = [];
            for (let i = 0; i < array.length; i += chunkSize) {
                result.push(array.slice(i, i + chunkSize));
            }
            return result;
        }
    
        $(document).ready(function(){
            spots = @json($spots);
            
            tbl = `<tr><td style="border-bottom: 1px solid black; text-align: center; vertical-align: middle; font-weight: bold; min-width: 120px;">Tanggal</td>`;
            
            spots.forEach(function(spot) {
                tbl += `<td style="border-bottom: 1px solid black; text-align: center; vertical-align: middle; font-weight: bold;">${spot.namaSpot}</td>`;
            });
            
            tbl += `<td style="border-bottom: 1px solid black; text-align: center; vertical-align: middle; font-weight: bold;">Jumlah</td>`;
            tbl += `<td style="border-bottom: 1px solid black; text-align: center; vertical-align: middle; font-weight: bold; min-width: 120px;">Action</td></tr>`;
            
            $.ajax({
                url: 'https://folpertaminafieldrantau.com/getTanggal',
                method: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    tanggal = chunkArray(response.data, 10);
                    
                    getDownload(tanggal[indexPage]);
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
        
        function getDownload(tgl){
            $('.loader').show();
            $.ajax({
                url: 'https://folpertaminafieldrantau.com/getDownloadList',
                method: 'POST',
                dataType: 'json',
                data: {
                    dates: tgl
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    viewList(response.data);
                    $('.loader').hide();
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
        
        function viewList(list){
            let tabel = '';
            
            if (indexPage == 0){
                $('.tableList').html(tbl);
            }
            
            list.forEach(function(l) {
                tabel += `<tr><td style="border-bottom: 1px solid black; text-align: center; vertical-align: middle; font-weight: bold;">${l.tanggal}</td>`;
                spots.forEach(function(s) {
                    tabel += `<td style="border-bottom: 1px solid black; text-align: center; vertical-align: middle;">${l[s.id]}</td>`;
                });
                tabel += `<td style="border-bottom: 1px solid black; text-align: center; vertical-align: middle;">${l.total_data}</td>`;
                tabel += `<td style="border-bottom: 1px solid black; text-align: center; vertical-align: middle;">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary download" data-tanggal="${l.tanggal}"><i class="fa fa-download""></i></button>
                            <button type="submit" class="btn btn-danger delete" data-tanggal="${l.tanggal}"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </td></tr>`;
            });
            
            $('.tableList').append(tabel);
            
            let btn = `<div class="load" id="loadBtn" style="padding: 20px;">Load More...</div>`;
            
            if (tanggal.length - indexPage <= 1){
                $('.button1').html('');
            } else {
                $('.button1').html(btn);
            }
        }
        
        $(document).on("click", "#loadBtn", function () {
            $('.loader').show();
            indexPage += 1;
            
            if (indexPage < tanggal.length){
                getDownload(tanggal[indexPage]);
            }
        });
        
        $(document).on("click", ".download", function () {
            const tgl = $(this).attr("data-tanggal");
            console.log(tgl);
            const form = $('<form>', {
                method: 'POST',
                action: 'https://folpertaminafieldrantau.com/downloadExcelFOL'
            });
        
            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: '{{ csrf_token() }}'
            }));
        
            form.append($('<input>', {
                type: 'hidden',
                name: 'tanggal',
                value: tgl
            }));
        
            $('body').append(form);
            form.submit();
        });
        
        let tanggalToDelete = '';

        $(document).on("click", ".delete", function () {
            tanggalToDelete = $(this).attr("data-tanggal");
            $('#tanggalToDelete').text(tanggalToDelete); // Tampilkan tanggal di modal
            $('#deleteModal').modal('show'); // Tampilkan modal
        });
        
        $(document).on("click", "#confirmDelete", function () {
            $.ajax({
                url: 'https://folpertaminafieldrantau.com/deleteDataFOL',
                method: 'POST',
                dataType: 'json',
                data: {
                    tanggal: tanggalToDelete
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        console.log("Data berhasil dihapus:", response.tanggal);
                        
                        // Hapus baris tabel yang sesuai
                        $(`button.delete[data-tanggal="${response.tanggal}"]`).closest('tr').remove();
                    } else {
                        console.error("Gagal menghapus data:", response.error);
                    }
                    $('#deleteModal').modal('hide'); // Tutup modal
                },
                error: function (error) {
                    console.error("Terjadi kesalahan saat menghapus data:", error);
                    $('#deleteModal').modal('hide'); // Tutup modal
                }
            });
        });
    </script>
</body>
</html>