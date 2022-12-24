<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <?php if ($this->uri->segment(2) != "mobile") : ?>
        <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
            <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
                <li class="breadcrumb-item active">FAQ</li>
            </ol>
        </nav>
    <?php endif ?>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-info-large"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">FAQ</h1>
                <small>Frequently Asked Questions</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div style="z-index:0" class="row w-faq">
    </div>
</div>
<!--/.body content-->
</div>