<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4  order-sm-last mb-3 mb-sm-0 p-0 ">

        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item active">Absensi</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-calendar"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Absensi</h1>
                <small>Melihat absensi personil</small>
            </div>
        </div>
    </div>

</div>


<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="row">
        <div class="col">
            <div class="media-body content-header header-title ">
                <h6>*Total User: <?= $total_user ?></h6>
            </div>
        </div>
        <div class="col d-flex flex-row-reverse">
        <?php if ($edit_permission){?>
            <button id="edit" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2 ml-2" data-toggle="modal" data-target="#editmodal"><span style="font-size:25px;" class="typcn typcn-edit"></span></button></br>
        <?php }?>
        <?php if ($add_permission){?>
            <button id="tambah" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#addmodal"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br>
        <?php }?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="d-flex flex-column p-3 mb-3 bg-white shadow-sm rounded">
                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Today's Attendance
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-size-2 text-monospace"><?= $total_attend ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="d-flex flex-column p-3 mb-3 bg-white shadow-sm rounded">
                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Today's Absence</div>
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-size-2 text-monospace"><?= $total_absence ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="d-flex flex-column p-3 mb-3 bg-white shadow-sm rounded">
                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">ONSITE</div>
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-size-2 text-monospace"><?= $total_onsite ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="d-flex flex-column p-3 mb-3 bg-white shadow-sm rounded">
                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">VIA</div>
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-size-2 text-monospace"><?= $total_via ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <div class="card mb-4">

        <div class="card-body">
            <ul class="nav nav-tabs header-tabs right">
                <li class="nav-item">
                    <a href="#" id="0" class="nav-link text-center show active" data-toggle="tab">
                        <h6 class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-1">
                            ABSENSI
                        </h6>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="2" class="nav-link text-center show " data-toggle="tab">
                        <h6 class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-1">
                            SUMMARY
                        </h6>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="2_" class="nav-link text-center show " data-toggle="tab">
                        <h6 class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-1">
                            OFF PANGGIL
                        </h6>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="1" class="nav-link text-center show" data-toggle="tab">
                        <h6 class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-1">
                            EVENT
                        </h6>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="3" class="nav-link text-center show" data-toggle="tab">
                        <h6 class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-1">
                            CHECKPOINT
                        </h6>
                    </a>
                </li>
            </ul>
            <div class="row">
                <div class="col-md-12 m-0 p-0">
                    <div class="btn-group m-0 p-0 mb-3">
                        <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Column visibility
                        </button>
                        <div class="dropdown-menu w-hide" x-placement="bottom-start" style="max-height: 300px;overflow-x:auto">
                            <a class="dropdown-item" href="#">Action</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top:42px">
                    <div id="filterDate" class="row"></div>
                </div>
            </div>

            <table id="myTable" class="table display nowrap table-bordered  table-striped table-hover sourced dataTable ">
                <div class="cusfil"></div>
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th style="width: 100px;">Tanggal Masuk</th>
                        <th style="width: 100px;">Payroll ID</th>
                        <th style="width: 100px;">Nama Lengkap</th>
                        <th style="width: 100px;">Organization</th>
                        <th style="width: 100px;">Nama Shift</th>
                        <th>Durasi Shift</th>
                        <th>Shift Mulai</th>
                        <th>Shift Akhir</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Pulang</th>
                        <th>Waktu Telat Masuk</th>
                        <th>Pulang Lebih awal</th>
                        <th>Total Kerja</th>
                        <th>Total Lembur Awal</th>
                        <th>Total Lembur Akhir</th>
                        <th>Tempat Masuk</th>
                        <th>Tempat Pulang</th>
                        <th>QR-ID Masuk</th>
                        <th>QR-ID Pulang</th>
                        <th>Status Lembur</th>
                        <th>Remark Lembur</th>
                        <th>Foto Masuk</th>
                        <th>Foto Pulang</th>
                        <th>Action</th>
                    </tr>
                    <tr class="clone-header">
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <table id="table_off_panggil" class="table display nowrap table-bordered  table-striped table-hover sourced dataTable ">
                <div class="cusfil"></div>
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th style="width: 100px;">Tanggal Masuk</th>
                        <th style="width: 100px;">Payroll ID</th>
                        <th style="width: 100px;">Nama Lengkap</th>
                        <th style="width: 100px;">Organization</th>
                        <th style="width: 100px;">Nama Shift</th>
                        <th>Durasi Shift</th>
                        <th>Shift Mulai</th>
                        <th>Shift Akhir</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Pulang</th>
                        <th>Waktu Telat Masuk</th>
                        <th>Pulang Lebih awal</th>
                        <th>Total Kerja</th>
                        <th>Total Lembur Awal</th>
                        <th>Total Lembur Akhir</th>
                        <th>Tempat Masuk</th>
                        <th>Tempat Pulang</th>
                        <th>QR-ID Masuk</th>
                        <th>QR-ID Pulang</th>
                        <th>Status Lembur</th>
                        <th>Remark Lembur</th>
                        <th>Foto Masuk</th>
                        <th>Foto Pulang</th>
                        <th>Action</th>
                    </tr>
                    <tr class="clone-header">
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                        <th class="filterhead not-fill"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <table id="table_event" class="table display  table-bordered table-striped table-hover sourced dataTable">
                <div class="cusfil"></div>
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th>QR ID</th>
                        <th>Description</th>
                        <th>Via</th>
                        <th>Create by</th>
                        <th>Create at</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <table id="table_sum" class="table display  table-bordered table-striped table-hover sourced dataTable">
                <div class="cusfil"></div>
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th>Nama Lengkap</th>
                        <th>No Telp</th>
                        <th>Jumlah Masuk</th>
                        <th>Jumlah Lembur</th>
                        <th>Jumlah Tidak Absen</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <table id="table_checkpoint" class="table display  table-bordered table-striped table-hover sourced dataTable">
                <div class="cusfil"></div>
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th>Nama Lengkap</th>
                        <th>QR</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Lat / Long</th>
                        <th>Photo</th>
                        <th>Selfie</th>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img id="img_modal" style="border: 15px solid white; height: 600px;object-fit:contain" src="#" />
        </div>
    </div>
</div>

<div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?= $this->lang->line('add') . ' ' . $this->lang->line('absence') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" id="form_claim" action="absensi/claim">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="fc_petugas" class="font-weight-600">Petugas</label>
                        <select class="basic-single form-control" id="fc_petugas" name="petugas" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fc_shift" class="font-weight-600">Shift Kerja</label>
                        <select class="basic-single form-control" id="fc_shift" name="shift" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="font-weight-600">Tanggal dan Waktu Absence</label>
                        <input class="form-control" type="datetime-local" name="submit_time" required>
                    </div>
                    <div class="form-group">
                        <label for="fc_status" class="font-weight-600">Jenis Absen</label>
                        <select class="form-control" id="fc_status" name="status" selected="1" required>
                            <option value="1" selected="selected">Masuk</option>
                            <option value="2">Pulang</option>
                        </select>
                    </div>
                    <div id="hidden-claim-form" class="d-none">
                        <div class="form-group form-check">
                            <input type="radio" class="form-check-input" id="is_sameday" name="is_overtime" checked>
                            <label class="form-check-label" for="is_sameday">Normal</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="radio" class="form-check-input" id="is_overtime" name="is_overtime">
                            <label class="form-check-label" for="is_overtime">Lembur</label>
                        </div>
                        <div class="form-group d-none" id="hidden-overtime">
                            <label for="fc_overtime" class="font-weight-600">Alasan Lembur</label>
                            <textarea class="form-control" id="fc_overtime" rows="5" name="overtime_reason"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
                    <button type="submit" class="btn btn-success"><?= $this->lang->line('apply') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?= $this->lang->line('add') . ' ' . $this->lang->line('absence') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" id="form_claim" action="absensi/claim">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="fc_petugas" class="font-weight-600">Petugas</label>
                        <select class="basic-single form-control" id="fc_petugas" name="petugas" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fc_shift" class="font-weight-600">Shift Kerja</label>
                        <select class="basic-single form-control" id="fc_shift" name="shift" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="font-weight-600">Tanggal dan Waktu Absence</label>
                        <input class="form-control" type="datetime-local" name="submit_time" required>
                    </div>
                    <div class="form-group">
                        <label for="fc_status" class="font-weight-600">Jenis Absen</label>
                        <select class="form-control" id="fc_status" name="status" selected="1" required>
                            <option value="1" selected="selected">Masuk</option>
                            <option value="2">Pulang</option>
                        </select>
                    </div>
                    <div id="hidden-claim-form" class="d-none">
                        <div class="form-group form-check">
                            <input type="radio" class="form-check-input" id="is_sameday" name="is_overtime" checked>
                            <label class="form-check-label" for="is_sameday">Normal</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="radio" class="form-check-input" id="is_overtime" name="is_overtime">
                            <label class="form-check-label" for="is_overtime">Lembur</label>
                        </div>
                        <div class="form-group d-none" id="hidden-overtime">
                            <label for="fc_overtime" class="font-weight-600">Alasan Lembur</label>
                            <textarea class="form-control" id="fc_overtime" rows="5" name="overtime_reason"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
                    <button type="submit" class="btn btn-success"><?= $this->lang->line('apply') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Detail Reguler Absen <span class="date-absen"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="margin-bottom: 0">
                            <label for="">Nama:</label>
                            <b data-detail-absen="full_name">-</b>
                        </div>
                        <div class="form-group" style="margin-bottom: 0">
                            <label for="">Shif:</label>
                            <b data-detail-absen="shift_name">-</b>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="margin-bottom: 0">
                            <label for="">Tanggal:</label>
                            <b data-detail-absen="date">-</b>
                        </div>
                        <div class="form-group" style="margin-bottom: 0">
                            <label for="">Jam Masuk:</label>
                            <b data-detail-absen="check_in_time">-</b>
                        </div>
                        <div class="form-group" style="margin-bottom: 0">
                            <label for="">Jam Keluar:</label>
                            <b data-detail-absen="check_out_time">-</b>
                        </div>
                    </div>
                </div>
                <hr>
                <table id="table-absen-reguler" class="table table-absen-reguler table-striped table-sm table-bordered ">
                    <thead>
                        <tr>
                            <!-- <th>No.</th> -->
                            <th>Post Name</th>
                            <th>Jam</th>
                            <th>Lat</th>
                            <th>Long</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal right fade" tabindex="-1" id="modal-close-only">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>