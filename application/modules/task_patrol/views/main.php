<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home'); ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('patrol_list'); ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-zoom"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('patrol_list'); ?></h1>
                <!-- <small>The list of patrol</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">

    <div class="row">
        <a class="col-md-3 col-lg-3" data-detail="<?= htmlspecialchars(json_encode($detail_security_summary), ENT_QUOTES, 'UTF-8') ?>" data-toggle="collapse" href="#summary_column" role="button" aria-expanded="false" aria-controls="summary_column">
            <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-white shadow-sm rounded">
                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Total Security</div>
                <i style="color: <?= $color_security ?>" class="fas fa fa-shield-alt opacity-25 fa-5x decorative-icon"></i>
                <div class="d-flex">
                    <h2><?= $total_security ?></h2>
                </div>
            </div>
        </a>
        <a class="col-md-3 col-lg-3" data-detail="<?= htmlspecialchars(json_encode($detail_checkpoint_summary), ENT_QUOTES, 'UTF-8') ?>" data-toggle="collapse" href="#summary_column" role="button" aria-expanded="false" aria-controls="summary_column">
            <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-white shadow-sm rounded">
                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Total Checkpoint</div>
                <i style="color: <?= $color_checkpoint ?>" class="fas fa fa-map-marker opacity-25 fa-5x decorative-icon"></i>
                <div class="d-flex">
                    <h2><?= $total_checkpoint ?></h2>
                </div>
            </div>
        </a>
        <a class="col-md-3 col-lg-3" data-detail="<?= htmlspecialchars(json_encode($detail_route_summary), ENT_QUOTES, 'UTF-8') ?>" data-toggle="collapse" href="#summary_column" role="button" aria-expanded="false" aria-controls="summary_column">
            <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-white shadow-sm rounded">
                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Total Route</div>
                <i style="color: <?= $color_route ?>" class="fas fa fa-map opacity-25 fa-5x decorative-icon"></i>
                <div class="d-flex">
                    <h2><?= $total_route ?></h2>
                </div>
            </div>
        </a>
        <a class="col-md-3 col-lg-3" data-detail="<?= htmlspecialchars(json_encode($detail_schedule_summary), ENT_QUOTES, 'UTF-8') ?>" data-toggle="collapse" href="#summary_column" role="button" aria-expanded="false" aria-controls="summary_column">
            <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-white shadow-sm rounded">
                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Total Route Has Schedule</div>
                <i style="color: <?= $color_schedule ?>" class="fas fa fa-clock opacity-25 fa-5x decorative-icon"></i>
                <div class="d-flex">
                    <h2><?= $total_schedule ?></h2>
                </div>
            </div>
        </a>
    </div>
    <div id="summary_column" class="row collapse accordion">
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <!-- <div style="margin-bottom: -75px; margin-top: 30px">
                <label>Show</label>
                <select style="width: 60px;" class="custom-select custom-select-sm form-control form-control-sm" onchange="changeLength(this.value)">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <label> entries</label>
            </div> -->
                    <!-- <div style="width: 100%; height: 50px; background-color: orange; color: white; padding: 12px; border-radius: 5px; margin-bottom: 10px;">
                        <h3 style="font-weight: bold">Last Patrol</h3>
                    </div> -->

                    <table id="myTable" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                        <thead style="background-color: black;">
                            <tr style="color:white">
                                <th style="width: 15%"><?= $this->lang->line('cluster_name'); ?></th>
                                <th style="width: 16%"><?= $this->lang->line('user'); ?></th>
                                <th style="width: 10%"><?= $this->lang->line('start'); ?></th>
                                <th style="width: 10%"><?= $this->lang->line('stop'); ?></th>
                                <th style="width: 5%;"><?= $this->lang->line('duration'); ?></th>
                                <th style="width: 3%;"><?= $this->lang->line('total_cp'); ?></th>
                                <!-- <th style="width: 5%;"><?= $this->lang->line('total_done'); ?></th> -->
                                <th style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.body content-->
</div>
<!--/.main content-->