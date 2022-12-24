<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4  order-sm-last mb-3 mb-sm-0 p-0 ">

        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item active">Log Absensi Offline</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-calendar"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Log Absensi Offline</h1>
                <small>Semua data absen masuk atau pulang</small>
            </div>
        </div>
    </div>

</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">

            <table id="myTable" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <div class="cusfil"></div>
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th style="width: 20px;">No</th>
                        <?php if($this->session->userdata("user_roles") == 'admin' || $this->session->userdata("username") == 'cudodev'){ ?>
                            <th>Action</th>
                        <?php } ?>
                        <th style="width: 100px;"><?= $this->lang->line('name') ?></th>
                        <th style="width: 100px;"><?= $this->lang->line('shift') ?></th>
                        <th><?= $this->lang->line('date') ?></th>
                        <th><?= $this->lang->line('time') ?></th>
                        <th><?= $this->lang->line('submit_time') ?></th>
                        <th><?= $this->lang->line('status') ?></th>
                        <th><?= $this->lang->line('image') ?></th>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img id="img_modal" style="border: 15px solid white; height: 600px;object-fit:contain" src="#" />
        </div>
    </div>
</div>
<!--/.main content-->