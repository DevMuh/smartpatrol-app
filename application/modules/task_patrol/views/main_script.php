<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="assets/apps/assets/dist/js/mypdf.js?_=<?= time() ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/6.26.0/polyfill.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.0/exceljs.min.js"></script>
<script>
    $('#Patrol').addClass('mm-active');
    $(document).ready(function() {
        var table = $('#myTable').DataTable({
            dom: 'Bfrtip',
            buttons: { //remove class in button default export datatables 
                dom: {
                    button: {
                        tag: 'button',
                        className: ''
                    }
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-success remove-radius export-taskpatrol export-excel-log',
                        title: 'Data Patrol',
                        text: '<i class="fa fa-file-excel"></i> Export to excel',
                        stripHtml: false,
                        exportOptions: {
                            modifier: {
                                page: 'all',
                            },
                        },
                        action: async function(e, dt, node, config) {
                            var month = $(".month").val()
                            var year = $(".year").val()
                            // console.log($(this));
                            var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Loading ... `
                            let buttons = $('button.export-taskpatrol')
                            $(buttons[0]).prop('disabled', true)
                            var button = $(buttons[0]).html()
                            $(buttons[0]).html(loader)
                            console.log("wwwwww");
                            console.log("month : ", month);
                            console.log("year : ", year);

                            await allTaskPatrolExcel(month,year)

                            var htmlSuccess = `<span><i class="fa fa-file-excel"></i> Export to excel</span>`
                            $(buttons[0]).html(htmlSuccess)
                            $(buttons[0]).prop('disabled', false)
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-danger remove-radius',
                        title: 'Data Patrol',
                        text: '<i class="fa fa-file-pdf"></i> Export to PDF',
                        exportOptions: {
                            modifier: {
                                page: 'all',
                            },
                        }
                    }
                ]
            },
            lengthChange: false,
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('task_patrol/ajax') ?>',
            order: [
                [2, "desc"]
            ]
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
            table.ajax.url(`<?= base_url('task_patrol/ajax') ?>/${month}/${year}`).load();
        })

        $('#myTables').DataTable();
    });
    $('.myImage').on('click', function() {
        $('#myModal').modal('show');
        var src = $(this).attr('src')
        $('#img_modal').attr('src', src)
    })

    function changeLength(length) {
        console.log(length)
        $('#myTable').DataTable().page.len(length).draw();
    }
    $(`a[href="#summary_column"]`).click(function() {
        let data = $(this).data("detail")
        setHtmlDetailSummary(data)
    })

    function setHtmlDetailSummary(data) {
        let html = "";
        if (data) {
            data.map(el => {
                html += `<div class="col-md-3 col-lg-3">
            <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-white shadow-sm rounded">
                <i style="color: ${el.color}" class="${el.icon} opacity-25 fa-5x decorative-icon"></i>
                <div class="d-flex">
                    <h2>${el.value == null ? 0 : el.value}${el.total ? "/" + el.total : ""}</h2>
                </div>
                <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">${el.label}</div>
                </div>
            </div>`;
            })
            $("div#summary_column").html(html);
        } else {
            $("div#summary_column").html("");
        }
    }

    function showr2() {
        var row2 = $('#row2')
        if (row2.hasClass('d-none')) {
            row2.removeClass('d-none')
        } else {
            row2.addClass('d-none')
        }
    }

    async function exportTaskPatrolPdf(e) {
        $(e).prop('disabled', true)
        var loader = `<i class="fa fa-spinner fa-spin"></i>`
        var button = $(e).html()
        $(e).html(loader)
        const id = $(e).data('id')
        console.log("data",id);
        await taskPatrolPDF(id)
        $(e).prop('disabled', false)
        $(e).html(button)
        return false;
    }

    function taskPatrolPDF(id) {
        return new Promise(async (resolve, reject) => {
            try {
                const {
                    data
                } = await $.getJSON(`<?= base_url('task_patrol/export_pdf_json/') ?>${id.b2b_token}/${id.id_}/${id.done_time}`)
                console.log("asasas",data);
                const dd = await genReportTaskPatrol(data)
                console.log("dd",JSON.stringify(dd));
                pdfMake.createPdf(dd).download(`task_patrol-${id.cluster_name}-${id.username}-${id.publish_date}.pdf`);
                resolve("ok")
            } catch (e) {
                resolve(e)
                alert("ERROR:".e);
            }
        })

    }

    async function exportTaskPatrolExcel(e) {
        $(e).prop('disabled', true)
        var loader = `<i class="fa fa-spinner fa-spin"></i>`
        var button = $(e).html()
        $(e).html(loader)
        const id = $(e).data('id')
        console.log("data",id);
        await taskPatrolExcel(id)
        $(e).prop('disabled', false)
        $(e).html(button)
        return false;
    }

    function taskPatrolExcel(id) {
        return new Promise(async (resolve, reject) => {
            try {
                const {
                    data
                } = await $.getJSON(`<?= base_url('task_patrol/export_excel_json/') ?>${id.b2b_token}/${id.id_}/${id.done_time}`)
                console.log("asasas",data);
                generateReportExcel(data);
                resolve("ok")
            } catch (e) {
                resolve(e)
                alert("ERROR:".e);
            }
        })

    }

    async function generateReportExcel(data){
        //console.log("aaaaa : ", data);
        let ms = Date.now();
        var label_report = "Report Task Patroli "+ms;

        var task_patrol_header = data.task_patrol_header;
        var header_cluster_name = task_patrol_header.cluster_name;
        var header_user = task_patrol_header.username;
        var header_duration = task_patrol_header.duration;
        var header_total_cp = task_patrol_header.total_cp;
        var header_start_date = task_patrol_header.publish_date+" "+task_patrol_header.publish_time;
        var header_end_date = task_patrol_header.done_date+" "+task_patrol_header.done_time;
        var header_foto_selfie = data.task_patrol_photo;

        var list_all_task = data.list_all_task;
        var task_patrol_detail = data.task_patrol_detail[0];
        var map_screen_shoot = data.map_screen_shoot;

        let workbook = new ExcelJS.Workbook();
        let worksheet = workbook.addWorksheet('Task Patroli');

        worksheet.getCell('A1').value = "Report Task Patroli";
        worksheet.getCell('A1').font = {
            name: 'Arial Black',
            family: 4,
            size: 16,
            underline: true,
            bold: true
        };
        worksheet.getCell('A1').alignment = { vertical: 'middle', horizontal: 'center' };
        worksheet.mergeCells("A1:F1");

        worksheet.getCell('A3').value = "Header Task Patroli";
        worksheet.getCell('A3').font = {
            name: 'Arial Black',
            family: 4,
            size: 12,
            bold: true
        };
        worksheet.getCell('A3').alignment = { vertical: 'middle', horizontal: 'left' };
        worksheet.mergeCells("A3:F3");

        //// Cell A4 - A6 ////
        worksheet.getCell('A4').value = "Cluster Name";
        worksheet.getCell('A4').font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getCell('A4').alignment = { vertical: 'middle', horizontal: 'left' };

        worksheet.getCell('A5').value = "User";
        worksheet.getCell('A5').font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getCell('A5').alignment = { vertical: 'middle', horizontal: 'left' };
        
        worksheet.getCell('A6').value = "Start Date";
        worksheet.getCell('A6').font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getCell('A6').alignment = { vertical: 'middle', horizontal: 'left' };

        //// Cell B4 - B6 ////
        worksheet.getCell('B4').value = header_cluster_name;
        worksheet.getCell('B5').value = header_user;
        worksheet.getCell('B6').value = header_start_date;

        //// Cell C4 - C6 ////
        worksheet.getCell('C4').value = "Total CP";
        worksheet.getCell('C4').font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getCell('C4').alignment = { vertical: 'middle', horizontal: 'left' };

        worksheet.getCell('C5').value = "Duration";
        worksheet.getCell('C5').font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getCell('C5').alignment = { vertical: 'middle', horizontal: 'left' };

        worksheet.getCell('C6').value = "End Date";
        worksheet.getCell('C6').font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getCell('C6').alignment = { vertical: 'middle', horizontal: 'left' };

        //// Cell D4 - D6 ////
        worksheet.getCell('D4').value = header_total_cp;
        worksheet.getCell('D5').value = header_duration;
        worksheet.getCell('D6').value = header_end_date;

        //// Cell E4 ////
        worksheet.getCell('E4').value = "Foto Selfie";
        worksheet.getCell('E4').font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getCell('E4').alignment = { vertical: 'middle', horizontal: 'left' };

        //// Cell F5 ////
        worksheet.getCell('F4').value = { text: header_foto_selfie, hyperlink: header_foto_selfie };

        //// Cell A8 ////
        worksheet.getCell('A8').value = "List Patroli";
        worksheet.getCell('A8').font = {
            name: 'Arial Black',
            family: 4,
            size: 12,
            bold: true
        };
        worksheet.getCell('A8').alignment = { vertical: 'middle', horizontal: 'left' };
        worksheet.mergeCells("A8:F8");

        var columns = [
            'Cluster Name','User','Start','Stop','Duration'
        ];

        worksheet.insertRow(9, columns);
        worksheet.getRow(9).font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getRow(9).alignment = { vertical: 'middle', horizontal: 'left' };
        
        row = 10;
        for (let index = 0; index < list_all_task.length; index++) {
            const cluster_name = list_all_task[index].cluster_name;
            const full_name = list_all_task[index].full_name;
            const start = list_all_task[index].start;
            const end = list_all_task[index].end;
            const duration = list_all_task[index].duration;
            
            val = [cluster_name,full_name,start,end,duration];
            worksheet.insertRow(row, val);
            row++;
        }

        //// Cell Attachment ////
        row_attch = row + list_all_task.length;
        worksheet.getCell('A'+row_attch).value = "Attachment";
        worksheet.getCell('A'+row_attch).font = {
            name: 'Arial Black',
            family: 4,
            size: 12,
            bold: true
        };
        worksheet.getCell('A'+row_attch).alignment = { vertical: 'middle', horizontal: 'left' };
        worksheet.mergeCells("A"+row_attch+":F"+row_attch);

        row_attch = row_attch + 1;
        row_attch2 = row_attch + 1;
        row_attch3 = row_attch2 + 1;
        //// Cell A row_attch ////
        worksheet.getCell('A'+row_attch).value = "Gambar 1";
        worksheet.getCell('A'+row_attch).font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getCell('A'+row_attch).alignment = { vertical: 'middle', horizontal: 'left' };
        //// Cell B row_attch ////
        worksheet.getCell('B'+row_attch).value = { text: task_patrol_detail.base64_img1, hyperlink: task_patrol_detail.base64_img1 };
        worksheet.mergeCells("B"+row_attch+":F"+row_attch);

        //// Cell A row_attch 2 ////
        worksheet.getCell('A'+row_attch2).value = "Gambar 2";
        worksheet.getCell('A'+row_attch2).font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getCell('A'+row_attch2).alignment = { vertical: 'middle', horizontal: 'left' };
        //// Cell B row_attch2 ////
        worksheet.getCell('B'+row_attch2).value = { text: task_patrol_detail.base64_img2, hyperlink: task_patrol_detail.base64_img2 };
        worksheet.mergeCells("B"+row_attch2+":F"+row_attch2);

        //// Cell A row_attch 3 ////
        worksheet.getCell('A'+row_attch3).value = "Gambar 3";
        worksheet.getCell('A'+row_attch3).font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getCell('A'+row_attch3).alignment = { vertical: 'middle', horizontal: 'left' };
        //// Cell B row_attch3 ////
        worksheet.getCell('B'+row_attch3).value = { text: task_patrol_detail.base64_img3, hyperlink: task_patrol_detail.base64_img3 };
        worksheet.mergeCells("B"+row_attch3+":F"+row_attch3);

        //// Cell Map ////
        row_map = row_attch3 + 2;
        worksheet.getCell('A'+row_map).value = "Map";
        worksheet.getCell('A'+row_map).font = {
            name: 'Arial Black',
            family: 4,
            size: 12,
            bold: true
        };
        worksheet.getCell('A'+row_map).alignment = { vertical: 'middle', horizontal: 'left' };
        worksheet.mergeCells("A"+row_map+":F"+row_map);

        row_value_map = row_map + 1;
        worksheet.getCell('A'+row_value_map).value = { text: map_screen_shoot, hyperlink: map_screen_shoot };
        worksheet.mergeCells("A"+row_value_map+":F"+row_value_map);

        const buffer = await workbook.xlsx.writeBuffer();
        const fileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        const fileExtension = '.xlsx';

        const blob = new Blob([buffer], {type: fileType});

        saveAs(blob, label_report + fileExtension);
    }

    function allTaskPatrolExcel(month, year) {
        return new Promise(async (resolve, reject) => {
            try {
                const {
                    data
                } = await $.getJSON(`<?= base_url('task_patrol/export_excel_all_taskpatrol_json/') ?>${month}/${year}`)
                console.log("asasas",data);
                generateAllReportExcel(data);
                resolve("ok")
            } catch (e) {
                resolve(e)
                alert("ERROR:".e);
            }
        })
    }

    async function generateAllReportExcel(data){
        let ms = Date.now();
        var label_report = "Report All Task Patroli "+ms;

        let workbook = new ExcelJS.Workbook();
        let worksheet = workbook.addWorksheet('All Task Patroli');

        worksheet.getCell('A1').value = "Report All Task Patroli";
        worksheet.getCell('A1').font = {
            name: 'Arial Black',
            family: 4,
            size: 16,
            underline: true,
            bold: true
        };
        worksheet.getCell('A1').alignment = { vertical: 'middle', horizontal: 'center' };
        worksheet.mergeCells("A1:G1");

        var columns = [
            'Cluster Name','User','Start','Stop','Duration','Total CP','Link URL Download PDF'
        ];

        worksheet.insertRow(3, columns);
        worksheet.getRow(3).font = {
            name: 'Arial Black',
            family: 4,
            size: 9,
            bold: true
        };
        worksheet.getRow(3).alignment = { vertical: 'middle', horizontal: 'left' };

        var list_all_task = data.list_all_task;

        row = 4;
        for (let index = 0; index < list_all_task.length; index++) {
            const cluster_name = list_all_task[index].cluster_name;
            const full_name = list_all_task[index].full_name;
            const start = list_all_task[index].start_date;
            const end = list_all_task[index].end_date;
            const duration = list_all_task[index].duration;
            const total_cp = list_all_task[index].total_cp;
            const link = list_all_task[index].link_download_pdf;
            
            //val = [cluster_name,full_name,start,end,duration,total_cp,link];
            val = [cluster_name,full_name,start,end,duration,total_cp,{ text: "Download", hyperlink: link }];
            worksheet.insertRow(row, val);
            row++;
        }

        const buffer = await workbook.xlsx.writeBuffer();
        const fileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        const fileExtension = '.xlsx';

        const blob = new Blob([buffer], {type: fileType});

        saveAs(blob, label_report + fileExtension);
    }
</script>