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
</style>
<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item"><a href="#"><?= $this->lang->line('master') ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('list_cabang') ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user-add"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('list_cabang') ?></h1>
                <!-- <small>Register new b2b</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            &nbsp;
            <table id="tb_b2b" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th><?= $this->lang->line('name') ?></th>
                        <th>Join Date</th>
                        <th><?= $this->lang->line('address') ?></th>
                        <th><?= $this->lang->line('phone') ?></th>
                        <!-- <th>Hidden Module</th> -->
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