<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item"><?= $this->lang->line('absent') ?></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('shift') ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-time"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('shift') ?></h1>
                <!-- <small>Mengatur jadwal shift</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <button id="tambah" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#addModal"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br>
            <div class="form-group form-inline">
                <label><?= $this->lang->line('select_shift') ?>&nbsp;</label>
                <div class="cfill"></div>
            </div>
            <table id="tb_client" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th><?= $this->lang->line('shift_code') ?></th>
                        <th><?= $this->lang->line('shift_name') ?></th>
                        <th><?= $this->lang->line('start_time') ?></th>
                        <th><?= $this->lang->line('end_time') ?></th>
                        <th>In Early (min)</th>
                        <th>In Late (min)</th>
                        <th>Out Late (min)</th>
                        <th>Type</th>
                        <th><?= $this->lang->line('duration') ?></th>
                        <th><?= $this->lang->line('status') ?></th>
                        <th style="width: 5px"></th>
                        <th style="width: 5px"></th>
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
<!-- <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Tambah Shift</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('shift/add')" method="POST">
                    <div class="form-group">
                        <label>Nama Shift</label>
                        <input name="shift_name" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Jam Masuk</label>
                        <input name="jam_masuk" type="time" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Jam Pulang</label>
                        <input name="jam_pulang" type="time" class="form-control">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutp</button>
                <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div> -->

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
</style>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Tambah Shift</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('shift/add')" method="POST">
                    <!-- <form action="<?= base_url('shift/add') ?>" method="POST"> -->
                    <div class="form-group">
                        <label>Kode Shift</label>
                        <input name="kode_shift" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Shift</label>
                        <input name="shift_name" type="text" class="form-control" required>
                    </div>
                    <!-- <div class="form-group">
                        <label>Hari</label>
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
                    </div> -->
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Jam Masuk</label>
                            <input name="jam_masuk" type="time" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Jam Pulang</label>
                            <input name="jam_pulang" type="time" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Durasi</label>
                            <input readonly type="text" placeholder="*auto generate" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Type</label>
                            <input readonly type="text" placeholder="*auto generate" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>In Early</label>
                            <div class="input-group">
                                <input name="in_early" type="number" class="form-control" min="0" step="1" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">min</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>In Late</label>
                            <div class="input-group">
                                <input name="in_late" type="number" class="form-control" min="0" step="1" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">min</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Out Late</label>
                            <div class="input-group">
                                <input name="out_late" type="number" class="form-control" min="0" step="1" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">min</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group ">
                        <label>Same Day?</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="is_same_day_shift_yes" name="is_same_day_shift" checked value="true">
                            <label class="form-check-label" for="is_same_day_shift_yes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="is_same_day_shift_no" name="is_same_day_shift" value="false">
                            <label class="form-check-label" for="is_same_day_shift_no">No</label>
                        </div>
                    </div> -->

                    <div style="margin-top: 10px" class="row">
                        <!-- <div class="col-md-4">
                            <div class="form-group form-inline">
                                <label>Jam Mulai&nbsp;</label>
                                <input name="jam_mulai" type="time" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-inline">
                                <label>Durasi Per Shift&nbsp;</label>
                                <input id="dura" name="durasi" type="number" min="0" max="24" class="form-control">
                                <label>&nbsp;Jam</label>
                            </div>
                        </div> -->

                    </div>
                    <!-- <div class="form-group">
                        <label>Jumlah Shift Per Hari</label>
                        <select class="form-control" name="jml_shift" id="jml_shift">
                        </select>
                    </div> -->
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Edit Shift</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('shift/edit')" method="POST" id="editForm">
                    <div class="form-group">
                        <label>Kode Shift</label>
                        <input name="ekode_shift" type="text" readonly class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nama Shift</label>
                        <input name="eshift_name" type="text" class="form-control">
                        <input id="editId" name="eid" type="hidden" class="form-control">
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Jam Masuk</label>
                            <input name="ejam_masuk" type="time" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Jam Pulang</label>
                            <input name="ejam_pulang" type="time" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>In Early</label>
                            <div class="input-group">
                                <input name="ein_early" type="number" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">min</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>In Late</label>
                            <div class="input-group">
                                <input name="ein_late" type="number" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">min</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Out Late</label>
                            <div class="input-group">
                                <input name="eout_late" type="number" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">min</span>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Ganti Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="deltitle">Ganti status shift?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <form action="<?= base_url() ?>shift/hapus" method="POST">
                    <input type="hidden" id="hid" name="hid">
                    <input type="hidden" id="stat" name="stat">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>