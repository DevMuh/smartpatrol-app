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
                <h1 class="font-weight-bold" style="color:#b11616">Pergantian</h1>
                <small>All data Pergantian</small>
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
                    <th style="width: 15%">Username</th>
                    <th style="width: 10%">Full Name</th>
                    <th style="width: 20%">Posisi</th>
                    <th style="width: 20%">Dari Regu</th>
                    <th style="width: 20%">Ke Regu</th>
            </thead>
            <tbody>

            </tbody>
        </table>
        </div>
    </div>
</div>
<!--/.body content-->