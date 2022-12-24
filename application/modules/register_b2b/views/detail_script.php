<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script>
    $('#1register_b2b').addClass('mm-active');
    $('#subm_Master').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2register_b2b').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });

    $(document).ready(function() {
        $('#tb_detail').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('register_b2b/ajax2/' . $id) ?>'
        });
    });

    function edit(id) {
        $('form .form-group p').remove()
        document.getElementById('eid').value = id
        $('.btn').on('click', function() {
            $(this).addClass('btns');
            var temp = document.getElementsByClassName('btns')[0].offsetParent.parentElement.getElementsByTagName('td')
            var a = temp[1].innerHTML;
            var b = temp[4].innerHTML;
            $('input[name=cluster_name]').val(a);
            $('textarea[name=description]').val(b);
            $(this).removeClass('btns');
        })
    }

    function hapus(id) {
        document.getElementById('hid').value = id
        $('.btn').on('click', function() {
            $(this).addClass('btns');
            var temp = document.getElementsByClassName('btns')[0].offsetParent.parentElement.getElementsByTagName('td')
            var dt = temp[1].innerHTML;
            $("#deltitle").html("Delete " + dt + " cluster?")
            // $('input[name=ecluster_name]').val(cluster);
            $(this).removeClass('btns');
        })
    }
</script>