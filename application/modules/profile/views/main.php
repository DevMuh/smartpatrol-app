<!--Content Header (Page header)-->
<!-- <div class="content-header row align-items-center m-0">
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
</div> -->
<!--/.Content Header (Page header)-->
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
        top: 150px;
        right: 125px;
        font-size: 20px;
        color: #fff
    }
</style>
<div class="body-content">
    <form action="<?= base_url('profile/update') ?>" enctype="multipart/form-data" method="post">
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <div>
                            <label for="profileUp" style="height: 300px">
                                <img id="profimg" style="max-height: 300px; max-width: 280px;" src="<?= base_url() ?>assets/apps/images/<?= $table->path_logo ?>">
                                <input name="logo" type="file" id="profileUp" style="display:none">
                                <div style="height: 300px; position: absolute; z-index: 1000; top: 24px; left: 24px; right: 24px; bottom: 0;" class="profile-upload">
                                    <i class="fa fa-undo position-absolute btn-plus"></i>
                                </div>
                            </label>
                            <hr>
                        </div>
                        <div style="text-align: center">
                            <h4><?= $table->title_nm ?></h4>
                            <label><i class="fa fa-map-marker-alt"></i> <?= $table->alamat ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 pr-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">Company</label>
                                    <input type="text" class="form-control" disabled="" placeholder="Company" value="<?= $table->title_nm ?>">
                                </div>
                            </div>
                            <div class="col-md-6 px-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">PIC</label>
                                    <input name="pic" type="text" class="form-control" placeholder="PIC" value="<?= $table->pic ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">Phone</label>
                                    <input onkeypress="validate(event)" name="phone" type="text" class="form-control" placeholder="Phone" value="<?= $table->phone ?>">
                                </div>
                            </div>
                            <div class="col-md-6 pl-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">Email</label>
                                    <input name="email" type="email" class="form-control" placeholder="email" value="<?= $table->email ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-600">Address</label>
                                    <textarea name="address" class="form-control" rows="9" placeholder="Adress"><?= $table->alamat ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success right">Simpan</button><br>
    </form>
    <!--/.body content-->
</div>
</div>