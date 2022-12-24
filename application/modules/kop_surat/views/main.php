<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#">><?=$this->lang->line('master')?></a></li>
            <li class="breadcrumb-item active">><?=$this->lang->line('kop_surat')?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-key"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">><?=$this->lang->line('kop_surat')?></h1>
                <!-- <small>Menambah, mengubah, mengaktifkan & menoaktifkan role/peran</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <table id="myTable" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th style="width: 10%;"><?=$this->lang->line('logo')?></th>
                        <th style="width: 100px;"><?=$this->lang->line('kop_surat')?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img style="width: 200px" src="<?=base_url('assets/apps/images/'.$kop->logo)?>"></td>
                        <td><?=$kop->kop?></td>
                    </tr>
                </tbody>
            </table>
            <button onclick="edit()" type="button" style="margin-top:-5px;" class="btn btn-info mb-2 right" data-toggle="modal" data-target="#exampleModal1"><span style="font-size:25px;" class="typcn typcn-pencil"></span></button>
        </div>
    </div>
</div>
<!--/.body content-->
</div>
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 700px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Tambah Role/Peran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <form onsubmit="return subm('kop_surat/edit')" method="POST" enctype="multipart/form-data"> -->
                <form action="<?= base_url('kop_surat/edit') ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group form inline">
                        <label>Logo</label>
                        <input name="logo" type="file" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Kop</label>
                        <textarea name="kop" id="editor1" rows="10" cols="80" style="visibility: hidden; display: none;">
                        </textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <button type="submit" class="btn btn-success"><?=$this->lang->line('save')?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/.main content-->