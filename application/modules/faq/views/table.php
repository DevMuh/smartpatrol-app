<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item"><a href="#"><?= $this->lang->line('master') ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('incident') ?></li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-world"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616"><?= $this->lang->line('incident') ?></h1>
                <!-- <small>From now on you will start your activities.</small> -->
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
            <div id="alertSuccess"></div>
            <table id="tb_faq" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white;">
                        <th style="width: 20px;">No</th>
                        <th style="width: 100px;">Faq Name</th>
                        <th>Question</th>
                        <th>Answer</th>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Add QNA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_add">
                    <div class="form-group">
                        <label class="  control-label">FAQ Name</label>
                        <div class=" ">
                            <select name="faq_id" required class="form-control select2">
                                <?php foreach ($faqs as $faq) { ?>
                                    <option value="<?= $faq->id ?>"><?= $faq->name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="  control-label">QNA</label>
                        <div class=" ">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th width="7">Sequence</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="w-qna">
                                    <tr class="t-qna">
                                        <td><textarea class="form-control" rows="3" name="question[]"></textarea>
                                        <td><textarea name="answer[]" id="" rows="3" class="form-control"></textarea></td>
                                        <td><input type="number" class="form-control" name="sequence_to[]" value=""></td>
                                        <td align="center"><a class="badge badge-danger remove" style="margin-bottom: 10px;cursor:pointer;color:white">Remove</a></td>
                                    </tr>
                                </tbody>
                            </table>
                            <a class="btn btn-default btn-sm add" style="margin-top: 10px"> <span class="fa fa-plus"></span> Add More QNA</a>
                        </div>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel4">Edit QNA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_update">
                    <input type="hidden" name="qna_id">
                    <div class="form-group">
                        <label class="  control-label">FAQ Name</label>
                        <div class=" ">
                            <select name="faq_id" required class="form-control select2">
                                <?php foreach ($faqs as $faq) { ?>
                                    <option value="<?= $faq->id ?>"><?= $faq->name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="  control-label">QNA</label>
                        <div class=" ">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th width="7">Sequence to</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <tr class="">
                                        <td><textarea rows="3" class="form-control" name="question"></textarea>
                                        <td><textarea name="answer" id="" rows="3" class="form-control"></textarea></td>
                                        <td><input type="number" class="form-control" name="sequence_to" value=""></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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