<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url() ?>register_b2b">Register B2B</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-map"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Cluster Route</h1>
                <small>Available cluster route</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <button id="tambah" type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2" data-toggle="modal" data-target="#exampleModal1"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br>
            &nbsp;
            <table id="tb_detail" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th style="width: 10px;">No</th>
                        <th>Cluster Name</th>
                        <th>Flag</th>
                        <th>Interval Option</th>
                        <th>Description</th>
                        <th style="width: 55px;"></th>
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
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Add Cluster</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('register_b2b/add')" method="POST">
                    <div class="form-group">
                        <label>Cluster Name</label>
                        <input name="cluster_name" type="text" class="form-control">
                        <input name="b2b" type="hidden" value="<?= $id ?>">
                    </div>
                    <div class="form-group">
                        <label>Flag</label>
                        <select name="flag_active" class="form-control">
                            <option value="1">Active</option>
                            <option value="2">Idle</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Interval Option</label>
                        <select name="interval_option" class="form-control">
                            <option value="1">Jam</option>
                            <option value="2">Week</option>
                            <option value="2">Month</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Apply</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Edit Cluster</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return subm('register_b2b/edit')" method="POST">
                    <div class="form-group">
                        <label>Cluster Name</label>
                        <input name="cluster_name" type="text" class="form-control">
                        <input name="eid" type='hidden' id="eid">
                    </div>
                    <div class="form-group">
                        <label>Flag</label>
                        <select name="flag_active" class="form-control">
                            <option value="1">Active</option>
                            <option value="2">Idle</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Interval Option</label>
                        <select name="interval_option" class="form-control">
                            <option value="1">Jam</option>
                            <option value="2">Week</option>
                            <option value="2">Month</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Apply</button>
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
                <form action="<?= base_url() ?>register_b2b/hapus" method="POST">
                    <input type="hidden" id="hid" name="did">
                    <button type="submit" class="btn btn-success">Apply</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/.main content-->