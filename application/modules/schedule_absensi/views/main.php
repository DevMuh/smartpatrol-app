<link rel="stylesheet" href="<?= base_url('assets/apps/assets/plugins/sweetalert/sweetalert.css') ?>">
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.1.0/css/fixedColumns.dataTables.min.css"> -->
<style type="text/css">
    .profile-upload:hover {
        cursor: pointer;
        background: rgba(0, 0, 0, 0.15);
    }

    .profile-upload:hover .btn-plus {
        display: block;
    }

    .btn-plus {
        display: none;
        top: 70px;
        right: 65px;
        font-size: 20px;
        color: #fff
    }
</style>
<style>
    @media screen and (min-width: 676px) {
        .modal-dialog {
        /* max-width: 1125px; New width for default modal */
        max-width: 100%;
        max-height: 80% !important;
        margin-left: 12px;
        }
    }

    .modal-body{
        max-height: 70vh;
        overflow-y: auto;
    }
</style>
<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item"><a href="#"><?= $this->lang->line('master') ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('schedule_absensi') ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-calendar"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('schedule_absensi') ?></h1>
                <!-- <small>Register new b2b</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <!-- <ul class="nav nav-tabs header-tabs right">
                <li class="nav-item">
                    <a href="#" id="0" class="nav-link text-center show active" data-toggle="tab">
                        <h6 class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-1">
                            View Row
                        </h6>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="2" class="nav-link text-center show " data-toggle="tab">
                        <h6 class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-1">
                            View Column
                        </h6>
                    </a>
                </li>
            </ul> -->
            <div class="row">
                <div class="col-md-3 d-flex">
                    <button id="tambah" type="button" class="btn btn-info mb-2" data-toggle="modal" data-target="#exampleModal1"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br>
                    &nbsp;
                    <!--<a href="<?= base_url('assets/template-import/template-import-row.xlsx') ?>" target="_blank" class="btn btn-sm btn-info mb-2" title="Download Template Row Import">
                        <span style="font-size:25px;" class="typcn typcn-download"></span> Template Row
                    </a>
                    &nbsp;
                    <a class="btn btn-info mb-2" title="Import Template Row" style="color: white;">
                        <label for="exampleFormControlFile1" style="margin-bottom: 0;"><span style="font-size:25px;" class="typcn typcn-upload"></span> Import Row</label>
                        <input type="file" class="upload up" name="userfile" id="exampleFormControlFile1" onchange="processupload(event);" style="display: none"/>
                    </a>
                    &nbsp;
                    <a href="<?= base_url('assets/template-import/template-import-column.xlsx') ?>" target="_blank" class="btn btn-info mb-2" title="Download Template Column Import">
                        <span style="font-size:25px;" class="typcn typcn-download"></span> Template Column
                    </a>-->
                </div> 
                <div class="col-md-9">
                    <ul class="nav nav-pills right" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">View Row</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">View Column</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <div id="filterDate" class="row"></div>
                    </div>
                    <div class="col-md-12"><?php echo $this->session->flashdata('success'); ?></div>
                    <div class="tab-content" id="pills-tabContent" style="margin-top: 25px;">
                        <div class="tab-pane fade " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <table id="tb_schedule" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                <thead style="background-color: black;">
                                    <tr style="color:white">
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Shift Code</th>
                                        <th>Shift Name</th>
                                        <th>Start Shift</th>
                                        <th>End Shift</th>
                                        <th style="width: 92px;"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <?php
                                $year = date("Y");
                                $month = date('n');
                                // echo $month;
                                // echo "<bre>";
                                // echo $year;

                                $d=cal_days_in_month(CAL_GREGORIAN,$month,$year);
                                //echo "There was $d days in Sept 2022.<br>";
                            ?>
                            <table id="tb_schedule_column" class="table display nowrap table-bordered  table-striped table-hover sourced dataTable ">
                                <div class="cusfil"></div>
                                <thead style="background-color: black;">
                                    <tr style="color:white;">
                                        <th style="width: 100px;" rowspan="2">No</th>
                                        <th style="width: 100px;" rowspan="2">User</th>
                                        <th style="width: 100px;" colspan="<?php echo $d; ?>" class="text-center">Tanggal</th>
                                    </tr>
                                    <tr style="color:white;">
                                        <?php for ($i=1; $i <= $d; $i++) { ?>

                                        <th style="width: 100px;"><?php echo $i; ?></th>

                                        <?php } ?>
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
    </div>
</div>
<!--/.body content-->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?= $this->lang->line('add') ?> Schedule Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formScheduleAbsensi">
                    <input type="hidden" name="act" id="act" value="create">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Date</label>
                                <input name="schedule_date" id="schedule_date" type="date" class="form-control">
                                <div class="invalid-feedback">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>User</label>
                                <select class="form-control" name="user_id" id="user_id">
                                </select>
                                <div class="invalid-feedback">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Shift</label>
                                <select class="form-control" name="shift_id" id="shift_id">
                                </select>
                                <div class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
                <button type="button" id="btnSave" onclick="save('create')" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?= $this->lang->line('edit') ?> Schedule Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editformScheduleAbsensi">
                    <input type="hidden" name="act" id="act" value="update">
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Date</label>
                                <input name="edit_schedule_date" id="edit_schedule_date" type="date" class="form-control">
                                <div class="invalid-feedback">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>User</label>
                                <select class="form-control" name="edit_user_id" id="edit_user_id">
                                </select>
                                <div class="invalid-feedback">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Shift</label>
                                <select class="form-control" name="edit_shift_id" id="edit_shift_id">
                                </select>
                                <div class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
                <button type="button" id="btnEditSave" onclick="save('update')" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="new-table-modal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <div class="container" style="max-width: 100%;">
                <div class="row" style="margin-bottom: 25px;">
                    <div class="col-md-4"><h5 class="modal-title"><div id="modal-title"></div></h5></div>  
                    <div class="col-md-4" id="labelMonth" style="color: white;"></div>
                    <div class="col-md-4" id="labelTotalRow" style="color: white;"></div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
            <div class="modal-body">
            <div class="container pl-0 pr-0" style="max-width: 100%;">
                <form id="new_table_form">
                    <input type="hidden" name="type_import" id="type_import">
                    <div id="content-here" class="card-body p-0 text-center col-md-12"></div>
                </form>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-shadow" id="save_new_table">Simpan</button>
            </div>
        </div>
    </div>
</div>