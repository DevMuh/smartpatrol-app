<div class="col-md-12 d-flex justify-content-center">
    <form method="POST" id="form_claim" class="col-md-8" action="absensi/claim">
        <div class="form-group">
            <label for="fc_petugas" class="font-weight-600">Petugas</label>
            <select class="basic-single form-control" id="fc_petugas" name="petugas" required>
            </select>
        </div>
        <div class="form-group">
            <label for="fc_shift" class="font-weight-600">Shift Kerja</label>
            <select class="basic-single form-control" id="fc_shift" name="shift" required>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1" class="font-weight-600">Tanggal dan Waktu Absence</label>
            <input class="form-control" type="datetime-local" name="submit_time" required>
        </div>
        <div class="form-group">
            <label for="fc_status" class="font-weight-600">Jenis Absen</label>
            <select class="form-control" id="fc_status" name="status" required>
                <option value="1">Masuk</option>
                <option value="2">Pulang</option>
            </select>
        </div>
        <div id="hidden-claim-form" class="d-none">
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="is_sameday" name="is_sameday">
                <label class="form-check-label" for="is_sameday">Shift hari yang sama</label>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="is_overtime" name="is_overtime">
                <label class="form-check-label" for="is_overtime">Lembur</label>
            </div>
            <div class="form-group d-none" id="hidden-overtime">
                <label for="fc_overtime" class="font-weight-600">Alasan Lembur</label>
                <textarea class="form-control" id="fc_overtime" rows="5" name="overtime_reason"></textarea>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <div class="form-group d-flex">
                <input type="submit" value="Simpan" class="btn btn-primary">
            </div>
        </div>
    </form>
</div>