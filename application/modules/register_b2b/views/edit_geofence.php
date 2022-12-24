<!-- <div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard">Home</a></li>
            <li class="breadcrumb-item active">Project</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold" style="color:#b11616">Tambah Project</h1>
                <small>Menambah project</small>
            </div>
        </div>
    </div>
</div> -->
<style type="text/css">
    #map {
        height: 60vh;
        position: relative;
        width: 100%;
    }

    #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
    }

    #infowindow-content .title {
        font-weight: bold;
    }

    #infowindow-content {
        display: none;
    }

    #map #infowindow-content {
        display: inline;
    }

    .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
    }

    #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
    }

    .pac-controls {
        display: inline-block;
        padding: 5px 11px;
    }

    .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }

    #pac-input {
        z-index: 999;
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }

    #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
    }

    #target {
        width: 345px;
    }
</style>
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <?= $this->session->flashdata('failed'); ?>
            <?= $this->session->flashdata('success'); ?>
            <h2 class="card-title text-center  font-weight-bold">Setting Geofence</h2>


            <div class="row">
                <div class="col-md-4">
                    <h5>Organization Treeview</h5>
                    <div class="jstree" style=" overflow: auto !important;height: 65vh;">
                        <?= $b2b_treeview ?>
                    </div>
                </div>
                <div class="col-md-8">

                    <div class="form-group row">
                        <input id="address" class="controls form-control col-md-10" type="text" placeholder="Enter Lat,Long or Adress">
                        <button type="button" class="col-md-2 btn btn-lg btn-outline-secondary " style="border-radius: 3px!important;" id="searchButton">Search</button>
                    </div>
                    <div class="form-group row">
                        <div id="map"></div>
                    </div>
                    <form method="POST" enctype="multipart/form-data" action="<?= base_url('register_b2b/upload_geofence_xml') ?>">
                        <div class="form-group row">
                            <input type="file" required accept="text/xml" name="coords_xml" class="form-control form-control-file col-md-10">
                            <button class="col-md-2 btn btn-lg btn-outline-primary " style="border-radius: 3px!important;" type="submit">Import .kml file</button>
                        </div>
                    </form>
                    <form>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for=""> <b>Allow Fake GPS?</b> </label>
                                <input type="checkbox" <?= json_decode($table->is_allow_fake_gps) === true ? 'checked' : '' ?> name="is_allow_fake_gps" id="">
                            </div>
                            <div class="col-md-6">
                                <label for=""> <b>Status Schedule?</b> </label>
                                <input type="checkbox" <?= json_decode($table->status_schedule) === true ? 'checked' : '' ?> name="status_schedule" id="">
                            </div>
                            <div class="right col-md-6 ">
                                <button onclick="resetmap()" type="reset" class="btn btn-lg btn-outline-danger">Reset Area</button>
                                <button onclick="preview()" type="button" class=" btn btn-lg btn-outline-primary mr-3">Preview Area</button>
                                <button onclick="createprj()" type="button" class="btn btn-lg btn-primary  js-save">Simpan</button>
                            </div>
                        </div>
                        <input type="hidden" id="colormap" name="colormap" value="#000">
                    </form>
                </div>
            </div>
            <hr>
            <div class="tile-footer mt-3">
            </div>


            <!-- <a href="<?= base_url($this->uri->segment(1)) ?>" class="btn btn-lg btn-secondary">Kembali</a> -->
        </div>
    </div>
</div>
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<script src="<?php echo base_url('assets/apps/assets/plugins/jQuery/jquery-3.4.1.min.js') ?>"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<style>
    .right {
        display: flex;
        justify-content: flex-end;
    }
</style>
<script type="text/javascript">
    var polygon
    var zoom = 18.5
    var fixloc = []
    var thelog = '<?= ($table->area_lat_long) ?>'
    thelog = thelog ? JSON.parse(thelog) : []
    var center = {
        lat: -6.200000,
        lng: 106.816666
    }
    if (thelog.length != 0) {
        center = {
            lat: parseFloat(thelog[0].lat),
            lng: parseFloat(thelog[0].lng)
        }
    } else if (fixloc.length != 0) {
        center = {
            lat: parseFloat(fixloc[0].lat),
            lng: parseFloat(fixloc[0].lng)
        }
    } else {

    }

    navigator.geolocation.getCurrentPosition(position => {
        console.log(position, 'position');
        center = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        }
        console.log(center, 'center');
    }, function(error) {

    }, {
        timeout: 10000
    });



    $(".jstree").bind("loaded.jstree", function(event, data) {
        data.instance.open_all();
    });
    $(".jstree").jstree({
        "core": {
            "themes": {
                "icons": false
            }
        }
    });

    $('.jstree').on("changed.jstree", function(e, data) {
        window.location.href = "<?= base_url("login/goredirect/") ?>" + data.node.data.b2b + "/true"
    })


    $('#address').keypress(function(e) {
        if (e.which == 13) {
            geocode()
            return false;
        }
    });
    $('#searchButton').click(function() {
        geocode()
    });

    function geocode() {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            'address': $('#address').val()
        }, function(data, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                $('#latlng').val(data[0].geometry.location.lat + ", " + data[0].geometry.location.lng);
                center = {
                    lat: data[0].geometry.location.lat(),
                    lng: data[0].geometry.location.lng()
                }
                zoom = 19
                console.log(data, "center");
                preview(thelog, false)
            } else {
                preview()
                alert("Location Unknown!, with status: " + status);
            }
        });
        return false;
    }


    function preview(locc = [], focus = true) {
        console.log(thelog);
        console.log(fixloc);
        console.log(center);
        if (locc.length == 0) {
            var latlng = [];
            b = 0;
            if (prevloc.length == 0) {
                latlng = fixloc
            } else {
                for (a = 0; a < prevloc.length; a++) {
                    if (prevloc[a].lat != '' && prevloc[a].lng != '') {
                        latlng[b] = prevloc[a]
                        b++;
                    }
                }
                resetmap()
                fixloc = latlng
            }

            var map = new google.maps.Map(document.getElementById("map"), {
                center: center,
                zoom: zoom
            });
            polygon = makePolygon(latlng, $('#colormap').val())
            polygon.setMap(map)
            focus && focusFitBounds(latlng, map)

            var mark = mapclick(map);
        } else {
            fixloc = locc
            var map = new google.maps.Map(document.getElementById("map"), {
                center: center,
                zoom: zoom
            });
            polygon = makePolygon(locc, document.getElementById('colormap').value)
            polygon.setMap(map)
            focus && focusFitBounds(locc, map)

            var mark = mapclick(map);
        }

    }

    function focusFitBounds(arr, map) {
        var bounds = new google.maps.LatLngBounds();
        for (i = 0; i < arr.length; i++) {
            bounds.extend(new google.maps.LatLng(arr[i].lat, arr[i].lng));
        }
        map.fitBounds(bounds);
    }


    function createprj() {
        var latlng = [];
        b = 0;
        if (fixloc.length == 0) {
            for (a = 0; a < prevloc.length; a++) {
                if (prevloc[a].lat != '' && prevloc[a].lng != '') {
                    latlng[b] = prevloc[a]
                    b++;
                }
            }
        } else {
            var polygonPath = polygon.getPath()
            for (let i = 0; i < polygonPath.getLength(); i++) {
                const xy = polygonPath.getAt(i);
                latlng.push({
                    lat: xy.lat(),
                    lng: xy.lng()
                })
            }
            // latlng = fixloc
        }
        $(".js-save").html(`<i class="fa fa-circle-o-notch fa-spin"></i> Loading...`)
        $(".js-save").prop("disabled", true)
        $.post("<?= base_url('register_b2b/edit_geofence_process') ?>", {
            area_lat_long: JSON.stringify(latlng),
            is_allow_fake_gps: $("[name='is_allow_fake_gps']").is(":checked") ? true : false,
            status_schedule: $("[name='status_schedule']").is(":checked") ? true : false
        }).done((res) => {
            $(".js-save").html("Simpan")
            $(".js-save").prop("disabled", false)
            toastr["success"]("Success Update Geofence")
        }).fail(err => {
            $(".js-save").html("Simpan")
            $(".js-save").prop("disabled", false)
            toastr["danger"]("Failed Update Geofence")
        })
    }


    function mapclick(map) {
        return (new google.maps.event.addListener(map, 'click', function(event) {
            var myLatLng = {
                lat: event.latLng.lat(),
                lng: event.latLng.lng()
            }
            prevloc[i] = myLatLng
            i++;
            var icon = {
                path: "M-7,0a7,7 0 1,0 14,0a7,7 0 1,0 -14,0",
                fillColor: $('#colormap').val(),
                fillOpacity: 1,
                strokeWeight: 0,
                scale: 0.75
            }
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: icon,
                title: 'as' + i,
            });

            marker.addListener('click', function(e) {
                for (j = 0; j < prevloc.length; j++) {
                    if (prevloc[j].lat == e.latLng.lat() && prevloc[j].lng == e.latLng.lng()) {
                        console.log('ok')
                        prevloc[j].lat = 0;
                        prevloc[j].lng = 0;
                        marker.setMap(null)
                    }
                }

                // console.log(e.latLng.lng())
            });
        }))
    }

    function resetmap() {
        myMap()
        prevloc = [];
        fixloc = []
        i = 0;
    }

    function changecolor() {
        myMap($('#colormap').val(), prevloc)
        if (fixloc.length != 0) {
            var map = new google.maps.Map(document.getElementById("map"), {
                center: center,
                zoom: zoom
            });
            polygon = makePolygon(fixloc, $('#colormap').val())
            polygon.setMap(map)

            var mark = mapclick(map);
        }
    }

    function number(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
    }

    function latlong(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 45 && charCode != 46)
            return false;
    }

    var prevloc = [];
    var i = 0;

    function makePolygon(paths, color) {
        return (new google.maps.Polygon({
            paths: paths,
            strokeColor: color,
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: color,
            fillOpacity: 0.35,
            editable: true,
        }));
    }

    function myMap(color = 'red', loc = []) {
        var mapProp = {
            center: center,
            zoom: zoom
        }
        var map = new google.maps.Map(document.getElementById("map"), mapProp);
        // var input = document.getElementById('address');
        // new google.maps.places.Autocomplete(input);
        var icon = {
            path: "M-7,0a7,7 0 1,0 14,0a7,7 0 1,0 -14,0",
            fillColor: color,
            fillOpacity: 1,
            strokeWeight: 0,
            scale: 0.75
        }

        if (loc.length > 0) {
            var marked = [];
            for (j = 0; j < loc.length; j++) {
                if (loc[j].lat != 0 && loc[j].lat != 0) {
                    marked[j] = new google.maps.Marker({
                        position: loc[j],
                        map: map,
                        icon: icon,
                        // title: 'Hello World!',
                    });

                    marked[j].addListener('click', function(e) {
                        for (k = 0; k < prevloc.length; k++) {
                            if (prevloc[k].lat == e.latLng.lat() && prevloc[k].lng == e.latLng.lng()) {
                                console.log('ok')
                                prevloc[k].lat = 0;
                                prevloc[k].lng = 0;
                                marked[k].setMap(null)
                            }
                        }

                        // console.log(e.latLng.lng())
                    });
                }
            }

        }

        // polygons = new google.maps.Polygon({
        //     paths: prevloc,
        //     strokeColor: color,
        //     strokeOpacity: 0.8,
        //     strokeWeight: 2,
        //     fillColor: color,
        //     fillOpacity: 0.35
        // })
        // polygons.setMap(map)

        var mark = mapclick(map)
    }

    setTimeout(() => {
        // if (thelog.length != 0) {
        preview(thelog)
        // }
    }, 1000)
</script>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&callback=myMap&key=AIzaSyBmtZNz9aMpD-tDGdjX_ZmvkdCLe8orp7U"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=&callback=myMap"></script> -->