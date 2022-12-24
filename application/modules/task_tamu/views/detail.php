<div class="body-content">
    <div class="">
        <?php
        if ($code == 200) {
            ?>
            <div class="row">
                <!-- <div class="col-md-3">
                        <img src="#" width="200" height="200">
                        <label>Name</label>
                    </div> -->
                <div class="col-sm-12 col-xl-6">
                    <div class="media d-flex m-1 ">
                        <div class="align-left p-1">
                            <a href="#" class="profile-image">
                                <!-- <img src="<?php echo $item->foto_pengunjung ?>"  
                                onError="this.onerror=null;this.src='<?= $image_cudo_pengunjung ?>'"
                                class="myImage avatar avatar-xl rounded-circle img-border height-100" alt="Card image"> -->
                                <img src="<?php echo $image_cudo_pengunjung; ?>" class="myImage avatar avatar-xl rounded-circle img-border height-100" alt="Card image">
                            </a>
                        </div>
                        <div class="media-body text-left ml-3 mt-1">
                            <h3 class="font-large-1 white"><?php echo $item->nama_tamu ?>
                                <div class="badge badge-warning fs-26 text-monospace mx-auto"><?php echo $item->id_ ?></div>

                                <!-- <span class="font-medium-1 white">(Project manager)</span> -->
                            </h3>
                            <p class="white">
                                <i class="fas fa-map-marker-alt"></i> <?php echo $item->tujuan_rumah ?> </p>
                        </div>
                    </div>
                </div>
                <nav aria-label="breadcrumb" class="col-sm-6 order-sm-last mb-3 mb-sm-0 p-0 ">
                    <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=base_url()?>dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=base_url()?>task_tamu">Guest Task</a></li>
                        <li class="breadcrumb-item active">Guest Task Detail</li>
                    </ol>
                </nav>
            </div>
            &nbsp;

            <div class="row">
                <div class="col-md-5">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="greet-user col-12 col-xl-12">
                                    <h2 class="fs-23 font-weight-600 mb-2">
                                        Identity Photo
                                    </h2>
                                    <!-- <img src="<?php echo $item->foto_identitas ?>" 
                                    onError="this.onerror=null;this.src='<?= $image_cudo_identitas ?>'"
                                    alt="..." class="img-fluid myImage mb-2"> -->
                                    <img src="<?php echo $image_cudo_identitas; ?>" alt="..." class="img-fluid myImage mb-2">
                                    &nbsp;
                                    <p class="" style="font-size:17px;">
                                        Day of visit : <span style="font-weight:bold;">Saturday, 17 / JUNE / 2019</span>
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
                            <!--Revenue today indicator-->
                            <div class="p-2 bg-white rounded p-3 mb-3 shadow-sm" style="height:150px;">
                                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">
                                    Guest Number
                                </div>
                                <div class="mx-auto" style="text-align: center; font-size: 60px;"><?php echo $item->id_ ?></div>
                                <div class="text-muted small mt-1">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6">
                            <!--Feedback-->
                            <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-white shadow-sm rounded" style="height:150px;">
                                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Destination Address</div>
                                <i class="fas fa fa-map-marker-alt opacity-25 fa-5x text-danger decorative-icon"></i>
                                <div class="d-flex">
                                    <div class="pl-3">
                                        <?=$item->tujuan_rumah?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6">
                            <!--Time on site indicator-->
                            <div class="d-flex flex-column p-3 mb-3  shadow-sm rounded" style="height:137px; background: #4cb558;">
                                <div class="header-pretitle font-weight-bold text-uppercase" style="color:white;">Visiting Time</div>
                                <i class="fas fa fa-clock-alt  fa-5x  decorative-icon"></i>
                                <div class="d-flex align-items-center" style="padding-top:10px;">
                                    <i class="fas fa fa-clock" style="color:white; font-size:40px; letter-spacing: 0.5em;"></i>
                                    <span class="text-size-2 text-monospace" style="color: white; letter-spacing: .1em;"><?= date_format(date_create($item->start_time), "g:i A")?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6">
                            <!--Time on site indicator-->
                            <div class="d-flex flex-column p-3 mb-3  shadow-sm rounded" style="height:137px; background: #d12323;">
                                <div class="header-pretitle font-weight-bold text-uppercase" style="color:white;">Leaving Time</div>
                                <i class="fas fa fa-clock-alt  fa-5x  decorative-icon"></i>
                                <div class="d-flex align-items-center" style="padding-top:10px;">
                                    <i class="fas fa fa-clock" style="color:white; font-size:40px; letter-spacing: 0.5em;"></i>
                                    <span class="text-size-2 text-monospace" style="color: white; letter-spacing: .1em;"><?= $item->end_time == null ? '-' : date_format(date_create($item->end_time), "g:i A")?></span>
                                </div>
                            </div>
                        </div>


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
        <div class="modal-content modal-lg">
            <img id="img_modal" style="border: 15px solid white; width: 100%" src="#" />
        </div>
    </div>
</div>