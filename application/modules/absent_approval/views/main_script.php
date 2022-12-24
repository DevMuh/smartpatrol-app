<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/sweetalert/sweetalert.min.js') ?>"></script>
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.css" rel="stylesheet">
<!-- <script src="https://cdn.datatables.net/scroller/2.0.5/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-pageLoadMore/1.0.0/js/dataTables.pageLoadMore.min.js"></script> -->

<script>
    const screenHeight = $(document).innerHeight()
    var table;
    let formVerification = $("#formVerification")
    let urlApproveAbsen = "<?= base_url() ?>absent_approval/verification"
    let urlApproveAbsenBatch = "<?= base_url() ?>absent_approval/verification_batch"
    let urlPrepareDataApproveAbsenBatch = "<?= base_url() ?>absent_approval/prepare_data_verification_batch"

    $(document).ready(function() {
        $('#tb_schedule').DataTable({
            deferRender:    true,
            scrollY:        200,
            scrollCollapse: true,
            scroller:       true,
            paging:false,
            ajax: '<?= base_url('absent_approval/ajax') ?>',
            "columnDefs": [
                { targets: 'nosort', orderable: false }
            ]
                    
        });
        ceklissubmit()
    })
    function ceklissubmit(){
        // Handle form submission event
   $('#ceklis_semua').on('click', function(e){
    if ($('#ceklis_semua')[0].checked) {
                    $("input[type=checkbox][name='approve[]']").each(function (params) {
                        $(this).prop("checked", true);
                    })
                    $(".ceklis_approved").css("display","block")
                }else{
                $("input[type=checkbox][name='approve[]']").each(function (params) {
                    $(this).prop("checked", false);
                })
                $(".ceklis_approved").css("display","none")
            }
        });
        $("#tb_schedule").on("click","#approved",function(e){
            // e.preventDefault()
            // alert("as")
            if ($('input.checkbox_check').is(':checked')) {
            $(this).prop("checked", true);
            $(".ceklis_approved").css("display","block")
        }else{
            $(".ceklis_approved").css("display","none")
            $(this).prop("checked", false);
            
        }
    })
        $("#frm_submit").on("click",function(){
            
            var log_pulang_id=[];
            var log_masuk_id=[];
            var formdata = new FormData()
            // var log_pulang_id=``;
            // var log_masuk_id=``;
            $('input[name="approve[]"]:checked').each(function () {
                if ($('input:checkbox:checked')) {
                    log_masuk_id.push((this.checked ? $(this).data("idmasuk") : ""));
                    log_pulang_id.push((this.checked ? $(this).data("idplng") : "")) ;
                    // formdata.append("log_id_masuk",(this.checked ? $(this).data("idmasuk") : ""))
                    // formdata.append("log_id_plng",(this.checked ? $(this).data("idplng") : ""))
                }
            });
            formdata.append("log_id_masuk",log_masuk_id)
            formdata.append("log_id_plng",log_pulang_id)
            Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Menyimpan Data Approved",
            type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Batal'
                }).then((result) => {
                if (result.value) {
                    // alert("asd")
                    $.ajax({
                                url:urlPrepareDataApproveAbsenBatch,
                                method:"POST",
                                data:formdata,
                                cache: false,
                                dataType:"JSON",
                                contentType: false,
                                processData: false,
                                success:function(e){
                                        // e.uid
                                        // console.log("ads"+ e.data)
                                        window.open(urlApproveAbsenBatch+"?uid="+e.data)
                                }
                    })
                }
            })
            })
            
    }
    function verification(data) {
        console.log(data);
        $('#approve-title').html(`Apakah anda yakin ingin Menyetujui Absensi a.n <b>`+data.full_name+`</b> di tanggal <b>`+data.date+`</b><br>dengan alasan : <b>`+data.overtime_reason+`</b><br> ? `)
        $('#log_id_masuk').val(data.log_masuk_id)
        $('#log_id_pulang').val(data.log_pulang_id)
    }

    function save() {
        // console.log("asasasa");

        var formData = new FormData(formVerification[0]);
        var url = urlApproveAbsen;

        var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving ... `
        let buttons = $('#btnSave')
        $(buttons).prop('disabled', true)
        $(buttons).html(loader)

        $.ajax({
            type: "POST",
            url: url,
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (resp) {
                if (resp.status) {
                    console.log(resp);
                    swal("Success", resp.message, "success")
                    
                    let buttons = $('#btnSave')
                    $(buttons).prop('disabled', false)
                    $(buttons).html("Save")

                    window.location.reload()
                }else{
                    swal("Upss!!!", resp.message, "warning")
                    
                    var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving ... `
                    let buttons = $('#btnSave')
                    $(buttons).prop('disabled', true)
                    $(buttons).html(loader)
                }                
            },
            beforeSend: function(){
                var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving ... `
                let buttons = $('#btnSave')
                $(buttons).prop('disabled', true)
                $(buttons).html(loader)
            },
            error: function (resp) {
                swal("Upss!!!", resp.message, "error")

                var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving ... `
                let buttons = $('#btnSave')
                $(buttons).prop('disabled', true)
                $(buttons).html(loader)
            }
        });
    }
</script>