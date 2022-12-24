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
     #active .switch-button label { /*bulat kecil*/
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
    .remove-radius{
        border-radius: 0;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">


<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?=base_url()?>dashboard"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('kantor_cabang')?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?=$this->lang->line('kantor_cabang')?></h1>
                <!-- <small>List Data for all Kantor Cabang</small> -->
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <a href="<?=base_url($this->uri->segment(1))?>/post" style="text-decoration: none">
                    <button type="button" style="border-radius:160; align-items:right;" class="btn btn-info mb-2">
                        <span style="font-size:25px;" class="typcn typcn-plus text-light"></span>
                    </button>
                </a>
                <button class="btn btn-sm btn-success remove-radius ml-auto" data-toggle="modal" data-target="#import" style="margin: 11px 0 11px 0">
                    <i class="typcn typcn-cloud-storage-outline"></i> Upload Data
                </button>
            </div>
        </br>
            &nbsp;
        <?php 
            echo $this->session->flashdata('success');  
        ?>
        <div id="alertSuccess"></div>
            <table id="tb_kantor_cabang" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th>id</th>
                        <th style="width: 2%">No</th>
                        <th style="width: 5%"><?=$this->lang->line('code')?></th>
                        <th style="width: 20%"><?=$this->lang->line('sub_office_name')?></th>
                        <th style="width: 15%"><?=$this->lang->line('prov')?></th>
                        <th style="width: 15%"><?=$this->lang->line('kab/kota')?></th>
                        <th style="width: 15%"><?=$this->lang->line('phone')?></th>
                        <th style="width: 10%"><?=$this->lang->line('status')?></th> <!-- display status for table data -->
                        <th style="width: 10%"><?=$this->lang->line('status')?></th> <!-- display status for export data -->
                        <th style="width: 10%"><?=$this->lang->line('action')?></th>
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


<!-- import -->
<div class="modal fade table-bordered" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none; margin-top:-7px" aria-hidden="true">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h4 class="modal-title text-white"><?=$this->lang->line('upload')?> Data <?=$this->lang->line('kantor_cabang')?></h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
    <form method="POST" enctype="multipart/form-data" id="FormUploadData">    
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" style="border: 0 !important">
                            <div class="card-body" style="padding: 0;">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group row ml-2">
                                            <label for="inputPassword" class="pt-0 col-form-label" style="width: 70px !important">Select File :</label>
                                            <div class="col-sm-9">
                                                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                                                <input type="file" class="form-control-file" name="file">
                                            </div>
                                        </div>
                                        <div class="text-center mt-2">
                                            <b>Noted : Only File Excel</b>
                                        </div>
                                        <div class="text-center ">
                                            <a href="<?=base_url('/assets/apps/assets/Template.xlsx')?>">Download Template</a>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input class="btn btn-primary save-site" type="submit" value="Save">
            </div>
    </form>
        </div>
    </div>
</div>
<!-- end import -->