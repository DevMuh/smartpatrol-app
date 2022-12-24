<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?=base_url()?>dashboard">Home</a></li>
            <li class="breadcrumb-item active">Kantor Cabang</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Tambah Kantor Cabang</h1>
                <small>Add Data Kantor Cabang</small>
            </div>
        </div>
    </div>
</div>
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
        <?=$this->session->flashdata('failed'); ?>
        <h2 class="card-title text-center mb-5 mt-3 font-weight-bold">Tambah Data Kantor Cabang</h2>
            <form method="POST" action="<?=base_url($this->uri->segment(1))?>/save">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Kode Cabang</label>
                            <input name="kode" type="text" class="form-control" value="<?=set_value('kode') ?>" required>
                            <?=form_error('kode', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label>Nama Cabang</label>
                            <input name="nama" type="text" class="form-control" value="<?=set_value('nama') ?>" required>
                            <?=form_error('nama', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label >Wilayah</label>
                            <input name="wilayah" type="text" class="form-control" value="<?=set_value('wilayah') ?>" required>
                            <?=form_error('wilayah', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label >Nama Manager</label>
                            <input name="manager" type="text" class="form-control" value="<?=set_value('manager') ?>" required>
                            <?=form_error('manager', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label >Telepon</label>
                            <input name="telepon" type="text" class="form-control" onkeypress="return number(event)" value="<?=set_value('telepon') ?>" required>
                            <?=form_error('telepon', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label >Fax</label>
                            <input name="fax" type="text" class="form-control" value="<?=set_value('fax') ?>" required>
                            <?=form_error('fax', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label >Email</label>
                            <input name="email" type="email" class="form-control" value="<?=set_value('email') ?>" required>
                            <?=form_error('email', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label >Alamat Detail</label>
                            <input name="alamat" type="text" class="form-control" value="<?=set_value('alamat') ?>" required>
                            <?=form_error('alamat', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label >Provinsi</label>
                            <select class="form-control text-dark" name="provinsi" id="provinsi" required>
                                <option value="" selected >--Pilih Provinsi--</option>
                                <?php foreach ($provinsi as $value ):?>
                                <option value="<?=$value->id?>" ><?=$value->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label >Kota/Kabupaten</label>
                            <select class="form-control text-dark" name="kota" id="kota">
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <input name="deskripsi" type="text" class="form-control" value="<?=set_value('deskripsi') ?>" required>
                            <?=form_error('deskripsi', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label>Kode Pos</label>
                            <input name="kode_pos" type="text" class="form-control" onkeypress="return number(event)" value="<?=set_value('kode_pos') ?>" required>
                            <?=form_error('kode_pos', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label >Longitude</label>
                            <input name="longitude" type="text" class="form-control" onkeypress="return latlong(event)" value="<?=set_value('longitude') ?>" required>
                            <?=form_error('longitude', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label >Latitude</label>
                            <input name="latitude" type="text" class="form-control" onkeypress="return latlong(event)" value="<?=set_value('latitude') ?>" required>
                            <?=form_error('latitude', "<small class='text-danger'>",'</small>') ?>
                        </div>
                        <div class="form-group">
                            <label >Lokasi Map</label>
                            <!-- <input name="lokasi" type="text" class="form-control" > -->
                        </div>
                    </div>
                </div>
                <hr>
                 <div class="tile-footer mt-3">
                    <button type="submit" class="btn btn-lg btn-primary mr-3">Simpan</button>
            </form>
                    <a href="<?=base_url($this->uri->segment(1))?>" class="btn btn-lg btn-secondary">Kembali</a>
                </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function number(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    }

    function latlong(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 45 && charCode != 46 )
        return false;
    }
</script>