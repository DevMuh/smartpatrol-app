<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;600&display=swap');

    * {
        font-family: 'Montserrat', sans-serif;
    }

    table {
        white-space: nowrap;
        width: 100%;
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 10px;
    }

    table {
        autosize: 1;
    }

    .title {
        text-align: center;
        font-size: 1rem;
    }
</style>
<table role="grid">
    <thead>
        <tr>
            <th colspan="15" class="title">REKAP ABSENSI <?= "$bulan $tahun" ?></th>
        </tr>
        <tr>

            <th>Nama Lengkap</th>
            <th>Organization</th>
            <th>Nama Shift</th>
            <th>Durasi Shift</th>
            <th>Shift Mulai</th>
            <th>Shift Akhir</th>
            <th>Waktu Masuk</th>
            <th>Waktu Pulang</th>
            <th>Waktu Telat Masuk</th>
            <th>Pulang Lebih Awal</th>
            <th>Total Jam Kerja</th>
            <th>Total Lembur Awal</th>
            <th>Total Lembur Akhir</th>
            <th>Tempat Masuk</th>
            <th>Tempat Pulang</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data_absen->rows['data'] as $key => $value) {
        ?>
            <tr>
                <td><?= $value[0] ?></td>
                <td><?= $value[1] ?></td>
                <td><?= $value[2] ?></td>
                <td><?= $value[3] ?></td>
                <td><?= $value[4] ?></td>
                <td><?= $value[5] ?></td>
                <td><?= $value[6] ?></td>
                <td><?= $value[7] ?></td>
                <td><?= $value[8] ?></td>
                <td><?= $value[9] ?></td>
                <td><?= $value[10] ?></td>
                <td><?= $value[11] ?></td>
                <td><?= $value[12] ?></td>
                <td><?= $value[13] ?></td>
                <td><?= $value[14] ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>