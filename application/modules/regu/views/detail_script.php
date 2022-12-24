<link href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.css" rel="stylesheet">
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/classie.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/modalEffects.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.js"></script>

<style>
    .hide_me {
        display: none;
    }
</style>
<script>
    $('#1Regu').addClass('mm-active');
    $('#subm_Absensi').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Regu').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });

    $(document).ready(function() {
        var tb_asal = $('#tb_asal').DataTable({
            responsive: true,
            info: false,
            lengthChange: false,
            autoWidth: false,
            processing: true,
            serverside: true,
            sort: false,
            paging: true,
            searching: true,
            length: -1,
            columnDefs: [{
                targets: [0],
                className: "hide_me",
                searchable: false
            }],
            initComplete: function() {},
            ajax: '<?= base_url('regu/ajax2') ?>',
        });

        var tb_pindah = $('#tb_pindah').DataTable({
            responsive: true,
            info: false,
            lengthChange: false,
            autoWidth: false,
            processing: true,
            serverside: true,
            sort: false,
            paging: true,
            searching: true,
            length: -1,
            columnDefs: [{
                targets: [0],
                className: "hide_me",
                searchable: false
            }],
            initComplete: function() {
                $('.dataTables_empty').parent().html('')

            },
            ajax: '<?= base_url('regu/ajax3') ?>',
        });

    });

    function addR(param) {
        $(param).html('<button class="btn mybt" onclick="removeR($(this).parent()[0])"><i class="fa fa-arrow-left"></i></button>')
        $('#pindah').append($(param).parent()[0])
    }

    function removeR(param) {
        $(param).html('<button class="btn mybt" onclick="addR($(this).parent()[0])"><i class="fa fa-arrow-right"></i></button>')
        $('#asal').append($(param).parent()[0])
    }

    function exet() {
        var a = Array()
        var b = Array()
        $('tr', '#pindah').each(function(i, data) {
            $('td', data).each(function(j, td) {
                if (j == 0) {
                    a[i] = $(td).html();
                }
            })
        })
        $('tr', '#asal').each(function(i, data) {
            $('td', data).each(function(j, td) {
                if (j == 0) {
                    b[i] = $(td).html();
                }
            })
        })
        $.ajax({
            url: '<?= base_url('regu/updateRegu') ?>',
            type: 'POST',
            data: {
                now: JSON.stringify(a),
                delete: JSON.stringify(b)
            },
            dataType: 'json',
            success: function(resp) {
                if (resp == 1) {
                    Swal.fire(
                        'Regu',
                        'Anggota regu berhasil diubah!',
                        'success'
                    )
                }
            }
        })
    }
</script>