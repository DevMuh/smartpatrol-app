<!doctype html>
<html lang="en">

<!-- Mirrored from bhulua.thememinister.com/blank-page.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 12 Sep 2019 15:11:48 GMT -->

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="Bdtask">
    <title><?= $this->session->userdata("app_domain") ? $this->session->userdata("app_domain") : "SMART PATROL" ?><?php $pagettl = $this->db->select(['judul_menu'])->where('link', $this->uri->segment(1))->get('tabel_menu')->row();
                                                                                                                    echo $pagettl ? ' | ' . $pagettl->judul_menu : ''; ?></title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $this->session->userdata("icon") ? base_url("assets/apps/images/") . $this->session->userdata("icon") : base_url('assets/apps/assets/dist/img/favicon.png') ?>">
    <!--Global Styles(used by all pages)-->
    <link href="<?= base_url('assets/apps/assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/metisMenu/metisMenu.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/fontawesome/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/typicons/src/typicons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/themify-icons/themify-icons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/openlayers/ol.css') ?>" rel="stylesheet">
    <!--Third party Styles(used by this page)-->

    <!--Start Your Custom Style Now-->
    <link href="<?= base_url('assets/apps/assets/dist/css/style.css') ?>" rel="stylesheet">

</head>

<body class="fixed">
    <!-- Page Loader -->
    <div id="loadd"></div>
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p><?= $this->lang->line('loading') ?></p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <div class="wrapper">
        <!-- Sidebar  -->

        <!-- Page Content  -->
        <div class="content-wrapper">
            <div class="main-content">