
<script>
    $('#1Users').addClass('mm-active');
    $('#subm_Master').css({
        'box-shadow': 'none',
        'background': 'transparent',
        'color': 'white'
    });
    $('#2Users').css({
        'color': '#474962',
        'border-radius': '4px',
        'background': 'white'
    });

    $(document).ready(function(){

    	$.ajax({

            url: "<?=base_url($this->uri->segment(1)).'/get_longlat'.'/'.$id ?>",
    		type: "GET",
            success: function(response) {
            	var toJson = JSON.parse(response);
            	var loc = []
                if(toJson.length == 0){
                    loc = [106.6879678, -6.3232336]
                } else {
                    loc = [toJson[0].latest_long, toJson[0].latest_lat]
                }
    	        createmap(loc)

                
            }


    	})
    })



		 function createmap(data) {
        // var point = data.loc
        // var addr = data.addr
        var element = document.getElementById('popup');
        var MAP = {
            myMap: null,
            layerVector: null,
            sourceVector: null,
            sourceVectorPoint: null,
            main: function() {
                this.sourceVector = new ol.source.Vector();
                this.createMarker([data]);
                // if (point != undefined) {
                    
                // } else {
                //     this.createMarker([
                //         [0, 0]
                //     ], ['']);
                // }
                this.createMap();
            },

            createMap: function() {
                var popup = new ol.Overlay({
                    element: element,
                    positioning: 'bottom-center',
                    stopEvent: false,
                    offset: [0, -30]
                });

                this.myMap = new ol.Map({
                    target: 'mapp',
                    layers: [
                        new ol.layer.Tile({
                            source: new ol.source.OSM()
                        }),
                        this.layerVector,
                    ],
                    view: new ol.View({
                        center: ol.proj.fromLonLat(data),
                        zoom: 17
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

            createMarker: function(place) {
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
            }
        }
        MAP.main();
    }


</script>