<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

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
        $('#tb_b2b').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('register_b2b/ajax') ?>'
        });
    });
    $('#profileUp').change(function(evt) {
        var tgt = evt.target || window.event.srcElement,
            files = tgt.files;

        // FileReader support
        if (FileReader && files && files.length) {
            var fr = new FileReader();
            fr.onload = function() {
                document.getElementById('profimg').src = fr.result;
            }
            fr.readAsDataURL(files[0]);
        }

    })
    $('#eprofileUp').change(function(evt) {
        var tgt = evt.target || window.event.srcElement,
            files = tgt.files;

        console.log('asd');

        // FileReader support
        if (FileReader && files && files.length) {
            var fr = new FileReader();
            fr.onload = function() {
                document.getElementById('eprofimg').src = fr.result;
            }
            fr.readAsDataURL(files[0]);
        }

    })
    var bf = 0;
    $('.level').change(function() {
        var arr = ['', '', 'cabang', 'project']
        if ($(this).val() == 1) {
            $('.cabang').addClass('d-none')
            $('.project').addClass('d-none')
        }
        if (arr[$(this).val()]) {
            $(`.${arr[$(this).val()]}`).removeClass('d-none')
        }
        if (bf != 0) {
            if (arr[bf]) {
                $(`.${arr[bf]}`).addClass('d-none')
            }
        }
        bf = $(this).val()
    })

    function edit(row) {
        console.log(row, 'row');
        $("form").trigger("reset")
        $('form .form-group p').remove()

        if (row.status_schedule === "true") {
            $(`input[name='status_schedule']`).prop("checked", true)
            //console.log("checked");
        }else{
            $(`input[name='status_schedule']`).prop("checked", false)
            //console.log("unchecked");
        }

        document.getElementById('eid').value = row.id_
        $('input[name=etitle_nm]').val(row.b);
        $('textarea[name=ealamat]').val(row.d);
        $('textarea[name=edomain]').val(row.domain);
        $('input[name=ephone]').val(row.e);
        var arr = ['', '', 'cabang', 'project']
        if (row.level == 1) {
            $('.cabang').addClass('d-none')
            $('.project').addClass('d-none')
        }
        if (arr[row.level]) {
            $(`.${arr[row.level]}`).removeClass('d-none')
        }
        if (bf != 0) {
            if (arr[bf]) {
                $(`.${arr[bf]}`).addClass('d-none')
            }
        }
        hf = row.hidden_feature ? JSON.parse(row.hidden_feature) : []
        $("input[name='hidden_feature']").val(hf)
        hf && hf.map(e => {
            if ($(`input[name='feature[]'][value='${e}']`).val() == e) {
                $(`input[name='feature[]'][value='${e}']`).prop("checked", false)
            }
        })
        if (row.path_logo == null || row.path_logo == "") {
            $('#eprofimg').attr('src', '<?= base_url('assets/apps/images/') ?>blanklogo.png')
        } else {
            $('#eprofimg').attr('src', '<?= base_url('assets/apps/images/') ?>' + row.path_logo)
            $('#eprofimg').on('error',function(params) {
                $('#eprofimg').attr('src', row.path_logo_ref)
            })
        }

        $("[name'elevel']").val(row.level)
        $("[name'epusat']").val(row.parent_id)
        $("[name'ecabang']").val(row.parent_id)
    }
    Array.prototype.remove = function() {
        var what, a = arguments,
            L = a.length,
            ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };
    $("input[name='feature[]']").click(function() {
        let o = $("input[name='hidden_feature']").val() ? $("input[name='hidden_feature']").val().split(",") : []
        let hidden_feature = o
        if ($(this).is(":checked")) {
            hidden_feature.remove($(this).val())
        } else {
            hidden_feature.push($(this).val())
        }
        $("input[name='hidden_feature']").val(hidden_feature.filter((v) => v != null).filter((v, i, a) => a.indexOf(v) === i))
        console.log($("input[name='hidden_feature']").val(), 'hidden_feature');

    })


    function hapus(id) {
        document.getElementById('hid').value = id
        $('.btn').on('click', function() {
            $(this).addClass('btns');
            var temp = document.getElementsByClassName('btns')[0].offsetParent.parentElement.getElementsByTagName('td')
            var dt = temp[0].innerHTML;
            $("#deltitle").html("Delete " + dt + " B2B?")
            // $('input[name=ecluster_name]').val(cluster);
            $(this).removeClass('btns');
        })
    }
</script>