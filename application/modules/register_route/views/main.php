<!--Content Header (Page header)-->
<style>
    .daycard {
        height: 100px;
        border: solid rgba(0, 0, 0, 0.15) 1px;
        margin-right: 5px;
        line-height: 100px;
        border-radius: 5px;
        color: black;
        font-size: 20px;
        text-align: center;
        vertical-align: middle;
        background: rgba(0, 0, 0, 0.15);
    }

    .daycard:hover {
        cursor: pointer;
        background: grey;
    }

    .daycard.dok {
        background: #28a745;
        border: solid #28a745 1px;
        color: white;
    }

    #preview {
        position: relative;
    }

    #table-scroll {
        height: 150px;
        overflow: auto;
        margin-top: 20px;
    }

    #preview table {
        width: 100%;

    }

    #preview table thead th .text {
        position: absolute;
        top: -20px;
        z-index: 2;
        height: 20px;
        width: 35%;
    }
</style>
<div class="modal fade" id="scheduler" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="title_modal_schedule"><?= $this->lang->line('task_schedule'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('register_route/scheduler')" method="POST">
                    <input type="hidden" name="scid" id="scid">
                    <div class="form-group form-inline">
                        <label for="">Schedule Type &nbsp;</label>
                        <select class="form-control " name="schedule_type" id=""></select>
                    </div>
                    <div class="form-group w-daycard">
                        <label><?= $this->lang->line('day'); ?></label>
                        <div style="margin: 0px" class="row">
                            <div class="col-md daycard">
                                <span>Senin</span>
                                <input type="hidden" name="day[]" disabled>
                            </div>
                            <div class="col-md daycard">
                                <span>Selasa</span>
                                <input type="hidden" name="day[]" disabled>
                            </div>
                            <div class="col-md daycard">
                                <span>Rabu</span>
                                <input type="hidden" name="day[]" disabled>
                            </div>
                            <div class="col-md daycard">
                                <span>Kamis</span>
                                <input type="hidden" name="day[]" disabled>
                            </div>
                            <div class="col-md daycard">
                                <span>Jumat</span>
                                <input type="hidden" name="day[]" disabled>
                            </div>
                            <div class="col-md daycard">
                                <span>Sabtu</span>
                                <input type="hidden" name="day[]" disabled>
                            </div>
                            <div class="col-md daycard">
                                <span>Minggu</span>
                                <input type="hidden" name="day[]" disabled>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 10px" class="row">
                        <div class="col-md-4">
                            <div class="form-group form-inline w-jam-mulai">
                                <label>Jam Kirim&nbsp;</label>
                                <input name="jam_mulai" type="time" class="form-control">
                            </div>
                        </div>
                    </div>
                    <!--  <div class="col-md-4">
                            <div class="form-group form-inline">
                                <label>Interval&nbsp;</label>
                                <input id="interval" name="interval" type="number" min="0" max="24" class="form-control">
                                <label>&nbsp;Jam</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-inline">
                                <label>Expired&nbsp;</label>
                                <input id="schedule_expired" name="schedule_expired" type="number" min="0" max="14" class="form-control">
                                <label>&nbsp;Hari</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Pengiriman Perhari</label>
                        <select class="form-control" name="jml_kirim" id="jml_kirim">
                        </select>
                    </div>
                    <div id="preview">
                        <div id="table-scroll">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 90%"><?= $this->lang->line('send_at'); ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="body_prev" style="height: 200px; overflow: scroll;">
                                </tbody>
                            </table>
                        </div>
                    </div> -->
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <button type="submit" id="save" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home'); ?></a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item active"><?= $this->lang->line('route'); ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-map"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('route'); ?></h1>
                <small>Daftar rute tersedia</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div>
                    <button id="tambah" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#tambahModal"><span style="font-size:25px;" class="typcn typcn-plus"></span></button>&nbsp;
                </div>
                <div class="col-md-3 d-flex flex-column p-3 mb-3 shadow-sm rounded" style="background-color: #ffc107; height: 55px">
                    <div class="d-flex align-items-center">
                        <h4 style="margin-top: -3px" id="total"></h4>
                    </div>
                </div>
            </div>
            <table id="tb_client" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th style="width: 20px;">No</th>
                        <th style="width: 20%"><?= $this->lang->line('cluster_name'); ?></th>
                        <th><?= $this->lang->line('description'); ?></th>
                        <th>Group</th>
                        <th style="width: 10%"><?= $this->lang->line('status'); ?></th>
                        <th style="width: 55px"></th>
                        <th style="width: 55px"></th>
                        <th style="width: 50px"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/.body content-->
</div>
<!--/.main content-->
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?= $this->lang->line('add'); ?> Cluster Route</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('register_route/tambah')" method="POST">
                    <div class="form-group">
                        <label>Cluster Name</label>
                        <input name="cluster_name" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Group</label>
                        <select name="group_id" class="form-control">
                            <?php foreach ($groups as $group) : ?>
                                <option value="<?= $group->id ?>"><?= $group->nama_regu ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Apply</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Edit Cluster Route</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('register_route/edit')" method="POST">
                    <div class="form-group">
                        <label>Cluster Name</label>
                        <input name="ecluster_name" type="text" class="form-control">
                        <input name="eid" type="hidden" id="editId">
                    </div>
                    <div class="form-group">
                        <label for="">Group</label>
                        <select name="group_id" class="form-control">
                            <?php foreach ($groups as $group) : ?>
                                <option value="<?= $group->id ?>"><?= $group->nama_regu ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="edescription" rows="3" class="form-control"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Apply</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="deltitle">Delete this data?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <form action="<?= base_url() ?>register_route/hapus" method="POST">
                    <input type="hidden" id="hid" name="hid">
                    <button type="submit" class="btn btn-success">Apply</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hapusChecpointModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;z-index: 10000;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="deltitleChecpointModal">Delete this data?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success" id="remove_cp_button" data-id="">Yes, remove it</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Send Mannually To?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body sendModalBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success js-submit-asign"> <span class="fa fa-paper-plane"></span> Send</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="asignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Setting Assign</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body asignModalBody d-flex justify-content-center">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addroute" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="routetitle">Route</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('register_route/route')" method="POST">
                    <input type="hidden" name="rid" id="rid">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8">
                                <label>Map <small class="text-muted">(Included Available & Current Cluster Route Checkpoint)</small></label>
                                <div id="map" style="height: 350px">
                                    <div id="popup"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <?php if ($this->session->flashdata('route_error')) { ?>
                                        <div class="alert alert-danger" id="error-alert"> <?= $this->session->flashdata('route_error') ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                    <?php } ?>
                                    <div class='form-group'>
                                        <label>Checkpoint</label>
                                        <small class="text-muted">(Select to add checkpoint for this route)</small>
                                        <select name='cpoint[]' class="form-control placeholder-multiple select2-hidden-accessible" multiple="" data-select2-id="13" tabindex="-1" aria-hidden="true">
                                            <optgroup data-select2-id="1" id="opt">
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Selected Route</label>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 5px">No</th>
                                                <th>CPID</th>
                                                <th>CP Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sopt"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Apply</button>
                </form>
            </div>
        </div>
    </div>
</div>