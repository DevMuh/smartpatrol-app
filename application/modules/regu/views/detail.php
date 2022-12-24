<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard">Absensi</a></li>
            <li class="breadcrumb-item"><a href="#">Regu</a></li>
            <li class="breadcrumb-item active">Anggota Regu</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-group"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Anggota Regu </h1>
                <small>Menambah dan mengurangi anggota regu</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="font-weigth-bold">Daftar Anggota <b><?= $regu->nama_regu ?></b> </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div style="border: #7a7a7a black 1px;" class="col-lg-6">
                    <h6>Anggota Tersedia</h6>
                    <table id="tb_asal" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Telepon</th>
                                <th>Organization</th>
                                <th style="width: 5px"></th>
                            </tr>
                        </thead>
                        <tbody id="asal">
                        </tbody>
                    </table>
                </div>
                <div style="border: #7a7a7a black 2px;" class="col-lg-6">
                    <h6>Anggota Saat Ini</h6>
                    <table id="tb_pindah" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Telepon</th>
                                <th>Organization</th>
                                <th style="width: 5px"></th>
                            </tr>
                        </thead>
                        <tbody id="pindah">
                        </tbody>
                    </table>
                </div>
            </div>
            <button class="btn btn-success right" onclick="exet()">Simpan</button>
            <!-- <button id="tambah" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#exampleModal1"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br>
            <table id="myTable" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <div class="cusfil"></div>
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th style="width: 20px;">No</th>
                        <th>Nama Regu</th>
                        <th style="width: 55px;"></th>
                        <th style="width: 5px;"></th> -->
            <!-- <th style="width: 10px;"></th>
                        <th style="width: 10px;"></th> -->
            <!-- </tr>
                </thead>
                <tbody>
                </tbody>
            </table> -->
        </div>
    </div>
</div>
<!--/.body content-->
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