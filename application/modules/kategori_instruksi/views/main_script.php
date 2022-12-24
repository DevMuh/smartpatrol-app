<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<!-- tombol export -->
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script>
    // $('#1Approval').addClass('mm-active');
    $('#1Kategori_instruksi').addClass('mm-active');
    $('#subm_Master').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Kategori_instruksi').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });

    $(document).ready(function() {
        var table = $('#tb_kategori_instruksi').DataTable({
            dom: 'Bfrtip',
            buttons: [
                //     {
                //     extend      : 'excelHtml5',
                //     className   : 'btn btn-primary',
                //     title       : 'Data Kategori Instruksi',
                //     text        : 'Export Excel',
                //     exportOptions: {
                //         columns: [1,2,3,4],
                //         modifier: {
                //             page: 'all',
                //         }
                //     }
                // }
            ],
            columnDefs: [{
                "targets": [0],
                "visible": false,
                "searchable": false
            }],
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('kategori_instruksi/ajax') ?>'
        });
    });

    $(document).on('submit', '#addKategori', function(e) {
        e.preventDefault()
        var data = new FormData(this)
        $.ajax({
            url: '<?php echo base_url() ?>kategori_instruksi/add',
            method: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(this),
            success: function(response) {
                var type = JSON.parse(response).type
                var message = JSON.parse(response).message
                if (type == 'success') {
                    $('#tb_kategori_instruksi').DataTable().ajax.reload(null, false)
                    $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
                    $("#addKategori").trigger("reset");
                    $('#exampleModal1').modal('hide')
                } else {
                    $("#alert").html('<div class="alert alert-danger"><button type="button" class="close">×</button>' + message + '</div>');
                }

                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            }
        })
    })


    function edit(id) {
        $.ajax({
            url: '<?= base_url('kategori_instruksi/get_by_id?id=') ?>' + id,
            method: 'GET',
            success: function(response) {
                var kode = JSON.parse(response)[0].kode
                var nama = JSON.parse(response)[0].nama
                var deskripsi = JSON.parse(response)[0].deskripsi
                $('#editModal').modal('show')
                $('#editKode').val(kode)
                $('#editNama').val(nama)
                $('#editDeskripsi').val(deskripsi)

                $('.update').attr("onclick", "update(" + id + ", event)")
            }
        })
    }

    function update(id, e) {
        e.preventDefault()
        $.ajax({
            url: '<?= base_url('kategori_instruksi/update?id=') ?>' + id,
            method: 'POST',
            data: new FormData(document.getElementById('formUpdate')),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                var type = JSON.parse(response).type
                var message = JSON.parse(response).message
                if (type == 'success') {
                    $('#tb_kategori_instruksi').DataTable().ajax.reload(null, false)
                    $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
                    $('#editModal').modal('hide')
                } else {
                    $("#alert").html('<div class="alert alert-danger"><button type="button" class="close">×</button>' + message + '</div>');
                }

            }


        })
    }

    function deleteConfirm(url, name) {
        $('#modalDelete').modal('show');
        $('#messageDelete').html('Are you sure want to delete data <b>' + name + '</b>?')
        $('#hapus').attr('href', url);
    }

    // close alert
    $(".alert").fadeTo(5000, 500).slideUp(500, function() {
        $(".alert").slideUp(500);
    });
</script>