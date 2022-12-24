<!-- Sweet alert -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.css" rel="stylesheet">


<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?=base_url()?>dashboard">Home</a></li>
            <li class="breadcrumb-item active">Instruksi</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Instruksi</h1>
                <small>All data Instruksi</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
          <div class="row">
              <a href="<?=base_url($this->uri->segment(1))?>/post" style="text-decoration: none">
                  <button type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2">
                      <span style="font-size:25px;" class="typcn typcn-plus text-light"></span>
                  </button>
              </a>
              <button class="btn btn-sm btn-success remove-radius ml-auto" data-toggle="modal" data-target="#import" style="margin: 11px 0 11px 0; border-radius: 0% !important">
                  <i class="typcn typcn-cloud-storage-outline"></i> Upload Data
              </button>
          </div>
        </br> 
            &nbsp;
        <?php 
            echo $this->session->flashdata('success');  
        ?>
        <div id="alertSuccess"></div>
        <table id="tb_data" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead style="background-color: black;">
                <tr style="color:white">
                    <th>id</th>
                    <th style="width: 7%">No</th>
                    <th style="width: 15%">Kategory Intruksi</th>
                    <th style="width: 10%">Tanggal Kirim</th>
                    <th style="width: 20%">Perihal</th>
                    <th style="width: 12%">Pengirim</th>
                    <th style="width: 10%">Tangal Mulai</th>
                    <th style="width: 10%">Tangal Selesai</th>
                    <th style="width: 5%">Feedback</th>
                    <th style="width: 15%">Detail</th>
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

<!-- CHANGE STATUS -->
    <div class="modal fade" id="modalChange" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="askChangeStatus">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <a class="btn btn-danger text-light" id="change" >Change</a>
            <!-- <a type="button" class="btn btn-danger text-light" id="change">Change</a> -->
          </div>
        </div>
      </div>
    </div>
<!-- END CHANGE STATUS -->



<!-- SHOW DETAIL -->
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="min-height: 300px">
            <div class="justify-content-center align-content-center flex-wrap h-100" id="container-loading">
              <div class="spinner-border text-danger" role="status" id="loading"></div>
            </div>
            <table class=" table table-bordered" id="content-detail">
                <tr>
                    <td class="font-weight-bold pr-0" width="160px">Kategori Instruksi</td>
                    <td width="10px">:</td>
                    <td id="nama"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" >Perihal</td>
                    <td width="10px">:</td>
                    <td id="perihal"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" >Feedback</td>
                    <td width="10px">:</td>
                    <td id="feedback"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" >Detail Instruksi</td>
                    <td width="10px">:</td>
                    <td id="detail"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" >Tanggal Kirim</td>
                    <td width="10px">:</td>
                    <td id="kirim"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" >Pengirim</td>
                    <td width="10px">:</td>
                    <td id="pengirim"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" >Lampiran</td>
                    <td width="10px">:</td>
                    <td id="lampiran"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" >Anggota</td>
                    <td width="10px">:</td>
                    <td id="list_anggota"></td>
                </tr>

            </table>
          </div>
          <div class="modal-footer">
            <div id="modal_status"></div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
<!-- END SHOW DETAIL -->


<!-- SHOW Anggota -->
    <div class="modal fade" id="modalAnggota" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="min-height: 300px">
            <div class="justify-content-center align-content-center flex-wrap h-100" id="container-loading">
              <div class="spinner-border text-danger" role="status" id="loading"></div>
            </div>
            <table class=" table table-bordered" id="content-detail">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Pilih</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $no=1;
                    foreach ($anggota as $value):
                ?>
                    <tr>
                        <td><?=$no ?></td>
                        <td><?=$value->username ?></td>
                        <td><?=$value->full_name ?></td>
                        <td><input type="checkbox" name="anggota[]"></td>
                    </tr>
                <?php 
                    $no++; 
                    endforeach; 
                ?>
                </tbody>

            </table>
          </div>
          <div class="modal-footer">
            <div id="modal_status"></div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
<!-- END SHOW Anggota -->


<!-- Import Data -->
<div class="modal fade table-bordered" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none; margin-top:-7px" aria-hidden="true">
            <div class="modal-dialog" role="document" >
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title text-light">Upload Data Instruksi</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
            <form method="POST" enctype="multipart/form-data" id="FormImport">    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card" style="border: 0 !important">
                                    <div class="card-body" style="padding: 0;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group row ml-2">
                                                    <label for="inputPassword" class="pt-0 col-form-label" style="width: 70px !important">Select File :</label>
                                                    <div class="col-sm-9">
                                                        <input type="file" class="form-control-file" name="file">
                                                    </div>
                                                </div>
                                                <div class="text-center mt-2">
                                                    <b>Noted : Only File Excel</b>
                                                </div>
                                                <div class="text-center ">
                                                    <a href="<?=base_url('/assets/apps/assets/template/Import_instruksi.xlsx')?>">Download Template</a>
                                                    <span>|</span>
                                                    <a href="<?=base_url('instruksi/export_anggota')?>">Data Anggota</a>
                                                </div>
                                            </div>
                                        </div>
                                        
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
<!-- End Import Data -->











<!-- Sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.js"></script>