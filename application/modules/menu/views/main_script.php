

<link href="<?=base_url('assets/apps/assets/plugins/select2/dist/css/select2.min.css')?>" rel="stylesheet">
<link href="<?=base_url('assets/apps/assets/plugins/select2-bootstrap4/dist/select2-bootstrap4.min.css')?>" rel="stylesheet">
<script src="<?=base_url('assets/apps/assets/plugins/nestable/jquery.nestable.js?_=').time()?>"></script>
<link href="<?= base_url('assets/apps/assets/plugins/sweetalert/sweetalert.css') ?>" rel="stylesheet">

<script src="<?= base_url('assets/apps/assets/plugins/sweetalert/sweetalert.min.js') ?>"></script>
<!-- Third Party Scripts(used by this page)-->
<script src="<?=base_url('assets/apps/assets/plugins/nestable/jquery.nestable.js?_=').time()?>"></script>
<script src="<?=base_url('assets/apps/assets/plugins/select2/dist/js/select2.min.js?_=').time()?>"></script>
<!--Page Active Scripts(used by this page)-->
<script src="<?=base_url('assets/apps/assets/plugins/nestable/nestable-list.active.js?_=').time()?>"></script>
<script>
    $(document).ready(function () {
        'use strict';
        $(".basic-single").select2();
        // activate Nestable for list 2
        fetch_menu('#is_main_menu')
        fetch_menu('#is_main_menu_edit')
        show_menu()

        $('.action_edit').click(function(){
            let id = $(this).data('id')
            $.get("menu/ajax?_id=" + id, function ( res ) {
                let data = JSON.parse(res)
                $('input[name=eid]').attr("value", data.id)
                $('input[name=judul_menu_edit]').attr("value", data.judul_menu)
                $('select[name=is_main_menu_edit]').val(data.is_main_menu); // Change the value or make some change to the internal state
                $('select[name=is_main_menu_edit]').trigger('change.select2'); // Notify only Select2 of changes
                $('input[name=link_edit]').attr("value", data.link)
                $('input[name=icon_edit]').attr("value", data.icon)
                $('input[name=modul_code_edit]').attr("value", data.modul_code)
                $('#secondaryModal').modal();
            })
        })
        $('#primaryModal').on('hidden.bs.modal', function (e) {
            $('input[name=eid]').attr("value", "")
            $('input[name=judul_menu]').attr("value", "")
            $('select[name=is_main_menu]').val(0); // Change the value or make some change to the internal state
            $('select[name=is_main_menu]').trigger('change.select2'); // Notify only Select2 of changes
            $('input[name=link]').attr("value", "")
            $('input[name=icon]').attr("value", "")
            $('input[name=modul_code]').attr("value", "")
        })
        $('#form_add').on('submit', function (e){
            e.preventDefault();
            let form = new FormData(document.getElementById('form_add'));
            $.ajax({
                url: '<?= base_url('menu/add') ?>',
                method: 'POST',
                data: form,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    swal({
                        title: "Success",
                        text: "Data has been saved!",
                        type: "success"
                    }, function (){
                        location.reload();
                    }) 
                }
            })
        });
        $('#form_edit').on('submit', function (e){
            e.preventDefault();
            let form = new FormData(document.getElementById('form_edit'));
            $.ajax({
                url: '<?= base_url('menu/edit') ?>',
                method: 'POST',
                data: form,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    swal({
                        title: "Success",
                        text: "Data has been updated!",
                        type: "success"
                    }, function (){
                        location.reload();
                    }) 
                }
            })
        });
        $('input[type=checkbox]').click(function(e){
            $('#floatGroup').attr("style", "display:block;")
        });
    });
    function hapus_data(id) {
        $("#hid").attr("value", id)
    }
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    function fetch_data_user_role() {
        $.get('menu/user_role', function( res ){
            let data = JSON.parse(res)
            sessionStorage.setItem('data_user_role', res);
        })
    }

    function handlerCancel() {
        let temp = sessionStorage.getItem('data_user_role')
        let data = JSON.parse(temp);
        let it = $('#user_role_select').val();
        let idx = data.findIndex(x => x.id === it);
        let tables = JSON.parse(data[idx].table_id)

        $(`input[type=checkbox]`).prop('checked', false)
        tables.id.forEach(element => {
            $(`#menu_${element}`).prop('checked', true).trigger('change')
        });

        $('.floatButton').attr("style", "display: none;")
    }
    function handlerCancelList() {

        let str = `<?= $menu ?>`;
        $('#nestable').html(str).nestable({
            group: 1,
            maxDepth: 2,
        })
        $('#user_role_select').trigger('change')
        $('.floatButton').attr("style", "display: none;")
        $('input[type=checkbox]').click(function(e){
            $('#floatGroup').attr("style", "display:block;")
        });
    }

    function handlerSave() {
        let tables = $('input[type=checkbox][name="menu[]"]').serializeArray()
        let table_id = tables.map(x => x.value)
        let id_role = $('#user_role_select').val();
        $.post( "menu/set_permission", { tables: table_id, 'id_role' : id_role } )
        .done(function (res) {
            fetch_data_user_role();
            window.location.reload()
        });
    }
    function handlerSaveList() {
        let source = $('#nestable').nestable('serialize')
        let data = setSequence(source, 0, [])
        $.post( "menu/set_menu_sequence", { data: data} )
        .done(function (res) {
            // console.log(res);
            window.location.reload()
        });
    }

    function setSequence(data, parent, result) {
        data.forEach((element, i) => {
            var menu = {
                id: element.id,
                is_main_menu: parent,
                sequence: i
            };
            result.push(menu)
            if (element.children != undefined) {
                setSequence(element.children, element.id, result)
            }
        });
        return result;
    }

    function fetch_menu(id_element = "#is_main_menu") {
        $.get('menu/ajax_menu', function( res ){
            let data = JSON.parse(res)
            $(id_element).html(``)
            $(id_element).append(`<option value="0">No Parent</option>`)
            data.forEach(element => {
                $(id_element).append(`<option value="${element.id}">${capitalizeFirstLetter(element.judul_menu)}</option>`)
            });
        })
    }

    function show_menu() {
        $.get('menu/user_role', function( res ){
            sessionStorage.setItem('data_user_role', res);
            let data = JSON.parse(res)
            data.forEach(element => {
                $('#user_role_select').append(`<option value="${element.id}">${capitalizeFirstLetter(element.roles_type)}</option>`)
            });
            $('#user_role_select').on('change', function (params) {
                $('#floatGroup').attr("style", "display:none;")
                let temp = sessionStorage.getItem('data_user_role')
                let data = JSON.parse(temp);
                let it = $(this)
                let idx = data.findIndex(x => x.id === it.val());
                let tables = JSON.parse(data[idx].table_id)
                $(`input[type=checkbox]`).prop('checked', false)
                tables.id.forEach(element => {
                    $(`#menu_${element}`).prop('checked', true).trigger('change')
                });
            })
            $('#user_role_select').trigger('change');
        })
    }
</script>