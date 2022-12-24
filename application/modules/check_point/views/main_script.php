<link href="<?= base_url('assets/apps/assets/plugins/select2/dist/css/select2.min.css') ?>" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.css" rel="stylesheet">
<link href="<?= base_url('assets/apps/assets/plugins/select2-bootstrap4/dist/select2-bootstrap4.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/apps/assets/plugins/bootstrap4-toggle/css/bootstrap4-toggle.min.css"') ?> rel=" stylesheet">
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/select2/dist/js/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/bootstrap4-toggle/js/bootstrap4-toggle.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.js"></script>

</script>
<script src="<?= base_url('assets/apps/assets/plugins/openlayers/ol.js') ?>"></script>
<script>
    $('#Route').addClass('mm-active');
    // $('#subm').css({'box-shadow': 'none', 'background' : 'transparent', 'color': 'white'});
    // $('#Client_List').css({'color': '#c81b1b', 'border-radius':'4px', 'background': 'white'});

    $('.daycard').click(function() {
        if ($(this).children('input').val() == '') {
            $(this).children('input').attr('disabled', false);
            $(this).children('input').val($(this).children('span').html());
            $(this).addClass('dok');
        } else {
            $(this).children('input').attr('disabled', true);
            $(this).children('input').val('');
            $(this).removeClass('dok');
        }
    })
    function preview(){
        var jam = $('input[name=jam_mulai]').val().substr(0, 2)
        var menit = $('input[name=jam_mulai]').val().substr(3)
        var max = $('#jml_kirim').val()
        var int = $('#interval').val()
        var view;
        $('#body_prev').html('')
        for(i=1;i<=max;i++){
            console.log(i);
            
            if (i == 1){
                $('#body_prev').append(`<tr><td>1</td><td>${$('input[name=jam_mulai]').val().substr(0, 2)+':'+menit}</td><td><button type="button" class="btn mybt"><span class="typcn typcn-tick"></span></button></td></tr>`);
            } else {
                jam = Number(jam) + Number(int);
                if(jam == 24){
                    jam = 23;
                    menit = 59;
                } else if(jam > 24){
                    break;
                }
                if(jam.toString().length == 1){
                    view = '0'+jam+':'+menit
                } else {
                    view = jam+':'+menit
                }
                $('#body_prev').append(`<tr><td>${i}</td><td>${view}</td><td><button type="button" class="btn mybt"><span class="typcn typcn-tick"></span></button></td></tr>`);
            }
        }
    }
    function ochg(ini){
        $('#jml_kirim').html('')
        var jam = $('input[name=jam_mulai]').val().substr(0, 2)        
        if($(ini).val()>=1){
            for(i=1;i<=24/$(ini).val();i++){
                jam = Number(jam) + Number($(ini).val());
                if(Number(jam) >= 24) break;
                $('#jml_kirim').append(`<option>${i}</option>`)
            }   
        }    
        preview()
    }
    $('#interval').change(function(){
        ochg(this)
        preview()
    })
    $('input[name=jam_mulai]').change(function(){
        ochg($('#interval'))
        preview()
    })
    $('#jml_kirim').change(function(){
        preview()
    })
    function resetprev(id){
        $('form .form-group p').remove()
        $('.daycard').children('input').attr('disabled', true)
        $('.daycard').children('input').val('')
        $('.daycard').removeClass('dok')
        $('#interval').val('')
        $('#jml_kirim').html('')
        $('input[name=jam_mulai]').val('')
        $('#body_prev').html('')
        $('#scid').val(id);
        $.ajax({
            url: '<?=base_url('register_route/getscheduler')?>',
            method: 'POST',
            data: {id : id},
            async: false,
            dataType: 'JSON',
            success: function(data){
                if(data != null){
                    var mulai = data.hours[0]
                    var jam = mulai.substr(0, 2)
                    var interval = 0
                    if(data.hours.length == 1){
                        interval = 1
                    }else {
                        interval = Number(data.hours[1].substr(0, 2)) - Number(mulai.substr(0, 2))
                    }                    
                    $('input[name=jam_mulai]').val(mulai)
                    $('#interval').val(interval)
                    for(i=1;i<=24/interval;i++){
                        
                        jam = Number(jam) + Number(interval);
                        if(i == Number(data.hours.length)){
                            $('#jml_kirim').append(`<option selected>${i}</option>`)
                            console.log('leng', data.hours.length);
                            
                        } else {
                            $('#jml_kirim').append(`<option>${i}</option>`)
                        }
                        if(jam >= 24) break;
                    }
                    preview()
                    var j = 0;
                    for(i=0;i<7;i++){
                        if($(`.daycard:eq(${i})`).children('span').html() == data.day[j]){
                            $(`.daycard:eq(${i})`).children('input').attr('disabled', false);
                            $(`.daycard:eq(${i})`).children('input').val($(`.daycard:eq(${i})`).children('span').html());
                            $(`.daycard:eq(${i})`).addClass('dok');
                            j++;
                        }
                    }
                }
            }
        })
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


    function edit(id) {
        $('form .form-group p').remove()
        document.getElementById('editId').value = id
        var temp = event.currentTarget.parentElement.parentElement.children;
        var a = temp[1].innerHTML;
        var b = temp[2].innerHTML;
        $('input[name=ecluster_name]').val(a);
        $('textarea[name=edescription]').val(b);
    }

    function hapus(id) {
        document.getElementById('hid').value = id
        var temp = event.currentTarget.parentElement.parentElement.children;
        var a = temp[1].innerHTML;
        $("#deltitle").html("Delete " + a + " route?")
    }

    function updatecp(id) {
        document.getElementById('rid').value = id
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
                resp.data.forEach(row => {
                    if (row.cluster_route == null) {
                        $("#opt").append("<option value='" + row.id + "'>" + row.cp_name + "</option>")
                    } else {
                        if(row.cluster_route == id){
                            $("#sopt").append("<tr><td>" + i + "</td><td>" + row.cp_id + "</td><td>" + row.cp_name + "</td></tr>")
                            i++;
                        }
                    }
                });
                createmap(resp)
                $(".basic-multiple").select2();
                $(".placeholder-multiple").select2({
                    placeholder: "Select route"
                });
            }
        })
    }
    
    function tomobile(b2btoken, id) {
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
                    url: "<?= base_url() ?>register_route/tomobile/" + b2btoken + '/' + id,
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
                    success: function(data) {
                        setTimeout(function() {
                            if (data == 1) {
                                Swal.fire(
                                    'Sukses',
                                    'Task berhasil dikirim ke mobile!',
                                    'success'
                                )
                            } else {
                                Swal.fire(
                                    'Gagal',
                                    'Pastikan status active dan mempunyai checkpoint!',
                                    'error'
                                )
                            }
                        }, 500)
                    }
                })
            }
        })

    }

    function createmap(data) {
        var point = data.loc
        var addr = data.addr
        var poly = data.poly
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
                        center: ol.proj.fromLonLat(point == undefined ? [106.6879678, -6.3232336] : point[0]),
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
                for (var i = 0; i < place.length; i++) {
                    if(place[i][0] == "null"){
                        place[i][0] = "0"
                        place[i][1] = "0"
                    }
                    marker[i] = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.fromLonLat(place[i])),
                        name: name[i]
                    })
                    marker[i].setStyle(styleMarker)
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