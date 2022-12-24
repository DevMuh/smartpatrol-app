<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url() ?>task_kejadian">Incident Task</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-world"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Incident Task Detail</h1>
                <small>This is a page for detail of events that occur</small>
            </div>
        </div>
    </div>
</div>

<div class="body-content">
    <div class="">
        <?php
        if ($code == 200) {
            ?>
            <div class="row">
                <div class="col-md-5">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="greet-user col-12 col-xl-12">
                                    <h2 class="fs-23 font-weight-600 mb-2">
                                        Incident Photo
                                    </h2>
                                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <!-- <img class="myImage d-block w-100" src="<?= $datanya->image_1 ?>" alt="First slide" 
                                                onError="this.onerror=null;this.src='<?= $image_1_cudo?>'"> -->
                                                <img class="myImage d-block w-100" src="<?= $image_1_cudo; ?>" alt="First slide">
                                            </div>
                                            <div class="carousel-item">
                                                <!-- <img class="myImage d-block w-100" src="<?= $datanya->image_2 ?>" alt="Seccond slide"
                                                onError="this.onerror=null;this.src='<?= $image_2_cudo?>'"> -->
                                                <img class="myImage d-block w-100" src="<?= $image_2_cudo; ?>" alt="Seccond slide">
                                            </div>
                                            <div class="carousel-item">
                                                <!-- <img class="myImage d-block w-100" src="<?= $datanya->image_3 ?>" alt="Third slide"
                                                onError="this.onerror=null;this.src='<?= $image_3_cudo?>'"> -->
                                                <img class="myImage d-block w-100" src="<?= $image_3_cudo; ?>" alt="Third slide">
                                            </div>
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                    &nbsp;
                                    <p class="" style="font-size:17px;">
                                        <!-- Photo Upload at : <span style="font-weight:bold;">Saturday, 17 / JUNE / 2019</span> -->
                                    </p>
                                    <!-- <a href="#!" class="btn btn-success">
                                        Try it for free
                                    </a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-7">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="d-flex flex-column p-3 mb-3 shadow-sm rounded" style="height:150px;<?= 'background: '.$color ?>">
                                <div class="header-pretitle font-weight-bold text-uppercase" style="color:white;">Category</div>
                                <i class="fas fa fa-clock-alt  fa-5x  decorative-icon"></i>
                                <div class="d-flex align-items-center" style="padding-top:20px;">
                                    <i class="fas fa fa-<?= $icon ?>" style="color:white; font-size:60px; letter-spacing: 0.5em;"></i>
                                    <span class="text-size-5" style="color: white; letter-spacing: 0.05em;"><?= $datanya->status ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <!--Feedback-->
                            <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-white shadow-sm rounded" style="height:150px;">
                                <div class="header-pretitle text-muted fs-14 font-weight-bold text-uppercase mb-2">Incident Address</div>
                                <i class="fas fa fa-map-marker-alt opacity-25 fa-5x text-danger decorative-icon"></i>
                                <div class="d-flex">
                                    <div class="pl-2 fs-15">
                                        <?= $datanya->block ?>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-12 col-lg-12">
                            <!--Time on site indicator-->
                            <div class="d-flex flex-column p-3 mb-3  shadow-sm rounded" style="height:137px; background: #4cb558;">
                                <div class="header-pretitle font-weight-bold text-uppercase" style="color:white;">Submit Date</div>
                                <i class="fas fa fa-clock-alt  fa-5x  decorative-icon"></i>
                                <div class="d-flex align-items-center" style="padding-top:10px;">
                                    <i class="fas fa fa-calendar-alt" style="color:white; font-size:40px; letter-spacing: 0.5em;"></i>
                                    <span class="text-size-3" style="color: white;"><?= date_format(date_create($datanya->submit_date), "d F Y") . " - " . $datanya->submit_time . ' WIB'?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div style="background: white; padding: 13px; border-radius: 5px" class="col-md-12 col-lg-12">
                    <div id="map" style="height: 400px; width: 100%;">
                        <div id="popup"></div>
                    </div>
                </div>
            </div>

        <?php
        } else {
            ?>
            <div class="col-md-12">
                <center>
                    <h2>404</br>Id not found</h2>
                </center>
            </div>
        <?php
        }
        ?>
    </div>

</div>
<!--/.body content-->
</div>
<!--/.main content-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img id="img_modal" style="border: 15px solid white;" src="#" />
        </div>
    </div>
</div>