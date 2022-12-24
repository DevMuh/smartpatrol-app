<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home'); ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('incident'); ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-world"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('incident'); ?></h1>
                <small>Menampilkan kejadian yang terjadi</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">

    <div class="card mb-4">

        <div class="card-body">
            <table id="tb_client" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th style="width: 15%"><?= $this->lang->line('category_name'); ?></th>
                        <th style="width: 25%"><?= $this->lang->line('description'); ?></th>
                        <th style="width: 25%"><?= $this->lang->line('location'); ?></th>
                        <th style="width: 20%">Pelapor</th>
                        <th style="width: 20%"><?= $this->lang->line('submit_date'); ?></th>
                        <th style="width: 3%"></th>
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