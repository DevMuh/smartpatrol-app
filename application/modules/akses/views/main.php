<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item active">User Roles</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-key"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Role/Peran</h1>
                <small>Menambah, mengubah, mengaktifkan & menoaktifkan role/peran</small>
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
            <table id="myTable" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th style="width: 20px;">No</th>
                        <th style="width: 100px;">Role/Peran</th>
                        <th>Ijin</th>
                        <th>Status</th>
                        <th style="width: 10px;"></th>
                        <th style="width: 10px;"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <!-- <div class="card mb-4">
        <div class="card-body"><br><br><br><br>
            <table id="tb_uakses" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th style="width: 5px">No</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>User Roles</th>
                        <th style="width: 10px;">Status</th>
                        <th style="width: 10px"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div> -->
</div>
<!--/.body content-->
</div>
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Tambah Role/Peran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('akses/add')" method="POST">
                    <div class="form-group">
                        <label>Role/Peran</label>
                        <input name="roles_type" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ijin</label>
                        <select name='table[]' class="form-control placeholder-multiple select2-hidden-accessible" multiple>
                            <?php foreach ($table as $row) { ?>
                                <option value="<?= $row->id ?>"><?= $row->judul_menu ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Additional Flag</label>
                        <select name='permission[]' class="form-control placeholder-multiple select2-hidden-accessible" multiple>
                            <?php foreach ($list_permission as $row) { ?>
                                <option value="<?= $row->action."|".$row->text; ?>"><?= $row->text; ?></option>
                            <?php } ?>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutp</button>
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
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Edit User Roles</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('akses/edit')" method="POST">
                    <div class="form-group">
                        <label>Tipe Role/Peran</label>
                        <input name="eroles_type" type="text" class="form-control">
                        <input name="eid" type="hidden" id="editId">
                    </div>
                    <div class="form-group">
                        <label>Ijin</label>
                        <select name='etable[]' id="edittablee" class="form-control placeholder-multiple select2-hidden-accessible" multiple>
                            <?php foreach ($table as $row) { ?>
                                <option class="opt-tabel" value="<?= $row->id ?>"><?= $row->judul_menu ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Additional Flag</label>
                        <select name='epermission[]' id="editpermission" class="form-control placeholder-multiple select2-hidden-accessible" multiple>
                            <?php foreach ($list_permission as $row) { ?>
                                <option class="opt-permission" value="<?= $row->action."|".$row->text; ?>"><?= $row->text; ?></option>
                            <?php } ?>
                        </select>
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
                <h4 id="deltitle">Ganti status role?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <form action="<?= base_url() ?>akses/hapus" method="POST">
                    <input type="hidden" id="hid" name="hid">
                    <input type="hidden" id="stat" name="stat">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edituserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel7" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel7">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="alert"></div>
                <form id="formUpdate">
                    <div class="form-group">
                        <label>Username</label>
                        <input name="username" type="text" class="form-control" id="editUsername" value="" required>
                        <input name="idEdit" type="hidden">
                    </div>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input name="fullname" type="text" class="form-control" id="editFullname" value="" required>
                    </div>
                    <div class="form-group">
                        <label>User Roles</label>
                        <select class="form-control" name="user_roles" required>
                            <?php
                            foreach ($select as $row) {
                                echo "<option>" . $row->roles_type . "</option>";
                            }
                            ?>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success update">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/.main content-->