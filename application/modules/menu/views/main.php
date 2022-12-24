<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item"><a href="#"><?= $this->lang->line('master') ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('menu_aplikasi') ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-world"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('menu_aplikasi') ?></h1>
                <!-- <small>From now on you will start your activities.</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center row">
                        <div class="col-lg-6 mt-1">
                            <h6 class="fs-17 font-weight-600 mb-0"><?= $this->lang->line('menu_aplikasi') ?></h6>
                            <div class="mt-2">
                                <div class="form-group row mb-0">
                                    <div class="col-sm-6 d-flex justify-align-center">
                                        <select class="form-control basic-single select2-hidden-accessible" 
                                        data-placeholder="-- Pilih User --" tabindex="-1" aria-hidden="true" name="user_role" id="user_role_select">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-1">
                            <div class="text-right">
                                <div class="actions">
                                    <button type='button' class='btn btn-primary btn-xs mb-3 mr-1' data-toggle='modal' data-target='#primaryModal'>
                                        <?= $this->lang->line('add') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dd" id="nestable">
                        <?= $menu; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Import Data -->
    <div class="modal fade table-bordered" id="primaryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none; margin-top:-7px" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-light">Form <?= $this->lang->line('menu_aplikasi') ?></h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form_add">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="is_main_menu" class="font-weight-600">Parent Menu</label>
                                        <select class="form-control basic-single" name="is_main_menu" id="is_main_menu">
                                        </select>
                                    </div>
                                    <div class="form-group row">
                                        <label for="modul_code" class="col-sm-3 col-form-label font-weight-600">Modul</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" placeholder="Masukan Nama Modul" id="modul_code" name="modul_code">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="judul_menu" class="col-sm-3 col-form-label font-weight-600">Judul
                                            Menu<span class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" placeholder="Masukan Judul Menu" id="judul_menu" name="judul_menu" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="link" class="col-sm-3 col-form-label font-weight-600">Link<span class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" placeholder="Masukan link" id="link" name="link" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="icon" class="col-sm-3 col-form-label font-weight-600">Nama Icon<span class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" placeholder="Masukan class icon" id="icon" name="icon" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input class="btn btn-primary save-site" type="submit" value="Save" style="border-radius: 0%">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade table-bordered" id="secondaryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none; margin-top:-7px" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-light">Form <?= $this->lang->line('menu_aplikasi') ?></h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form_edit">
                        <input type="hidden" name="eid">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="is_main_menu_edit" class="font-weight-600">Parent Menu</label>
                                        <select class="form-control basic-single" name="is_main_menu_edit" id="is_main_menu_edit">
                                        </select>
                                    </div>
                                    <div class="form-group row">
                                        <label for="modul_code_edit" class="col-sm-3 col-form-label font-weight-600">Modul</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" placeholder="Masukan Nama Modul" id="modul_code_edit" name="modul_code_edit">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="judul_menu_edit" class="col-sm-3 col-form-label font-weight-600">Judul
                                            Menu<span class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" placeholder="Masukan Judul Menu" id="judul_menu_edit" name="judul_menu_edit" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="link_edit" class="col-sm-3 col-form-label font-weight-600">Link<span class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" placeholder="Masukan link" id="link_edit" name="link_edit" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="icon_edit" class="col-sm-3 col-form-label font-weight-600">Nama Icon<span class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" placeholder="Masukan class icon" id="icon_edit" name="icon_edit" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input class="btn btn-primary save-site" type="submit" value="Save" style="border-radius: 0%">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- End Import Data -->
</div>

<style>
    .floatButton {
        position: fixed;
        bottom: 16px;
        right: 60px;
        color: #000;
        display: none;
    }

    .floatButton>div.btn>button.btn {
        background-color: #54e346;
        color: #000;
        width: 64px;
        height: 32px;
        border: #000;
    }

    .floatButton>div.btn>button.btn:nth-child(odd) {
        background-color: #ff4646;
        color: #fff;
    }
</style>

<div id="floatGroup" class="floatButton">
    <div class="btn btn-group">
        <button class="btn font-weight-600" onclick="handlerCancel()">Cancel</button>
        <button class="btn font-weight-600" onclick="handlerSave()">Save</button>
    </div>
</div>
<div id="floatGroupB" class="floatButton">
    <div class="btn btn-group">
        <button class="btn font-weight-600" onclick="handlerCancelList()">Cancel</button>
        <button class="btn font-weight-600" onclick="handlerSaveList()">Save</button>
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
                <form action="<?= base_url() ?>menu/hapus" method="POST">
                    <input type="hidden" id="hid" name="hid">
                    <button type="submit" class="btn btn-success">Apply</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/.body content-->
<!--/.main content-->