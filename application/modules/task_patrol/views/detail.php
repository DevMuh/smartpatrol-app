<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Page</a></li>
            <li class="breadcrumb-item active">Clint List</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-puzzle-outline"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Client List</h1>
                <small>Client List</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <div class="">
                <?php
                if ($code == 200) {
                    ?>
                    <div class="row">
                        <div class="col-md-12">

                            <table class="table table-hover table-striped table-bordered">
                                <tr>
                                    <th>CP ID</th>
                                    <td><?= $cp_id ?></td>
                                </tr>
                                <tr>
                                    <th>CP Name</th>
                                    <td><?= $cp_name ?></td>
                                </tr>
                                <tr>
                                    <th>CP Nfc</th>
                                    <td><?= $cp_nfc ?></td>
                                </tr>
                                <tr>
                                    <th>CP Qr</th>
                                    <td><?= $cp_qr ?></td>
                                </tr>
                                <tr>
                                    <th>CP Lat</th>
                                    <td><?= $cp_lat ?></td>
                                </tr>
                                <tr>
                                    <th>CP Lang</th>
                                    <td><?= $cp_long ?></td>
                                </tr>
                                <tr>
                                    <th>Cluster Name</th>
                                    <td><?= $cluster_name ?></td>
                                </tr>
                                <tr>
                                    <th>Remark</th>
                                    <td><?= $remark ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <center>
                                <?php
                                    foreach ($images as $row) {
                                        ?>
                                    <img src="<?= $row ?>" width="200" height="200">
                                <?php
                                    }
                                    ?>
                            </center>
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
    </div>
</div>
<!--/.body content-->
</div>
<!--/.main content-->