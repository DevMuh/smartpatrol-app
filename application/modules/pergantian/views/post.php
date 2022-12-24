
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?=base_url()?>dashboard">Home</a></li>
            <li class="breadcrumb-item active">Pergantian</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Pergantian Sementara Anggota</h1>
                <small>Pergantian Sementara Anggota</small>
            </div>
        </div>
    </div>
</div>
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
        <?=$this->session->flashdata('failed'); ?>
        <h2 class="card-title text-center mb-5 mt-3 font-weight-bold">Data anggota</h2>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table id="tb_post" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                        <thead style="background-color: black;">
                            <tr style="color:white">
                                <th>id</th>
                                <th style="width: 7%">No</th>
                                <th style="width: 15%">Username</th>
                                <th style="width: 20%">Full Name</th>
                                <th style="width: 10%">Posisi</th>
                                <th style="width: 7%">Regu</th>
                                <th style="width: 10%">Aksi</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <hr>
                 <div class="tile-footer mt-3">
                    <a href="<?=base_url($this->uri->segment(1))?>" class="btn btn-lg btn-secondary">Kembali</a>
                </div>
        </div>
    </div>
</div>


<!-- SHOW DETAIL -->
    <div class="modal fade" id="modalGanti" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Pilih Grup</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="min-height: 80px">
            <div class="justify-content-center align-content-center flex-wrap h-100" id="container-loading">
              <div class="spinner-border text-danger" role="status" id="loading"></div>
            </div>
        <form id="formPindah">
            <input type="hidden" name="id" value="" id="idAnggota">    
            <select id="select" class="custom-select" name="grup_id">
            </select>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-info" >Pindahkan </button>
          </div>
        </form>
        </div>
      </div>
    </div>
<!-- END SHOW DETAIL -->