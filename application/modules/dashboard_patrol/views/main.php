<style>
    .text-success {
        color: #28a745 !important;
    }
</style>
<div class="body-content">

    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="card card-stats statistic-box mb-4">
                        <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                            <div class="card-icon d-flex align-items-center justify-content-center">
                                <i class="fa fa-users"></i>
                            </div>
                            <p class="card-category text-uppercase fs-14 font-weight-bold text-muted">Guest</p>
                            <h3 class="card-title fs-28 font-weight-bold"><?php echo $data['tamu'] ?></h3>
                        </div>
                        <div class="card-footer p-3">
                            <div class="stats">
                                <i class="typcn typcn-calendar-outline mr-2"></i>Todays Guest
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="card card-stats statistic-box mb-4">
                        <div class="card-header card-header-success card-header-icon position-relative border-0 text-right px-3 py-0">
                            <div class="card-icon d-flex align-items-center justify-content-center">
                                <i class="fa fa-check"></i>
                            </div>
                            <p class="card-category text-uppercase fs-14 font-weight-bold text-muted">Patrol Complete
                            </p>
                            <h3 class="card-title fs-28 font-weight-bold"><?php echo $data['complate'] ?></h3>
                        </div>
                        <div class="card-footer p-3">
                            <div class="stats">
                                Todays Patrol by Security
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="card card-stats statistic-box mb-4">
                        <div class="card-header card-header-warning card-header-icon position-relative border-0 text-right px-3 py-0">
                            <div class="card-icon d-flex align-items-center justify-content-center">
                                <!-- <h3 style="padding-top: 15px" class="text-white font-weight-bold">SOS</h3> -->
                                <i class="fa fa-bell"></i>
                            </div>
                            <p class="card-category text-uppercase fs-14 font-weight-bold text-muted">Alert</p>
                            <h3 class="card-title fs-28 font-weight-bold"><?php echo $data['alert'] ?></h3>
                        </div>
                        <div class="card-footer p-3">
                            <div class="stats">
                                Todays Alert Severity
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="card card-stats statistic-box mb-4">
                        <div class="card-header card-header-danger card-header-icon position-relative border-0 text-right px-3 py-0">
                            <div class="card-icon d-flex align-items-center justify-content-center">
                                <i class="fa fa-exclamation"></i>
                            </div>
                            <p class="card-category text-uppercase fs-14 font-weight-bold text-muted">Incident</p>
                            <h3 class="card-title fs-28 font-weight-bold"><?php echo $data['incident'] ?></h3>
                        </div>
                        <div class="card-footer p-3">
                            <div class="stats">
                                Todays Incident on Coverage Area
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="section">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <center>
                                    <h6 class="fs-24 font-weight-600 mb-0">Patrol</h6>
                                </center>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <h6 class="fs-17 font-weight-600 right">
                                            <?= $this->lang->line('current_month_sec'); ?></h6>
                                        <div id="donutchart" style="height: 250px;"></div>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <br>
                                        <div style="padding-bottom: 5%; font-size: 1em">
                                            <i style="color: #b81919" class="fa fa-circle mr-2"></i>
                                            <span><?= $this->lang->line('critical'); ?></span>
                                        </div>
                                        <div style="padding-bottom: 5%; font-size: 1em">
                                            <i style="color: #ed9a00" class="fa fa-circle mr-2"></i>
                                            <span><?= $this->lang->line('alert'); ?></span>
                                        </div>
                                        <div style="padding-bottom: 5%; font-size: 1em">
                                            <i style="color: #0c8456" class="fa fa-circle mr-2"></i>
                                            <span><?= $this->lang->line('secured'); ?></span>
                                        </div>
                                        <div style="padding-bottom: 5%; font-size: 1em" class="font-weight-bold">
                                            <span class="mr-2">Checkpoint</span><?= $donut['total'] ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div style="height: 250px" id="highbar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <center>
                                    <h6 class="fs-24 font-weight-600 mb-0">SOS</h6>
                                </center>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div style="height: 300px" id="sosbar1"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div style="height: 300px" id="sosbar2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <center>
                                    <h6 class="fs-24 font-weight-600 mb-0">Incident</h6>
                                </center>
                                <br>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <center>
                                            <h6 class="fs-17 font-weight-600 mb-0">
                                                <?= $this->lang->line('current_month_inc'); ?></h6>
                                        </center>
                                        <div style="height: 250px;" id="barchart"></div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div style="height: 250px" id="highline"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section" id="incident">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-24 font-weight-600 mb-0"><?= $this->lang->line('dash_map'); ?>
                                            &trade;</h6>
                                    </div>
                                    <div class="text-right">
                                        <select id="mon" class="form-control" onchange="callmap(this.value)">
                                            <option value="0">--<?= $this->lang->line('select_month'); ?>--</option>
                                            <option value="1"><?= $this->lang->line('last_month'); ?></option>
                                            <option value="3">3 <?= $this->lang->line('month'); ?></option>
                                            <option value="6">6 <?= $this->lang->line('month'); ?></option>
                                            <option value="12">12 <?= $this->lang->line('month'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="map" style="height: 400px; width: 100%;"></div>

                                <div style="padding: 10px; border: solid grey 3px;border-radius: 5px;background: #F1F1F1; width:180px; height: 200px; position: absolute; bottom: 25px; right: 25px">
                                    <!-- <h4>Legend</h4> -->
                                    <div class="form-group form-inline">
                                        <img width="30" src="<?= base_url('assets/apps/assets/dist/img/incident/accident.png') ?>">
                                        <label class="fs-15 ml-1">Kecelakaan</label>
                                    </div>
                                    <div class="form-group form-inline">
                                        <img width="30" src="<?= base_url('assets/apps/assets/dist/img/incident/dead.png') ?>">
                                        <label class="fs-15 ml-1">Kematian</label>
                                    </div>
                                    <div class="form-group form-inline">
                                        <img width="30" src="<?= base_url('assets/apps/assets/dist/img/incident/fire.png') ?>">
                                        <label class="fs-15 ml-1">Kebakaran</label>
                                    </div>
                                    <div class="form-group form-inline">
                                        <img width="30" src="<?= base_url('assets/apps/assets/dist/img/incident/steal.png') ?>">
                                        <label class="fs-15 ml-1">Pencurian</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div style="max-width: 1000px" class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div id="cpmap" style="min-height: 550px; border: solid white 12px"></div>
                    </div>
                </div>
            </div>


            <div class="modal right fade" tabindex="-1" id="modal-close-only">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.main content-->