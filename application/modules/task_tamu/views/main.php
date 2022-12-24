<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home'); ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('guest'); ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-contacts"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('guest'); ?></h1>
                <small>Daftar tamu masuk dan keluar</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">

    <div class="card mb-4">
        <div class="card-header" style="border: 0; margin-bottom: -40px">
            <label id="fillterby"></label>
            <ul class="nav nav-tabs header-tabs right" style="margin-top: -20px;z-index: 1000; position: relative">
                <li class="nav-item">
                    <a href="#" id="0" class="nav-link text-center show active" data-toggle="tab">
                        <h6 class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-1">
                            Table
                        </h6>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="1" class="nav-link text-center show" data-toggle="tab">
                        <h6 class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-1">
                            Chart
                        </h6>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="form-group" style="margin-bottom: -35px;margin-left: 277px;">
                <button onclick="$('#mfillter').modal('show');" class="btn btn-warning text-white">Fillter</button>
                <!-- <label class="mr-2">Select Penghuni</label>
                <select class="form-control">
                    <option></option>
                    <option>Frendi</option>
                    <option>Frendi</option>
                    <option>Frendi</option>
                </select> -->
            </div>

            <div id="tbl_">
                <table id="tb_client" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                    <thead style="background-color: black;">
                        <tr style="color:white">
                            <th style="width: 120px"><?= $this->lang->line('time_arrival'); ?></th>
                            <th><?= $this->lang->line('duration'); ?></th>
                            <th style="width: 130px"><?= $this->lang->line('guest_name'); ?></th>
                            <th style="width: 465px"><?= $this->lang->line('destination_house'); ?></th>
                            <th style="width: 300px"><?= $this->lang->line('intended_person'); ?></th>
                            <th style="width: 5px"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div id="chart_" class="d-none"><br>
                <div id="current"></div>
                <div id="monthly"></div>
            </div>
        </div>
    </div>
</div>
<!--/.body content-->
</div>
<!--/.main content-->
<div class="modal fade" id="mfillter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Fillter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Pilih Penghuni</label>
                    <select id="s_fill" class="form-control select2">
                        <option value="0">Show All</option>
                        <?php foreach ($penghuni as $row) { ?>
                            <option value="<?= $row->id_ ?>"><?= $row->no_kavling . ' - ', $row->client_name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button onclick="reDraw($('#s_fill').val()); $('#mfillter').modal('hide');" type="submit" class="btn btn-success">Apply</button>
            </div>
        </div>
    </div>
</div>