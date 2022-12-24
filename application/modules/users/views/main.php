<style type="text/css">
    .switch-button.switch-button-lg {
        width: 90px;
    }

    #inactive .switch-button {
        border-radius: 50px;
        background-color: #b3b3b3;
        position: relative;
    }

    #inactive .switch-button label {
        border-radius: 50%;
        background-color: #fff;
        margin-left: 5px;
        height: 19px;
        width: 19px;
        z-index: 1;
        display: inline-block;
        cursor: pointer;
        margin-top: 5px;
        margin-bottom: 1px;
    }

    #inactive .switch-button label:before {
        position: absolute;
        font-size: 0.8462rem;
        font-weight: 600;
        z-index: 0;
        content: "Inactive";
        right: 0;
        display: block;
        width: 100%;
        height: 100%;
        line-height: 31px;
        top: 0;
        text-align: right;
        padding-right: 7px;
        color: #fff;
    }

    #active .switch-button {
        border-radius: 50px;
        background-color: #33b5e5;
        position: relative;
    }

    #active .switch-button label {
        /*bulat kecil*/
        border-radius: 50%;
        background-color: #fff;
        margin-left: 64px;
        height: 19px;
        width: 19px;
        z-index: 1;
        display: inline-block;
        cursor: pointer;
        margin-top: 5px;
        margin-bottom: 1px;
    }

    #active .switch-button label:before {
        position: absolute;
        font-size: 0.8462rem;
        font-weight: 600;
        z-index: 0;
        content: "Active";
        right: 0;
        display: block;
        width: 100%;
        height: 100%;
        line-height: 31px;
        top: 0;
        text-align: right;
        padding-right: 32px;
        color: #fff;
    }

    #map {
        height: 400px;
        position: relative;
        width: 100%;
    }

    /* Important part */
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body{
        height: 70vh;
        overflow-y: auto;
    }
</style>

<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('users') ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('users') ?></h1>
                <!-- <small>List Data for all users</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <button id="tbbtn" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#exampleModal1"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br>
            &nbsp;
            <?php
            echo $this->session->flashdata('delete');
            ?>
            <div id="alertSuccess"></div>
            <div class="table-responsive">

                <table id="tb_users" class="table display nowrap table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                    <thead style="background-color: black;">
                        <tr style="color:white">
                            <th>ID</th>
                            <th><?= $this->lang->line('payroll_id') ?></th>
                            <th><?= $this->lang->line('username') ?></th>
                            <th><?= $this->lang->line('full_name') ?></th>
                            <th>Regu</th>
                            <th>Shift</th>
                            <th>Position</th>
                            <th><?= $this->lang->line('user_roles') ?></th>
                            <th>Phone Number</th>
                            <th>Device Name</th>
                            <th>Mobile App Version</th>
                            <th>Pin Outside Geofence</th>
                            <th><?= $this->lang->line('active_at') ?></th>
                            <th style="width: 75px"><?= $this->lang->line('status') ?></th>
                            <th style="width: 130px"><?= $this->lang->line('action') ?></th>
                            <!-- <th style="width: 5px"></th> -->
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--/.body content-->
</div>




<!-- Add Users -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="alert"></div>
                <form id="addUsers">
                    <div class="form-group">
                        <label><?= $this->lang->line('payroll_id') ?></label>
                        <input name="payroll_id" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input name="username" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input list="list_position" name="position" type="text" class="form-control" id="position" value="" required>
                        <datalist id="list_position" />
                        <?php foreach ($positions as $p) : ?>
                            <option value="<?= $p->name ?>">
                            <?php endforeach ?>
                            </datalist>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input name="no_tlp" type="tel" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input name="fullname" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>User Roles</label>
                        <select class="form-control" name="user_roles" required>
                            <option value="admin">Admin</option>
                            <option value="anggota">Anggota</option>
                            <option value="danru">Danru</option>
                            <option value="hrd">HRD</option>
                            <option value="chief">Chief</option>
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <label>Choose Organization</label>
                        <?php
                        $cabang = $_SESSION['choose_b2b'];
                        if (count($cabang) > 0) {
                            foreach ($cabang as $row) {
                        ?>
                                <label class="col-md-12"><input name="cabb[]" class="form-check-input position-static" type="checkbox" checked value="<?= $row->b2b_token ?>"> <?= $row->title_nm ?></label>
                        <?php }
                        } ?>
                    </div> -->
                    <div class="form-group">
                        <label>Choose Sub-organization</label>
                        <select class="form-control" name="user_sub_org" id="user_sub_org" required>
                            <option value="">-- Choose Sub-organization --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Configuration Absensi</label>
                        <select name="level" class="form-control level">
                            <option value="1">Pusat</option>
                            <option value="2">Cabang</option>
                            <option value="3">Project</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Add Users -->


<!-- Edit Users -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="w-alert"></div>
                <form id="formUpdate">
                    <input name="idEdit" type="hidden">
                    <div class="form-group">
                        <label><?= $this->lang->line('payroll_id') ?></label>
                        <input name="payroll_id" id="editpayroll_id" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input name="username" type="text" class="form-control" id="editUsername" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input list="list_position" name="position" type="text" class="form-control" id="editposition" value="" required>
                        <datalist id="list_position" />
                        <?php foreach ($positions as $p) : ?>
                            <option value="<?= $p->name ?>">
                            <?php endforeach ?>
                            </datalist>
                    </div>
                    <div class="form-group">
                        <label for="b2b">Cabang</label>
                        <input type="hidden" name="editb2bhidden" id="editb2bhidden">
                        <select class="form-control basic-single" name="b2b" 
                        data-placeholder="-- Pilih Cabang --" id="editb2b">
                            <option></option>
                            <?php
                            $cabang = $_SESSION['choose_b2b'];
                            if (count($cabang) > 0) {
                                foreach ($cabang as $row) {
                            ?>
                                <option value="<?= $row->b2b_token ?>"> 
                                    <?= $row->title_nm ?>
                                </option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input name="no_tlp" type="tel" class="form-control" id="no_tlp" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input name="password" type="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input name="fullname" type="text" class="form-control" id="editFullname" value="" required>
                    </div>
                    <div class="form-group">
                        <label>User Roles</label>
                        <select id="roles" class="form-control" name="user_roles" required>
                        <?php if($_SESSION["user_roles"] == "cudo" || $_SESSION["user_roles"] == "superadmin"){?>
                            <option value="srbadmin">Admin SRB</option>
                        <?php }?>
                            <option value="admin">Admin</option>
                            <option value="anggota">Anggota</option>
                            <option value="danru">Danru</option>
                            <option value="hrd">HRD</option>
                            <option value="demo">Demo</option>
                            <option value="chief">Chief</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Choose Sub-organization</label>
                        <select class="form-control" name="user_sub_org" id="edituser_sub_org" required>
                            <option value="choose">-- Choose Sub-organization --</option>
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <label>Choose Organization</label>
                        <?php
                        if (count($cabang) > 0) {
                            foreach ($cabang as $row) {
                        ?>
                                <label class="col-md-12"><input name="cabb[]" class="form-check-input position-static ecabb" type="checkbox" value="<?= $row->b2b_token ?>"> <?= $row->title_nm ?></label>
                        <?php }
                        } ?>
                    </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success update">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Edit Users -->


<!-- Delete -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="messageDelete">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a type="button" class="btn btn-danger text-light" id="hapus">Delete</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalLogout" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">
                Logout Mobile Manualy?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a type="button" class="btn btn-danger text-light" id="logout">Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeActive" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ubah Tempat Aktif</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>Aktif di</label>
                                <select id="modee" onchange="getConf(this.value)" class="form-control">
                                    <option>Pilih salah satu</option>
                                    <option value="1">Project</option>
                                    <option value="2">Cabang</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <input type="hidden" id="usrid">
                                <label>&nbsp;</label>
                                <select class="form-control" id="config">
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button onclick="updateCo()" type="button" class="btn btn-success text-light">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!-- End Delete -->

<div class="modal fade" id="geomap" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600">Map Geo Tagging</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map"></div>
                <br>
                <div class="form-group">
                    <label>Radius</label>
                    <input type="number" id="maprad" class="from-control" value="100">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button onclick="saveTag()" type="submit" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="visitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Visit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body visitModalBody d-flex justify-content-center">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>