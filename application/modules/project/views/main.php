<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('project')?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-map"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?=$this->lang->line('project')?></h1>
                <!-- <small>List of project</small> -->
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
                    <button onclick="window.location.href = '<?=base_url('project/frm_tambah')?>'" id="tambah" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2"><span style="font-size:25px; padding: 0px" class="typcn typcn-plus"></span></button>&nbsp;
                    <button onclick="window.location.href = '<?=base_url('project/all_map')?>'" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2"><span style="font-size:25px;" class="typcn typcn-map"></span><b> Show All Project</b></button>
                </div>
            </div>
            <table id="tb_client" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th style="width: 20px;">No</th>
                        <th><?=$this->lang->line('code')?></th>
                        <th><?=$this->lang->line('project_name')?></th>
                        <th style="width: 55px"></th>
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
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?=$this->lang->line('add').' '.$this->lang->line('project')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('project/tambah')" method="POST">
                    <div class="form-group">
                        <label><?=$this->lang->line('project_name')?></label>
                        <input name="nama_project" type="text" class="form-control">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <button type="submit" class="btn btn-success"><?=$this->lang->line('apply')?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Edit Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('project/edit')" method="POST">
                    <div class="form-group">
                        <label>Nama Project</label>
                        <input name="enama_project" id="nm_prj" type="text" class="form-control">
                        <input name="eid" type="hidden" id="editId">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?=$this->lang->line('delete')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="deltitle"><?=$this->lang->line('are_you_sure')?></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <form action="<?= base_url() ?>project/hapus" method="POST">
                    <input type="hidden" id="hid" name="hid">
                    <button type="submit" class="btn btn-success"><?=$this->lang->line('delete')?></button>
                </form>
            </div>
        </div>
    </div>
</div>