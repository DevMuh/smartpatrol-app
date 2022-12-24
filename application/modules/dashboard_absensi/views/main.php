<style>
    .text-success {
        color: #28a745 !important;
    }
</style>
<div class="body-content">
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="row mb-2">
                <div class="col-9"></div>
                <div class="col-3">
                    <select name="tanggal" id="tanggal_absen" class="form-control" aria-placeholder="-- Pilih Tanggal --" placeholder="-- Pilih Tanggal --">
                        <?php 
                        foreach ($last_day as $key => $value) {
                            echo "<option value=' " . $value->date . " '>" . $value->date_format . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <a href="#" class="js-user-absen" target="_blank" data-type="all" data-title="Semua Karyawan">
                        <div class="d-flex flex-column p-3 mb-3 bg-white shadow-sm rounded">
                            <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Total
                                Karyawan</div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa fa-users opacity-25 mr-2 text-size-3  text-dark"></i>
                                <span class="text-size-2 text-dark text-monospace"><?= $summary_absen["total_user"] ?></span>
                            </div>
                            <span class="small text-dark">Total karyawan aktif</span>
                        </div>
                    </a>
                </div>
                <div class="col-md">
                    <a href="#" class="js-user-absen" data-type="absen" data-title="Sudah Absen">
                        <div class="p-2 bg-white rounded p-3 mb-3 shadow-sm">
                            <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">
                                Sudah Absen
                            </div>
                            <div class="text-muted">
                                <!-- <i class="fas fa fa-long-arrow-alt-up text-success"></i> -->
                                <span class="text-success text-size-2 text-monospace">
                                    
                                    <span id="total_attend_percentage">
                                        <?= $summary_absen["total_attend_percentage"] ?>
                                    </span>
                                    
                                </span>
                            </div>
                            <div class="small text-dark">
                                <span class="sub-absen">Hari ini</span> <b>|</b> Total: <span class="text-monospace mx-auto font-weight-bold"><?= $summary_absen["total_attend"] ?>/<?= $summary_absen["total_user"] ?></span>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md">
                    <a href="#" class="js-user-absen" data-type="unabsen" data-title="Tidak Absen">
                        <div class="p-2 bg-white rounded p-3 mb-3 shadow-sm">
                            <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">
                                Tidak Absen
                            </div>
                            <div class="text-muted">
                                <!-- <i class="fas fa fa-long-arrow-alt-up text-success"></i> -->
                                <span class="text-danger text-size-2 text-monospace">
                                    <span id="total_absence_percentage">
                                        <?= $summary_absen["total_absence_percentage"] ?>
                                    </span>
                                </span>
                            </div>
                            <div class="small text-dark">
                                <span class="sub-absen">Hari ini</span> <b>|</b> Total: <span class="text-monospace mx-auto font-weight-bold"><?= $summary_absen["total_absence"] ?>/<?= $summary_absen["total_user"] ?></span>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md">
                    <a href="#" class="js-user-absen" data-type="per_hour" data-title="Absen Per-Jam">
                        <div class="p-2 bg-white rounded p-3 mb-3 shadow-sm">
                            <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">
                                Absen Per-Jam
                            </div>
                            <div class="text-muted">
                                <!-- <i class="fas fa fa-long-arrow-alt-up text-success"></i> -->
                                <span class="text-info text-size-2 text-monospace">
                                    <span id="total_perjam">
                                        <?= $summary_absen["total_perjam"] ?>
                                    </span>
                                </span>
                            </div>
                            <div class="small text-dark">
                                <span class="sub-absen">Hari ini</span>
                            </div>
                            <!-- <span class="small text-dark">Hari ini</span> -->
                            <!-- <span class="small text-dark text-monospace mx-auto">50/<?= $summary_absen["total_user"] ?></span> -->
                            <!-- </span> -->
                        </div>
                    </a>
                </div>

                <div class="col-md">
                    <a href="#" class="js-user-absen" data-type="event" data-title="Absen Event">
                        <div class="p-2 bg-white rounded p-3 mb-3 shadow-sm">
                            <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">
                                Absen Event
                            </div>
                            <div class="text-muted">
                                <!-- <i class="fas fa fa-long-arrow-alt-up text-success"></i> -->
                                <span class="text-warning text-size-2 text-monospace">
                                    <span id="total_event">
                                        <?= $summary_absen["total_event"] ?>
                                    </span>
                                </span>
                            </div>
                            <div class="small text-dark">
                                <span class="sub-absen">Hari ini</span>
                            </div>
                            <!-- <span class="small text-dark">Hari ini</span> -->
                            <!-- <span class="small text-monospace mx-auto">50/<?= $summary_absen["total_user"] ?></span> -->
                            <!-- </span> -->
                        </div>
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="fs-17 font-weight-600 mb-0">Total & Akumulasi Absensi - <?= date("F, Y") ?></h6>
                        </div>
                        <div class="card-body ">
                            <div style="height: 50vh;" id="bar-chart-total-absen"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card" style="min-height: 567px; max-height:567px">
                            <div class="card-header">
                                <h6 class="fs-17 font-weight-600 mb-0">Yang sudah Absen Masuk <span class="sub-absen">Hari ini</span></h6>
                            </div>
                            <div class="card-body ">
                                <table id="tb_absen_masuk" class="table display table-bordered table-striped table-hover sourced dataTable nowrap" role="grid" aria-describedby="DataTables_Table_0_info">
                                    <thead>
                                        <tr>
                                            <td>Nama</td>
                                            <td>Posisi</td>
                                            <td>Organisasi</td>
                                            <td>Jam Masuk</td>
                                            <td>Jam Pulang</td>
                                            <td>QR IN</td>
                                            <td>QR OUT</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card" style="min-height: 567px; max-height:567px">
                            <div class="card-header">
                                <h6 class="fs-17 font-weight-600 mb-0">Yang Belum Absen <span class="sub-absen">Hari ini</span></h6>
                            </div>
                            <div class="card-body ">
                                <table id="tb_belum_absen" class="table display table-bordered table-striped table-hover sourced dataTable nowrap" role="grid" aria-describedby="DataTables_Table_0_info">
                                    <thead>
                                        <tr>
                                            <th><?= $this->lang->line('full_name') ?></th>
                                            <td>Organisasi</td>
                                            <th>Position</th>
                                            <th>Regu</th>
                                            <th>Shift</th>
                                            <th>Device Name</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.main content-->