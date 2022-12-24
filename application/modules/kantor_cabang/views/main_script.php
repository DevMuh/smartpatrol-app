<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<!-- tombol export -->
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script>
    // $('#1Approval').addClass('mm-active');
    $('#1Kantor_cabang').addClass('mm-active');
    $('#subm_Master').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Kantor_cabang').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });

    $(document).ready(function() {
        var table = $('#tb_kantor_cabang').DataTable({
            dom: 'Bfrtip',
            buttons: { //remove class in button default export datatables 
                dom: {
                    button: {
                        tag: 'button',
                        className: ''
                    }
                },
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'btn btn-success remove-radius',
                    title: 'Data Kantor Cabang',
                    text: '<i class="typcn typcn-cloud-storage-outline"></i> Export Excel',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 8],
                        modifier: {
                            page: 'all',
                        },
                    }
                }]
            },
            columnDefs: [{
                "targets": [0],
                "visible": false,
                "searchable": false
            }],
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('kantor_cabang/ajax') ?>'
        });
    });

    $('#provinsi').change(function() {
        var provinsi = $(this).val();
        console.log(provinsi)
        $.ajax({
            type: 'POST',
            url: '<?= base_url('kantor_cabang/kota') ?>',
            data: 'provinsi =' + provinsi,
            success: function(data) {
                $('#kota').html(data);
            }
        });
    });


    function changeStatus(id) {
        $.ajax({
            url: '<?= base_url('kantor_cabang/changeStatus?id=') ?>' + id,
            method: 'POST',
            cache: false,
            processData: false,
            success: function(response) {
                $('#tb_kantor_cabang').DataTable().ajax.reload(null, false)
            }
        })
    }

    $('#FormUploadData').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('kantor_cabang/uploadData') ?>',
            method: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(this),
            // beforeSend: function( xhr ) {
            //     $('#loading').addClass('loading')
            // },
            success: function(response) {
                // $('#loading').removeClass('loading')
                // let status   = JSON.parse(response).status;
                // let message  = JSON.parse(response).message;
                // if(status=="success"){
                //     AlertSuccessUpload(message);
                //     $('#addSite').modal('hide')
                //     datatabel.ajax.reload();
                // }else{
                //     AlertFailedUpload(message);
                // }
            }
        })
    })




    // close alert
    $(".alert").fadeTo(5000, 500).slideUp(500, function() {
        $(".alert").slideUp(500);
    });
</script>