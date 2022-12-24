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
    let formUpdateLogAbsensi = $("#form-update-log-absensi")
    $(document).ready(function() {
        $("nav.sidebar.sidebar-bunker").addClass("active")
        $("nav.navbar-custom-menu").addClass("active")
        $("#sidebarCollapse").addClass("open")
    })
    $('#1Log_absen').addClass('mm-active');
    $('#subm_Absensi').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Log_absen').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });
    $(document).ready(function() {
        var table = $('#myTable').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: '<?= base_url('log_absen/ajax') ?>',
            initComplete: function() {
                $('.dt-button').addClass('btn btn-info');
                $('.dt-button').css({
                    'height': '50px'
                })
                // console.log(document.getElementById('myTable').getElementsByTagName('tr')[1].getElementsByTagName('td')[2])
                $('#myTable thead tr th').clone(true).addClass('fill').appendTo('#myTable thead');
                $("#myTable thead .fill").each(function(i) {
                    if (i > 0 && i != 7) {
                        var select = $('<select class="form-control"><option value="">Semua</option></select>')
                            .appendTo($(this).empty())
                            .on('change', function() {
                                table.column(i)
                                    .search($(this).val())
                                    .draw();
                            });
                        // console.log(i)
                        table.column(i).data().unique().sort().each(function(d, j) {
                            var c = d;
                            if (d == '-' && i == 6) {
                                c = 'Kosong'
                            } else if (d == '-' && i == 5) {
                                c = 'Belum Pulang'
                            }
                            select.append('<option value="' + d + '">' + c + '</option>')
                        });
                    } else {
                        $('').appendTo($(this).empty())
                    }
                });
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
        $('.dataTables_filter > label').addClass("right")

        $(".dataTables_filter").append(`
            <div class="col-md-5  right mr-5 ">
                <div class=" row  mb-0   ">
                    <label class="col-md-2 mr-2  mt-2 ">Filter: </label>
                    <select id="month" class="month form-control js-filter form-control-sm mr-1 col-md">
                        <?php for ($m = 1; $m <= 12; ++$m) {
                            $month_label = date('F', mktime(0, 0, 0, $m, 1));
                        ?>
                            <option <?= date("m", strtotime($month_label)) == date("m") ? 'selected' : '' ?> value="<?= date("m", strtotime($month_label)); ?>"><?= $month_label; ?></option>
                        <?php } ?>
                    </select>
                    <select id="year" class="year form-control js-filter form-control-sm col-md">
                        <?php
                        $year = date('Y');
                        $min = $year - 1;
                        $max = $year;
                        for ($i = $max; $i >= $min; $i--) { ?>
                            <option value='<?= $i ?>'><?= $i ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        `)

        $(".js-filter").change(function() {
            var month = $("#month").val()
            var year = $("#year").val()
            table.ajax.url(`<?= base_url('log_absen/ajax') ?>/${month}/${year}`).load();
        })
    });


    function edit(id) {
        $.ajax({
            url: '<?= base_url('log_absen/get_by_id?id=') ?>' + id,
            method: 'GET',
            success: function(response) {
                let res = JSON.parse(response)
                //console.log(res)
                var history_id = res.id
                var user_id = res.user_id
                var shift_id = res.shift_id
                var status = res.status
                var date = res.date
                var time = res.time

                $('#history_id').val(history_id)
                $('#edit_tanggal_shift').val(date)
                $('#edit_waktu_shift').val(time)
                
                getSelectShift(user_id,shift_id)
            }
        })
    }

    function getSelectShift(user_id, shift_id) {
        $.ajax({
          url: '<?= base_url('log_absen/get_list_shift?user_id=') ?>'+user_id,
          type: "GET",
          success: function (result) {
            let response = JSON.parse(result)
            var html_ = `<option value="">--Choose Shift--</option>`
            //var html_ = ``
            $.each(response,function(e,res) {
              html_+=`
                <option value="${res.shift_id}">${res.shift_name} (${res.waktu_start} - ${res.waktu_end}) </option>
              `
            })
            $('#shift_id').html(html_)
            $('#shift_id').val(shift_id)
          }
        })
    }

    function handleFormOnUpdateLogAbsensi() {
        formUpdateLogAbsensi.submit(function (e) {
           updateLogAbsensi()
        })
    }

    function updateLogAbsensi() {
        //e.preventDefault(); // avoid to execute the actual submit of the form.
        var formData = new FormData(formUpdateLogAbsensi[0]);

        // $.ajax({
        //     type: "POST",
        //     url: '<?= base_url('log_absen/update') ?>',
        //     xhr: function() {
        //         var myXhr = $.ajaxSettings.xhr();
        //         return myXhr;
        //     },
        //     data: formData,
        //     cache: false,
        //     contentType: false,
        //     processData: false,
        //     success: function (resp) {
        //         console.log(resp);
        //         // swal({
        //         //     title:"Success Update Case",
        //         //     icon: "success",
        //         // }).then(function (params) {
        //         //     window.location.reload()
        //         // })
        //         // $('.animate-login').removeClass("lds-ripple")
                
        //     },
        //     error: function (resp) {
        //         console.log(resp)
        //         // swal({
        //         //     title:"Upss!!!",
        //         //     text: resp.responseJSON.message,
        //         //     icon: "warning",
        //         // })
        //         // $('.animate-login').removeClass("lds-ripple")
        //     }
        // });

        $.ajax({
            url: '<?= base_url('log_absen/update') ?>',
            method: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                var type = JSON.parse(response).type    
                var message = JSON.parse(response).message
                if (type == 'success') {
                    $('#myTable').DataTable().ajax.reload(null, false)
                    $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
                    $('#editModal').modal('hide')
                } else {
                    $(".w-alert").html('<div class="alert alert-danger"><button type="button" class="close">×</button>' + message + '</div>');
                }
            }
        })
    }

    function modalImg(ini) {
        $('#myModal').modal('show');
        var src = $(ini).attr('src')
        $('#img_modal').attr('src', src)
    }
</script>