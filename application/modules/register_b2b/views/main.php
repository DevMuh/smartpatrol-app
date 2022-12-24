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
<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item"><a href="#"><?= $this->lang->line('master') ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('register_b2b') ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user-add"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('register_b2b') ?></h1>
                <!-- <small>Register new b2b</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <button id="tambah" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#exampleModal1"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br>
            &nbsp;
            <div class="col-md-12"><?php echo $this->session->flashdata('success'); ?></div>
            <table id="tb_b2b" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th><?= $this->lang->line('name') ?></th>
                        <th>Level</th>
                        <th>Parent Organization</th>
                        <th>Join Date</th>
                        <!-- <th><?= $this->lang->line('address') ?></th> -->
                        <th>Domain</th>
                        <th><?= $this->lang->line('phone') ?></th>
                        <th>Hidden Module</th>
                        <th style="width: 3px;">Status Schedule</th>
                        <th style="width: 92px;"></th>
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
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?= $this->lang->line('add') ?> B2B</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <form onsubmit="return subm('register_b2b/add2')" method="POST"> -->
                <form action="<?= base_url('register_b2b/add2') ?>" enctype="multipart/form-data" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?= $this->lang->line('name') ?></label>
                                <input name="title_nm" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label><?= $this->lang->line('address') ?></label>
                                <textarea rows="3" name="alamat" type="text" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Domain</label>
                                <input name="domain" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label><?= $this->lang->line('phone') ?></label>
                                <input onkeypress="validate(event)" name="phone" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Module</label>
                                <div class="form-check">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 29%;"><label><input name="feature[]" class="form-check-input position-static" type="checkbox" checked value="absen"> Absen</label></td>
                                            <td style="width: 29%;"><label><input name="feature[]" class="form-check-input position-static" type="checkbox" checked value="patroli"> Patrol</label></td>
                                            <td style="width: 29%;"><label><input name="feature[]" class="form-check-input position-static" type="checkbox" checked value="kejadian"> Kejadian</label></td>
                                            <td style="width: 29%;"><label><input name="feature[]" class="form-check-input position-static" type="checkbox" checked value="tamu"> Tamu</label></td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="hidden_feature">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="profileUp" style="height: 150px">
                                    <img id="profimg" style="max-height: 150px; max-width: 150px;" src="<?= base_url() ?>assets/apps/images/blanklogo.png">
                                    <input name="logo" type="file" id="profileUp" style="display:none">
                                    <div style="height: 150px; width: 150px; position: absolute; z-index: 1000; top: 0px; left: 15px; right: 0px; bottom: 0;" class="profile-upload">
                                        <i class="fa fa-undo position-absolute btn-plus"></i>
                                    </div>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Level Organization</label>
                                <select name="level" class="form-control level">
                                    <option value="1">Pusat</option>
                                    <option value="2">Cabang</option>
                                    <option value="3">Project</option>
                                </select>
                            </div>
                            <div class="form-group d-none cabang">
                                <label>Parent Organization</label>
                                <select name="pusat" class="form-control">
                                    <?php
                                    $parent = $this->db->where('level', 1)->get('m_register_b2b')->result();
                                    if (count($parent) > 0) {
                                        foreach ($parent as $row) {
                                    ?>
                                            <option value="<?= $row->b2b_token ?>"> <?= $row->title_nm ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group d-none project">
                                <label>Parent Organization</label>
                                <select name="cabang" class="form-control">
                                    <?php
                                    $parent = $this->db->where('level', 2)->get('m_register_b2b')->result();
                                    if (count($parent) > 0) {
                                        foreach ($parent as $row) {
                                    ?>
                                            <option value="<?= $row->b2b_token ?>"> <?= $row->title_nm ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Schedule B2B</label>
                                <div class="form-check">
                                    <label><input name="status_schedule" class="form-check-input position-static" type="checkbox" value="status_schedule"> Status Schedule</label>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
                <button type="submit" class="btn btn-success"><?= $this->lang->line('apply') ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?= $this->lang->line('edit') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <form id="form_edit" onsubmit="return subm('register_b2b/edit2')" method="POST"> -->
                <form action="<?= base_url('register_b2b/edit2') ?>" enctype="multipart/form-data" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?= $this->lang->line('name') ?></label>
                                <input name="etitle_nm" type="text" class="form-control">
                                <input name="eid" type='hidden' id="eid">
                            </div>
                            <div class="form-group">
                                <label><?= $this->lang->line('address') ?></label>
                                <textarea rows="3" name="ealamat" type="text" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Domain</label>
                                <input name="edomain" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label><?= $this->lang->line('phone') ?></label>
                                <input onkeypress="validate(event)" name="ephone" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Module</label>
                                <div class="form-check">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 29%;"><label><input name="feature[]" class="form-check-input position-static" type="checkbox" checked value="absen"> Absen</label></td>
                                            <td style="width: 29%;"><label><input name="feature[]" class="form-check-input position-static" type="checkbox" checked value="patroli"> Patrol</label></td>
                                            <td style="width: 29%;"><label><input name="feature[]" class="form-check-input position-static" type="checkbox" checked value="kejadian"> Kejadian</label></td>
                                            <td style="width: 29%;"><label><input name="feature[]" class="form-check-input position-static" type="checkbox" checked value="tamu"> Tamu</label></td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="hidden_feature">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label for="eprofileUp" style="height: 150px">
                                    <img id="eprofimg" style="max-height: 150px; max-width: 150px;">
                                    <input name="logo" type="file" id="eprofileUp" style="display:none">
                                    <div style="height: 150px; width: 150px; position: absolute; z-index: 1000; top: 0px; left: 15px; right: 0px; bottom: 0;" class="profile-upload">
                                        <i class="fa fa-undo position-absolute btn-plus"></i>
                                    </div>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Level Organization <?= $this->lang->line('register_b2b') ?></label>
                                <select name="elevel" class="form-control level">
                                    <option value="1">Pusat</option>
                                    <option value="2">Cabang</option>
                                    <option value="3">Project</option>
                                </select>
                            </div>
                            <div class="form-group d-none cabang">
                                <label>Parent Organization</label>
                                <select name="epusat" class="form-control">
                                    <?php
                                    $parent = $this->db->where('level', 1)->get('m_register_b2b')->result();
                                    if (count($parent) > 0) {
                                        foreach ($parent as $row) {
                                    ?>
                                            <option value="<?= $row->b2b_token ?>"> <?= $row->title_nm ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group d-none project">
                                <label>Parent Organization</label>
                                <select name="ecabang" class="form-control">
                                    <?php
                                    $parent = $this->db->where('level', 2)->get('m_register_b2b')->result();
                                    if (count($parent) > 0) {
                                        foreach ($parent as $row) {
                                    ?>
                                            <option value="<?= $row->b2b_token ?>"> <?= $row->title_nm ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Schedule B2B</label>
                                <div class="form-check">
                                    <label><input name="status_schedule" class="form-check-input position-static" type="checkbox" value="status_schedule"> Status Schedule</label>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
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
                <form action="<?= base_url() ?>register_b2b/hapus2" method="POST">
                    <input type="hidden" id="hid" name="did">
                    <button type="submit" class="btn btn-success">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/.main content-->