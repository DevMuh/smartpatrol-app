<link href="<?= base_url('assets/apps/assets/plugins/jquery-transfer/css/jquery.transfer.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/apps/assets/plugins/jquery-transfer/icon_font/css/icon_font.css') ?>" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<link href="<?= base_url('assets/apps/assets/plugins/sweetalert/sweetalert.css') ?>" rel="stylesheet">

<script src="<?= base_url('assets/apps/assets/plugins/sweetalert/sweetalert.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/jquery-transfer/js/jquery.transfer.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>

<link href="<?=base_url('assets/apps/assets/plugins/select2/dist/css/select2.min.css')?>" rel="stylesheet">
<link href="<?=base_url('assets/apps/assets/plugins/select2-bootstrap4/dist/select2-bootstrap4.min.css')?>" rel="stylesheet">
<!-- Third Party Scripts(used by this page)-->
<script src="<?=base_url('assets/apps/assets/plugins/select2/dist/js/select2.min.js')?>"></script>


<script>
    // var socket = io("<?= $this->config->item('base_url_socket') ?>", {
    //     transports: ["websocket"],
    //     query: {
    //         username: "web_<?= $this->session->userdata("id") ?>",
    //     },
    //     reconnect: true
    // });
    // socket.emit('join', "<?= $this->session->userdata("b2b_token") ?>");
    // socket.emit('join', "<?= $this->session->userdata("regu") ?>");

    let urlGetOptionSubB2bToken = "<?php echo $this->config->item('base_url_api_go'); ?>api/select-option/data-sub-b2btoken/<?= $this->session->userdata("b2b_token") ?>"
    // console.log(urlGetOptionSubB2bToken)
    // let urlGetOptionSubB2bToken = '<?= base_url('register_b2b/subB2B') ?>'
    let urlGeneratePin = "<?php echo $this->config->item('base_url_api_go'); ?>api/generate-pin"


    // $('#1Approval').addClass('mm-active');
    $('#1Users').addClass('mm-active');
    $('#subm_Master').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Users').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });

    $(document).ready(function() {
        $(".basic-single").select2();
        $("nav.sidebar.sidebar-bunker").addClass("active")
        $("nav.navbar-custom-menu").addClass("active")
        $("#sidebarCollapse").addClass("open")
        var table = $('#tb_users').DataTable({
            order: [
                [0, 'DESC']
            ],
            columnDefs: [{
                "targets": [0],
                // "visible": false,
                "searchable": false
            }],
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('users/ajax') ?>'
        });

        getSelectSubB2btoken()
    });

    function getSelectSubB2btoken() {
        $.ajax({
          url: urlGetOptionSubB2bToken,
          type: "GET",
          success: function (result) {
            let response = result.data
            var html_ = ``
            $.each(response,function(e,res) {
              html_+=`
                <option value="${res.b2b_token}">${res.title_nm}</option>
              `
              $('#user_sub_org').append(html_)
              $('#edituser_sub_org').append(html_)
            })
          }
        })
    }

    $('#tbbtn').click(function() {
        $('input').val('')
    })

    $(document).on('submit', '#addUsers', function(e) {
        e.preventDefault()
        var data = new FormData(this)
        $.ajax({
            url: '<?php echo base_url() ?>users/add',
            method: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(this),
            success: function(response) {
                var type = JSON.parse(response).type
                var message = JSON.parse(response).message
                if (type == 'success') {
                    $('#tb_users').DataTable().ajax.reload(null, false)
                    $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
                    $(".addUsers").trigger("reset");
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

    function changeAc(id) {
        $('#changeActive').modal('show');
        $('#usrid').val(id);
    }

    function getConf(param) {
        $.ajax({
            url: '<?= base_url('users/conf') ?>',
            method: 'POST',
            data: {
                act: param
            },
            dataType: 'json',
            success: function(resp) {
                if (param == 1) {
                    var a = 'Projek'
                } else if (param == 2) {
                    var a = 'Cabang'
                } else {
                    var a = ''
                }
                $('#config').html('<option>Pilih ' + a + '</option>')
                for (i = 0; i < resp.length; i++) {
                    $('#config').append('<option value="' + resp[i].id + '">' + resp[i].nama + '</option>')
                }
            }
        })
    }

    function updateCo() {
        var id = $('#usrid').val()
        var par = $('#config').val()
        var mode = $('#modee').val()
        $.ajax({
            url: '<?= base_url('users/upconf') ?>',
            method: 'POST',
            data: {
                id: id,
                par: par,
                mode: mode
            },
            dataType: 'json',
            success: function(resp) {
                if (resp == 1) {
                    location.reload();
                } else {
                    alert('Error');
                }
            }
        })
    }

    function changeStatus(id) {
        $.ajax({
            url: '<?= base_url('users/changeStatus?id=') ?>' + id,
            method: 'POST',
            cache: false,
            processData: false,
            success: function(response) {
                $('#tb_users').DataTable().ajax.reload(null, false)
            }
        })
    }



    function edit(id) {
        $('.update').attr("onclick", "update(" + id + ", event)")
        $("#formUpdate").trigger("reset")
        $.ajax({
            url: '<?= base_url('users/get_by_id?id=') ?>' + id,
            method: 'GET',
            success: function(response) {
                let res = JSON.parse(response)
                let res_otherdata = JSON.parse(res.other_data)
                var username = res.username
                var fullname = res.full_name
                var no_tlp = res.no_tlp
                var userRule = res.user_roles
                var b2b = res.b2b_token
                var idb2btoken = res.b2b_token
                if (res_otherdata.b2b_token_previous == undefined || res_otherdata.b2b_token_previous == "") {
                    $('#edituser_sub_org').val($("#edituser_sub_org option:first").val());
                }else{
                    $('#edituser_sub_org').val(idb2btoken)

                }
                console.log("as",idb2btoken)
                $('#editModal').modal('show')
                $('#editUsername').val(username)
                $('#editpayroll_id').val(res.payroll_id)
                $('#editFullname').val(fullname)
                $('#editb2bhidden').val(b2b);
                $('#editb2b').val(b2b);
                $('#editb2b').trigger('change');
                $('#no_tlp').val(no_tlp)
                $('#roles').val(userRule.toLowerCase()).change()

                var otherData = JSON.parse(res.other_data)
                if (!!otherData && !!otherData.position) $('#editposition').val(otherData.position)
            }
        })
    }

    function update(id, e) {
        e.preventDefault()

        let form = new FormData(document.getElementById('formUpdate'));

        if (form.get('editb2bhidden') != form.get('b2b')) {
            swal({
                title: "Ubah B2B ?",
                text: "Kemungkinan data menghilang dari daftar ini!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Ubah!",
                closeOnConfirm: false
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        url: '<?= base_url('users/update?id=') ?>' + id,
                        method: 'POST',
                        data: form,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(response) {
                            var type = JSON.parse(response).type    
                            var message = JSON.parse(response).message
                            if (type == 'success') {
                                swal.close()
                                $('#tb_users').DataTable().ajax.reload(null, false)
                                $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
                                $('#editModal').modal('hide')
                            } else {
                                $(".w-alert").html('<div class="alert alert-danger"><button type="button" class="close">×</button>' + message + '</div>');
                            }
                        }
                    })
                }
            });
        } else {
            $.ajax({
                        url: '<?= base_url('users/update?id=') ?>' + id,
                        method: 'POST',
                        data: form,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(response) {
                            var type = JSON.parse(response).type    
                            var message = JSON.parse(response).message
                            if (type == 'success') {
                                $('#tb_users').DataTable().ajax.reload(null, false)
                                $("#alertSuccess").html('<div class="alert alert-success"><button type="button" class="close">×</button>' + message + '</div>');
                                $('#editModal').modal('hide')
                            } else {
                                $(".w-alert").html('<div class="alert alert-danger"><button type="button" class="close">×</button>' + message + '</div>');
                            }
                        }
                    })
        }
    }

    function deleteConfirm(url, name) {
        $('#modalDelete').modal('show');
        $('#messageDelete').html('Are you sure want to delete data <b>' + name + '</b>?')
        $('#hapus').attr('href', url);
    }

    // close alert
    $(".alert").fadeTo(5000, 500).slideUp(500, function() {
        $(".alert").slideUp(500);
    });

    var marker;
    var latlang;

    function mapclick(map) {
        return (new google.maps.event.addListener(map, 'click', function(event) {
            var myLatLng = {
                lat: event.latLng.lat(),
                lng: event.latLng.lng()
            }
            console.log(myLatLng);

            if (marker != null) marker.setMap(null)
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: 'GeoTag',
            });
            latlang = myLatLng
        }))
    }

    function myMap(center) {

        if (center == null) {
            var mapProp = {
                center: {
                    lat: -0.789275,
                    lng: 113.9213257
                },
                zoom: 5
            }
        } else {
            var mapProp = {
                center: {
                    lat: center["lat"],
                    lng: center["lng"]
                },
                zoom: 17
            }
        }
        var map = new google.maps.Map(document.getElementById("map"), mapProp);
        if (center != null) {
            marker = new google.maps.Marker({
                position: center,
                map: map,
                title: 'GeoTag',
            });
        }

        var mark = mapclick(map)
    }
    var idi;

    function geoTag(param, lat = 0, lng = 0, radius = 0) {
        idi = param
        $('#geomap').modal('show');
        if (lat != 0 || lng != 0 || radius != 0) {
            myMap({
                lat: lat,
                lng: lng
            })
            latlang = {
                lat: lat,
                lng: lng
            }
            $('#maprad').val(radius)
        }

    }

    function logout_manual(id) {
        $('#modalLogout').modal('show');
        $('#logout').click(function() {
            $(this).html("Loading...")
            $.post(`<?= base_url('users/remove_device_info') ?>/` + id)
                .done(resp => {
                    let res = JSON.parse(resp)
                    if (res.status) {
                        toastr["success"](res.message)
                        $('#modalLogout').modal('hide');
                        $(this).html("Logout")
                        $('#tb_users').DataTable().ajax.reload(null, false)
                        var data = {
                            type_notif: "logout",
                            room_id: res.data.b2b_token,
                            to: res.data.id,
                            ...res.data
                        }
                        $.ajax({
                            url: `<?= $this->config->item('base_url_socket') ?>fcm/send`,
                            type: "POST",
                            data: JSON.stringify({
                                topic: data.room_id,
                                notification: {
                                    title: "Oops!",
                                    body: "anda terlogout manual dari web oleh admin"
                                },
                                data: {
                                    data: JSON.stringify(data)
                                }
                            }),
                            contentType: "application/json; charset=utf-8",
                            dataType: "json"
                        })
                    } else {
                        toastr["danger"](res.message)
                    }
                })
                .fail(e => {
                    $(this).html("Logout")
                    toastr["danger"]("Failed Logout Mannualy!")

                })
        });
    }

    function saveTag() {
        $.ajax({
            url: '<?= base_url('users/savetag') ?>',
            type: 'post',
            data: {
                id: idi,
                loc: latlang,
                radius: $('#maprad').val()
            },
            success: function(data) {
                location.reload()

            }
        })
    }

    function visitModal(id) {
        $('#visitModal').modal('show');
        $('.visitModalBody').html('<div>Loading ...</div>');
        let listb2b = <?= json_encode($listb2b) ?>;
        let b2b = "<?= $this->session->userdata('b2b_token') ?>"
        $.getJSON(`<?= base_url("users/get_visit") ?>/${id}`, visit => {
            var setting = {
                dataArray: [],
                itemName: "title_nm",
                tabNameText: "List Organization",
                rightTabNameText: "Selected Organization",
                valueName: "b2b_token",
                callable: function(items) {
                    $.post(`<?= base_url("users/set_visit") ?>`, {
                        visitb2b: items.map(e => e.b2b_token).filter(f => f !== b2b),
                        user_id: id
                    }).done(() => {
                        toastr["success"]("Success Set visit")
                    }).fail(err => {
                        toastr["danger"]("Failed Set visit")
                    })
                }
            };
            setting.dataArray = listb2b.map(e => {
                return {
                    ...e,
                    selected: visit && visit.includes(e.b2b_token),
                    disabled: e.b2b_token == b2b
                }
            })
            console.log(setting.dataArray, 'setting.dataArray');
            $('.visitModalBody').html('<div id="tf_assign"></div>');
            $("#tf_assign").transfer(setting);
        })
    }

    function generatePin(id) {
        console.log("Generate Pin ", id);

        var formData = new FormData();
        formData.set("user_id", id)

        var url = urlGeneratePin;

        var loader = `<span class="fa fa-spinner fa-spin"></span>&nbsp;`
        let buttons = $('#btnGenPin'+id)
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
                    
                    let buttons = $('#btnGenPin'+id)
                    $(buttons).prop('disabled', false)
                    $(buttons).html("Save")

                    window.location.reload()
                }else{
                    swal("Upss!!!", resp.message, "warning")
                    
                    var loader = `<span class="fa fa-spinner fa-spin"></span>&nbsp;`
                    let buttons = $('#btnGenPin'+id)
                    $(buttons).prop('disabled', true)
                    $(buttons).html(loader)
                }                
            },
            beforeSend: function(){
                var loader = `<span class="fa fa-spinner fa-spin"></span>&nbsp;`
                let buttons = $('#btnGenPin'+id)
                $(buttons).prop('disabled', true)
                $(buttons).html(loader)
            },
            error: function (resp) {
                swal("Upss!!!", resp.message, "error")

                var loader = `<span class="fa fa-spinner fa-spin"></span>&nbsp;`
                let buttons = $('#btnGenPin'+id)
                $(buttons).prop('disabled', true)
                $(buttons).html(loader)
            }
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmtZNz9aMpD-tDGdjX_ZmvkdCLe8orp7U&callback=myMap"></script>