<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4  order-sm-last mb-3 mb-sm-0 p-0 ">

        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item active">Report Absensi</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-calendar"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Report Absensi</h1>
                <small>Semua data absen masuk atau pulang</small>
            </div>
        </div>
    </div>

</div>

<div class="body-content">
    <iframe class="py-2" src="<?php echo $base_url_report; ?>report/<?php echo $this->session->userdata("id"); ?>" id="card-body" style="width: 100%; border-radius: 8px;" frameborder="0"></iframe>
</div>