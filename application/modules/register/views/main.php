<!--Content Header (Page header)-->
<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item active">Complete Your Registration</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-document-text"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Registration</h1>
                <small>From now on you will start your activities.</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
        <?=$this->session->flashdata('failed'); ?>
            <h4 class="font-weight-bold text-center mb-5">Complete Registration</h4>
            <?php $get = $this->input->get() ?>
            <form class="user" method="POST" action="<?=base_url('register/save_complate?email='.$get['email'].'&token='.$get['token'])?>" enctype="multipart/form-data">
                <div class="form-group mb-3 row">
                    <div class="col-sm-6">
                        <label class="text-dark font-weight-bold">PIC Name</label>
                        <input type="text" class="form-control" placeholder="PIC Name..." name="pic" value="<?=set_value('pic') ?>" required>
                        <?=form_error('pic', "<small class='text-danger'>",'</small>') ?>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-dark font-weight-bold">Phone</label>
                        <input type="text" class="form-control" placeholder="08xxxxx..." name="phone" value="<?=set_value('phone') ?>" onkeypress="return number(event)" required>
                        <?=form_error('phone', "<small class='text-danger'>",'</small>') ?>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="text-dark font-weight-bold">Address</label>
                    <textarea name="address" class="form-control " rows="3" required><?=set_value('address') ?></textarea>
                    <?=form_error('address', "<small class='text-danger'>",'</small>') ?>
                </div>
                <div class="form-group mb-3 row">
                    <div class="col-sm-6">
                        <label class="text-dark font-weight-bold">Upload Logo</label>
                        <input type="file" class="form-control" name="logo" required>
                        <small class="text-danger">* Max 2 Mb </small>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-dark font-weight-bold">Upload Document</label>
                        <input type="file" class="form-control" name="document" required>
                        <small class="text-danger">* Max 2 Mb </small>
                    </div>
                </div>
                <button type="submit" class="btn btn-danger mr-3 mt-4">Register</button>
            </form>        
        </div>
    </div>
</div>
<!--/.body content-->

<!--/.main content-->

<script type="text/javascript">
    function number(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
        return true;
    }
</script>