<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- Basic Page Needs
  ================================================== -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Metas
  ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- For Search Engine Meta Data  -->
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="yoursite.com" />

    <title><?= $this->session->userdata("app_domain") ? $this->session->userdata("app_domain") : 'SMART PATROL ' ?> | Choose Organization</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= $this->session->userdata("icon") ? base_url("assets/apps/images/") . $this->session->userdata("icon") : base_url('assets/apps/assets/dist/img/favicon.png') ?>">

    <!-- Main structure css file -->
    <link href="<?= base_url('assets/apps/assets/plugins/login/css/login4-style.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if IE]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

    <style type="text/css">
        .swal2-popup {
            font-size: 1.4rem !important;
        }

        .container-loading {
            height: 100%;
            position: relative;
            background-color: white;
        }

        .center-loading {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-right: -50%;
        }

        .box-center {
            width: 500px;
            height: 400px;
            background-color: rgba(255, 255, 255, 0.2);
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            margin: auto;
            max-width: 100%;
            max-height: 100%;
            overflow: auto;
            padding: 18px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .my-list {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
            font-weight: bold;
            height: 60px;
            align-items: center;
            display: flex;
            flex-direction: row;
        }

        .my-list:hover {
            color: black !important;
        }
    </style>

</head>

<body style="overflow: hidden;background:<?= $this->session->userdata("color_1") ?>">

    <!-- Start Preloader -->
    <div id="preload-block">
        <div class="square-block"></div>
    </div>
    <!-- Preloader End -->

    <div class="container-loading">
        <img src="<?= base_url('assets/apps/assets/plugins/login/images/preloader.gif') ?>" class="center-loading">
    </div>

    <div class="container-fluid">
        <div class="h-100 d-flex justify-content-center align-items-center vh-100">
            <div class="box-center">
                <h3 class="text-center" style="color:white;font-weight:bold;margin-bottom:16px;margin-top:0;">Select Organization</h3>
                <div class="list-group" style="max-height: 320px;overflow:scroll">
                    <?php foreach ($data as $d) : ?>
                        <a href="<?= base_url("login/goredirect/") . $d->b2b_token ?>" class="list-group-item list-group-item-action my-list">
                            <img src="<?= file_exists(base_url("assets/apps/images/") . $d->path_logo) ? base_url("assets/apps/images/") . $d->path_logo : base_url("assets/apps/assets/user.png") ?>" style="width: 50px;height:50px;margin-right:12px;border-radius:25px;" alt="">
                            <?= $d->title_nm ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div> <!-- ./row -->
    </div> <!-- ./container -->


    <!-- initialize jQuery Library -->
    <script src="<?= base_url('assets/apps/assets/plugins/login/js/jquery-2.2.4.min.js') ?>"></script>

    <!-- for Bootstrap js -->
    <script src="<?= base_url('assets/apps/assets/plugins/login/js/bootstrap.min.js') ?>"></script>
    <!-- Sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.js"></script>

    <!-- Custom js-->
    <script src="<?= base_url('assets/apps/assets/plugins/login/js/custom.js') ?>"></script>
    <script>
        $("#error-alert").fadeTo(2000, 500).slideUp(500, function() {
            $("#error-alert").slideUp(500);
        });
    </script>

    <?php include 'main_script.php' ?>

</body>

<!-- Mirrored from gitapp.top/demo/authfy/demo/login-04.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 24 Sep 2019 10:05:46 GMT -->

</html>