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

  <title><?= $app_domain ? $app_domain : 'SMART PATROL ' ?> | Login</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="<?= $icon ? base_url("assets/apps/images/") . $icon : base_url('assets/apps/assets/dist/img/favicon.png') ?>">

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
  </style>

</head>

<body style="overflow: hidden;background:<?= $color_1 ?>">

  <!-- Start Preloader -->
  <div id="preload-block">
    <div class="square-block"></div>
  </div>
  <!-- Preloader End -->

  <div class="container-loading">
    <img src="<?= base_url('assets/apps/assets/plugins/login/images/preloader.gif') ?>" class="center-loading">
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-7 col-md-5 col-lg-4 authfy-panel-left">
        <!-- brand-logo start -->
        <div class="brand-logo text-center">
          <img height="60" style="object-fit:contain " src="<?= $logo ? base_url("assets/apps/images/") . $logo : base_url('assets/apps/assets/plugins/login/images/smart.png') ?>" alt="brand-logo">
        </div><!-- ./brand-logo -->
        <div class="brand-logo text-center">
          <img height="120" style="object-fit:contain " src="<?= $company_logo ?>" width="250" alt="brand-logo">
          <label class="checkbox text-center" style="margin-top:30px; color:white">"<?= $company_name ?>"</label> </div><!-- ./brand-logo -->
        <!-- authfy-login start -->
        <div class="authfy-login">
          <!-- panel-login start -->
          <div class="authfy-panel panel-login text-center active">
            <div class="authfy-heading">
              <h3 class="auth-title" style="color:white"><?= $this->lang->line('login') ?></h3>
              <p style="color:white"><?= $this->lang->line('no_acc') ?> <a class="lnk-toggler" data-panel=".panel-signup" href="#" style="color:yellow"><?= $this->lang->line('register_here') ?></a></p>
            </div>
            <!-- social login buttons start -->
            <!-- <div class="row social-buttons">
                  <div class="col-xs-4 col-sm-4">
                    <a href="#" class="btn btn-lg btn-block btn-facebook">
                    <i class="fa fa-facebook"></i>
                    </a>
                  </div>
                  <div class="col-xs-4 col-sm-4">
                    <a href="#" class="btn btn-lg btn-block btn-twitter">
                    <i class="fa fa-twitter"></i>
                    </a>
                  </div>
                  <div class="col-xs-4 col-sm-4">
                    <a href="#" class="btn btn-lg btn-block btn-google">
                    <i class="fa fa-google-plus"></i>
                    </a>
                  </div>
                </div> -->
            <!-- ./social-buttons -->
            &nbsp;
            <div class="row">
              <div class="col-xs-12 col-sm-12">
                <?php
                if ($this->session->flashdata('category_error')) { ?>
                  <div class="alert alert-danger" id="error-alert"> <?= $this->session->flashdata('category_error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                <?php } ?>

                <?php
                if ($this->session->flashdata('success')) { ?>
                  <div class="alert alert-success" id="error-alert"> <?= $this->session->flashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                <?php } ?>

                <form name="loginForm" class="loginForm" action="<?= base_url('login/login_validation') ?>" method="POST">
                  <div class="form-group">
                    <input style="margin-bottom: 0" type="username" class="form-control username" name="username" value="<?= set_value("username") ?>" placeholder="Username Or Phone Number">
                    <?= form_error('username', "<small style='color: darkorange;'>", '</small>') ?>
                  </div>
                  <div class="form-group">
                    <div class="pwdMask">
                      <input style="margin-bottom: 0" type="password" class="form-control password" name="password" value="<?= set_value("password") ?>" placeholder="<?= $this->lang->line('password') ?>">
                      <span class="fa fa-eye-slash pwd-toggle"></span>
                      <?= form_error('password', "<small style='color: darkorange;'>", '</small>') ?>
                    </div>
                  </div>
                  <div class="row remember-row">
                    <div class="col-xs-6 col-sm-6">
                      <label class="checkbox text-left" style="color:white">
                        <input type="checkbox" value="1" name="remember_me"><span class="label-text"><?= $this->lang->line('remember_me') ?></span>
                      </label>
                    </div>
                    <div class="col-xs-6 col-sm-6">
                      <p class="forgotPwd">
                        <a class="lnk-toggler" data-panel=".panel-forgot" href="#" style="color:yellow"><?= $this->lang->line('forget_pass') ?></a>
                      </p>
                    </div>
                  </div> <!-- ./remember-row -->
                  <br>
                  <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block" type="submit"><?= $this->lang->line('login') ?></button>
                  </div>
                </form>
              </div>
            </div>
            <div style="padding-top:10px;">
              <a href="https://www.cudocomm.com">
                <img height="50" src="<?= base_url('assets/apps/assets/plugins/login/images/cudologo.png') ?>" />
              </a>
            </div>
          </div>
          <!-- ./panel-login -->

          <!-- panel-signup start -->
          <div class="authfy-panel panel-signup text-center">
            <div class="row">
              <div class="col-xs-12 col-sm-12">
                <div class="authfy-heading">
                  <h3 class="auth-title" style="color:white">Sign up for free!</h3>
                </div>
                <!-- <form name="signupForm" class="signupForm" action="<?= base_url('register') ?>" method="POST"> -->
                <form name="signupForm" class="signupForm">
                  <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Company Name" required>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                  </div>
                  <div class="form-group">
                    <div class="pwdMask">
                      <input type="password" class="form-control" name="password" placeholder="Password" required>
                      <span class="fa fa-eye-slash pwd-toggle"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <p class="term-policy text-muted small" style="color:white">Saya setuju <a href="#" style="color:yellow">dengan syarat</a> and <a href="#" style="color:yellow">ketentuan</a>.</p>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block " type="submit" id="btn_signup">Sign up with email</button>
                  </div>
                </form>
                <a class="lnk-toggler" data-panel=".panel-login" style="color:yellow" href="#">Already have an account?</a>
              </div>
            </div>
            <div style="padding-top:10px;">
              <a href="https://www.cudocomm.com">
                <img src="assets/apps/assets/plugins/login/images/cudologo.png" width="10%">
              </a>
            </div>
          </div> <!-- ./panel-signup -->

          <!-- panel-forget start -->
          <div class="authfy-panel panel-forgot">
            <div class="row">
              <div class="col-xs-12 col-sm-12">
                <div class="authfy-heading">
                  <h3 class="auth-title" style="color:white">Recover your password</h3>
                  <p style="color:white">Fill in your e-mail address below and we will send you an email with further instructions.</p>
                </div>
                <form name="forgetForm" class="forgetForm" action="#" method="POST">
                  <div class="form-group">
                    <input type="email" class="form-control" name="username" placeholder="Email address">
                  </div>
                  <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Recover your password</button>
                  </div>
                  <div class="form-group">
                    <a class="lnk-toggler" style="color:yellow" data-panel=".panel-login" href="#">Already have an account?</a>
                  </div>
                  <div class="form-group">
                    <a class="lnk-toggler" style="color:yellow" data-panel=".panel-signup" href="#">Don’t have an account?</a>
                  </div>
                </form>
                <div style="padding-top:10px;">
                  <a href="https://www.cudocomm.com">
                    <img src="assets/apps/assets/plugins/login/images/cudologo.png" width="10%">
                  </a>
                </div>
              </div>
            </div>
          </div> <!-- ./panel-forgot -->
        </div> <!-- ./authfy-login -->
      </div> <!-- ./authfy-panel-left -->
      <div class="col-md-7 col-lg-8 authfy-panel-right hidden-xs hidden-sm">
        <div class="hero-heading row">
          <div id="authfySlider" class="headline carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <!-- <ol class="carousel-indicators">
                <li data-target="#authfySlider" data-slide-to="0" class="active"></li>
                <li data-target="#authfySlider" data-slide-to="1"></li>
              </ol> -->
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
              <div class="item active">
                <!-- <h3 style="color:#ffba00">Welcome to Authfy Account</h3>
                  <p style="color:#ffba00">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p> -->
              </div>
              <div class="item">
                <!-- <h3 style="color:#ffba00">Welcome to Authfy Account</h3>
                  <p style="color:#ffba00">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p> -->
              </div>
            </div>
          </div>
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
