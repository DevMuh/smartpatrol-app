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
        height: 400px;
        position: relative;
        width: 100%;
    }
</style>
<div class="body-content">
    <div class="card mb-4">
        <div class="card-body">
            <?= $this->session->flashdata('failed'); ?>
            <h2 class="card-title text-center mb-5 mt-3 font-weight-bold">Tambah Projek</h2>
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-inline">
                            <label>Kode Projek&nbsp;</label>
                            <input id="kode" name="kode" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-inline">
                            <label>Nama Projek&nbsp;</label>
                            <input style="width: 80%" name="nama" type="text" class="form-control" id="nama" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-inline">
                            <label>Warna&nbsp;</label>
                            <input onchange="changecolor()" class="form-control" type="color" value="#ff0000" id="colormap" style="width: 30px;padding: 1px;height: 30px;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="tile-footer mt-3">
                    <button onclick="createprj()" type="button" class="btn btn-lg btn-primary mr-3">Simpan</button>
            </form>
            <a href="<?= base_url($this->uri->segment(1)) ?>" class="btn btn-lg btn-secondary">Kembali</a>
            <button onclick="resetmap()" type="reset" class="btn btn-lg btn-warning right">Reset</button>
            <button style="background-color: #b11616" onclick="preview()" type="button" class=" btn btn-lg btn-primary right mr-3">Preview Area</button>
        </div>
    </div>
</div>
</div>


<script type="text/javascript">
    var fixloc = []

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
            latlng = fixloc
        }
        var formdata = new FormData
        formdata.append('area', JSON.stringify(latlng))
        formdata.append('kode', $('#kode').val())
        formdata.append('nama', $('#nama').val())
        formdata.append('warna', $('#colormap').val())
        $.ajax({
            url: '<?= base_url('project/tambah') ?>',
            data: formdata,
            dataType: 'json',
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                if (data != 1) {
                    for (i = 0; i < data.length; i++) {
                        $(".form-group:eq(" + i + ")" + ' p').remove()
                        $(".form-group:eq(" + i + ")").append(data[i]);
                    }
                } else {
                    window.location.href = '<?= base_url('project') ?>'
                }
            }
        })
    }

    function preview() {
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
            center: {
                lat: -0.789275,
                lng: 113.9213257
            },
            zoom: 5
        });
        var polygon = makePolygon(latlng, $('#colormap').val())
        polygon.setMap(map)

        var mark = mapclick(map);
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
                center: {
                    lat: -0.789275,
                    lng: 113.9213257
                },
                zoom: 5
            });
            var polygon = makePolygon(fixloc, $('#colormap').val())
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
            fillOpacity: 0.35
        }));
    }

    function myMap(color = 'red', loc = []) {
        var mapProp = {
            center: {
                lat: -0.789275,
                lng: 113.9213257
            },
            zoom: 5
        }
        var map = new google.maps.Map(document.getElementById("map"), mapProp);

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
                                prevloc[k].lat = 0;
                                prevloc[k].lng = 0;
                                marked[k].setMap(null)
                            }
                        }
                    });
                }
            }
        }

        var mark = mapclick(map)
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmtZNz9aMpD-tDGdjX_ZmvkdCLe8orp7U&callback=myMap"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=&callback=myMap"></script> -->