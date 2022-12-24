<script src="<?= base_url('assets/apps/assets/plugins/openlayers/ol.js') ?>"></script>
<script>
  var MAP = {
    myMap: null,
    layerVector: null,
    sourceVector: null,
    sourceVectorPoint: null,
    main: function() {
      this.sourceVector = new ol.source.Vector();
      this.createMarker([
        [<?= $loc['longitude'] . ', ' . $loc['latitude'] ?>],
      ], [
        ['<?= $datanya->lokasi ?>']
      ]);
      this.createMap();
    },

    createMap: function() {
      var element = document.getElementById('popup');

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
          center: ol.proj.fromLonLat([<?= $loc['longitude'] . ', ' . $loc['latitude'] ?>]),
          zoom: 16
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
      var marker = []

      styleMarker = new ol.style.Style({
        image: new ol.style.Icon({
          anchor: [0.5, 1],
          scale: 0.03,
          src: '<?=base_url()?>assets/apps/assets/dist/img/marker.png'
        })
      })
      for (var i = 0; i < place.length; i++) {
        marker[i] = new ol.Feature({
          geometry: new ol.geom.Point(ol.proj.fromLonLat(place[i])),
          name: ' ' + name[i]
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
  $('#Kejadian').addClass('mm-active');
  $('.myImage').on('click', function() {
    $('#myModal').modal('show');
    // console.log($(this).attr('src'))
    var src = $(this).attr('src')
    $('#img_modal').attr('src', src)
  })
</script>