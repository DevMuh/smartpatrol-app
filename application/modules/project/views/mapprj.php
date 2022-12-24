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
            <h5>All Project</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<script type="text/javascript">
    function preview() {
        var map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: -0.789275,
                lng: 113.9213257
            },
            zoom: 5
        });
        var js = [];
        var clr = [];
        <?php
        $js = '';
        $clr = '';
        foreach ($table as $row) {
            $js = $row->area_lat_long . ',' . $js;
            $clr = "'".$row->area_color . "'," . $clr;
        }
        echo "js = [$js];";
        echo " clr = [$clr];";
        ?>
        var polygon = [];
        for (i = 0;i<js.length; i++) {
            polygon[i] = makePolygon(js[i], clr[i])
            polygon[i].setMap(map)
        }
    }

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
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmtZNz9aMpD-tDGdjX_ZmvkdCLe8orp7U&callback=preview"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=&callback=myMap"></script> -->