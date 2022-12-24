
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?=base_url()?>dashboard">Home</a></li>
            <li class="breadcrumb-item active">Approval</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Approval</h1>
                <small>Approval for a new users</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        
        <div class="card-body">
            <table id="tb_approval" class="table display table-bordered table-striped table-hover sourced dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead style="background-color: black;">
                    <tr style="color:white">
                        <th style="width: 7%">ID</th>
                        <th style="width: 7%">Detail</th>
                        <th style="width: 30%">Company Name</th>
                        <th style="width: 30%">PIC Name</th>
                        <th style="width: 15%">Submit Date</th>
                        <th style="width: 15%">Status</th>
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

<!-- CHANGE STATUS -->
    <div class="modal fade" id="modalChange" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="askChangeStatus">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <a class="btn btn-danger text-light" id="change" >Change</a>
            <!-- <a type="button" class="btn btn-danger text-light" id="change">Change</a> -->
          </div>
        </div>
      </div>
    </div>
<!-- END CHANGE STATUS -->

<!-- SHOW DETAIL -->
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="min-height: 300px">
            <div class="justify-content-center align-content-center flex-wrap h-100" id="container-loading">
              <div class="spinner-border text-danger" role="status" id="loading"></div>
            </div>
            <table class=" table table-bordered" id="content-detail">
                <tr>
                    <td class="font-weight-bold pr-0" width="106px">Company</td>
                    <td width="10px">:</td>
                    <td id="company"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" width="106px">PIC</td>
                    <td width="10px">:</td>
                    <td id="pic"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" width="106px">Email</td>
                    <td width="10px">:</td>
                    <td id="email"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" width="106px">Phone</td>
                    <td width="10px">:</td>
                    <td id="phone"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" width="106px">Address</td>
                    <td width="10px">:</td>
                    <td id="address"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" width="106px">Logo</td>
                    <td width="10px">:</td>
                    <td id="image"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-0" width="106px">Document</td>
                    <td width="10px">:</td>
                    <td id="document"> </td>
                </tr>

            </table>
          </div>
          <div class="modal-footer">
            <div id="modal_status"></div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
<!-- END SHOW DETAIL -->


<!-- SHOW IMAGE -->
    <div class="modal fade" id="showImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalScrollableTitle">Image</h5>
          </div>
          <div class="modal-body">
            <img id="modalImage" width="664px">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="closeImage">Close</button>
          </div>
        </div>
      </div>
    </div>
<!-- END SHOW IMAGE -->

<!-- SHOW PDF -->
    <div class="modal fade bd-example-modal-lg" id="showPdf" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" >
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold">Document</h5>
          </div>
            <div class="modal-body" >
                <div id="show_document" style="height: 400px">
                </div>
            </div>
          <div class="modal-footer">
            <button type="button" id="closePdf" class="btn btn-secondary" >Close</button>
          </div>
        </div>
      </div>
    </div>
<!-- END SHOW PDF -->

