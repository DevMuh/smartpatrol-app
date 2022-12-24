<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi</title>
</head>

<body>

    <style>
        * {
            padding: 0px;
            margin: 0px;
            overflow: hidden;
            font-size: 12px;
        }

        table {
            width: 100%;
        }
    </style>


    <iframe id='pdfV' style="width: 100vw; height: 100vh; border: 0px;" src="<?= base_url()?>absensi/loading"></iframe>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <script async>
        // playground requires you to assign document definition to a variable called dd
        const urlParams = new URLSearchParams(window.location.search);
        const month = urlParams.get('laskdfjapoiefleksjuadv');
        const year = urlParams.get('dsafaewflijsejfmlnvesi');
        $.post('<?= base_url() ?>absensi/export_excel', {
                month: month,
                year: year
            })
            .done(function(res) {
                let data = JSON.parse(res)
                let title = `REKAP ABSENSI ${data.bulan} ${data.tahun}`
                document.title = title
                let body = [
                    [{
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

                pdfMake.createPdf(dd).download('sample.pdf');
                // pdfMake.createPdf(dd, tableLayouts).getDataUrl(function(outDoc) {
                //     // console.log(outDoc);
                //     // $scope.$apply(function() {
                //     //     $scope.stats = 'generated in ' + (new Date().getTime() - lastGen.getTime()) + ' ms';
                //     // });
                //     document.getElementById('pdfV').src = outDoc;
                //     // let pdfWindow = window.open("")
                //     // pdfWindow.document.title = 'Laporan.pdf';
                //     // pdfWindow.document.write(
                //     //     `
                //     // <style>
                //     // * {
                //     //     padding: 0px;
                //     //     margin: 0px;
                //     //     overflow: hidden;
                //     //     font-size: 12px;
                //     // }
                //     // table {
                //     //     width: 100%;
                //     // }
                //     // </style>
                //     // <iframe id='pdfV' style="width: 100%; height: 100%; border: 0px" src='${encodeURI(outDoc)}'></iframe>
                //     // `
                //     // )

                // });
            })


        function generate() {
            lastGen = new Date();

            // eval(editor.getSession().getValue());


        }
        // pdfMake.createPdf(dd).download('sample.pdf');
        // pdfMake.createPdf(dd).print()
        generate()
    </script>


</body>

</html>