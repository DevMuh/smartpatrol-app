<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<style>
    .mr-60 {
        margin-right: 60px !important;
    }
</style>
<script>
    $('#1Client_list').addClass('mm-active');
    $('#subm_Master').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Client_list').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });
    $(document).ready(function() {
        $('#tb_client').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                messageTop: 'Client List Report',
                text: 'Excel',
                className: 'btn btn-success right mr-5 mr-60 js-excell bt-export',
            }, {
                extend: 'pdfHtml5',
                messageTop: 'Client List Report',
                text: 'PDF',
                className: 'btn btn-danger right bt-export',
            }],
            lengthChange: false,
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('client_list/ajax') ?>',
            initComplete: function() {
                $(".js-excell").css({
                    "margin-right": "60px"
                })
                var temp = $("#tb_client_info").html()
                var start = temp.indexOf('of ') + 3;
                var end = temp.indexOf(' entries');
                var total = temp.substring(start, end)
                $("#total").html('<i style="font-size: 18px" class="fa fa-user"></i> Total Client : <b>' + total + '</b>');
            }
        });
    });

    function changeLength(length) {
        console.log(length)
        $('#tb_client').DataTable().page.len(length).draw();
    }

    function edit(id) {
        $('form .form-group p').remove()
        document.getElementById('editId').value = id
        var temp = event.currentTarget.parentElement.parentElement.children;
        var no_kav = temp[1].innerHTML;
        var client = temp[2].innerHTML;
        var username = temp[3].innerHTML;
        var phone = temp[4].innerHTML;
        $('input[name=eno_kavling]').val(no_kav);
        $('input[name=eclient_name]').val(client);
        $('input[name=username]').val(username);
        $('input[name=ephone]').val(phone);
    }

    function hapus(id) {
        document.getElementById('hid').value = id
        var temp = event.path[3].getElementsByTagName('td');
        var dt = temp[2].innerHTML;
        $("#deltitle").html("Delete client " + dt + "?")
    }
</script>