<style>
    .table td,
    .table th {
        border: none;
    }

    .table th {
        width: 25%;
    }
</style>
<div class="body-content">
    <div class="row mb-3">
        <div class="col-sm-12 col-xl-8">
            <div class="media d-flex m-1 ">
                <div class="align-left p-1">
                    <a href="#" class="profile-image">
                        <img src="<?= base_url('assets/apps/assets/dist/img/avatar3.png') ?>" class="avatar avatar-xl rounded-circle img-border height-100" alt="Card image">
                    </a>
                </div>
                <div class="media-body text-left ml-3 mt-1">
                    <h3 class="font-large-1 white"><?= $user->full_name ?></h3>
                    <span class="font-medium-1 white">(<?= ucfirst($user->user_roles) ?>)</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <td style="width: 3px">:</td>
                            <td><?= $user->full_name ?></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>:</td>
                            <td><?= $user->username ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>:</td>
                            <td><?= $user->email ?></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>:</td>
                            <td><?= $user->no_tlp ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 font-weight-600">Company</h6>
                        </div>
                        <div class="col-auto">
                            <time class="fs-13 font-weight-600 text-muted" datetime="1988-10-24"><?= $table->title_nm ?></time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 font-weight-600">Joined</h6>
                        </div>
                        <div class="col-auto">
                            <time class="fs-13 font-weight-600 text-muted" datetime="2018-10-28"><?= date_format(date_create($table->tgl_join), "d F Y") ?></time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 font-weight-600">Location</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 font-weight-600 text-muted"><?= $table->alamat ?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 font-weight-600">Phone</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 font-weight-600 text-muted"><?= $table->phone ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>