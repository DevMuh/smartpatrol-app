<style type="text/css">
    .my-custom-scrollbar {
        position: relative;
        height: 200px;
        overflow: auto;
    }

    .table-wrapper-scroll-y {
        display: block;
    }
</style>


<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard">Home</a></li>
            <li class="breadcrumb-item active">Intruksi</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Tambah Intruksi</h1>
                <small>Add Data Intruksi</small>
            </div>
        </div>
    </div>
</div>
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <?= $this->session->flashdata('failed'); ?>
            <h2 class="card-title text-center mb-5 mt-3 font-weight-bold">Tambah Data Intruksi</h2>
            <form onsubmit="socketS()" method="POST" action="<?= base_url($this->uri->segment(1)) ?>/save" enctype='multipart/form-data'>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Perihal</label>
                            <input name="perihal" type="text" class="form-control" value="<?= set_value('perihal') ?>" required>
                            <?= form_error('perihal', "<small class='text-danger'>", '</small>') ?>
                        </div>
                        <div class="form-group">
                            <label>Tingkat Urgensi</label>
                            <select class="form-control text-dark" name="id_kategori_instruksi" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($instruksi as $value) : ?>
                                    <option value="<?= $value->id ?>"><?= $value->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Detail Instruksi</label>
                            <textarea class="form-control" rows="4" name="detail"><?= set_value('detail') ?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input name="mulai" type="date" class="form-control" value="<?= set_value('mulai') ?>" required>
                            <?= form_error('mulai', "<small class='text-danger'>", '</small>') ?>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Selesai</label>
                            <input name="selesai" type="date" class="form-control" value="<?= set_value('selesai') ?>" required>
                            <?= form_error('selesai', "<small class='text-danger'>", '</small>') ?>
                        </div>
                        <div class="form-group">
                            <label>Lampiran</label>
                            <input name="image" type="file" class="form-control">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck2" name="feedback" value="1" <?php echo set_checkbox('feedback', '1'); ?>>
                                <label class="custom-control-label" for="customCheck2">Feedback</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input value="1" type="radio" name="customRadio" id="rad_anggota" checked> <label for="rad_anggota">Anggota</label>
                    <input value="2" type="radio" name="customRadio" id="rad_regu"> <label for="rad_regu">Regu</label>
                    <input value="3" type="radio" name="customRadio" id="rad_shift"> <label for="rad_shift">Shift</label>
                </div>
                <div id="anggota" class="d-none">
                    <label class="mt-4 font-weight-bold">Pilih Anggota</label>
                    <div class="table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered">
                            <tr>
                                <td>No</td>
                                <td>Username</td>
                                <td>Full Name</td>
                                <td>Aksi</td>
                            </tr>
                            <?php
                            $no = 1;
                            foreach ($anggota as $value) :
                            ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $value->username ?></td>
                                    <td><?= $value->full_name ?></td>
                                    <td>
                                        <input type="checkbox" name="anggota[]" value="<?= $value->id ?>">
                                    </td>
                                </tr>
                            <?php
                                $no++;
                            endforeach;
                            ?>
                        </table>
                    </div>
                </div>
                <div id="regu" class="d-none">
                    <label class="mt-4 font-weight-bold">Pilih Regu</label>
                    <div class="table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered">
                            <tr>
                                <td>No</td>
                                <td>Nama Regu</td>
                                <td>Aksi</td>
                            </tr>
                            <?php
                            $no = 1;
                            foreach ($regu as $value) :
                            ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $value->nama_regu ?></td>
                                    <td>
                                        <input type="checkbox" name="regu[]" value="<?= $value->id ?>">
                                    </td>
                                </tr>
                            <?php
                                $no++;
                            endforeach;
                            ?>
                        </table>
                    </div>
                </div>
                <div id="shift" class="d-none">
                    <label class="mt-4 font-weight-bold">Pilih Shift</label>
                    <div class="table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered">
                            <tr>
                                <td>No</td>
                                <td>Nama Shift</td>
                                <td>Durasi</td>
                                <td>Jam Masuk</td>
                                <td>Jam Pulang</td>
                                <td>Hari</td>
                                <td>Aksi</td>
                            </tr>
                            <?php
                            $no = 1;
                            foreach ($shift as $value) :
                            ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $value->shift_name ?></td>
                                    <td><?= $value->durasi ?> jam</td>
                                    <td><?= $value->waktu_start ?></td>
                                    <td><?= $value->waktu_end ?></td>
                                    <td><?php foreach(json_decode($value->day) as $row) {echo $row.', ';} ?></td>
                                    <td>
                                        <input type="checkbox" name="shift[]" value="<?= $value->id_ ?>">
                                    </td>
                                </tr>
                            <?php
                                $no++;
                            endforeach;
                            ?>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="tile-footer mt-3">
                    <button type="submit" class="btn btn-lg btn-primary mr-3">Simpan</button>
            </form>
            <a href="<?= base_url($this->uri->segment(1)) ?>" class="btn btn-lg btn-secondary">Kembali</a>
        </div>
    </div>
</div>
</div>


<!-- SHOW Anggota -->
<div class="modal fade" id="modalAnggota" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="min-height: 300px">
                <div class="justify-content-center align-content-center flex-wrap h-100" id="container-loading">
                    <div class="spinner-border text-danger" role="status" id="loading"></div>
                </div>
                <table class=" table table-bordered" id="content-detail">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($anggota as $value) :
                        ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $value->username ?></td>
                                <td><?= $value->full_name ?></td>
                                <td><input type="checkbox" name="anggota[]"></td>
                            </tr>
                        <?php
                            $no++;
                        endforeach;
                        ?>
                    </tbody>

                </table>
            </div>
            <div class="modal-footer">
                <div id="modal_status"></div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END SHOW Anggota -->