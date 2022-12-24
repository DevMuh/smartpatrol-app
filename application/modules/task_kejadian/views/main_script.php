<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="assets/apps/assets/dist/js/mypdf.js?_=<?= time() ?>"></script>
<script>
    $('#Incident').addClass('mm-active');
    $(document).ready(function() {
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
                    //     title: 'Data Kejadian',
                    //     text: '<i class="fa fa-file-excel"></i> Export to excel',
                    //     exportOptions: {
                    //         modifier: {
                    //             page: 'all',
                    //         },
                    //     }
                    // },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-danger remove-radius',
                        title: 'Data Kejadian',
                        text: '<i class="fa fa-file-pdf"></i> Export to PDF',
                        exportOptions: {
                            modifier: {
                                page: 'all',
                            },
                        }
                    }
                ]
            },
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('task_kejadian/ajax') ?>'
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
            table.ajax.url(`<?= base_url('task_kejadian/ajax') ?>/${month}/${year}`).load();
        })
    });

    async function exportTaskKejadianPdf(e) {
        $(e).prop('disabled', true)
        var loader = `<i class="fa fa-spinner fa-spin"></i>`
        var button = $(e).html()
        $(e).html(loader)
        const id = $(e).data('id')
        console.log("data",id);
        await taskKejadianPDF(id)
        $(e).prop('disabled', false)
        $(e).html(button)
        return false;
    }

    function taskKejadianPDF(id) {
        return new Promise(async (resolve, reject) => {
            try {
                const {
                    data
                } = await $.getJSON(`<?= base_url('task_kejadian/export_pdf_json/') ?>${id.b2b_token}/${id.id_}`)
                console.log("asasas",data);
                const dd = await genReportTaskKejadian(data)
                console.log("dd",JSON.stringify(dd));
                pdfMake.createPdf(dd).download(`task_kejadian-${id.kategori_name}-${id.id_}.pdf`);
                resolve("ok")
            } catch (e) {
                resolve(e)
                alert("ERROR:".e);
            }
        })

    }
</script>