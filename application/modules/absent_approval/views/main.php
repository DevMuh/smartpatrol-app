<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        top: 70px;
        right: 65px;
        font-size: 20px;
        color: #fff
    }
    .dataTable tbody input, .dataTable tbody select, .dataTable tfoot input, .dataTable tfoot select{
        height: calc(1.5em + .75rem + -17px);
    }
    input#ceklis_semua{
        height: calc(1.5em + 0.75rem + -17px);
    }
    .dataTables_scrollBody{
    max-height: 44vh !important;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/apps/assets/plugins/sweetalert/sweetalert.css') ?>">
<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item"><a href="#"><?= $this->lang->line('master') ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('absent_approval') ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-calendar"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('absent_approval') ?></h1>
                <!-- <small>Register new b2b</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <!-- <button id="tambah" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#exampleModal1"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br> -->
            &nbsp;
            <div class="col-md-12"><?php echo $this->session->flashdata('success'); ?></div>
            <form action="">
                <table id="tb_schedule" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                    <thead style="background-color: black;">
                        <tr style="color:white">
                            <th style="width: 92px; text-align: center;" class="nosort"><input type="checkbox" id="ceklis_semua" class="form-control"></th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Pulang</th>
                            <th>Shift Name</th>
                            <th>Start Shift</th>
                            <th>End Shift</th>
                            <th style="width: 92px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
    
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-success remove-radius ml-auto ceklis_approved" style="float: right; display: none;" id="frm_submit">Approved</button>
            </form>
        </div>
    </div>
</div>
<!--/.body content-->

<div class="modal fade" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Form Approve Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 id="approve-title">Apakah anda yakin ingin Menyetujui Absensi?</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <form id="formVerification">
                    <input type="hidden" id="log_id_masuk" name="log_id_masuk">
                    <input type="hidden" id="log_id_pulang" name="log_id_pulang">
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>