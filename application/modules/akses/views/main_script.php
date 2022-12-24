<link href="<?= base_url('assets/apps/assets/plugins/select2/dist/css/select2.min.css') ?>" rel="stylesheet">
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/classie.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/modalEffects.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/select2/dist/js/select2.min.js') ?>"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<!-- <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script>
    // <?php
        //     if(validation_errors()){
        //         echo validation_errors();
        //     } else {
        //         echo "a";
        //     }
        // 
        ?>

    $('#1Akses').addClass('mm-active');
    $('#subm').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Akses').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });
    $(document).ready(function() {
        $(".placeholder-multiple").select2({
            placeholder: "Select table"
        });
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ],
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('akses/ajax') ?>',
            initComplete: function() {
                $('.dt-buttons').css({
                    'margin-bottom': '-20px',
                    'margin-top': '-77px',
                    'margin-left': '80px'
                })
                $('.dt-button').addClass('btn btn-info');
                $('.dt-button').css({
                    'height': '50px'
                })
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
        // $('#tb_uakses').DataTable({
        //     dom: 'Bfrtip',
        //     buttons: [
        //         'excel'
        //     ],
        //     responsive: true,
        //     autoWidth: false,
        //     processing: true,
        //     serverside: true,
        //     ajax: '<?= base_url('akses/ajaxuser') ?>',
        //     initComplete: function() {
        //         $('.dt-buttons').css({
        //             'margin-bottom': '-20px',
        //             'margin-left': '80px'
        //         })
        //         $('.dt-button').addClass('btn btn-info');
        //         $('.dt-button').css({
        //             'height': '50px'
        //         })
        //     }
        // });
    });


    function edit(id, event, ini) {
        $("form").trigger("reset")
        var row = $(ini).data("row")
        console.log(row);
        var table_id = JSON.parse(row.table_id)
        var additional_flag = JSON.parse(row.additional_flag)
        $('form .form-group p').remove()
        document.getElementById('editId').value = id
        var temp = event.path[3].getElementsByTagName('td');
        var a = temp[1].innerHTML;
        var b = temp[2].innerHTML;
        $('input[name=eroles_type]').val(a);
        $('#edittablee').val(table_id.id);
        $(".opt-tabel").each((i, el) => {
            if (table_id.id.indexOf($(el).val()) === -1) {
                $(el).prop("checked", true)
            }
        })
        // console.log(additional_flag.permission);
        const array_value = [];
        for (let index = 0; index < additional_flag.permission.length; index++) {
            const text = additional_flag.permission[index].text;
            const action = additional_flag.permission[index].action;

            const value = action+"|"+text;
            array_value.push(value);
        }
        // $('#editpermission').val(additional_flag.permission);
        $('#editpermission').val(array_value);
        $(".opt-permission").each((i, el) => {
            if (additional_flag.permission.indexOf($(el).val()) === -1) {
                $(el).prop("checked", true)
            }
        })
        $(".placeholder-multiple").select2({
            placeholder: "Select table"
        });
    }

    // function edituser(id){
    //     $('#edituserModal').modal('show');
    //     document.getElementById('editId').value = id
    //     var temp = event.path[3].getElementsByTagName('td');
    //     var a = temp[1].innerHTML;
    //     var b = temp[2].innerHTML;
    //     $('input[name=idEdit]').val(id);
    //     $('input[name=username]').val(a);
    //     $('input[name=fullname]').val(b);
    // }

    function hapus(id, event) {
        document.getElementById('hid').value = id
        var dt = event.path[3].getElementsByTagName('td');
        var st = 0;
        var msg = '';
        if (dt[3].innerHTML == 'Active') {
            msg = 'Matikan';
            st = 0
        } else {
            msg = 'Aktifkan';
            st = 1
        }
        $("#deltitle").html(msg + " role " + dt[1].innerHTML + "?")
        document.getElementById('stat').value = st;

    }
</script>