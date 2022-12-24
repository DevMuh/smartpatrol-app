<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script>
    $('#1Shift').addClass('mm-active');
    $('#subm_Absensi').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Shift').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });
    $(document).ready(function() {
        var table = $('#tb_client').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('shift/ajax') ?>',
            initComplete: function() {
                // console.log(document.getElementById('tb_client').getElementsByTagName('tr')[1].getElementsByTagName('td')[2])
                // $('#tb_client thead tr th').clone(true).addClass('fill').appendTo('#tb_client thead');
                $("#tb_client thead th").each(function(i) {
                    if (i == 0) {
                        var select = $('<select class="form-control"><option value=""></option></select>')
                            .appendTo($('.cfill').empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                table.column(i)
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });
                        table.column(i).data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    }
                });
                $('.fill').css('background', 'white')
                $('.fill').removeClass('sorting')
                $('.fill').removeClass('sorting_asc')
            }
        });
    });

    $('.daycard').click(function() {
        if ($(this).children('input').val() == '') {
            $(this).children('input').attr('disabled', false);
            $(this).children('input').val($(this).children('span').html());
            $(this).addClass('dok');
        } else {
            $(this).children('input').attr('disabled', true);
            $(this).children('input').val('');
            $(this).removeClass('dok');
        }
    })

    $('#dura').change(function() {
        var d = $(this).val();
        var jumlah = Math.floor(24 / d);
        $('#jml_shift').html('')
        if (d > 0) {
            for (i = 1; i <= jumlah; i++) {
                $('#jml_shift').append('<option>' + i + '</option>');
                if (i == 5) break;
            }
        }
    })

    function edit(id) {
        $('form .form-group p').remove()
        document.getElementById('editId').value = id
        var temp = event.path[3].getElementsByTagName('td');
        $('[name=ekode_shift]').val(temp[0].innerHTML);
        $('[name=eshift_name]').val(temp[1].innerHTML);
        $('[name=ejam_masuk]').val(temp[2].innerHTML);
        $('[name=ejam_pulang]').val(temp[3].innerHTML);
        $('[name=ein_early]').val(temp[4].innerHTML);
        $('[name=ein_late]').val(temp[5].innerHTML);
        $('[name=eout_late]').val(temp[6].innerHTML);
    }

    function hapus(id) {
        document.getElementById('hid').value = id
        var dt = event.path[3].getElementsByTagName('td');
        var st = 0;
        var msg = '';
        if (dt[9].innerHTML == 'Active') {
            msg = 'Matikan ';
            st = 0
        } else {
            msg = 'Aktifkan ';
            st = 1
        }
        //console.log(dt[9].innerHTML)
        $("#deltitle").html(msg + dt[1].innerHTML + "?")
        document.getElementById('stat').value = st;

    }
</script>