<link href="<?= base_url('assets/apps/assets/plugins/select2/dist/css/select2.min.css') ?>" rel="stylesheet">
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<!-- tombol export -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>

<script src="<?= base_url('assets/apps/assets/plugins/select2/dist/js/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/highchart/highcharts-custom.src.js') ?>"></script>

<script>
    $('.select2').select2();
    $('#Guest').addClass('mm-active');

    $('#0').click(function() {
        $('#tb_client').removeClass('d-none');
        $('#tb_client_filter').removeClass('d-none');
        $('#tb_client_info').removeClass('d-none');
        $('#tb_client_paginate').removeClass('d-none');
        $('#chart_').addClass('d-none');
        $(".w-filter").show()
    })
    $('#1').click(function() {
        $(".w-filter").hide()
        $('#tb_client').addClass('d-none');
        $('#tb_client_filter').addClass('d-none');
        $('#tb_client_info').addClass('d-none');
        $('#tb_client_paginate').addClass('d-none');
        $('#chart_').removeClass('d-none');
    })

    function charts(id) {
        $.ajax({
            url: '<?= base_url('task_tamu/chart') ?>',
            type: 'post',
            data: {
                id_penghuni: id
            },
            dataType: 'json',
            success: function(resp) {
                console.log(resp);
                new Highcharts.chart('current', {
                    chart: {
                        type: 'spline'
                    },
                    title: {
                        text: 'Current Month Guest'
                    },
                    xAxis: {
                        categories: resp.monthly.label
                    },
                    yAxis: {
                        title: {
                            text: 'Value'
                        },
                        labels: {
                            formatter: function() {
                                return this.value;
                            }
                        }
                    },
                    tooltip: {
                        crosshairs: true,
                        shared: true
                    },
                    plotOptions: {
                        spline: {
                            marker: {
                                radius: 4,
                                lineColor: '#666666',
                                lineWidth: 1
                            }
                        },
                        series: {
                            events: {
                                legendItemClick: function() {
                                    return false;
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Guest',
                        marker: {
                            symbol: 'bullet'
                        },
                        data: resp.monthly.value,
                    }]
                });

                new Highcharts.chart('monthly', {
                    chart: {
                        type: 'spline'
                    },
                    title: {
                        text: 'Monthly Guest'
                    },
                    xAxis: {
                        categories: resp.year.label
                    },
                    yAxis: {
                        title: {
                            text: 'Value'
                        },
                        labels: {
                            formatter: function() {
                                return this.value;
                            }
                        }
                    },
                    tooltip: {
                        crosshairs: true,
                        shared: true
                    },
                    plotOptions: {
                        spline: {
                            marker: {
                                radius: 4,
                                lineColor: '#666666',
                                lineWidth: 1
                            }
                        },
                        series: {
                            events: {
                                legendItemClick: function() {
                                    return false;
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Guest',
                        marker: {
                            symbol: 'bullet'
                        },
                        data: resp.year.value,
                    }]
                });
            }
        })
    }
    charts()

    var table = $('#tb_client').DataTable({
        dom: 'Bfrtip',
        buttons: { //remove class in button default export datatables 
            dom: {
                button: {
                    tag: 'button',
                    className: ''
                }
            },
            buttons: [
                //     {
                //     extend: 'excelHtml5',
                //     className: 'btn btn-success remove-radius',
                //     title: 'Data Tamu',
                //     text: '<i class="fa fa-file-excel"></i> Export to excel',
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4],
                //         modifier: {
                //             page: 'all',
                //         },
                //     }
                // },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-danger remove-radius',
                    title: 'Data Tamu',
                    text: '<i class="fa fa-file-pdf"></i> Export to PDF',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
                        modifier: {
                            page: 'all',
                        },
                    }
                }
            ]
        },
        order: [
            [0, 'desc'],
        ],
        responsive: true,
        autoWidth: false,
        processing: true,
        serverside: true,
        ajax: '<?= base_url('task_tamu/ajax') ?>'
    });
    $('.dataTables_filter > label').addClass("right")
    $(".dataTables_filter").append(`
                <div class="col-md-4  right mr-5 ">
                    <div class=" row  mb-0   ">
                        <label class="col-md-1 mr-3  mt-2 ">Filter: </label>
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
        table.ajax.url(`<?= base_url('task_tamu/ajax') ?>/${$('#s_fill').val()}/${month}/${year}`).load();
    })

    function reDraw(id, name) {
        if (id != 0) {
            $('#fillterby').html('Fillter By: ' + $(`option[value=${id}]`).html())
        } else {
            $('#fillterby').html('')
        }
        charts(id)
        table.destroy()
        table = $('#tb_client').DataTable({
            dom: 'Bfrtip',
            buttons: { //remove class in button default export datatables 
                dom: {
                    button: {
                        tag: 'button',
                        className: ''
                    }
                },
                buttons: [
                    //     {
                    //     extend: 'excelHtml5',
                    //     className: 'btn btn-success remove-radius',
                    //     title: 'Data Tamu',
                    //     text: '<i class="fa fa-file-excel"></i> Export to excel',
                    //     exportOptions: {
                    //         columns: [0, 1, 2, 3, 4],
                    //         modifier: {
                    //             page: 'all',
                    //         },
                    //     }
                    // }
                ]
            },
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('task_tamu/ajax/') ?>' + id
        });

        table.draw()
        if (!$('#chart_').hasClass('d-none')) {
            $('#tb_client_filter').addClass('d-none');
            $('#tb_client_info').addClass('d-none');
            $('#tb_client_paginate').addClass('d-none');
        }
    }
</script>