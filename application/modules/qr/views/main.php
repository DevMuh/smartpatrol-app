<style type="text/css">
    .switch-button.switch-button-lg {
        width: 90px;
    }

    #inactive .switch-button {
        border-radius: 50px;
        background-color: #b3b3b3;
        position: relative;
    }

    #inactive .switch-button label {
        border-radius: 50%;
        background-color: #fff;
        margin-left: 5px;
        height: 19px;
        width: 19px;
        z-index: 1;
        display: inline-block;
        cursor: pointer;
        margin-top: 5px;
        margin-bottom: 1px;
    }

    #inactive .switch-button label:before {
        position: absolute;
        font-size: 0.8462rem;
        font-weight: 600;
        z-index: 0;
        content: "Inactive";
        right: 0;
        display: block;
        width: 100%;
        height: 100%;
        line-height: 31px;
        top: 0;
        text-align: right;
        padding-right: 7px;
        color: #fff;
    }

    #active .switch-button {
        border-radius: 50px;
        background-color: #33b5e5;
        position: relative;
    }

    #active .switch-button label {
        /*bulat kecil*/
        border-radius: 50%;
        background-color: #fff;
        margin-left: 64px;
        height: 19px;
        width: 19px;
        z-index: 1;
        display: inline-block;
        cursor: pointer;
        margin-top: 5px;
        margin-bottom: 1px;
    }

    #active .switch-button label:before {
        position: absolute;
        font-size: 0.8462rem;
        font-weight: 600;
        z-index: 0;
        content: "Active";
        right: 0;
        display: block;
        width: 100%;
        height: 100%;
        line-height: 31px;
        top: 0;
        text-align: right;
        padding-right: 32px;
        color: #fff;
    }

    #map {
        height: 400px;
        position: relative;
        width: 100%;
    }
</style>

<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item active">Barcode Absen</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Barcode Absen</h1>
                <!-- <small>List Data for all users</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <button type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2 js-add" data-toggle="modal" data-target="#exampleModal1"><span style="font-size:25px;" class="typcn typcn-plus"></span></button></br>
            &nbsp;
            <?php
            echo $this->session->flashdata('delete');
            ?>
            <div id="alertSuccess"></div>
            <table id="tb_qr" class="table display table-bordered table-striped table-hover sourced dataTable" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th>Post Name</th>
                        <th>QR ID</th>
                        <th width="5%">QR Image</th>
                        <th width="17%" align="center">Action</th>
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




<!-- Add Users -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Add QR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="alert"></div>
                <form id="formAdd">

                    <div class="form-group">
                        <label>Post Name</label>
                        <input name="name" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>QR ID</label>
                        <input name="qr_id" type="text" class="form-control" required>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success js-submit">Generate</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Add Users -->


<!-- Edit Users -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Edit QR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="alert-edit"></div>
                <form id="formUpdate">
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label>Post Name</label>
                        <input name="name" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>QR ID</label>
                        <input name="qr_id" type="text" class="form-control" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success js-edit">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- End Edit Users -->


<!-- Delete -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="messageDelete">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a type="button" class="btn btn-danger text-light" id="hapus">Delete</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img id="img_modal" style="border: 15px solid white; " height="500px" width="500px" src="#" />
        </div>
    </div>
</div>