<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url() ?>task_patrol">Patrol List</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-puzzle-outline"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Patrol List Detail</h1>
                <small>Checkpoint of patrol list</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="col-sm-12 col-xl-8">
        <div class="media d-flex m-1 ">
            <div class="align-left p-1">
                <a href="#" class="profile-image">
                    <!-- <img src="<?php echo $foto_orang_nya ? $foto_orang_nya->foto_orang : base_url('assets/apps/assets/no_image.png') ?>" 
                    onError="this.onerror=null;this.src='<?php echo $foto_orang_nya_cudo ? $foto_orang_nya_cudo->foto_orang : base_url('assets/apps/assets/no_image.png') ?>'"
                    class="avatar avatar-xl rounded-circle img-border height-100 myImage" alt="Card image"> -->
                    <img src="<?php echo $foto_orang_nya; ?>" class="avatar avatar-xl rounded-circle img-border height-100 myImage" alt="Card image">
                </a>
            </div>
            <div class="media-body text-left ml-3 mt-1">
                <h3 class="font-large-1 white"><?php echo $nama_orang->full_name ?>
                    <span class="font-medium-1 white"></span>
                </h3>
                <p class="white">
                    <i class="fas fa-map-marker-alt"></i> <?php echo "PATROL AT " . $nama_orang->title_nm . " ROUTE (" . $nama_orang->cluster_name . ")" ?> </p>
            </div>
        </div>
    </div>
    <!-- <?php echo $this->config->item('base_url_api') . 'cli_trigger/maps2/' . $nama_orang->b2b_token . '/' . $nama_orang->idnya . '/' . $nama_orang->id_route ?> -->
    <div class="card mb-4">
        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <!-- <?php if ($map == 1) { ?> -->
                        <div class="row justify-content-center" style="height: 700px;">
                            <!-- <div class="greet-user col-12 col-xl-10"> -->
                            <iframe align="center" width="100%" height="100%" src="<?php echo $this->config->item('base_url_api') . 'cli_trigger/maps2/' . base64_encode($nama_orang->all_id_task_patrol); ?>" frameborder="yes" scrolling="yes" name="myIframe" id="myIframe"> </iframe>
                            <!-- <iframe align="center" width="100%" height="100%" src="<?php echo 'http://localhost:8585/cli_trigger/maps2/' . $nama_orang->idnya ?>" frameborder="yes" scrolling="yes" name="myIframe" id="myIframe"> </iframe> -->
                        </div>
                        <!-- <?php } else { ?>
                            <center>
                                <h3>Map Not Found</h3>
                            </center>
                        <?php } ?> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="card-body">
                    <table id="myTables" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                        <thead style="background-color: black;">
                            <tr style="color:white">
                                <th style="width: 15%"><?= $this->lang->line('cluster_name'); ?></th>
                                <th style="width: 16%"><?= $this->lang->line('user'); ?></th>
                                <th style="width: 13%"><?= $this->lang->line('start'); ?></th>
                                <th style="width: 13%"><?= $this->lang->line('stop'); ?></th>
                                <th style="width: 7%;"><?= $this->lang->line('duration'); ?></th>
                                <!-- <th style="width: 5%;"><?= $this->lang->line('total_cp'); ?></th>
                                <th style="width: 5%;"><?= $this->lang->line('total_done'); ?></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no=1;
                            $dur="-";
                            $last_start_date="";
                            $start_time="";
                            foreach ($data_list_task as $value) 
                            { 
                                if ($no == 1) {
                                    if (!empty($value->done_time)) {
                                        // var_dump("masuk");die;
                                        $start_date = new DateTime($value->publish_time);
                                        $end_date = new DateTime($value->done_time);
                                        $dd = date_diff($end_date, $start_date);
                                        $dur = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
                                        $last_start_date = $value->done_time;
                                        $start_time = $value->publish_time;
                                    } else {
                                        $dur = 0;
                                    }
                                }else if($no == count($data_list_task)) {
                                    if (!empty($last_start_date)) {
                                        $start_date = new DateTime($last_start_date);
                                        $end_date = new DateTime($value->done_time);
                                        // var_dump(""$end_date);die;
                                        $dd = date_diff($end_date, $start_date);
                                        $dur = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
                                        $start_time = $last_start_date;
                                    } else {
                                        $dur = 0;
                                    }
                                }else{
                                    if (!empty($last_start_date)) {
                                        $start_date = new DateTime($last_start_date);
                                        $end_date = new DateTime($value->done_time);
                                        $dd = date_diff($end_date, $start_date);
                                        $dur = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
                                        $start_time = $last_start_date;
                                    } else {
                                        $dur = 0;
                                    }
                                }
                            ?>
                            <tr>
                                <td><?php echo $value->cluster_name;  ?></td>
                                <td><?php echo $value->full_name == '' ? 'Anonim' : $value->full_name; ?></td>
                                <td><?php echo $value->publish_date . " " . $start_time; ?></td>
                                <td><?php echo $value->done_date . " " . $value->done_time; ?></td>
                                <td>
                                    <?php 
                                        echo $dur; 
                                    ?>
                                </td>
                                <!-- <td><?php echo $value->total_cp; ?></td>
                                <td><?php echo $value->total_done; ?></td> -->
                            </tr>
                            <?php 
                                $no++;
                            } 
                            ?>
                        </tbody>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if ($code == 404) { ?>
                <center>
                    <h3>No Checkpoint</h3>
                </center>
            <?php } ?>
            <div class="timeline">
                <?php $count = 0 ?>
                <?php 
                    $status_server = false;
                    if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
                        $status_server = true;
                    }else{
                        $status_server = false;
                    }

                    $no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");

                    foreach ($details as $key) {

                        $new_img_1 = "";
                        $new_img_2 = "";
                        $new_img_3 = "";

                        if ($status_server) {
                            $this->load->library('curl');
                            $image_cudo = $this->config->item("base_url_server_cudo") . "assets/cp/" . $key->image_1;
                            $image_cudo2 = $this->config->item("base_url_server_cudo") . "assets/cp/" . $key->image_2;
                            $image_cudo3 = $this->config->item("base_url_server_cudo") . "assets/cp/" . $key->image_3;

                            $image = $this->config->item('base_url_api') . "assets/images/cp/" . $key->image_1;
                            $image2 = $this->config->item('base_url_api') . "assets/images/cp/" . $key->image_2;
                            $image3 = $this->config->item('base_url_api') . "assets/images/cp/" . $key->image_3;
                            
                            $result = $this->curl->simple_get($image_cudo);
                            $result2 = $this->curl->simple_get($image_cudo2);
                            $result3 = $this->curl->simple_get($image_cudo3);
                            
                            if($result != "" || $result2 != "" || $result3 != ""){
                                $new_img_1 = $image_cudo;
                                $new_img_2 = $image_cudo2;
                                $new_img_3 = $image_cudo3;
                            }elseif(@getimagesize($image)){
                                $new_img_1 = $image;
                                $new_img_2 = $image2;
                                $new_img_3 = $image3;
                            }else{
                                $new_img_1 = $no_image;
                                $new_img_2 = $no_image;
                                $new_img_3 = $no_image;
                            }
                        } else {
                            $image = $this->config->item('base_url_api') . "assets/images/cp/" . $key->image_1;
                            $image2 = $this->config->item('base_url_api') . "assets/images/cp/" . $key->image_2;
                            $image3 = $this->config->item('base_url_api') . "assets/images/cp/" . $key->image_3;

                            if(@getimagesize($image)){
                                $new_img_1 = $image;
                                $new_img_2 = $image2;
                                $new_img_3 = $image3;
                            }else{
                                $new_img_1 = $no_image;
                                $new_img_2 = $no_image;
                                $new_img_3 = $no_image;
                            }
                        }

                        $count++;
                        if ($count % 2 == 0) {
                            $classnya = "tcontainer tleft";
                        } else {
                            $classnya = "tcontainer tright";
                        }

                ?>

                    <div class="<?php echo $classnya ?>">
                        <div class="content">
                            <h4><?php echo $key->cp_id ?></h4>
                            <p><?php echo $key->note ?></p>
                        </div>
                        <div class="img-content">
                            <center>
                                <div id="carouselExampleControls<?= $count ?>" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner1">
                                        <div class="carousel-item active">
                                            <img class="myImage img-responsive" src="<?php echo $new_img_1 ? $new_img_1 : base_url('assets/apps/assets/no_image.png'); ?>" />
                                        </div>
                                        <div class="carousel-item">
                                            <img class="myImage img-responsive" src="<?php echo $new_img_2 ? $new_img_2 : base_url('assets/apps/assets/no_image.png'); ?>" />
                                        </div>
                                        <div class="carousel-item">
                                            <img class="myImage img-responsive" src="<?php echo $new_img_3 ? $new_img_3 : base_url('assets/apps/assets/no_image.png'); ?>" />
                                        </div>
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleControls<?= $count ?>" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleControls<?= $count ?>" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </center>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!--/.body content-->
</div>
<!--/.main content-->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img id='img_modal' style="border: 15px solid white; height: 500px" src="#" />
        </div>
    </div>
</div>