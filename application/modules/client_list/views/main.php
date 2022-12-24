<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('client_list')?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?=$this->lang->line('client_list')?></h1>
                <!-- <small>This is client list table</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div>
                    <button id="tambah" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#tambahModal"><span style="font-size:25px;" class="typcn typcn-plus"></span></button>&nbsp;
                </div>
                <div class="col-md-3 d-flex flex-column p-3 mb-3 shadow-sm rounded" style="background-color: #ffc107; height: 55px">
                    <div class="d-flex align-items-center">
                        <h4 style="margin-top: -3px" id="total"></h4>
                    </div>
                </div>
            </div>
            <div class="dataTables_length" style="margin-bottom: -40px">
                <label>Show</label>
                <select style="width: 60px;" class="custom-select custom-select-sm form-control form-control-sm" onchange="changeLength(this.value)">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <label> entries</label>
            </div>
            <table id="tb_client" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th style="width: 20px;">No</th>
                        <th style="width: 20%"><?=$this->lang->line('kavling_number')?></th>
                        <th><?=$this->lang->line('client_name')?></th>
                        <th><?=$this->lang->line('username')?></th>
                        <th style="width: 170px"><?=$this->lang->line('phone')?></th>
                        <th style="width: 55px"></th>
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
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?=$this->lang->line('add').' '.$this->lang->line('client_list')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('client_list/tambah')" method="POST">
                    <div class="form-group">
                        <label><?=$this->lang->line('kavling_number')?></label>
                        <input name="no_kavling" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('client_name')?></label>
                        <input name="client_name" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('username')?></label>
                        <input name="username" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('password')?></label>
                        <input name="password" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('phone')?></label>
                        <input maxlength="15" onkeypress="validate(event)" name="phone" type="text" class="form-control">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <button type="submit" class="btn btn-success"><?=$this->lang->line('apply')?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4"><?=$this->lang->line('edit').' '.$this->lang->line('client_list')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('client_list/edit')" method="POST">
                    <div class="form-group">
                        <label><?=$this->lang->line('kavling_number')?></label>
                        <input name="eno_kavling" type="text" class="form-control">
                        <input name="eid" type="hidden" id="editId">
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('client_name')?></label>
                        <input name="eclient_name" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('username')?></label>
                        <input name="username" type="text" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('phone')?></label>
                        <input maxlength="15" onkeypress="validate(event)" name="ephone" type="text" class="form-control">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <button type="submit" class="btn btn-success"><?=$this->lang->line('apply')?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="deltitle">Delete this data?</h4>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <form action="<?= base_url() ?>client_list/hapus" method="POST">
                    <input type="hidden" id="hid" name="hid">
                    <button type="submit" class="btn btn-success">Apply</button>
                </form>
            </div>
        </div>
    </div>
</div>