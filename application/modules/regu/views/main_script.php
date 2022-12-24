<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/classie.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/modalEffects.js') ?>"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<!-- <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
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
        var table = $('#myTable').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'excel'
            // ],
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('regu/ajax') ?>',
            initComplete: function() {
                $('.dt-button').addClass('btn btn-info');
                $('.dt-button').css({
                    'height': '50px'
                })
                $('.fill').css('background', 'white')
                $('.fill').removeClass('sorting')
                $('.fill').removeClass('sorting_asc')
            },
            language: {
                "decimal": "",
                "emptyTable": "Tidak ada data",
                "info": "Menampilkan _START_ sampai     _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(Difilter dari _MAX_ data)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Menampilkan _MENU_ data",
                "loadingRecords": `<i class="fa fa-refresh fa-spin text-dark"></i>Loading`,
                "processing": `<i class="fa fa-refresh fa-spin text-dark"></i>Loading`,
                "search": "Mencari:",
                "zeroRecords": "Data tidak ditemukan",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "aria": {
                    "sortAscending": ": diurutkan dari data terkecil",
                    "sortDescending": ": diurutkan dari data terbesar"
                }
            }
        });
    });

    function changeAc(id) {
        $('#changeActive').modal('show');
        $('#usrid').val(id);
        $('.form-group').children('p').css('display', 'none');
        document.getElementById('sid').value = id
        var dt = event.path[3].getElementsByTagName('td');
        console.log(dt, 'dt');
        var regu = dt[1].innerHTML
        var shift = dt[2].innerHTML
        var at = dt[5].innerHTML
        $('select[name=shift_regu]').val("")
        $('#config').val("")
        $('select[name=shift_regu]').children('option').each(function(i, e) {
            if ($(e).text() == shift) {
                $(this).val($(e).val())
                $(e).prop('selected', true)
            }
        })
        $('select[name=mode]').val("")
        $('select[name=mode]').children('option').each(function(i, e) {
            if (at.indexOf($(e).html()) == 0) {
                $(e).prop('selected', true)
                getConf($(e).val(), at)
            }
        })
        var msg = 'Ganti shift ' + regu
        document.getElementById('shiftt').innerHTML = msg
    }

    function getConf(param, at = '') {
        $.ajax({
            url: '<?= base_url('users/conf') ?>',
            method: 'POST',
            data: {
                act: param
            },
            dataType: 'json',
            beforeSend: function() {
                $('#config').attr('disabled', true)
                $('#config').html('<option> Loading ... </option>')
            },
            success: function(resp) {
                if (param != 0 && param != 3) {
                    $('#config').attr('disabled', false)
                }
                if (param == 1) {
                    var a = 'Projek'
                } else if (param == 2) {
                    var a = 'Cabang'
                } else {
                    var a = ''
                }
                $('#config').html('<option>Pilih ' + a + '</option>')
                for (i = 0; i < resp.length; i++) {
                    console.log(at)
                    if (at.indexOf(resp[i].nama) != -1) {
                        $('#config').append('<option value="' + resp[i].id + '" selected="true">' + resp[i].nama + '</option>')
                    } else {
                        $('#config').append('<option value="' + resp[i].id + '">' + resp[i].nama + '</option>')
                    }
                }
            }
        })
    }


    function edit(id) {
        $('form .form-group p').remove()
        document.getElementById('editId').value = id
        var temp = event.path[3].getElementsByTagName('td');
        var a = temp[1].innerHTML;
        var b = temp[2].innerHTML;
        $('input[name=enama_regu]').val(a);
        console.log(a)
    }

    function hapus(id) {
        document.getElementById('hid').value = id
        var dt = event.path[3].getElementsByTagName('td');
        var regu = dt[1].innerHTML
        var msg = 'Hapus ' + regu + '?'
        document.getElementById('deltitle').innerHTML = msg
    }

    function shift(id) {

    }
</script>