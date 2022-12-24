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
    <div class="card mb-1">
        <div class="card-header">
            <h5>Add Checkpoint</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Checkpoint Name : </label><br>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Location : </label><br>
                        <div id="map"></div>
                    </div>
                    <div class="form-group">
                        <input type="button" class="btn btn-success right ml-2" value="Submit">
                        <input type="button" class="btn btn-danger right" value="Cancel">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<script type="text/javascript">
    var map;
    var marker;
    var circle;
    var latlng = [];

    var minZoomLevel = 17;

    // Bounds for North America


    // Listen for the dragend event



    function preview() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: -6.321998428580317,
                lng: 106.6885956700417
            },
            zoom: minZoomLevel
        });

        var strictBounds = new google.maps.LatLngBounds(
            new google.maps.LatLng(106.6885956700417, -6.321998428580317)
        );

        new google.maps.event.addListener(map, 'dragend', function() {
            map.setCenter({
                lat: -6.321998428580317,
                lng: 106.6885956700417
            });
        });

        // Limit the zoom level
        new google.maps.event.addListener(map, 'zoom_changed', function() {
            if (map.getZoom() < minZoomLevel) map.setZoom(minZoomLevel);
        });

        new google.maps.event.addListener(map, 'mousewheel', function() {
            if (map.getZoom() < minZoomLevel) map.setZoom(minZoomLevel);
        });
        new google.maps.event.addListener(map, 'DOMMouseScroll', function() {
            if (map.getZoom() < minZoomLevel) map.setZoom(minZoomLevel);
        });

        new google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
        });
    }

    function deg2rad(degrees) {
        var pi = Math.PI;
        return degrees * (pi / 180);
    }

    function rad2deg(radians) {
        var pi = Math.PI;
        return radians * (180 / pi);
    }

    function distance(lat1, lon1, lat2, lon2) {
        var theta = lon1 - lon2;
        var dist = Math.sin(deg2rad(lat1)) * Math.sin(deg2rad(lat2)) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.cos(deg2rad(theta));
        dist = Math.acos(dist);
        dist = rad2deg(dist);
        miles = dist * 60 * 1.1515;
        return miles;
    }

    function placeMarker(location) {
        // if (distance(latlng.lat, latlng.lng, location.lat(), location.lng()) * 1609.34 > 100) {
        //     alert('radius');
        // }
        latlng = {
            lat: location.lat(),
            lng: location.lng()
        }
        if (marker) {
            marker.setPosition(location);
            // circle.setCenter(latlng)
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map
            });
            // circle = new google.maps.Circle({
            //     strokeColor: '#FF0000',
            //     strokeOpacity: 0.8,
            //     strokeWeight: 2,
            //     fillColor: '#FF0000',
            //     fillOpacity: 0.35,
            //     map: map,
            //     center: latlng,
            //     radius: 100
            // });
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmtZNz9aMpD-tDGdjX_ZmvkdCLe8orp7U&callback=preview"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=&callback=myMap"></script> -->