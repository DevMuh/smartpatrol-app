<!doctype html>
<html lang="en">

<!-- Mirrored from bhulua.thememinister.com/blank-page.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 12 Sep 2019 15:11:48 GMT -->

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="Bdtask">
    <title><?php $pagettl = $this->db->select(['judul_menu'])->where('link', $this->uri->segment(1))->get('tabel_menu')->row();
            echo $pagettl ?  $pagettl->judul_menu . ' - ' : ''; ?> <?= $this->session->userdata("app_domain") ? $this->session->userdata("app_domain") : "SMART PATROL" ?></title>
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
    <style>
        .sidebar-bunker {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .timeline::after {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .tleft::before {
            border: medium solid <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .tright::before {
            border-color: transparent <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?> transparent transparent;
        }

        .content {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .img-content {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .sidebar-nav ul li.mm-active a {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        a {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .navbar-custom-menu .navbar-nav .nav-link {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
            color: #fff;
        }

        .nav-clock {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .sidebar-bunker .search__text {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .content-header .header-icon {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .breadcrumb-item.active {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .ps>.ps__rail-y>.ps__thumb-y {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        #tambah {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
            border-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .dataTables_wrapper .pagination .page-item.active>.page-link {
            background: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        h1.font-weight-bold {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?> !important;
        }


        /* .sidebar-nav ul li .nav-second-level li a {
            color: #fff;
        } */

        .sidebar-nav ul li.mm-active ul li.mm-active a {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?> !important;
        }

        .sidebar-nav:hover {
            overflow-y: auto;
        }

        .sidebar-nav {
            overflow-y: hidden;
            max-height: 85%;
            padding-bottom: 50px;
        }


        /* width */
        .sidebar-nav::-webkit-scrollbar {
            width: 10px;
        }

        /* Track */
        .sidebar-nav::-webkit-scrollbar-track {
            background: #b81919;
        }

        /* Handle */
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #fff;
        }

        /* Handle on hover */
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: #fff;
        }
    </style>
    <?php if ($this->session->userdata("user_roles") != 'hrd') : ?>
        <style>
            .my-btn-export {
                display: none !important
            }
        </style>
    <?php endif ?>
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
        <nav class="sidebar sidebar-bunker">
            <div class="sidebar-header" style="box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
">
                <!--<a href="index.html" class="logo"><span>bd</span>task</a>-->
                <a style="margin-left: auto; margin-right: auto" href="<?= base_url() ?>dashboard" class="logo"><img style="margin-top: -20px;padding:8px" src="<?= $this->session->userdata("path_logo") ? base_url("assets/apps/images/") . $this->session->userdata("path_logo") : base_url('assets/apps/images/logo.png') ?>" alt=""></a>
            </div>
            <!--/.sidebar header--><br>
            <div class="profile-element p-0 d-flex justify-content-center align-items-center">
                <div class="avatar" style="width: 75px; height: 75px">
                    <img style="height: 75px;" src="<?= base_url('assets/apps/assets/dist/img/avatar3.png') ?>" class="rounded-circle" alt="">
                </div>
            </div>
            <br>
            <div class="p-0 d-flex justify-content-center align-items-center">
                <div class="profile-text">
                    <h5 class="m-0" style="color:white; font-weight:bold;"><?= $_SESSION['full_name'] ?></h5>
                    <span class="p-0 d-flex text-white justify-content-center align-items-center"><i><?= $_SESSION['user_roles'] ?></i></span>
                </div>
            </div>
            <br>

            <!--/.profile element-->
            <!-- <form class="search sidebar-form" action="#" method="get">
                <div class="search__inner">
                    <input type="text" class="search__text" placeholder="Search...">
                    <i class="typcn typcn-zoom-outline search__helper" data-sa-action="search-close"></i>
                </div>
            </form> -->
            <!--/.search-->
            <div class="sidebar-body">
                <nav class="sidebar-nav">
                        <?php
                            $CI = &get_instance();
                            $CI->load->view('layout/sidebar_menu');
                        ?>
                </nav>
            </div><!-- sidebar-body -->
        </nav>
        <!-- Page Content  -->
        <div class="content-wrapper">
            <div class="main-content">
                <nav class="navbar-custom-menu navbar navbar-expand-lg m-0">
                    <div class="sidebar-toggle-icon" id="sidebarCollapse">
                        <span></span>
                    </div>
                    <div style="padding-top: 10px; padding-left: 10px">
                        <h4 style="color:#7a7a7a"><?= $this->session->userdata('title_nm') ?></h4>
                    </div>
                    <!--/.sidebar toggle icon-->
                    <div class="d-flex flex-grow-1">
                        <div class="nav-clock  ml-auto">
                            <div class="time">
                                <span class="time-hours"></span>
                                <span class="time-min"></span>
                                <span class="time-sec"></span>
                            </div>
                        </div><!-- nav-clock -->
                        <ul class="navbar-nav flex-row align-items-center">
                            <li class="nav-item dropdown user-menu">
                                <a class="nav-link dropdown-toggle" href="<?= $this->session->userdata("user_roles") == "cudo" || $this->session->userdata("user_roles") == "cudo" ? "#" : base_url("faq") ?>" <?= $this->session->userdata("user_roles") == "cudo" || $this->session->userdata("user_roles") == "cudo" ? "data-toggle='dropdown'"  : "" ?> title="FAQ">
                                    <i class="typcn typcn-info-large"></i>
                                </a>
                                <div style="top: 0; min-width: 200px;" class="dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-header d-sm-none">
                                        <a href="#" class="header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                                    </div>
                                    <a href="<?= base_url('faq') ?>" class="dropdown-item"><i class="typcn typcn-info-large"></i> FAQ</a>
                                    <a href="<?= base_url('faq/table') ?>" class="dropdown-item"><i class="typcn typcn-spanner"></i> FAQ Setting</a>
                                </div>
                            </li>
                            <?php if (count($this->session->userdata("choose_b2b")) > 1) : ?>
                                <li class="nav-item dropdown user-menu">
                                    <a class="nav-link dropdown-toggle" href="#" data-toggle='dropdown' title="Choose Organization">
                                        <i class="typcn typcn-group"></i>
                                    </a>
                                    <div style="top: 0; min-width: 200px;overflow:auto;max-height:300px" class="dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-header d-sm-none">
                                            <a href="#" class="header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                                        </div>
                                        <?php foreach ($this->session->userdata("choose_b2b") as $d) : ?>
                                            <a href="<?= base_url("login/goredirect/") . $d->b2b_token . "/" . "refresh" ?>" class="dropdown-item"><i class="typcn typcn typcn-group-outline"></i><?= $d->title_nm ?></a>
                                        <?php endforeach ?>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                    <i class="typcn typcn-user"></i>
                                </a>
                                <div style="top: 0; min-width: 200px;" class="dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-header d-sm-none">
                                        <a href="#" class="header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                                    </div>
                                    <!-- <div class="user-header">
                                        <div class="img-user">
                                            <img src="<?= base_url('assets/apps/assets/dist/img/avatar-1.jpg') ?>" alt="">
                                        </div>
                                        <h6><?= $_SESSION['full_name'] ?></h6>
                                    </div> -->
                                    <!-- user-header -->
                                    <!-- <a href="<?= base_url('profile/user') ?>" class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a> -->
                                    <a href="#" onclick="$('#profileModal').modal({backdrop: 'static', show: true})" class="dropdown-item"><i class="typcn typcn-user-outline"></i> <?= $this->lang->line('my_profile') ?></a>
                                    <a href="<?= base_url('profile/user/edit') ?>" class="dropdown-item"><i class="typcn typcn-edit"></i> <?= $this->lang->line('edit_profile') ?></a>
                                    <!-- <a href="#" class="dropdown-item"><i class="typcn typcn-arrow-shuffle"></i> Activity Logs</a>
                                    <a href="#" class="dropdown-item"><i class="typcn typcn-cog-outline"></i> Account Settings</a> -->
                                    <a style="margin-bottom: 0px" href="<?= base_url('login/logout') ?>" class="dropdown-item"><i class="typcn typcn-key-outline"></i> <?= $this->lang->line('sign_out') ?></a>
                                </div>

                            </li>

                        </ul>
                        <!--/.navbar nav-->
                    </div>
                </nav>
                <!--/.navbar-->