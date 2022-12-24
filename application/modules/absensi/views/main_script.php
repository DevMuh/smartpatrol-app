<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/classie.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/modalEffects.js') ?>"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.5.2/css/colReorder.dataTables.min.css">
<script src="https://cdn.datatables.net/colreorder/1.5.2/js/dataTables.colReorder.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://datatables.net/release-datatables/extensions/FixedColumns/css/fixedColumns.bootstrap4.css">

<script src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>

<link href="<?= base_url('assets/apps/assets/plugins/select2/dist/css/select2.min.css') ?>" rel="stylesheet">
<script src="<?= base_url('assets/apps/assets/plugins/select2/dist/js/select2.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.3.17/dist/sweetalert2.all.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.0/exceljs.min.js"></script>
<script src="assets/apps/assets/dist/js/mypdf.js?_=<?= time() ?>"></script>

<script>
    var modal_close_only = "#modal-close-only"
    $(modal_close_only).on('hidden.bs.modal', function(e) {
        $(this).find(".modal-body").empty()
        $(this).find(".modal-title").empty()
    })
    $("#addmodal").on('show.bs.modal', async function(e) {
        // e.preventDefault();
        // e.stopPropagation();
        try {
            let type = $(this).data("type"),
                title = $(this).data("title")
            let petugas = await $.getJSON(`<?= base_url("users/select2") ?>`)
            let shift = await $.getJSON(`<?= base_url("shift/select2") ?>`)
            $("#fc_petugas").select2({
                data: petugas
            });
            $("#fc_shift").select2({
                data: shift
            });
            $('#fc_petugas').on('select2:select', async function (e) {
                var data = e.params.data;
                console.log(data);
                $('#fc_shift').val(null).empty().select2('destroy')
                let shift = await $.getJSON(`<?= base_url("shift/select2") ?>?id_user=${data.id}`)
                $("#fc_shift").select2({
                    data: shift
                });
            });
            $('#fc_status').on('change', function(e) {
                let val = $(this).val();
                if (val == 1) {
                    $('#hidden-claim-form').addClass('d-none')
                } else {
                    $('#hidden-claim-form').removeClass('d-none')
                }
            })
            $('#fc_status').val(1)
            $('#fc_status').trigger('change')
            $('#is_overtime').on('change', function(e) {
                $('#hidden-overtime').removeClass('d-none')
            })
            $('#is_sameday').on('change', function(e) {
                $('#hidden-overtime').addClass('d-none')
            })
        } catch (error) {
            alert(error)
        }
    })
</script>


<style>
    th {
        background-color: black;
    }

    .select2-selection--multiple {
        overflow-y: auto !important;
        max-height: 40px !important;
    }

    .select2-search__field {}

    .clone-header {
        display: none;
        opacity: 1;
        height: 0 !important;
    }

    div.dataTables_processing {
        z-index: 999;
        font-weight: bold
    }

    .dataTables_scrollBody {
        overflow: scroll !important;
    }

    /* div.DTFC_LeftHeadWrapper {
        border-right: 1px solid #aeaeae;
    } */

    div.DTFC_LeftBodyLiner {
        border-right: 1px solid #aeaeae;

    }

    div.DTFC_LeftBodyWrapper {
        border-right: 1px solid #aeaeae;

    }

    div.DTFC_RightBodyLiner {
        border-left: 1px solid #aeaeae;

    }

    div.DTFC_RightHeadLiner {
        border-left: 1px solid #aeaeae;

    }
</style>

<script>
    function getHeaderNames(table) {
        // Gets header names.
        //params:
        //  table: table ID.
        //Returns:
        //  Array of column header names.

        var header = $(table).DataTable().columns().header().toArray();

        var names = [];
        header.forEach(function(th) {
            names.push($(th).html());
        });

        return names;
    }

    function buildCols(data) {
        // Builds cols XML.
        //To do: deifne widths for each column.
        //Params:
        //  data: row data.
        //Returns:
        //  String of XML formatted column widths.

        var cols = '<cols>';

        for (i = 0; i < data.length; i++) {
            colNum = i + 1;
            cols += '<col min="' + colNum + '" max="' + colNum + '" width="20" customWidth="1"/>';
        }

        cols += '</cols>';

        return cols;
    }

    function buildRow(data, rowNum, styleNum) {
        // Builds row XML.
        //Params:
        //  data: Row data.
        //  rowNum: Excel row number.
        //  styleNum: style number or empty string for no style.
        //Returns:
        //  String of XML formatted row.

        var style = styleNum ? ' s="' + styleNum + '"' : '';

        var row = '<row r="' + rowNum + '">';

        for (i = 0; i < data.length; i++) {
            colNum = (i + 10).toString(36).toUpperCase(); // Convert to alpha

            var cr = colNum + rowNum;

            row += '<c t="inlineStr" r="' + cr + '"' + style + '>' +
                '<is>' +
                '<t>' + data[i] + '</t>' +
                '</is>' +
                '</c>';
        }

        row += '</row>';

        return row;
    }

    function getTableData(table, title) {
        // Processes Datatable row data to build sheet.
        //Params:
        //  table: table ID.
        //  title: Title displayed at top of SS or empty str for no title.
        //Returns:
        //  String of XML formatted worksheet.

        var header = getHeaderNames(table);
        var table = $(table).DataTable();
        var rowNum = 1;
        var mergeCells = '';
        var ws = '';

        ws += buildCols(header);
        ws += '<sheetData>';

        if (title.length > 0) {
            ws += buildRow([title], rowNum, 51);
            rowNum++;

            mergeCol = ((header.length - 1) + 10).toString(36).toUpperCase();

            mergeCells = '<mergeCells count="1">' +
                '<mergeCell ref="A1:' + mergeCol + '1"/>' +
                '</mergeCells>';
        }

        ws += buildRow(header, rowNum, 2);
        rowNum++;

        // Loop through each row to append to sheet.    
        table.rows().every(function(rowIdx, tableLoop, rowLoop) {
            var data = this.data();

            // If data is object based then it needs to be converted 
            // to an array before sending to buildRow()
            ws += buildRow(data, rowNum, '');

            rowNum++;
        });

        ws += '</sheetData>' + mergeCells;

        return ws;

    }

    function setSheetName(xlsx, name) {
        // Changes tab title for sheet.
        //Params:
        //  xlsx: xlxs worksheet object.
        //  name: name for sheet.

        if (name.length > 0) {
            var source = xlsx.xl['workbook.xml'].getElementsByTagName('sheet')[0];
            source.setAttribute('name', name);
        }
    }

    function addSheet(xlsx, table, title, name, sheetId) {
        //Clones sheet from Sheet1 to build new sheet.
        //Params:
        //  xlsx: xlsx object.
        //  table: table ID.
        //  title: Title for top row or blank if no title.
        //  name: Name of new sheet.
        //  sheetId: string containing sheetId for new sheet.
        //Returns:
        //  Updated sheet object.

        //Add sheet2 to [Content_Types].xml => <Types>
        //============================================
        var source = xlsx['[Content_Types].xml'].getElementsByTagName('Override')[1];
        var clone = source.cloneNode(true);
        clone.setAttribute('PartName', '/xl/worksheets/sheet' + sheetId + '.xml');
        xlsx['[Content_Types].xml'].getElementsByTagName('Types')[0].appendChild(clone);

        //Add sheet relationship to xl/_rels/workbook.xml.rels => Relationships
        //=====================================================================
        var source = xlsx.xl._rels['workbook.xml.rels'].getElementsByTagName('Relationship')[0];
        var clone = source.cloneNode(true);
        clone.setAttribute('Id', 'rId' + sheetId + 1);
        clone.setAttribute('Target', 'worksheets/sheet' + sheetId + '.xml');
        xlsx.xl._rels['workbook.xml.rels'].getElementsByTagName('Relationships')[0].appendChild(clone);

        //Add second sheet to xl/workbook.xml => <workbook><sheets>
        //=========================================================
        var source = xlsx.xl['workbook.xml'].getElementsByTagName('sheet')[0];
        var clone = source.cloneNode(true);
        clone.setAttribute('name', name);
        clone.setAttribute('sheetId', sheetId);
        clone.setAttribute('r:id', 'rId' + sheetId + 1);
        xlsx.xl['workbook.xml'].getElementsByTagName('sheets')[0].appendChild(clone);

        //Add sheet2.xml to xl/worksheets
        //===============================
        var newSheet =
            `<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" 
      xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" 
      xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" 
      xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac" 
      mc:Ignorable="x14ac">` +
            getTableData(table, title) +
            `</worksheet>`;

        xlsx.xl.worksheets['sheet' + sheetId + '.xml'] = $.parseXML(newSheet);

    }
</script>

<script>
    $('#0').click(function() {
        $('#myTable_wrapper').fadeIn();
        $('.export-absensi').fadeIn();

        $('#table_event_wrapper').hide();
        $('#table_sum_wrapper').hide();
        $('#table_off_panggil_wrapper').hide();
        $('#table_checkpoint_wrapper').hide();
        $('.export-event').hide();
        $('.export-sum').hide();
        $('.export-off-panggil').hide();

        // $('#table_off_panggil_previous').hide();
        // $('#table_off_panggil_next').hide();

        // $('#myTable_previous').fadeIn();
        // $('#myTable_next').fadeIn();
    })
    $('#1').click(function() {
        $('#table_event_wrapper').fadeIn();
        $('.export-event').fadeIn();

        $('#myTable_wrapper').hide();
        $('#table_sum_wrapper').hide();
        $('#table_off_panggil_wrapper').hide();
        $('#table_checkpoint_wrapper').hide();
        $('.export-absensi').hide();
        $('.export-sum').hide();
        $('.export-off-panggil').hide();
    })
    $('#2').click(function() {
        $('#table_sum_wrapper').fadeIn();
        $('.export-sum').fadeIn();

        $('#table_event_wrapper').hide();
        $('#table_off_panggil_wrapper').hide();
        $('#table_checkpoint_wrapper').hide();
        $('#myTable_wrapper').hide();
        $('.export-absensi').hide();
        $('.export-event').hide();
        $('.export-off-panggil').hide();
    })
    $('#2_').click(function() {
        $('#table_off_panggil_wrapper').fadeIn();
        $('.export-off-panggil').fadeIn();

        $('#table_event_wrapper').hide();
        $('#table_sum_wrapper').hide();
        $('#table_checkpoint_wrapper').hide();
        $('#myTable_wrapper').hide();
        $('.export-absensi').hide();
        $('.export-event').hide();

        // $('#myTable_previous').hide();
        // $('#myTable_next').hide();

        // $('#table_off_panggil_previous').fadeIn();
        // $('#table_off_panggil_next').fadeIn();
    })
    $('#3').click(function() {
        $('#table_checkpoint_wrapper').fadeIn();
        $('.export-checkpoint').fadeIn();

        $('#table_event_wrapper').hide();
        $('#table_sum_wrapper').hide();
        $('#table_off_panggil_wrapper').hide();
        $('#myTable_wrapper').hide();
        $('.export-absensi').hide();
        $('.export-event').hide();
        $('.export-sum').hide();
        $('.export-off-panggil').hide();
    })

    const monthNames = {
        "01": "Januari",
        "02": "Februari",
        "03": "Maret",
        "04": "April",
        "05": "Mei",
        "06": "Juni",
        "07": "Juli",
        "08": "Agustus",
        "09": "September",
        "10": "Oktober",
        "11": "November",
        "12": "Desember"
    };


    $(document).ready(function() {
        $("nav.sidebar.sidebar-bunker").addClass("active")
        $("nav.navbar-custom-menu").addClass("active")
        $("#sidebarCollapse").addClass("open")


        $('#example2').DataTable();

        $('#myTable_wrapper').fadeIn();
        $('#table_event_wrapper').hide();
        $('#table_sum_wrapper').hide();
        $('#table_off_panggil_wrapper').hide();
        $('#table_checkpoint_wrapper').hide();
        $('.export-absensi').fadeIn();
        $('.export-event').hide();
        $('.export-sum').hide();
        $('.export-sum').hide();
        $('.export-off-panggil').hide();

        removeBtn();
    })

    $('#1Rekap_absen').addClass('mm-active');
    $('#subm_Absensi').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Rekap_absen').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });

    function range(start, end) {
        return Array(end - start + 1).fill().map((_, idx) => start + idx)
    }

    function combineArray(source, addon) {
        return source.concat(addon)
    }

    function fiterTable(node, api) {
        node.each(function(i) {
            var column = api.column(i);
            var select = $('<select class="select2" placeholder="All" multiple data-index="' + i + '">')
                .appendTo($(this).empty());
            column.data().unique().sort().each(function(d, j) {
                select.append('<option value="' + d + '">' + d + '</option>');
            });
        });
        $(".select2").select2({
            placeholder: "All",
        })
    }

    var table_absen = $('#myTable').DataTable({
        dom: 'Bfrtip',
        // colReorder: true,
        stateSaveCallback: function(settings, data) {
            console.log(settings, 'settings');
        },
        buttons: { //remove class in button default export datatables 
            dom: {
                button: {
                    tag: 'button',
                    className: ''
                }
            },
            buttons: [{
                    <?php if ($role == "hrd") {
                        echo "className: 'btn btn-success remove-radius export-absensi export-excel-log download_excel',";
                    } else {
                        echo "className: 'btn btn-success remove-radius export-absensi my-btn-export download_excel',";
                    } ?>
                    extend: 'excelHtml5',
                    title: function() {
                        var day = $(".day").val()
                        var month = $(".month").val()
                        var year = $(".year").val()
                        return `Rekap Absen ${day} ${monthNames[month.toString()]} ${year}`
                    },
                    text: '<i class="fa fa-file-excel"></i> Export to excel',
                    exportOptions: {
                        stripHtml: false,
                        modifier: {
                            page: 'all',
                        },
                        columns: combineArray(range(1, 15), [18, 19])
                    },
                    action: async function(e, dt, node, config) {
                        // console.log($(this));
                        var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Loading ... `
                        let buttons = $('button.export-absensi')
                        $(buttons[0]).prop('disabled', true)
                        var button = $(buttons[0]).html()
                        $(buttons[0]).html(loader)

                        e.stopPropagation()
                        e.preventDefault()

                        let workbook = new ExcelJS.Workbook();
                        let worksheet_absensi = workbook.addWorksheet('Absensi');
                        let worksheet_log = workbook.addWorksheet('Checkpoint');
                        let day = $(".day").val() === undefined ? "" : $(".day").val();
                        var month = $(".month").val()
                        var year = $(".year").val()
                        $.ajax({
                            'type': "POST",
                            'url': "<?= base_url('absensi/export_excel') ?>?day="+day,
                            'data': {
                                month,
                                year
                            },
                            'success': response => {
                                let {
                                    status,
                                    data_absen,
                                    data_log
                                } = JSON.parse(response);
                                if (status) {
                                    let {
                                        rows,
                                        columns
                                    } = data_absen
                                    let {
                                        rows_,
                                        columns_
                                    } = data_log
                                    let bulan = monthNames[month];
                                    let tahun = year;
                                    worksheet_absensi.columns = columns
                                    worksheet_absensi.addRows(rows.data);

                                    worksheet_log.columns = columns_;
                                    worksheet_log.addRows(rows_);
                                    workbook.xlsx.writeBuffer()
                                        .then(buffer => {
                                            saveAs(new Blob([buffer]), `Rekap Absensi ${day} ${bulan} ${tahun}.xlsx`)
                                            $(buttons[0]).prop('disabled', false);
                                            $(buttons[0]).html(button)
                                        })
                                        .catch(err => {
                                            console.log(err);
                                            Swal.fire('Failed!', 'Error writing excel export ' + err, 'error')
                                            $(buttons[0]).prop('disabled', false);
                                            $(buttons[0]).html(button)
                                        })
                                } else {
                                    Swal.fire('Info!', `No Data in ${day}-${bulan}-${year}`, 'warning')
                                    $(buttons[0]).prop('disabled', false);
                                    $(buttons[0]).html(button)
                                }
                            },
                            'error': (err) => {
                                Swal.fire('Failed!', 'Something wrong, Make Sure You Connected Internet', 'error')
                                $(buttons[0]).prop('disabled', false);
                                $(buttons[0]).html(button)
                            }
                        })
                    },
                    customize: async function(xlsx) {
                        // var m = $(".month").val()
                        // var y = $(".year").val()
                        // let title = `Rekap Absensi ${monthNames[m]} ${y}`
                        // setSheetName(xlsx, 'Absensi Reguler');
                        // addSheet(xlsx, '#dt_absen_normal', title, 'Absensi Reguler', '3');
                        // addSheet(xlsx, '#dt_absen_log_reguler', 'Log Absensi Reguler', 'Check Point', '2');
                        // $('#temporaryTableAbsen').html('')
                        // $('#temporaryTable').html('')
                    },
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: "A4",
                    className: 'btn btn-danger remove-radius export-absensi download_pdf',
                    title: function() {
                        var day = $(".day").val()
                        var month = $(".month").val()
                        var year = $(".year").val()
                        return `Rekap Absen ${day} ${monthNames[month.toString()]} ${year}`
                    },
                    text: '<i class="fa fa-file-pdf"></i> Export to PDF',
                    exportOptions: {
                        stripHtml: false,
                        modifier: {
                            page: 'all',
                            order: 'index',
                            search: 'none'
                        },
                        columns: range(1, 15)
                    },
                    customize: function(pdf) {

                    },
                    action: async function(e, dt, node, config) {
                        e.stopPropagation()
                        e.preventDefault()
                        var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Loading ... `
                        let buttons = $('button.export-absensi')
                        $(buttons[1]).prop('disabled', true)
                        var button = $(buttons[1]).html()
                        $(buttons[1]).html(loader)
                        var day = $(".day").val()
                        var month = $(".month").val()
                        var year = $(".year").val()
                        $.post('<?= base_url() ?>absensi/export_excel?day='+day, {
                                month: month,
                                year: year
                            })
                            .done(function(res) {
                                let data = JSON.parse(res)
                                let title = `REKAP ABSENSI ${day} ${data.bulan} ${data.tahun}`
                                document.title = title
                                let body = [
                                    [{
                                        text: 'Payroll ID',
                                        alignment: 'center',
                                        style: 'titleTh'
                                    }, {
                                        text: 'Nama Lengkap',
                                        alignment: 'center',
                                        style: 'titleTh'
                                    }, {
                                        text: 'Organization',
                                        alignment: 'center',
                                        style: 'titleTh'
                                    }, {
                                        text: 'Nama Shift',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Durasi Shift',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Shift Mulai',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Shift Akhir',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Waktu Masuk',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Waktu Pulang',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Waktu Telat Masuk',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Pulang Lebih Awal',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Total Jam Kerja',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Total Lembur Awal',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Total Lembur Akhir',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Tempat Masuk',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }, {
                                        text: 'Tempat Pulang',
                                        alignment: 'center',
                                        style: 'titleTh'

                                    }]
                                ];
                                data.data_absen.rows.data.forEach(element => {
                                    console.log("element");
                                    console.log(element);
                                    body.push([
                                        element[0],
                                        element[1],
                                        element[2],
                                        element[3],
                                        element[4],
                                        element[5],
                                        element[6],
                                        element[7],
                                        element[8],
                                        element[9],
                                        element[10],
                                        element[11],
                                        element[12],
                                        element[13],
                                        element[14],
                                        element[15],
                                    ])
                                });
                                var dd = {
                                    info: {
                                        title: title,
                                    },
                                    pageOrientation: 'landscape',
                                    pageSize: {
                                        width: 793,
                                        height: 1280
                                    },
                                    // footer: 
                                    //     function(currentPage, pageCount) {
                                    //         return '       ' + currentPage.toString() + ' of ' + pageCount;
                                    //     }
                                    // ,
                                    content: [{
                                            text: title,
                                            alignment: 'center',
                                            style: 'subheader'
                                        },
                                        {
                                            style: 'table',
                                            table: {
                                                headerRows: 1,
                                                // dontBreakRows: true,
                                                // keepWithHeaderRows: 1,
                                                body: body
                                            }
                                        },
                                    ],
                                    styles: {
                                        header: {
                                            fontSize: 18,
                                            bold: true,
                                            margin: [0, 0, 0, 10]
                                        },
                                        subheader: {
                                            fontSize: 16,
                                            bold: true,
                                            margin: [0, 10, 0, 5]
                                        },
                                        titleTh: {
                                            fontSize: 12,
                                            bold: true,
                                            margin: [0, 10, 0, 5]
                                        },
                                        table: {
                                            margin: [0, 5, 0, 15]
                                        },
                                        tableHeader: {
                                            bold: true,
                                            fontSize: 13,
                                            color: 'black'
                                        }
                                    },
                                    defaultStyle: {
                                        // alignment: 'justify'
                                    }

                                }
                                let tableLayouts = {
                                    exampleLayout: {
                                        hLineWidth: function(i, node) {
                                            if (i === 0 || i === node.table.body.length) {
                                                return 0;
                                            }
                                            return (i === node.table.headerRows) ? 2 : 1;
                                        },
                                        vLineWidth: function(i) {
                                            return 0;
                                        },
                                        hLineColor: function(i) {
                                            return i === 1 ? 'black' : '#aaa';
                                        },
                                        paddingLeft: function(i) {
                                            return i === 0 ? 0 : 8;
                                        },
                                        paddingRight: function(i, node) {
                                            return (i === node.table.widths.length - 1) ? 0 : 8;
                                        }
                                    }
                                };

                                pdfMake.createPdf(dd).download(`${title}.pdf`);
                                $(buttons[1]).prop('disabled', false);
                                $(buttons[1]).html(button)
                            }).fail(function() {
                                Swal.fire('Failed!', 'Something wrong, Make Sure You Connected Internet', 'error')
                                $(buttons[1]).prop('disabled', false);
                                $(buttons[1]).html(button)
                            })
                    },
                },
                {
                    className: 'btn btn-success remove-radius export-absensi download_excel',
                    text: '<i class="fa fa-file-excel"></i> New Export to Excel',
                    action: function (e, dt, node, config)
                    {
                        var day = $(".day").val()
                        var month = $(".month").val()
                        var year = $(".year").val()

                        //This will send the page to the location specified
                        window.open('<?php echo base_url('export/download_rekap'); ?>?day='+day+'&month='+month+'&year='+year);
                    }
                }
                // {
                //     className: 'btn btn-danger remove-radius export-absensi',
                //     text: '<i class="fa fa-credit-card"></i> Export Payroll to PDF',
                //     customize: function(pdf) {

                //     },
                // },
                // {
                //     className: 'btn btn-danger remove-radius export-absensi',
                //     text: 'Cuti',
                //     customize: function(pdf) {

                //     },
                // },
                // {
                //     className: 'btn btn-danger remove-radius export-absensi',
                //     text: 'Penilaian',
                //     customize: function(pdf) {

                //     },
                // }

            ]
        },
        responsive: true,
        autoWidth: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('absensi/ajax') ?>?day=<?= date("d") ?>',
        },
        initComplete: function() {
            $('.dt-button').addClass('btn btn-info');
            $('.dt-buttons').css({
                // 'height': '50px',
                'margin-top': '15px',
            })
            // $('.export-absensi').css({
            //     'width': '15%',
            // })
            var api = this.api();
            setTimeout(() => {
                var select = $('.filterhead:not(.not-fill)', ".DTFC_LeftHeadWrapper > table > thead");
                fiterTable(select, api)
                var selects = $('.filterhead:not(.not-fill)', api.table().header())
                fiterTable(selects, api)
                table_absen
                    .order([0, 'desc'], [1, 'asc'])
                    .draw();
            }, 500);
            $('.filterhead').css('background', 'white')
            $('.filterhead').removeClass('sorting')
            $('.filterhead').removeClass('sorting_asc')
            $('.filterhead').removeClass('sorting_desc')
            $('.clone-header').show()
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
        },
        order: [
            [0, "desc"],
            [1, "asc"]
        ],
        "scrollX": true,
        // "scrollY": "50vh",
        // pageLength: 5,
        fixedColumns: {
            leftColumns: 4,
            rightColumns: 0
        },
        scrollCollapse: true,
        orderCellsTop: true,
    });



    $(table_absen.table().container()).on('change', 'thead select', function() {
        var val = $(this).val().join("|")
        table_absen.column($(this).data('index'))
            .search(val ? val : '', true, false)
            .draw();
    });

    var table_off_panggil = $('#table_off_panggil').DataTable({
        dom: 'Bfrtip',
        buttons: { //remove class in button default export datatables 
            dom: {
                button: {
                    tag: 'button',
                    className: ''
                }
            },
            buttons: []
        },
        responsive: true,
        autoWidth: false,
        processing: true,
        serverside: true,
        ajax: '<?= base_url('absensi/ajax_off_panggil') ?>?day=<?= date("d") ?>',
        initComplete: function() {
            $('.dt-button').addClass('btn btn-info');
            $('.dt-button').css({
                'height': '50px'
            })
            // $('#table_off_panggil thead tr th:not(.not-fill)').clone(true).addClass('fill').appendTo('#table_off_panggil thead');
            // $("#table_off_panggil thead .fill").each(function(i) {
            //     var select = $('<select class="form-control"><option value="">Semua</option></select>')
            //         .appendTo($(this).empty())
            //         .on('change', function() {
            //             table_off_panggil.column(i)
            //                 .search($(this).val())
            //                 .draw();
            //         });
            //     // console.log(i)
            //     table_off_panggil.column(i).data().unique().sort().each(function(d, j) {
            //         var c = d;
            //         if (d == '---' && i == 8) {
            //             c = 'Kosong'
            //         } else if (d == '---' && i == 6) {
            //             c = 'Belum Pulang'
            //         }
            //         select.append('<option value="' + d + '">' + c + '</option>')
            //     });
            // });



            // $('.fill').css('background', 'white')
            // $('.fill').removeClass('sorting')
            // $('.fill').removeClass('sorting_asc')

            var api = this.api();
            setTimeout(() => {
                var select = $('.filterhead:not(.not-fill)', ".DTFC_LeftHeadWrapper > table > thead");
                fiterTable(select, api)
                var selects = $('.filterhead:not(.not-fill)', api.table().header())
                fiterTable(selects, api)
                table_off_panggil
                    .order([0, 'desc'], [1, 'asc'])
                    .draw();
            }, 500);
            $('.filterhead').css('background', 'white')
            $('.filterhead').removeClass('sorting')
            $('.filterhead').removeClass('sorting_asc')
            $('.filterhead').removeClass('sorting_desc')
            $('.clone-header').show()
        },
        "scrollX": true,
        fixedColumns: {
            leftColumns: 4
        },
        scrollCollapse: true,
        orderCellsTop: true
    });

    $(table_off_panggil.table().container()).on('change', 'thead select', function() {
        var val = $(this).val().join("|")
        table_off_panggil.column($(this).data('index'))
            .search(val ? val : '', true, false)
            .draw();
    });

    var table_event = $('#table_event').DataTable({
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
                    className: 'btn btn-success remove-radius my-btn-export',
                    title: function() {
                        var day = $(".day").val()
                        var month = $(".month").val()
                        var year = $(".year").val()
                        return `Absen Event ${monthNames[month.toString()]} ${year}`
                    },
                    text: '<i class="fa fa-file-excel"></i> Export to excel',
                    exportOptions: {
                        stripHtml: false,
                        modifier: {
                            page: 'all',
                        },
                        columns: [0, 1, 2, 3, 4]
                    },
                    customize: function(doc) {

                    },
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-danger remove-radius export-event',
                    title: function() {
                        var month = $(".month").val()
                        var year = $(".year").val()
                        return `Absen Event ${monthNames[month.toString()]} ${year}`
                    },
                    text: '<i class="fa fa-file-pdf"></i> Export to PDF',
                    exportOptions: {
                        stripHtml: false,
                        modifier: {
                            page: 'all',
                        },
                        columns: [0, 1, 2, 3, 4]
                    },
                    customize: function(doc) {

                    },
                }

            ]
        },
        responsive: true,
        autoWidth: false,
        processing: true,
        serverside: true,
        ajax: '<?= base_url('absensi/ajax_event') ?>',
        initComplete: function() {
            $('.dt-button').addClass('btn btn-info');
            $('.dt-button').css({
                'height': '50px'
            })
            $('#table_event thead tr th:not(.not-fill)').clone(true).addClass('fill').appendTo('#table_event thead');
            $("#table_event thead .fill").each(function(i) {
                var select = $('<select class="form-control"><option value="">Semua</option></select>')
                    .appendTo($(this).empty())
                    .on('change', function() {
                        table_event.column(i)
                            .search($(this).val())
                            .draw();
                    });
                // console.log(i)
                table_event.column(i).data().unique().sort().each(function(d, j) {
                    var c = d;
                    if (d == '---' && i == 8) {
                        c = 'Kosong'
                    } else if (d == '---' && i == 6) {
                        c = 'Belum Pulang'
                    }
                    select.append('<option value="' + d + '">' + c + '</option>')
                });
            });
            $('.fill').css('background', 'white')
            $('.fill').removeClass('sorting')
            $('.fill').removeClass('sorting_asc')

        }
    });



    var table_sum = $('#table_sum').DataTable({
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
                    title: function() {
                        var month = $(".month").val()
                        var year = $(".year").val()
                        return `Absen Summary ${monthNames[month.toString()]} ${year}`
                    },
                    text: '<i class="fa fa-file-excel"></i> Export to excel',
                    exportOptions: {
                        stripHtml: false,
                        modifier: {
                            page: 'all',
                        },
                        columns: [0, 1, 2, 3, 4]
                    },
                    customize: function(doc) {

                    },
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-danger remove-radius export-sum ',
                    title: function() {
                        var month = $(".month").val()
                        var year = $(".year").val()
                        return `Absen Summary ${monthNames[month.toString()]} ${year}`
                    },
                    text: '<i class="fa fa-file-pdf"></i> Export to PDF',
                    exportOptions: {
                        stripHtml: false,
                        modifier: {
                            page: 'all',
                        },
                        columns: [0, 1, 2, 3, 4]
                    },
                    customize: function(doc) {

                    },
                }

            ]
        },
        responsive: true,
        autoWidth: false,
        processing: true,
        serverside: true,
        ajax: '<?= base_url('absensi/ajax_sum') ?>',
        initComplete: function() {
            $('.dt-button').addClass('btn btn-info');
            $('.dt-button').css({
                'height': '50px'
            })
            $('.fill').css('background', 'white')
            $('.fill').removeClass('sorting')
            $('.fill').removeClass('sorting_asc')
        }
    });

    var table_checkpoint = $('#table_checkpoint').DataTable({
        dom: 'Bfrtip',
        buttons: { //remove class in button default export datatables 
            dom: {
                button: {
                    tag: 'button',
                    className: ''
                }
            },
            buttons: []
        },
        responsive: true,
        autoWidth: false,
        processing: true,
        serverside: true,
        ajax: '<?= base_url('absensi/ajax_checkpoint') ?>',
        initComplete: function() {
            $('.dt-button').addClass('btn btn-info');
            $('.dt-button').css({
                'height': '50px'
            })
            $('#table_checkpoint thead tr th:not(.not-fill)').clone(true).addClass('fill').appendTo('#table_checkpoint thead');
            $("#table_checkpoint thead .fill").each(function(i) {
                var select = $('<select class="form-control"><option value="">Semua</option></select>')
                    .appendTo($(this).empty())
                    .on('change', function() {
                        table_checkpoint.column(i)
                            .search($(this).val())
                            .draw();
                    });
                // console.log(i)
                table_checkpoint.column(i).data().unique().sort().each(function(d, j) {
                    var c = d;
                    if (d == '---' && i == 8) {
                        c = 'Kosong'
                    } else if (d == '---' && i == 6) {
                        c = 'Belum Pulang'
                    }
                    select.append('<option value="' + d + '">' + c + '</option>')
                });
            });
            $('.fill').css('background', 'white')
            $('.fill').removeClass('sorting')
            $('.fill').removeClass('sorting_asc')

        }
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        //console.log("tab : ",e);
        if (e.currentTarget.id == '0') {
            var day = $(".day").val()
            var month = $(".month").val()
            var year = $(".year").val()
            $(".day").val(day)
            $(".month").val(month)
            $(".year").val(year)
            //table_absen.columns.adjust().draw()

            table_absen.ajax.url(`<?= base_url('absensi/ajax') ?>/${month}/${year}?day=${day}`).load(function() {
                var select = $('.filterhead:not(.not-fill)', ".DTFC_LeftHeadWrapper > table > thead");
                var selects = $('.filterhead:not(.not-fill)', table_absen.table().header())
                fiterTable(select, table_absen)
                fiterTable(selects, table_absen)
                table_absen
                    .order([0, 'desc'], [1, 'asc'])
                    .draw();
            });
            
        }else if (e.currentTarget.id == '2_') {
            var day = $(".day").val()
            var month = $(".month").val()
            var year = $(".year").val()
            $(".day").val(day)
            $(".month").val(month)
            $(".year").val(year)
            //table_off_panggil.columns.adjust().draw()
            table_off_panggil.ajax.url(`<?= base_url('absensi/ajax_off_panggil') ?>/${month}/${year}?day=${day}`).load(function() {
                var select = $('.filterhead:not(.not-fill)', ".DTFC_LeftHeadWrapper > table > thead");
                var selects = $('.filterhead:not(.not-fill)', table_off_panggil.table().header())
                fiterTable(select, table_off_panggil)
                fiterTable(selects, table_off_panggil)
                table_off_panggil
                    .order([0, 'desc'], [1, 'asc'])
                    .draw();
            });
        }
    })

    $('.dataTables_filter > label').addClass("right")
    // $('.dataTables_wrapper').css({
    //             'margin-top': '-39px'
    //         })
    
    $("#filterDate").append(`
                <div class="col-md-4"></div>
                <div class="col-md-6">
                    <div class=" row  mb-0   ">
                        <label class="col-md-2 right mr-2  mt-2 ">Filter :&nbsp;</label>
                        <select class="day form-control js-filter form-control-sm mr-1 col-md">
                            <option value="">All</option>
                            <?php for ($d = 1; $d <= 31; ++$d) {
                                $date_label = date("d", mktime(0,0,0,0,$d));
                            ?>
                                <option <?= date("d") == $date_label ? "selected" : "" ?> value="<?= $date_label; ?>"><?= $date_label; ?></option>
                            <?php } ?>
                        </select>
                        <select class="month form-control js-filter form-control-sm mr-1 col-md">
                            <?php for ($m = 1; $m <= 12; $m++) {
                                $month_label = date('F', mktime(0, 0, 0, $m, 1));
                            ?>
                                <option <?= date("m", strtotime($month_label)) == date("m") ? 'selected' : '' ?> value="<?= date("m", strtotime($month_label)); ?>"><?= $month_label; ?></option>
                            <?php } ?>
                        </select>
                        <select class="year form-control js-filter form-control-sm col-md">
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
                <div class="col-md-2"></div>
            `);

    $("#table_event_filter > div > div > select").attr("disabled", true)
    $("#table_sum_filter > div > div > select").attr("disabled", true)

    $(".js-filter").change(function() {
        var day = $(".day").val()
        var month = $(".month").val()
        var year = $(".year").val()
        $(".day").val(day)
        $(".month").val(month)
        $(".year").val(year)
        table_absen.ajax.url(`<?= base_url('absensi/ajax') ?>/${month}/${year}?day=${day}`).load(function() {
            var select = $('.filterhead:not(.not-fill)', ".DTFC_LeftHeadWrapper > table > thead");
            var selects = $('.filterhead:not(.not-fill)', table_absen.table().header())
            fiterTable(select, table_absen)
            fiterTable(selects, table_absen)
            table_absen
                .order([0, 'desc'], [1, 'asc'])
                .draw();
        });
        // table_off_panggil.ajax.url(`<?= base_url('absensi/ajax_off_panggil') ?>/${month}/${year}?day=${day}`).load(function() {
        //     var select = $('.filterhead:not(.not-fill)', ".DTFC_LeftHeadWrapper > table > thead");
        //     var selects = $('.filterhead:not(.not-fill)', table_off_panggil.table().header())
        //     fiterTable(select, table_off_panggil)
        //     fiterTable(selects, table_off_panggil)
        //     table_off_panggil
        //         .order([0, 'desc'], [1, 'asc'])
        //         .draw();
        // });
        //table_off_panggil.ajax.url(`<?= base_url('absensi/ajax_off_panggil') ?>/${month}/${year}?day=${day}`).load();
        table_sum.ajax.url(`<?= base_url('absensi/ajax_sum') ?>/${month}/${year}?day=${day}`).load();
        table_event.ajax.url(`<?= base_url('absensi/ajax_event') ?>/${month}/${year}?day=${day}`).load();
        table_checkpoint.ajax.url(`<?= base_url('absensi/ajax_checkpoint') ?>/${month}/${year}?day=${day}`).load();
    })


    function modalImg(ini) {
        $('#myModal').modal('show');
        var src = $(ini).attr('src')
        $('#img_modal').attr('src', src)
    }


    var table_absen_reguler = $('#table-absen-reguler').DataTable({
        responsive: true,
        autoWidth: false,
        processing: true,
        serverside: true,
    });

    function detail(data) {
        for (const key in data) {
            $(`b[data-detail-absen="${key}"]`).text(data[key])
        }
        $(".date-absen").text(data.date)
        table_absen_reguler.clear().draw();
        table_absen_reguler.ajax.url('<?= base_url('absensi/table_absen_reguler/') ?>' + data.user_id + '/' + data.date).load();

    }

    async function exportPdf(e) {
        $(e).prop('disabled', true)
        var loader = `<i class="fa fa-spinner fa-spin"></i>`
        var button = $(e).html()
        $(e).html(loader)
        const id = $(e).data('id')
        console.log("data",id);
        await exportCheckpointPDF(id)
        $(e).prop('disabled', false)
        $(e).html(button)
        return false;
    }

    function exportCheckpointPDF(id) {
        return new Promise(async (resolve, reject) => {
            try {
                const {
                    data
                } = await $.getJSON(`<?= base_url('absensi/export_checkpoint_json/') ?>${id.user_id}/${id.shift_name}/${id.date}/${id.check_in_time}/${id.check_out_time}`)
                console.log("asasas",data);
                const dd = await genReportCheckpoint(data)
                console.log("dd",JSON.stringify(dd));
                pdfMake.createPdf(dd).download(`checkpoint-${id.date}-${id.full_name}-${id.shift_name}.pdf`);
                resolve("ok")
            } catch (e) {
                resolve(e)
                alert("ERROR:".e);
            }
        })

    }

    async function exportPayrollPdf(e) {
        $(e).prop('disabled', true)
        var loader = `<i class="fa fa-spinner fa-spin"></i>`
        var button = $(e).html()
        $(e).html(loader)
        const id = $(e).data('id')
        console.log("data",id);
        await payrollPDF(id)
        $(e).prop('disabled', false)
        $(e).html(button)
        return false;
    }

    function payrollPDF(id) {
        return new Promise(async (resolve, reject) => {
            try {
                // const {
                //     data
                // } = id
                var data = id
                console.log("asasas",data);
                const dd = await genReportPayroll(data)
                console.log("dd",JSON.stringify(dd));
                pdfMake.createPdf(dd).download(`payroll-${id.date}-${id.full_name}-${id.shift_name}.pdf`);
                resolve("ok")
            } catch (e) {
                resolve(e)
                alert("ERROR:".e);
            }
        })

    }


    function storeHiddenColumn(table, column) {
        $.post(`<?= base_url("users/get_hidden_column") ?>`, {
            table,
            column
        })
    }

    function getHiddenColumn(table) {
        let promise = new Promise((resolve, reject) => {
            $.post(`<?= base_url("users/get_hidden_column") ?>`, {
                    table
                })
                .done(res => resolve(JSON.parse(res)))
                .fail(err => reject(err))
        })
        return promise
    }

    function refreshColumn() {
        let columns = []
        $("#myTable > thead > tr > th").each((_, el) => {
            columns.push($(el).text())
        })
        return columns
    }

    function toggleHideColumn(table, element) {
        // Get the column API object
        var column = table.column($(element).attr('data-column'));
        // Toggle the visibility
        column.visible(!column.visible());
        // storeHiddenColumn('<?= $this->uri->segment(1) ?>', columns)
    }

    function setListHideColumn(column) {
        var html = ''
        $(".w-hide").html(" ")
        column.map((e, i) => html += `<a class="dropdown-item toggle-hide" href="#" data-column='${i}'>${e}</a>`)
        $(".w-hide").html(html)
        $(".toggle-hide").click(function(e) {
            e.preventDefault()
            toggleHideColumn(table_absen, $(this))
        })
    }

    setListHideColumn(refreshColumn())

    table_absen.on('column-reorder', function(e, settings, details) {
        let columns = refreshColumn()
        setListHideColumn(columns)

    });

    function removeBtn() {
        <?php 
        foreach ($permission_hide as $value) 
        { 
        ?>
            $('.<?php echo $value; ?>').remove();
        <?php 
        } 
        ?>
    }
</script>