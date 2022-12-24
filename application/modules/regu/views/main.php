<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item"><?= $this->lang->line('absensi') ?></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('regu') ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-group"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('regu') ?></h1>
                <!-- <small>Mengatur regu dan mengganti shift regu</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <button id="tambah" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#addmodal"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br>
            <table id="myTable" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <div class="cusfil"></div>
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th style="width: 20px;">No</th>
                        <th><?= $this->lang->line('group_name') ?></th>
                        <th style="width: 150px;"><?= $this->lang->line('shift') ?></th>
                        <th><?= $this->lang->line('start_time') ?></th>
                        <th><?= $this->lang->line('end_time') ?></th>
                        <th><?= $this->lang->line('active_at') ?></th>
                        <th><?= $this->lang->line('status') ?></th>
                        <th style="width: 55px;"></th>
                        <th style="width: 50px;"></th>
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

<div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?= $this->lang->line('add') . ' ' . $this->lang->line('regu') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('regu/add')" method="POST">
                    <div class="form-group">
                        <label><?= $this->lang->line('group_name') ?></label>
                        <input name="nama_regu" type="text" class="form-control">
                    </div>
                    <!-- <div class="form-group">
                        <label>Leader</label>
                        <select class="form-control" name="leader">
                            <option value="0">-- Select Danru --</option>
                            <?php foreach ($leader as $row) { ?>
                                <option value="<?= $row->id ?>"><?= $row->full_name ?></option>
                            <?php } ?>
                        </select>
                    </div> -->
                    <div class="form-group">
                        <label>Shift</label>
                        <select name="shift_regu" class="form-control">
                            <option value="0">Pilih Shift</option>
                            <?php foreach ($shift as $row) { ?>
                                <option value="<?= $row->id_ ?>"><?= $row->shift_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
                <button type="submit" class="btn btn-success"><?= $this->lang->line('apply') ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?= $this->lang->line('delete') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="deltitle"><?= $this->lang->line('are_you_sure') ?></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
                <form action="<?= base_url() ?>regu/hapus" method="POST">
                    <input type="hidden" id="hid" name="hid">
                    <button type="submit" class="btn btn-success"><?= $this->lang->line('delete') ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel7" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel7"><?= $this->lang->line('edit') . ' ' . $this->lang->line('group') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="alert"></div>
                <form onsubmit="return subm('regu/edit')">
                    <div class="form-group">
                        <label><?= $this->lang->line('group_name') ?></label>
                        <input name="enama_regu" type="text" class="form-control" value="" required>
                        <input id="editId" name="eid" type="hidden">
                    </div>
                    <div class="form-group">
                        <label><?= $this->lang->line('status') ?></label>
                        <select name="flag_active" class="form-control">
                            <option value="1"><?= $this->lang->line('active') ?></option>
                            <option value="0"><?= $this->lang->line('inactive') ?></option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
                <button type="submit" class="btn btn-success update"><?= $this->lang->line('apply') ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeActive" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Group Setting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('regu/shift')" method="POST">
                    <div class="form-group">
                        <label id="">Shift</label>
                        <input type="hidden" id="sid" name="sid">
                        <select name="shift_regu" class="form-control">
                            <option value="0">Pilih Shift</option>
                            <?php foreach ($shift as $row) { ?>
                                <option value="<?= $row->id_ ?>"><?= $row->shift_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>Branch</label>
                                <select name="mode" onchange="getConf(this.value)" class="form-control">
                                    <option value="0">Pusat</option>
                                    <option value="1">Project</option>
                                    <option value="2">Cabang</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <!-- <input type="hidden" id="usrid"> -->
                                <label>&nbsp;</label>
                                <select name="par" disabled class="form-control" id="config">
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" type="button" class="btn btn-success text-light">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/.main content-->