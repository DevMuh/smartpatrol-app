<link href="<?= base_url('assets/apps/assets/plugins/select2/dist/css/select2.min.css') ?>" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.css" rel="stylesheet">
<link href="<?= base_url('assets/apps/assets/plugins/jquery-transfer/css/jquery.transfer.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/apps/assets/plugins/jquery-transfer/icon_font/css/icon_font.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/apps/assets/plugins/select2-bootstrap4/dist/select2-bootstrap4.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/apps/assets/plugins/bootstrap4-toggle/css/bootstrap4-toggle.min.css') ?>" rel="stylesheet">
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/select2/dist/js/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/bootstrap4-toggle/js/bootstrap4-toggle.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/jquery-transfer/js/jquery.transfer.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>


</script>
<script src="<?= base_url('assets/apps/assets/plugins/openlayers/ol.js') ?>"></script>
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
    $('#1Route').addClass('mm-active');
    $('#subm_Master').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Route').css({
        'color': '#c81b1b',
        'border-radius': '4px',
        'background': 'white'
    });
    // $('#subm').css({'box-shadow': 'none', 'background' : 'transparent', 'color': 'white'});
    // $('#Client_List').css({'color': '#c81b1b', 'border-radius':'4px', 'background': 'white'});
    function setDayInput($this) {
        if ($this.children('input').val() == '') {
            $this.children('input').attr('disabled', false);
            $this.children('input').val($this.children('span').html());
            $this.addClass('dok');
        } else {
            $this.children('input').attr('disabled', true);
            $this.children('input').val('');
            $this.removeClass('dok');
        }
    }

    function resetDayInput() {
        $("input[name='day[]']").attr("disabled", false)
        $("input[name='day[]']").val("")
        $(".daycard").removeClass('dok');
    }
    $("select[name='schedule_type']").change(function() {
        resetDayInput()
        if ($(this).val() == "daily" || $(this).val() == "weekly") {
            $(".w-daycard").show()
            $('.w-jam-mulai').show()
        } else {
            $(".w-daycard").hide()
            $('.w-jam-mulai').hide()
        }
        if ($(this).val() == "daily") {
            for (i = 0; i < 7; i++) {
                $(`.daycard:eq(${i})`).children('input').attr('disabled', false);
                $(`.daycard:eq(${i})`).children('input').val($(`.daycard:eq(${i})`).children('span').html());
                $(`.daycard:eq(${i})`).addClass('dok');
            }
        } else {

            for (i = 0; i < 1; i++) {
                $(`.daycard:eq(${i})`).children('input').attr('disabled', false);
                $(`.daycard:eq(${i})`).children('input').val($(`.daycard:eq(${i})`).children('span').html());
                $(`.daycard:eq(${i})`).addClass('dok');
            }
        }
    })
    $('.daycard').click(function() {
        let schedule_type = $("select[name='schedule_type']").val()
        if (schedule_type == "daily") {
            setDayInput($(this))
        } else {
            resetDayInput()
            setDayInput($(this))
        }
        let day_selected = []
        $("input[name='day[]']").each(function() {
            day_selected.push($(this).val());
        });
        day_selected = day_selected.filter((el) => el);
        console.log(day_selected, 'day_selected');

    })

    const toTitleCase = (phrase) => {
        return phrase
            .toLowerCase()
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    };

    function preview() {
        var jam = $('input[name=jam_mulai]').val().substr(0, 2)
        var menit = $('input[name=jam_mulai]').val().substr(3)
        var max = $('#jml_kirim').val()
        var int = $('#interval').val()
        var view;
        $('#body_prev').html('')
        for (i = 1; i <= max; i++) {

            if (i == 1) {
                $('#body_prev').append(`<tr><td>1</td><td>${$('input[name=jam_mulai]').val().substr(0, 2)+':'+menit}</td><td><button type="button" class="btn mybt"><span class="typcn typcn-tick"></span></button></td></tr>`);
            } else {
                jam = Number(jam) + Number(int);
                if (jam == 24) {
                    jam = 23;
                    menit = 59;
                } else if (jam > 24) {
                    break;
                }
                if (jam.toString().length == 1) {
                    view = '0' + jam + ':' + menit
                } else {
                    view = jam + ':' + menit
                }
                $('#body_prev').append(`<tr><td>${i}</td><td>${view}</td><td><button type="button" class="btn mybt"><span class="typcn typcn-tick"></span></button></td></tr>`);
            }
        }
    }

    function ochg(ini) {
        $('#jml_kirim').html('')
        var jam = $('input[name=jam_mulai]').val().substr(0, 2)
        if ($(ini).val() >= 1) {
            for (i = 1; i <= 24 / $(ini).val(); i++) {
                jam = Number(jam) + Number($(ini).val());
                if (Number(jam) >= 24) break;
                $('#jml_kirim').append(`<option>${i}</option>`)
            }
        }
        preview()
    }
    $('#interval').change(function() {
        ochg(this)
        preview()
    })
    $('input[name=jam_mulai]').change(function() {
        ochg($('#interval'))
        preview()
    })
    $('#jml_kirim').change(function() {
        preview()
    })

    function setScheduleType(params) {
        let opt_data = ['weekly', 'daily', 'monthly', 'annual']
        let opt_html
        opt_data.map(el => opt_html += `<option ${params == el ? 'selected' : ''} value="${el}">${toTitleCase(el)}</option>`)
        $("select[name='schedule_type']").html(opt_html)
        if (params == "weekly" || params == "daily") {
            $(".w-daycard").show()
            $('.w-jam-mulai').show()
        } else {
            $(".w-daycard").hide()
            $('.w-jam-mulai').hide()
        }
        if (params == "daily") {
            for (i = 0; i < 7; i++) {
                $(`.daycard:eq(${i})`).children('input').attr('disabled', false);
                $(`.daycard:eq(${i})`).children('input').val($(`.daycard:eq(${i})`).children('span').html());
                $(`.daycard:eq(${i})`).addClass('dok');
            }
        } else {

            for (i = 0; i < 1; i++) {
                $(`.daycard:eq(${i})`).children('input').attr('disabled', false);
                $(`.daycard:eq(${i})`).children('input').val($(`.daycard:eq(${i})`).children('span').html());
                $(`.daycard:eq(${i})`).addClass('dok');
            }
        }
    }

    function resetprev(param) {
        console.log(param);
        let id = param.id_route
        let data = JSON.parse(param.otherdata)
        setScheduleType(data.schedule_type)
        $('form .form-group p').remove()
        $('.daycard').children('input').attr('disabled', true)
        $('.daycard').children('input').val('')
        $('.daycard').removeClass('dok')
        $('#interval').val('')
        $('#title_modal_schedule').html("Cluster: " + param.cluster_name)
        $('#jml_kirim').html('')
        $('input[name=jam_mulai]').val('')
        $('#body_prev').html('')
        $('#scid').val(id);
        if (data != null) {
            $('input[name=jam_mulai]').val(data.jam_mulai)
            var j = 0;
            for (i = 0; i < 7; i++) {
                if ($(`.daycard:eq(${i})`).children('span').html() == data.day[j]) {
                    $(`.daycard:eq(${i})`).children('input').attr('disabled', false);
                    $(`.daycard:eq(${i})`).children('input').val($(`.daycard:eq(${i})`).children('span').html());
                    $(`.daycard:eq(${i})`).addClass('dok');
                    j++;
                }
            }
            // var mulai = data.hours[0]
            // var jam = mulai.substr(0, 2)
            // var interval = 0
            // if (data.hours.length == 1) {
            //     interval = 1
            // } else {
            //     interval = Number(data.hours[1].substr(0, 2)) - Number(mulai.substr(0, 2))
            // }
            // $('input[name=jam_mulai]').val(mulai)
            // $('#interval').val(interval)
            // for (i = 1; i <= 24 / interval; i++) {

            //     jam = Number(jam) + Number(interval);
            //     if (i == Number(data.hours.length)) {
            //         $('#jml_kirim').append(`<option selected>${i}</option>`)

            //     } else {
            //         $('#jml_kirim').append(`<option>${i}</option>`)
            //     }
            //     if (jam >= 24) break;
            // }
            // preview()

        }
    }
    var table = $('#tb_client').DataTable({
        responsive: true,
        autoWidth: false,
        processing: true,
        serverside: true,
        ajax: '<?= base_url('register_route/ajax') ?>',
        initComplete: function() {
            var temp = $("#tb_client_info").html()
            var start = temp.indexOf('of ') + 3;
            var end = temp.indexOf(' entries');
            var total = temp.substring(start, end)
            $("#total").html('<i style="font-size: 25px" class="typcn typcn-map"></i> Total Route : <b>' + total + '</b>');
        }
    });



    $(".basic-multiple").select2();
    $(".placeholder-multiple").select2({
        placeholder: "Select route"
    });


    function edit(id, group_id = "") {
        $('form .form-group p').remove()
        document.getElementById('editId').value = id
        var temp = event.currentTarget.parentElement.parentElement.children;
        console.log(temp);
        var a = temp[1].innerHTML;
        var b = temp[2].innerHTML;
        $('input[name=ecluster_name]').val(a);
        $('textarea[name=edescription]').val(b);
        $('select[name=group_id]').val(group_id);
    }

    function hapus(id) {
        document.getElementById('hid').value = id
        var temp = event.currentTarget.parentElement.parentElement.children;
        var a = temp[1].innerHTML;
        $("#deltitle").html("Delete " + a + " route?")
    }

    function hapusCheckpoint(cpName, cpId) {
        $('#remove_cp_button').data("id", cpId)
        $("#deltitleChecpointModal").html("Are you sure to remove " + cpName + " ?")
    }

    $(function() {
        $('#remove_cp_button').click(function() {
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>register_route/hapusChecpoint",
                data: {
                    id: $('#remove_cp_button').data('id')
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        $('#hapusChecpointModal').modal('hide')
                        getCheckpoint($('#remove_cp_button').data("cpid"))
                    }
                },
            });
        })
    })

    function updatecp(id) {
        document.getElementById('rid').value = id
    }

    function set_cp(id, geo) {
        $('#remove_cp_button').data("cpid", id)
        let area_lat_long = JSON.parse(geo.area_lat_long)
        if (!area_lat_long) {
            Swal.fire({
                title: 'Information!',
                text: "You haven't determined geofence!",
                type: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Set Geofence Now!',
                cancelButtonText: "Not Now",
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    // return new Promise(function(resolve) {
                    window.location = '<?= base_url("register_b2b/edit_geofence") ?>'
                    // });
                },
                allowOutsideClick: false
            });
        } else {
            route(id)
        }

    }

    function getCheckpoint(id) {
        $("#sopt").html('');
        $.ajax({
            type: "POST",
            url: "<?= base_url('register_route/ajaxcp') ?>",
            data: {
                idroute: id
            },
            dataType: "JSON",
            success: function(resp) {
                $("#opt").html('');
                $("#map").html('<div id="popup"></div>');
                var i = 1;
                let selected_cp = []
                resp.data.forEach(row => {
                    if (row.cluster_route == null) {
                        $("#opt").append("<option value='" + row.id + "'>" + row.cp_name + "</option>")
                    } else {
                        if (row.cluster_route == id) {
                            $("#sopt").append("<tr><td>" + i + "</td><td>" + row.cp_id + "</td><td>" + row.cp_name + "</td><td><button type='button' title='Hapus' data-toggle='modal' data-target='#hapusChecpointModal' onclick='hapusCheckpoint(\"" + row.cp_name + "\", \"" + row.id + "\")' class='btn mybt'><span class='typcn typcn-trash'></span></button></td></tr>")
                            i++;
                            selected_cp.push(row)
                        }
                    }
                });
                resp.selected_cp = selected_cp
                createmap(resp, id)
                $(".basic-multiple").select2();
                $(".placeholder-multiple").select2({
                    placeholder: "Select route"
                });
            }
        })
    }

    function route(id) {
        $('form .form-group p').remove()
        var cluster = ''
        updatecp(id);
        var temp = event.currentTarget.parentElement.parentElement.children;
        cluster = temp[1].innerHTML;
        document.getElementById('routetitle').innerHTML = cluster

        $.ajax({
            type: "POST",
            url: "<?= base_url('register_route/ajaxcp') ?>",
            data: {
                idroute: id
            },
            dataType: "JSON",
            success: function(resp) {
                $("#opt").html('');
                $("#sopt").html('');
                $("#map").html('<div id="popup"></div>');
                var i = 1;
                let selected_cp = []
                resp.data.forEach(row => {
                    if (row.cluster_route == null) {
                        $("#opt").append("<option value='" + row.id + "'>" + row.cp_name + "</option>")
                    } else {
                        if (row.cluster_route == id) {
                            $("#sopt").append("<tr><td>" + i + "</td><td>" + row.cp_id + "</td><td>" + row.cp_name + "</td><td><button type='button' title='Hapus' data-toggle='modal' data-target='#hapusChecpointModal' onclick='hapusCheckpoint(\"" + row.cp_name + "\", \"" + row.id + "\")' class='btn mybt'><span class='typcn typcn-trash'></span></button></td></tr>")
                            i++;
                            selected_cp.push(row)
                        }
                    }
                });
                resp.selected_cp = selected_cp
                createmap(resp, id)
                $(".basic-multiple").select2();
                $(".placeholder-multiple").select2({
                    placeholder: "Select route"
                });
            }
        })
    }

    function listUser(b2btoken, id, regu) {
        $('#sendModal').modal('show');
        $(".sendModalBody").html(`<select data-b2b='${b2btoken}' data-idrute='${id}' class="form-control" id="optUser"> </select>`)
        $.getJSON(`<?= base_url("users/get_by_regu") ?>?regu_id=${regu}`).then(res => {
            let optHtml = ''
            res.map(el => {
                optHtml += `<option value="${el.id}">${el.full_name}</option>`
            })
            $("#optUser").html(optHtml)
            $("#optUser").select2();
        })
        $("#optUser").select2();
    }




    function asignModal(b2btoken, id, regu) {
        $('#asignModal').modal('show');
        $('.asignModalBody').html('<div>Loading ...</div>');
        if (!regu) $('.asignModalBody').html('<div>Belum Ada Regu</div>')
        $.getJSON(`<?= base_url("users/get_by_regu") ?>?regu_id=${regu}`, res => {
            if (res.length == 0) {
                $('.asignModalBody').html('<h3>Regu Anda Belum ada anggota !</h3>');
            } else {
                $.getJSON(`<?= base_url("register_route/get_assign") ?>/${id}`, assign => {
                    var setting = {
                        dataArray: [],
                        itemName: "full_name",
                        tabNameText: "Group Member",
                        rightTabNameText: "Selected Member",
                        valueName: "id",
                        callable: function(items) {
                            $.post(`<?= base_url("register_route/set_assign") ?>`, {
                                user_id: items.map(e => e.id),
                                route_id: id
                            })
                        }
                    };
                    setting.dataArray = res.map(e => {
                        return {
                            ...e,
                            selected: assign && assign.includes(e.id)
                        }
                    })
                    $('.asignModalBody').html('<div id="tf_assign"></div>');
                    $("#tf_assign").transfer(setting);
                })
            }
        })

    }

    $(".js-submit-asign").click(function() {
        let user_id = $("#optUser").val()
        let idrute = $("#optUser").data('idrute')
        let b2btoken = $("#optUser").data('b2b')
        tomobile(b2btoken, idrute, user_id)
    })

    function tomobile(b2btoken, id, user_id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Task akan dikirim ke mobile",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?= base_url() ?>register_route/tomobile/" + b2btoken + '/' + id + '/' + user_id,
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses . . .',
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            }
                        })
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire(
                                'Sukses',
                                res.message,
                                'success'
                            )
                            var data = {
                                type_notif: "patrol",
                                room_id: res.data.regu_id,
                                to: user_id,
                                ...res.data
                            }
                            $.ajax({
                                url: `<?= $this->config->item('base_url_socket') ?>fcm/send`,
                                type: "POST",
                                data: JSON.stringify({
                                    topic: data.room_id,
                                    notification: {
                                        title: "Ada Patroli Baru!",
                                        body: ""
                                    },
                                    data: {
                                        data: JSON.stringify(data)
                                    }
                                }),
                                contentType: "application/json; charset=utf-8",
                                dataType: "json"
                            })
                            // socket.emit('send patrol', {
                            //     room_id: res.data.regu_id,
                            //     to: user_id,
                            //     ...res.data
                            // });
                        } else {
                            Swal.fire(
                                'Gagal',
                                res.message,
                                'error'
                            )
                        }
                    }
                })
            }
        })

    }

    function createmap(data, id) {
        var point = data.loc
        var addr = data.addr
        var poly = data.poly
        var selected_cp = data.selected_cp
        let selected_cp_long_lat = selected_cp.map(el => JSON.stringify([el.cp_long, el.cp_lat]))
        console.log(selected_cp_long_lat, 'selected_cp_long_lat');


        var element = document.getElementById('popup');
        var MAP = {
            myMap: null,
            layerVector: null,
            sourceVector: null,
            sourceVectorPoint: null,
            main: function() {
                this.sourceVector = new ol.source.Vector();
                if (point != undefined) {
                    this.createMarker(point, addr);
                } else {
                    this.createMarker([
                        [0, 0]
                    ], ['']);
                }
                this.createMap();
                this.createPolygon(poly);
            },

            createMap: function() {
                var popup = new ol.Overlay({
                    element: element,
                    positioning: 'bottom-center',
                    stopEvent: false,
                    offset: [0, -30]
                });

                this.myMap = new ol.Map({
                    target: 'map',
                    layers: [
                        new ol.layer.Tile({
                            source: new ol.source.OSM()
                        }),
                        this.layerVector,
                    ],
                    view: new ol.View({
                        center: ol.proj.fromLonLat(poly == undefined ? [106.665596, -6.320138] : poly[0][0]),
                        zoom: 15
                    })
                });
                this.myMap.addOverlay(popup);
                var map = this.myMap
                this.myMap.on('click', function(evt) {
                    var feature = map.forEachFeatureAtPixel(evt.pixel,
                        function(feature) {
                            return feature;
                        });
                    if (feature) {
                        var coordinates = feature.getGeometry().getCoordinates();
                        if (coordinates.length > 2) {
                            popup.setPosition(coordinates[0]);
                        } else {
                            popup.setPosition(coordinates);
                        }
                        $(element).popover('dispose');
                        $(element).popover({
                            placement: 'top',
                            html: true,
                            content: feature.get('name')
                        });
                        $(element).popover('show');
                    } else {
                        $(element).popover('dispose');
                    }
                });

                // change mouse cursor when over marker
                map.on('pointermove', function(e) {
                    if (e.dragging) {
                        $(element).popover('dispose');
                        return;
                    }
                });

            },

            createMarker: function(place, name = '') {
                var styleMarker;
                var marker = [];
                styleMarker = new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [0.5, 1],
                        scale: 0.05,
                        src: '<?= base_url() ?>assets/apps/assets/dist/img/incident/marker.png'
                    })
                })
                styleMarker2 = new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [0.5, 1],
                        scale: 0.05,
                        src: '<?= base_url() ?>assets/apps/assets/dist/img/incident/blue-marker.png'
                    })
                })
                for (var i = 0; i < place.length; i++) {
                    if (place[i][0] == "null") {
                        place[i][0] = "0"
                        place[i][1] = "0"
                    }
                    marker[i] = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.fromLonLat(place[i])),
                        name: name[i]
                    })
                    console.log(selected_cp_long_lat.includes(JSON.stringify(place[i])) ? place[i] : "styleMarker");

                    marker[i].setStyle(selected_cp_long_lat.includes(JSON.stringify(place[i])) ? styleMarker2 : styleMarker)
                }

                this.layerVector = new ol.layer.Vector({
                    source: this.sourceVector
                })
                this.sourceVector.addFeatures(marker);
            },

            createPolygon: function(points) {
                var geojsonObject;
                var color = 'red'
                var col = ol.color.asArray(color);
                col = col.slice();
                col[3] = 0.25;
                var styles = [
                    new ol.style.Style({
                        stroke: new ol.style.Stroke({
                            color: 'red',
                            width: 2
                        }),
                        fill: new ol.style.Fill({
                            color: 'blue'
                        })
                    })
                ]
                for (var i = 0; i < points.length; i++) {
                    var point = []
                    for (var j = 0; j < points[i].length; j++) {
                        point[j] = ol.proj.fromLonLat(points[i][j])
                    }
                    geojsonObject = {
                        'type': 'FeatureCollection',
                        'crs': {
                            'type': 'name',
                            'properties': {
                                'name': 'EPSG:4326'
                            }
                        },
                        'features': [{
                            'type': 'Feature',
                            'geometry': {
                                'type': 'Polygon',
                                'coordinates': [point]
                            }
                        }]
                    };

                    this.layerVector = new ol.layer.Vector({
                        source: this.sourceVector,
                        style: styles
                    })
                    this.sourceVector.addFeatures((new ol.format.GeoJSON()).readFeatures(geojsonObject))
                }
            }
        }
        MAP.main();
    }
</script>