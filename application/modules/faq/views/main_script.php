<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/classie.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/modalEffects.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url() ?>/assets/apps/assets/dist/js/faq.js"></script>

<script>
    // <?php
        //     if(validation_errors()){
        //         echo validation_errors();
        //     } else {
        //         echo "a";
        //     }
        // 
        ?>

    $('#1Incident').addClass('mm-active');
    $('#subm_Master').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Incident').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });

    function initMinimize(params) {
        var minimized_elements = $('.minimize');
        minimized_elements.each(function() {
            var t = $(this).html();
            if (t.length < 30) return;
            $(this).html(
                t.slice(0, 30) + '<span>... </span><a href="#" class="more">More</a>' +
                '<span style="display:none;">' + t.slice(30, t.length) + ' <a href="#" class="less">Less</a></span>'
            );
        });
        $('a.more', minimized_elements).click(function(event) {
            event.preventDefault();
            $(this).hide().prev().hide();
            $(this).next().show();
        });

        $('a.less', minimized_elements).click(function(event) {
            event.preventDefault();
            $(this).parent().hide().prev().show().prev().show();
        });
    }

    $(document).ready(function() {
        $('#tb_faq').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('faq/ajax') ?>',
            "initComplete": function(settings, json) {
                initMinimize()
            }
        });
    });

    $(document).on('submit', '#form_add', function(e) {
        e.preventDefault()
        var data = new FormData(this)
        $.ajax({
            url: '<?php echo base_url() ?>faq/add',
            method: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(this),
            success: function(response) {
                var type = JSON.parse(response).type
                var message = JSON.parse(response).message
                if (type == 'success') {
                    $('#tb_faq').DataTable().ajax.reload(null, false)
                    initMinimize()
                    $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
                    $("form").trigger("reset");
                    $('#exampleModal1').modal('hide')
                } else {
                    $("#alert").html('<div class="alert alert-danger"><button type="button" class="close">×</button>' + message + '</div>');
                }

                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            }
        })
    })
    $(document).on('submit', '#form_update', function(e) {
        e.preventDefault()
        var data = new FormData(this)
        $.ajax({
            url: '<?php echo base_url() ?>faq/update',
            method: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(this),
            success: function(response) {
                var type = JSON.parse(response).type
                var message = JSON.parse(response).message
                if (type == 'success') {
                    $('#tb_faq').DataTable().ajax.reload(null, false)
                    initMinimize()
                    $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
                    $("form").trigger("reset");
                    $('#editModal').modal('hide')
                } else {
                    $("#alert").html('<div class="alert alert-danger"><button type="button" class="close">×</button>' + message + '</div>');
                }

                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            }
        })
    })

    function edit(data) {
        $("form").trigger("reset");
        for (const key in data) {
            $(`[name="${key}"]`).val(data[key])
        }
    }

    function hapus(id) {
        $.post(`<?= base_url() ?>faq/delete/` + id).done((res) => {
            let resp = JSON.parse(res)
            if (resp.type == 'success') {
                $('#tb_faq').DataTable().ajax.reload(null, false)
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

    let data = function get() {
        let d = []
        $.ajax({
            'async': false,
            'type': "GET",
            'url': `<?= base_url('faq/get') ?>`,
            'success': res => {
                let result = JSON.parse(res)
                if (result.status) {
                    d = result.data
                } else {
                    d = []
                }
            }
        })
        return d;
    }()

    function setHTML(data) {
        let html = ``
        data.map(item => {
            html += `<div class="col-md-6">
                    <div class="panel panel-colorful" style="background-color:#fff">
                        <div class="panel-body" style="padding-bottom:0">
                            <h4 class="push-down-0">${item.faq_name}</h4>
                        </div>
                        <div class="panel-body faq" style="padding-top:0">`;
            item.qna.map(i => {
                html += `    <div class="faq-item">
                                <div class="faq-title"><span class="fa fa-angle-down"></span>${i.question}</div>
                                <div class="faq-text">
                                    <h5>${i.question}</h5>
                                    <p>${i.answer}</p>
                                </div>
                            </div>`
            })
            html += `     </div>
                    </div>
                </div>
                `
        })
        $('.w-faq').html(html)
    }
    setHTML(data)

    $("#faqSearchKeyword").keyup(function() {
        let filtredData = data.filter(item =>
            item.qna.some(y =>
                Object.keys(y).some(z =>
                    y[z] && y[z].toString()
                    .toLowerCase()
                    .includes($(this).val().toString().toLowerCase())
                )
            )
        );
        setHTML(filtredData)
    })
    $('.add').on('click', add);
    var wrapper = $(".w-qna"); //Input fields wrapper
    function add() {
        $(wrapper).append(`<tr class="t-qna">
            <td><textarea  class="form-control" rows="3" name="question[]"></textarea></td>
            <td><textarea name="answer[]" id="" rows="3" class="form-control"></textarea></td>
            <td><input type="number" class="form-control" name="sequence_to[]" /></td>
            <td align="center"><a  class=" badge badge-danger remove" style="margin-bottom: 10px;cursor:pointer;color:white">Remove</a></td>
            </tr>`);
    }
    $(wrapper).on("click", ".remove", function(e) {
        e.preventDefault();
        $(this).parent().parent('tr').remove();
    });
</script>