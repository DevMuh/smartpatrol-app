<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script>
    $('#1register_b2b').addClass('mm-active');
    $('#subm_Master').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2register_b2b').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });

    $(document).ready(function() {
        $('#tb_qr').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('qr/ajax/') ?>'
        });
    });

    $("input[name='qr_id']").on('input', function(e) {
        $("input[name='qr_id']").val($(this).val().replace(/ /g, ""));
    });

    $(document).on('submit', '#formAdd', function(e) {
        e.preventDefault()
        let btn_submit = $(".js-submit");
        btn_submit.html("Loading ...")
        btn_submit.attr("disabled", true)
        var data = new FormData(this)
        $.ajax({
            url: '<?php echo base_url() ?>qr/add',
            method: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(this),
            success: function(response) {
                var type = JSON.parse(response).type
                var message = JSON.parse(response).message
                if (type == 'success') {
                    $('#tb_qr').DataTable().ajax.reload(null, false)
                    $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
                    $("#formAdd").trigger("reset");
                    $('#exampleModal1').modal('hide')
                    btn_submit.html("Generate")
                    btn_submit.attr("disabled", false)

                } else {
                    $("#alert").html('<div class="alert alert-danger"><button type="button" class="close">×</button>' + message + '</div>');
                    btn_submit.html("Generate")
                    btn_submit.attr("disabled", false)
                }

                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            }
        })
    })

    $(document).on('submit', '#formUpdate', function(e) {
        e.preventDefault()
        let btn_submit = $(".js-edit");
        btn_submit.html("Loading ...")
        btn_submit.attr("disabled", true)
        var data = new FormData(this)
        $.ajax({
            url: '<?php echo base_url() ?>qr/update',
            method: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(this),
            success: function(response) {
                var type = JSON.parse(response).type
                var message = JSON.parse(response).message
                if (type == 'success') {
                    $('#tb_qr').DataTable().ajax.reload(null, false)
                    $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
                    $("#formUpdate").trigger("reset");
                    $('#editModal').modal('hide')
                    btn_submit.html("Update")
                    btn_submit.attr("disabled", false)

                } else {
                    $("#alert-edit").html('<div class="alert alert-danger"><button type="button" class="close">×</button>' + message + '</div>');
                    btn_submit.html("Update")
                    btn_submit.attr("disabled", false)
                }

                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            }
        })
    })

    $(".js-add").click(() => {
        $("#formAdd").trigger("reset");
        $("#exampleModalLabel4").text("Add Qr")
        $(".js-submit").text("Generate")
        $(".js-submit").attr("disabled", false)
    })

    function edit(data) {
        $("#formUpdate").trigger("reset");
        $(".js-edit").text("Update")
        for (const key in data) {
            $(`[name="${key}"]`).val(data[key])
        }
    }

    function modalImg(ini) {
        $('#myModal').modal('show');
        var src = $(ini).attr('src')
        $('#img_modal').attr('src', src)
    }

    function ImagetoPrint(source, label = "") {
        return "<html><head><scri" + "pt>function step1(){\n" +
            "setTimeout('step2()', 10);}\n" +
            "function step2(){window.print();window.close()}\n" +
            "</scri" +
            "pt></head><body onload='step1()'>\n" +
            "<div align='center'><img align='center' src='" + source + "' /><h1 style='margin-top:-50' align='center'>" + label + "</h1></div></body></html>";
    }

    function PrintImage(source, label) {
        var Pagelink = "about:blank";
        var pwa = window.open(Pagelink, "_new");
        pwa.document.open();
        pwa.document.write(ImagetoPrint(source, label));
        pwa.document.close();
    }

    function hapus(id) {
        $.post(`<?= base_url() ?>qr/delete/` + id).done((res) => {
            let resp = JSON.parse(res)
            if (resp.type == 'success') {
                $('#tb_qr').DataTable().ajax.reload(null, false)
                $('#modalDelete').modal('hide');
                $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
            } else {
                $('#modalDelete').modal('hide');
                $("#alertSuccess").html('<div class="alert alert-danger"><button type="button" class="close">×</button>' + message + '</div>');

            }
            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                $(".alert").slideUp(500);
            });
        }).fail(err => {
            console.log(err, 'err');

        })
    }

    function deleteConfirm(id) {
        $('#modalDelete').modal('show');
        $('#messageDelete').html('Are you sure want to delete data <b>' + name + '</b>?')
        $('#hapus').attr('onclick', "hapus(" + id + ")");
    }
</script>