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
        [107.6880956, -6.7342852],
      ], [
        ['Entah']
      ]);
      this.createPolygon([
        [
          [106.6880956, -6.3342852],
          [106.6, -6.3],
          [106.4, -6.4],
          [106.6, -6.4]
        ],
        [
          [107.6880956, -6.3342852],
          [107.6, -6.2],
          [107.4, -6.3],
          [107.6, -6.5],
          [107.6, -6.6]
        ],
        [
          [108.6, -6.8],
          [108.4, -6.7]
        ]
      ]);
      // this.createRoute([
      //     [
      //       [106.6795039, -6.330916],
      //       [106.6796805, -6.3309499],
      //       [106.6883065, -6.3209924],
      //       [106.6876592, -6.3219446]
      //     ],
      //     [
      //       [107.6880956, -6.3342852],
      //       [107.6, -6.2],
      //       [107.4, -6.3],
      //       [107.6, -6.5],
      //       [107.6, -6.6]
      //     ],
      //     [
      //       [108.6, -6.8],
      //       [108.4, -6.7]
      //     ]
      //   ],
      //   [
      //     ['blok n1', 'blok n2', 'blok n4', 'blok n8'],
      //     ['e', 'f', 'g', 'h', 'i', 'j'],
      //     ['k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']
      //   ]);
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
          center: ol.proj.fromLonLat([106.6795039, -6.330916]),
          zoom: 13
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
          src: 'http://localhost/bat_web/assets/apps/assets/dist/img/marker.png'
        })
      })
      for (var i = 0; i < place.length; i++) {
        marker[i] = new ol.Feature({
          geometry: new ol.geom.Point(ol.proj.fromLonLat(place[i])),
          name: i + 1 + '. ' + name[i]
        })
        marker[i].setStyle(styleMarker)
      }

      this.layerVector = new ol.layer.Vector({
        source: this.sourceVector
      })
      this.sourceVector.addFeatures(marker);

    },

    createPolygon: function(points) {
      var geojsonObject = []
      var styless = []
      var color = '#b11616'
      var col = ol.color.asArray(color);
      col = col.slice();
      col[3] = 0.25;
      for (var i = 0; i < points.length; i++) {

        var styles = [
          new ol.style.Style({
            stroke: new ol.style.Stroke({
              color: color,
              width: 2
            }),
            fill: new ol.style.Fill({
              color: col
            })
          })
        ]
        styless[i] = styles
        var point = []
        for (var j = 0; j < points[i].length; j++) {
          point[j] = ol.proj.fromLonLat(points[i][j])
        }

        geojsonObject[i] = {
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
          style: styless[i]
        })
        this.sourceVector.addFeatures((new ol.format.GeoJSON()).readFeatures(geojsonObject[i]))
      }
    },

    // createRoute: function(points, city = '') {

    //   var color = [
    //     ['orange']
    //   ]
    //   var geojsonObject = []
    //   var styless = []
    //   for (var i = 0; i < points.length; i++) {
    //     this.createMarker(points[i], city[i])
    //     var styles = [
    //       new ol.style.Style({
    //         stroke: new ol.style.Stroke({
    //           color: color[0][0],
    //           width: 2
    //         })
    //       }),
    //       new ol.style.Style({
    //         geometry: function(feature) {
    //           // return the coordinates of the first ring of the polygon
    //           var coordinates = feature.getGeometry().getCoordinates()[0];
    //           return new ol.geom.MultiPoint(coordinates);
    //         }
    //       })
    //     ]

    //     styless[i] = styles
    //     var point = []
    //     for (var j = 0; j < points[i].length; j++) {
    //       point[j] = ol.proj.fromLonLat(points[i][j])
    //     }

    //     geojsonObject[i] = {
    //       'type': 'FeatureCollection',
    //       'crs': {
    //         'type': 'name',
    //         'properties': {
    //           'name': 'EPSG:3857'
    //         }
    //       },
    //       'features': [{
    //         'type': 'Feature',
    //         'geometry': {
    //           'type': 'MultiLineString',
    //           'coordinates': [point]
    //         }
    //       }]
    //     };


    //     this.layerVector = new ol.layer.Vector({
    //       source: this.sourceVector,
    //       style: styless[i]
    //     })
    //     this.sourceVector.addFeatures((new ol.format.GeoJSON()).readFeatures(geojsonObject[i]))
    //   }
    // }
  }

  MAP.main();

  // createRoute old
  // var polyline = [
  //   'hldhx@lnau`BCG_EaC??cFjAwDjF??uBlKMd@}@z@??aC^yk@z_@se@b[wFdE??wFfE}N',
  //   'fIoGxB_I\\gG}@eHoCyTmPqGaBaHOoD\\??yVrGotA|N??o[N_STiwAtEmHGeHcAkiA}^',
  //   'aMyBiHOkFNoI`CcVvM??gG^gF_@iJwC??eCcA]OoL}DwFyCaCgCcCwDcGwHsSoX??wI_E',
  //   'kUFmq@hBiOqBgTwS??iYse@gYq\\cp@ce@{vA}s@csJqaE}{@iRaqE{lBeRoIwd@_T{]_',
  //   'Ngn@{PmhEwaA{SeF_u@kQuyAw]wQeEgtAsZ}LiCarAkVwI}D??_}RcjEinPspDwSqCgs@',
  //   'sPua@_OkXaMeT_Nwk@ob@gV}TiYs[uTwXoNmT{Uyb@wNg]{Nqa@oDgNeJu_@_G}YsFw]k',
  //   'DuZyDmm@i_@uyIJe~@jCg|@nGiv@zUi_BfNqaAvIow@dEed@dCcf@r@qz@Egs@{Acu@mC',
  //   'um@yIey@gGig@cK_m@aSku@qRil@we@{mAeTej@}Tkz@cLgr@aHko@qOmcEaJw~C{w@ka',
  //   'i@qBchBq@kmBS{kDnBscBnFu_Dbc@_~QHeU`IuyDrC_}@bByp@fCyoA?qMbD}{AIkeAgB',
  //   'k_A_A{UsDke@gFej@qH{o@qGgb@qH{`@mMgm@uQus@kL{_@yOmd@ymBgwE}x@ouBwtA__',
  //   'DuhEgaKuWct@gp@cnBii@mlBa_@}|Asj@qrCg^eaC}L{dAaJ_aAiOyjByH{nAuYu`GsAw',
  //   'Xyn@ywMyOyqD{_@cfIcDe}@y@aeBJmwA`CkiAbFkhBlTgdDdPyiB`W}xDnSa}DbJyhCrX',
  //   'itAhT}x@bE}Z_@qW_Kwv@qKaaAiBgXvIm}A~JovAxCqW~WanB`XewBbK{_A`K}fBvAmi@',
  //   'xBycBeCauBoF}}@qJioAww@gjHaPopA_NurAyJku@uGmi@cDs[eRaiBkQstAsQkcByNma',
  //   'CsK_uBcJgbEw@gkB_@ypEqDoqSm@eZcDwjBoGw`BoMegBaU_`Ce_@_uBqb@ytBwkFqiT_',
  //   'fAqfEwe@mfCka@_eC_UmlB}MmaBeWkkDeHwqAoX}~DcBsZmLcxBqOwqE_DkyAuJmrJ\\o',
  //   '~CfIewG|YibQxBssB?es@qGciA}RorAoVajA_nAodD{[y`AgPqp@mKwr@ms@umEaW{dAm',
  //   'b@umAw|@ojBwzDaaJsmBwbEgdCsrFqhAihDquAi`Fux@}_Dui@_eB_u@guCuyAuiHukA_',
  //   'lKszAu|OmaA{wKm}@clHs_A_rEahCssKo\\sgBsSglAqk@yvDcS_wAyTwpBmPc|BwZknF',
  //   'oFscB_GsaDiZmyMyLgtHgQonHqT{hKaPg}Dqq@m~Hym@c`EuiBudIabB{hF{pWifx@snA',
  //   'w`GkFyVqf@y~BkoAi}Lel@wtc@}`@oaXi_C}pZsi@eqGsSuqJ|Lqeb@e]kgPcaAu}SkDw',
  //   'zGhn@gjYh\\qlNZovJieBqja@ed@siO{[ol\\kCmjMe\\isHorCmec@uLebB}EqiBaCg}',
  //   '@m@qwHrT_vFps@kkI`uAszIrpHuzYxx@e{Crw@kpDhN{wBtQarDy@knFgP_yCu\\wyCwy',
  //   'A{kHo~@omEoYmoDaEcPiuAosDagD}rO{{AsyEihCayFilLaiUqm@_bAumFo}DgqA_uByi',
  //   '@swC~AkzDlhA}xEvcBa}Cxk@ql@`rAo|@~bBq{@``Bye@djDww@z_C_cAtn@ye@nfC_eC',
  //   '|gGahH~s@w}@``Fi~FpnAooC|u@wlEaEedRlYkrPvKerBfYs}Arg@m}AtrCkzElw@gjBb',
  //   'h@woBhR{gCwGkgCc[wtCuOapAcFoh@uBy[yBgr@c@iq@o@wvEv@sp@`FajBfCaq@fIipA',
  //   'dy@ewJlUc`ExGuaBdEmbBpBssArAuqBBg}@s@g{AkB{bBif@_bYmC}r@kDgm@sPq_BuJ_',
  //   's@{X_{AsK_d@eM{d@wVgx@oWcu@??aDmOkNia@wFoSmDyMyCkPiBePwAob@XcQ|@oNdCo',
  //   'SfFwXhEmOnLi\\lbAulB`X_d@|k@au@bc@oc@bqC}{BhwDgcD`l@ed@??bL{G|a@eTje@',
  //   'oS~]cLr~Bgh@|b@}Jv}EieAlv@sPluD{z@nzA_]`|KchCtd@sPvb@wSb{@ko@f`RooQ~e',
  //   '[upZbuIolI|gFafFzu@iq@nMmJ|OeJn^{Qjh@yQhc@uJ~j@iGdd@kAp~BkBxO{@|QsAfY',
  //   'gEtYiGd]}Jpd@wRhVoNzNeK`j@ce@vgK}cJnSoSzQkVvUm^rSgc@`Uql@xIq\\vIgg@~k',
  //   'Dyq[nIir@jNoq@xNwc@fYik@tk@su@neB}uBhqEesFjoGeyHtCoD|D}Ed|@ctAbIuOzqB',
  //   '_}D~NgY`\\um@v[gm@v{Cw`G`w@o{AdjAwzBh{C}`Gpp@ypAxn@}mAfz@{bBbNia@??jI',
  //   'ab@`CuOlC}YnAcV`@_^m@aeB}@yk@YuTuBg^uCkZiGk\\yGeY}Lu_@oOsZiTe[uWi[sl@',
  //   'mo@soAauAsrBgzBqgAglAyd@ig@asAcyAklA}qAwHkGi{@s~@goAmsAyDeEirB_{B}IsJ',
  //   'uEeFymAssAkdAmhAyTcVkFeEoKiH}l@kp@wg@sj@ku@ey@uh@kj@}EsFmG}Jk^_r@_f@m',
  //   '~@ym@yjA??a@cFd@kBrCgDbAUnAcBhAyAdk@et@??kF}D??OL'
  // ].join('');

  // var route = (new ol.format.Polyline({
  //   factor: 1e6
  // }).readGeometry(polyline, {
  //   dataProjection: 'EPSG:4326',
  //   featureProjection: 'EPSG:3857'
  // }));
  // console.log(route)
  // var routeCoords = route.getCoordinates();
  // var routeLength = routeCoords.length;
  // var routeFeature = new ol.Feature({
  //   type: 'route',
  //   geometry: route
  // });
  // var geoMarker = (new ol.Feature({
  //   type: 'geoMarker',
  //   geometry: new ol.geom.Point(routeCoords[0])
  // }));
  // var startMarker = new ol.Feature({
  //   type: 'icon',
  //   geometry: new ol.geom.Point(routeCoords[0])
  // });
  // var endMarker = new ol.Feature({
  //   type: 'icon',
  //   geometry: new ol.geom.Point(routeCoords[routeLength - 1])
  // });
  // var styles = {
  //   'route': new ol.style.Style({
  //     stroke: new ol.style.Stroke({
  //       width: 6,
  //       color: [237, 212, 0, 0.8]
  //     })
  //   }),
  //   'icon': new ol.style.Style({
  //     image: new ol.style.Icon({
  //       anchor: [0.5, 1],
  //       src: 'data/icon.png'
  //     })
  //   }),
  //   'geoMarker': new ol.style.Style({
  //     image: new ol.style.Circle({
  //       radius: 7,
  //       fill: new ol.style.Fill({
  //         color: 'blue'
  //       }),
  //       stroke: new ol.style.Stroke({
  //         color: 'white',
  //         width: 2
  //       })
  //     })
  //   })
  // };

  // var animating = false;
  // this.layerVector = new ol.layer.Vector({
  //   source: this.sourceVector,
  //   style: function(feature) {
  //     // hide geoMarker if animation is active
  //     if (animating && feature.get('type') === 'geoMarker') {
  //       return null;
  //     }
  //     return styles[feature.get('type')];
  //   }
  // });
  // this.sourceVector.addFeatures([routeFeature, geoMarker, startMarker, endMarker])
</script>