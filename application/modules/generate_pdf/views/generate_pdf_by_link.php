<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="<?= base_url('assets/apps/assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/metisMenu/metisMenu.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/fontawesome/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/typicons/src/typicons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/themify-icons/themify-icons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

    <style>
        #loadingModal{
            z-index: 8;
            box-shadow: unset;
            position: absolute;
            left: 30%;
            top: 140px;
            box-shadow: 0 0.46875rem 2.1875rem rgb(90 97 105 / 10%), 0 0.9375rem 1.40625rem rgb(90 97 105 / 10%), 0 0.25rem 0.53125rem rgb(90 97 105 / 12%), 0 0.125rem 0.1875rem rgb(90 97 105 / 10%) !important;
        }
        #loading-backdrop{
            background-color: rgb(12, 12, 12);
            opacity: 0.7;
            z-index: 6;
            background-color: #8c9094;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="modal-backdrop d-none" id="loading-backdrop"></div>
    <div class="card justify-content-center d-none"  id="loadingModal">
        <div style="position: relative; height: 420px; width: 666px;">
            <div style="position: absolute; left: 134px; top: 35px;">
                <div class="lds-dual-ring"></div>
                <img src="<?php echo base_url('assets/apps/images/loading.gif')  ?>" alt="" style="width: 70%; margin-left: 65px;">
                <p class="text-center" style="margin-top: 30px;">Wait a moment to Generate file PDF</p>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/apps/assets/plugins/jQuery/jquery-3.4.1.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/apps/assets/dist/js/popper.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/apps/assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="<?= base_url(); ?>assets/apps/assets/dist/js/mypdf.js?_=<?= time() ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/6.26.0/polyfill.js"></script>
    <script>
        $(document).ready(function() {
            var id = {
                "b2b_token": "<?php echo $b2b_token; ?>",
                "id_": "<?php echo $id_header; ?>",
                "done_time": "<?php echo $done_time; ?>"
            }
            console.log(id);            
            taskPatrolPDF(id)
        })

        function taskPatrolPDF(id) {
            return new Promise(async (resolve, reject) => {
                try {
                    $("#loadingModal").removeClass("d-none");
                    $("#loading-backdrop").removeClass("d-none");
                    const {
                        data
                    } = await $.getJSON(`<?= base_url('generate_pdf/export_pdf_json/') ?>${id.b2b_token}/${id.id_}/${id.done_time}`)
                    console.log("asasas",data);
                    const dd = await genReportTaskPatrol(data)
                    console.log("dd",JSON.stringify(dd));
                    //pdfMake.createPdf(dd).download(`task_patrol-${id.cluster_name}-${id.username}-${id.publish_date}.pdf`);
                    let ms = Date.now();
                    pdfMake.createPdf(dd).download(`task_patrol_`+ms+`.pdf`);
                    resolve("ok")

                    $("#loadingModal").addClass("d-none");
                    $("#loading-backdrop").addClass("d-none");
                } catch (e) {
                    resolve(e)
                    alert("ERROR:".e);
                }
            })
        }
    </script>
</body>
</html>