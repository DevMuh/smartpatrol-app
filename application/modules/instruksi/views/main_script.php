<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script>
    $('#Instruksi').addClass('mm-active');

    $(document).ready(function() {


        var table = $('#tb_data').DataTable({
            order: [0, "desc"],
            columnDefs: [{
                "targets": [0],
                "searchable": false,
                "visible": false
            }],
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('instruksi/ajax') ?>'
        });



        $(document).on('submit', '#FormImport', function(e) {
            e.preventDefault();
            $.ajax({
                url: '<?= base_url('instruksi/import') ?>',
                method: 'POST',
                contentType: false,
                cache: false,
                processData: false,
                data: new FormData(this),
                beforeSend: function(xhr) {
                    $('#loading').addClass('loading')
                },
                success: function(response) {
                    $('#loading').removeClass('loading')
                    let status = JSON.parse(response).type;
                    let message = JSON.parse(response).message;
                    if (status == "success") {
                        $('#addSite').modal('hide')
                        table.ajax.reload();
                    }
                    Alert(status, message)
                }
            })
        })





    }); //end document ready


    function detail(id, b2b_token) {
        $('#modalDetail').modal();
        $.ajax({
            url: '<?= base_url($this->uri->segment(1)) ?>/get_by_id?id=' + id + '&token=' + b2b_token,
            method: 'GET',
            beforeSend: function(xhr) {
                $('#container-loading').addClass('d-flex')
                $('#loading').fadeIn()
                $('#content-detail').fadeOut()
                $('#modal_status').fadeOut()
            },
            success: function(response) {
                var toJson = JSON.parse(response);
                var detail = toJson.detail[0];
                var anggota = toJson.anggota;

                $('#modal_status').fadeIn()
                $('#container-loading').removeClass('d-flex')
                $('#container-loading').fadeOut()
                $('#loading').fadeOut()
                $('#content-detail').fadeIn(1000)
                var nama = detail.nama
                var detail_instruksi = detail.detail_instruksi
                var kirim = detail.tanggal_kirim
                var pengirim = detail.pengirim
                var mulai = detail.tanggal_mulai
                var selesai = detail.tanggal_selesai
                var feedback = detail.feedback
                var perihal = detail.perihal
                var lampiran = detail.lampiran


                $('#nama').html(nama)
                $('#detail').html(detail_instruksi)
                $('#kirim').html(kirim)
                $('#pengirim').html(pengirim)
                $('#mulai').html(mulai)
                $('#selesai').html(selesai)
                $('#perihal').html(perihal)

                $('#list_anggota').empty();

                if (anggota.length == 0) {
                    $("#list_anggota").html("-")

                } else {
                    anggota.forEach(function(item, index) {
                        $("#list_anggota").append("<div>" + (index + 1) + ". " + item.full_name + "</div>")
                    })
                }

                $('#anggota').html(anggota)

                if (feedback != 1) {
                    $('#feedback').html("No")
                } else {
                    $('#feedback').html("Yes")
                }

                if (lampiran != null) {
                    $('#lampiran').html(`<img src="<?= base_url() ?>assets/apps/assets/img/instruksi/${lampiran}" width="150px" 
                    onError="this.onerror=null;this.src='<?= $this->config->item("base_url_server_cudo")?>assets/foto_feedback_intruksi/${lampiran}'">`)
                } else {
                    $('#lampiran').html('<img src="<?= base_url() ?>assets/apps/assets/no_image.png" width="150px">')
                }

            }
        })
    }


    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
        $(".alert").slideUp(500);
    });



    function Alert(type, message) {
        Swal.fire({
            html: true,
            title: type,
            html: message,
            type: type,
        })
    }

    var mode;
    var data = [];

    function radioFor() {
        $('input[name=customRadio]').each(function(i, e) {
            if (e.checked) {
                if (e.value == 1) {
                    $('#anggota').removeClass('d-none');
                    $('#regu').addClass('d-none');
                    $('#shift').addClass('d-none');
                    mode = 1
                } else if (e.value == 2) {
                    $('#anggota').addClass('d-none');
                    $('#regu').removeClass('d-none');
                    $('#shift').addClass('d-none');
                    mode = 2
                } else if (e.value == 3) {
                    $('#anggota').addClass('d-none');
                    $('#regu').addClass('d-none');
                    $('#shift').removeClass('d-none');
                    mode = 3
                }
            }
        })
    }
    $('input[name=customRadio]').change(function() {
        radioFor()
    })
    radioFor()

    const socket = io('http://smartpatrol.id:3001')
    // const socket = io('http://192.168.100.13:3001')

    function socketS() {
        var i = 0;
        if (mode == 1) {
            document.getElementsByName('anggota[]').forEach(function(ee, ii) {
                if (ee.checked) {
                    data[i] = ee.value
                    i++;
                }
            })
        } else if (mode == 2) {
            document.getElementsByName('regu[]').forEach(function(ee, ii) {
                if (ee.checked) {
                    data[i] = ee.value
                    i++;
                }
            })
        } else if (mode == 3) {
            document.getElementsByName('shift[]').forEach(function(ee, ii) {
                if (ee.checked) {
                    data[i] = ee.value
                    i++;
                }
            })
        }
        socket.emit('instruksi', {
            b2b_token: "<?= $_SESSION['b2b_token'] ?>",
            id_user: "<?= $_SESSION['id'] ?>",
            full_name: "<?= $_SESSION['full_name'] ?>",
            perihal: $('input[name=perihal]').val(),
            instruksi: $('textarea[name=detail]').val(),
            tgl_mulai: $('input[name=mulai]').val(),
            tgl_selesai: $('input[name=selesai]').val(),
            mode: mode,
            data: data
        })

        // console.log({
        //     b2b_token: "<?= $_SESSION['b2b_token'] ?>",
        //     id_user: "<?= $_SESSION['id'] ?>",
        //     full_name: "<?= $_SESSION['full_name'] ?>",
        //     perihal: $('input[name=perihal]').val(),
        //     instruksi: $('textarea[name=detail]').val(),
        //     tgl_mulai: $('input[name=mulai]').val(),
        //     tgl_selesai: $('input[name=selesai]').val(),
        //     mode: mode,
        //     data: data
        // })
    }
</script>