<div class="body-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fs-17 font-weight-600 mb-0">Edit Profile</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form onsubmit="return subm('profile/update2', false)" method="POST">
                        <div class="row">
                            <div class="col-md-5 pr-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">Company</label>
                                    <input type="text" class="form-control" disabled="" placeholder="Company" value="<?=$table->title_nm?>">
                                </div>
                            </div>
                            <div class="col-md-3 px-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">Username</label>
                                    <input name="username" type="text" class="form-control" placeholder="Username" value="<?=$user->username?>">
                                </div>
                            </div>
                            <div class="col-md-4 pl-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">Fullname</label>
                                    <input name="full_name" type="text" class="form-control" value="<?=$user->full_name?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">Email</label>
                                    <input name="email" type="text" class="form-control" value="<?=$user->email?>">
                                </div>
                            </div>
                            <div class="col-md-6 pl-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">Phone</label>
                                    <input name="no_tlp" type="text" class="form-control" value="<?=$user->no_tlp?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pr-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">Old Password</label>
                                    <input name="old_password" type="password" class="form-control">
                                    <?php
                                    if ($this->session->flashdata('old_password')) { 
                                            echo $this->session->flashdata('old_password');
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4 px-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">New Password</label>
                                    <input name="new_password" type="password" class="form-control"">
                                </div>
                            </div>
                            <div class="col-md-4 pl-md-1">
                                <div class="form-group">
                                    <label class="font-weight-600">Confirm Password</label>
                                    <input name="confirm_password" type="password" class="form-control">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-fill btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>