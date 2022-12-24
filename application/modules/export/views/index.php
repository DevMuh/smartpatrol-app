<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $this->session->userdata("icon") ? base_url("assets/apps/images/") . $this->session->userdata("icon") : base_url('assets/apps/assets/dist/img/favicon.png') ?>">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- FontAwesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <title><?= $title ?></title>
    <style>

        #log-wrapper{
            background-color: #696969; height: 56vh;color: #fff; overflow-y: scroll;
        }
        #log-wrapper::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px #696969;
            background-color: #F5F5F5;
        }

        #log-wrapper::-webkit-scrollbar {
            width: 10px;
            background-color: #F5F5F5;
        }

        #log-wrapper::-webkit-scrollbar-thumb {
            background-color: #eee;
            border: 2px solid #555555;
        }
    </style>
</head>

<body style="background-color: #EEE;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card mt-3">
                    <!-- <h5 class="card-header"><?= $title ?></h5> -->
                    <div class="card-body">
                        <h5 style="text-align: center;"><?= $title ?></h5>
                        <p class="card-text" id="item-label"></p>
                        <div class="progress mb-3" style="height: 30px;">
                            <div id="progressbar-download" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button id="button-download" class="btn btn-danger" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mt-1">
                    <h6 class="card-header" style="background-color: #FF7F50; color: #fff;">Log</h6>
                    <div class="card-body" id="log-wrapper">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script>
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--     
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    -->
</body>

</html>