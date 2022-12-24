<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">

<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?=base_url()?>dashboard"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('kategori_instruksi')?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?=$this->lang->line('kategori_instruksi')?></h1>
                <!-- <small>List Data for all Kategori Instruksi</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <button type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#exampleModal1"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br>
            &nbsp;
        <?php 
        echo $this->session->flashdata('delete'); 
        ?>
        <div id="alertSuccess"></div>
            <table id="tb_kategori_instruksi" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th>id</th>
                        <th style="width: 5%">No</th>
                        <th style="width: 35%"><?=$this->lang->line('code')?></th>
                        <th style="width: 20%"><?=$this->lang->line('name')?></th>
                        <th style="width: 20%"><?=$this->lang->line('description')?></th>
                        <th style="width: 15%"><?=$this->lang->line('action')?></th>
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



<!-- Add Kategori -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Add Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="alert"></div>
                <form id="addKategori">
                    <div class="form-group">
                        <label>Kode</label>
                        <input name="kode" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input name="nama" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label >Deskripsi</label>
                      <textarea class="form-control" rows="3" name="deskripsi"></textarea>
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
<!-- End Add Kategori -->


<!-- Edit Users -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Edit Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="alert"></div>
                <form id="formUpdate">
                    <div class="form-group">
                        <label>Kode</label>
                        <input name="kode" type="text" class="form-control" id="editKode" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input name="nama" type="text" class="form-control" id="editNama" value="" required>
                    </div>
                    <div class="form-group">
                      <label >Deskripsi</label>
                      <textarea class="form-control" rows="3" name="deskripsi" id="editDeskripsi"></textarea>
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
<!-- End Delete -->