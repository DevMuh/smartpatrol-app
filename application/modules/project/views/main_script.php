<link href="<?= base_url('assets/apps/assets/plugins/select2/dist/css/select2.min.css') ?>" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.css" rel="stylesheet">
<link href="<?= base_url('assets/apps/assets/plugins/select2-bootstrap4/dist/select2-bootstrap4.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/apps/assets/plugins/bootstrap4-toggle/css/bootstrap4-toggle.min.css"') ?> rel=" stylesheet">
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/select2/dist/js/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/bootstrap4-toggle/js/bootstrap4-toggle.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.js"></script>
<script>
    $('#1Project').addClass('mm-active');
    $('#subm_Master').css({'box-shadow': 'none', 'background' : 'transparent', 'color': 'white'});
    $('#2Project').css({'color': '#c81b1b', 'border-radius':'4px', 'background': 'white'});

    var table = $('#tb_client').DataTable({
        responsive: true,
        autoWidth: false,
        processing: true,
        serverside: true,
        ajax: '<?= base_url('project/ajax') ?>'
    });

    $(".basic-multiple").select2();
    $(".placeholder-multiple").select2({
        placeholder: "Select route"
    });


    function edit(id) {
        $('form .form-group p').remove()
        document.getElementById('editId').value = id
        var temp = event.path[3].getElementsByTagName('td');
        document.getElementById('nm_prj').value = temp[1].innerHTML;
    }

    function hapus(id) {
        document.getElementById('hid').value = id
        var temp = event.path[3].getElementsByTagName('td');
        $("#deltitle").html("Hapus projek " + temp[1].innerHTML + "?")
    }
</script>