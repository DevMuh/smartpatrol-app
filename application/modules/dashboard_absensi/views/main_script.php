<link rel="stylesheet" href="<?= base_url('assets/apps/assets/plugins/morris/morris.css') ?>">
<script src="<?= base_url('assets/apps/assets/plugins/morris/raphael.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/morris/morris.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/morris/morris.active.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/highchart/highcharts-custom.src.js') ?>"></script>

<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/classie.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/modalEffects.js') ?>"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>

<script>
	async function fetchInfo(params) {
		let data = await $.getJSON(`<?=base_url("dashboard/get_absen_summary")?>` + params)
		$('#total_event').html(data['total_event']);
		$('#total_perjam').html(data['total_perjam']);
		$('#total_absence_percentage').html(data['total_absence_percentage']);
		$('#total_attend_percentage').html(data['total_attend_percentage']);
	}
	const LAST_DAY = `<?= json_encode($last_day) ?>`;
	$('#tanggal_absen').on('change', function (e) {
		let id = $(this)[0].selectedIndex;
		let days = JSON.parse(LAST_DAY)
		$('.sub-absen').html(days[id].text)
		let arr_date = days[id].date.split("-")
		let qp_dt = `&d=${arr_date[2]}&m=${arr_date[1]}&y=${arr_date[0]}`;
		let qp_info = `?tanggal=${arr_date[2]}-${arr_date[1]}-${arr_date[0]}`;
		fetchDataTable(qp_dt)
		fetchInfo(qp_info)
	})
</script>

<script>
	var modal_close_only = "#modal-close-only"
$(modal_close_only).on('hidden.bs.modal', function (e) {
	$(this).find(".modal-body").empty()
	$(this).find(".modal-title").empty()
})
$(".js-user-absen").click(async function (e) {
	e.preventDefault();
	e.stopPropagation();
	$(modal_close_only).modal("show")
	$(modal_close_only).find(".modal-body").html("Loading...")
	try {
		let type = $(this).data("type"),
			title = $(this).data("title")
		$(modal_close_only).find(".modal-title").text(title)
		$(modal_close_only).find(".modal-dialog").addClass("full")
		let	html_modal_body = await $.get(`<?=base_url("dashboard/table_user/")?>`+type)
		$(modal_close_only).find(".modal-body").html(html_modal_body)
		$('.js-table-absensi').DataTable({
			ajax: `<?= base_url('dashboard/data_user_') ?>`+type,
			responsive: true,
			autoWidth: true,
			processing: true,
			serverside: true,
			searching: true,
			paging: true,
			// lengthChange: true,
			// bInfo: false
		});
	} catch (error) {
		alert(error)
	}
})
async function barChartTotalAbsen() {
	var {category,val_absen, acc_absen, val_unabsen, acc_unabsen }= await $.getJSON(`<?=base_url("dashboard/absen_akumulasi")?>`)
	Highcharts.chart('bar-chart-total-absen', {
    chart: {
        zoomType: 'xy'
    },
    title: {
        text: '',
        align: 'left'
    },
    subtitle: {
        text: '',
        align: 'left'
    },
    xAxis: [{
        categories: category,
        crosshair: true
    }],
    plotOptions: {
        series: {
            pointWidth: 40
        }
    },
    yAxis: [{ // Primary yAxis
        labels: {
            format: '{value}',
            style: {
                color: "green"
            }
        },
        title: {
            text: 'Sudah Absen',
            style: {
                color: "green"
            }
        },
        opposite: true

    }, { // Secondary yAxis
        gridLineWidth: 0,
        title: {
            text: 'Tidak Absen',
            style: {
                color: "red"
            }
        },
        labels: {
            format: '{value}',
            style: {
                color: "red"
            }
        }

    }],
    tooltip: {
        shared: true
    },
    legend: {
        layout: 'horizontal',
        align: 'center',
        verticalAlign: 'bottom',
        backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || // theme
            'rgba(255,255,255,0.25)'
    },
    series: [{
        name: 'Sudah Absen',
        type: 'column',
        yAxis: 0,
        data: val_absen,
        tooltip: {
            valueSuffix: ''
        }

    },
    {
        name: 'Tidak Absen',
        type: 'column',
        yAxis: 0,
        data: val_unabsen,
        tooltip: {
            valueSuffix: ''
        }

    },
    {
        name: 'Akumulasi Sudah Absen',
        type: 'spline',
        yAxis: 1,
        data: acc_absen,
        // marker: {
        //     enabled: false
        // },
        // dashStyle: 'shortdot',
        tooltip: {
            valueSuffix: ''
        }

    }, {
        name: 'Akumulasi Tidak Absen',
        type: 'spline',
		yAxis: 1,
        data: acc_unabsen,
        tooltip: {
            valueSuffix: ''
        }
    }],
    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    floating: false,
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom',
                    x: 0,
                    y: 0
                },
                yAxis: [{
                    labels: {
                        align: 'right',
                        x: 0,
                        y: -6
                    },
                    showLastLabel: false
                }, {
                    labels: {
                        align: 'left',
                        x: 0,
                        y: -6
                    },
                    showLastLabel: false
                }, {
                    visible: false
                }]
            }
        }]
    }
});
}

barChartTotalAbsen()




	new Morris.Donut({
		// ID of the element in which to draw the chart.
		element: 'donutchart',
		// Chart data records -- each entry in this array corresponds to a point on
		// the chart.
		data: [{
				label: '<?= $this->lang->line('critical'); ?>',
				value: <?= $donut['critical'] == null ? 0 : $donut['critical'] ?>
			},
			{
				label: '<?= $this->lang->line('alert'); ?>',
				value: <?= $donut['alert'] == null ? 0 : $donut['alert'] ?>
			},
			{
				label: '<?= $this->lang->line('secured'); ?>',
				value: <?= $donut['secured'] == null ? 0 : $donut['secured'] ?>
			}
		],
		colors: ['#b81919', '#ed9a00', '#0c8456'],
		resize: true
	});
	new Morris.Bar({
		element: 'barchart',
		data: [{
				label: 'Kebakaran',
				value: <?= $bar['kebakaran'] == null ? 0 : $bar['kebakaran'] ?>
			},
			{
				label: 'Pencurian',
				value: <?= $bar['pencurian'] == null ? 0 : $bar['pencurian'] ?>
			},
			{
				label: 'Kecelakaan',
				value: <?= $bar['kecelakaan'] == null ? 0 : $bar['kecelakaan'] ?>
			},
			{
				label: 'Kematian',
				value: <?= $bar['kematian'] == null ? 0 : $bar['kematian'] ?>
			},
		],
		xkey: 'label',
		ykeys: ['value'],
		labels: ['Jumlah'],
		barColors: ['#b81919'],
		gridTextColor: '#000000',
		resize: true,
		barRatio: 0.4,
		xLabelAngle: 35,
		hideHover: 'auto',
	});

	new Highcharts.chart('highbar', {
		chart: {
			type: 'column'
		},
		title: {
			text: '<?= $this->lang->line('monthly_cond'); ?>'
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: <?= json_encode($highbar['mon']) ?>,
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Jumlah'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				'<td style="padding:0"><b>{point.y}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [{
			name: '<?= $this->lang->line('critical'); ?>',
			data: <?= json_encode($highbar['critical']) ?>,
			color: '#b81919'
		}, {
			name: '<?= $this->lang->line('alert'); ?>',
			data: <?= json_encode($highbar['warning']) ?>,
			color: '#ed9a00'
		}, {
			name: '<?= $this->lang->line('secured'); ?>',
			data: <?= json_encode($highbar['secured']) ?>,
			color: '#0c8456'
		}]
	});

	new Highcharts.chart('highline', {

		title: {
			text: 'Monthly Incident'
		},

		subtitle: {
			text: ''
		},

		yAxis: {
			title: {
				text: 'Jumlah Kejadian'
			}
		},
		xAxis: {
			categories: <?= json_encode($highline['mon']) ?>,
			crosshair: true
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle'
		},

		series: [{
				name: 'Kebakaran',
				data: <?= json_encode($highline['kebakaran']) ?>,
				color: 'orange'
			}, {
				name: 'Pencurian',
				data: <?= json_encode($highline['pencurian']) ?>,
				color: 'blue'
			}, {
				name: 'Kecelakaan',
				data: <?= json_encode($highline['kecelakaan']) ?>,
				color: 'red'
			},
			{
				name: 'Kematian',
				data: <?= json_encode($highline['kematian']) ?>,
				color: 'black'
			}
		],

		responsive: {
			rules: [{
				condition: {
					maxWidth: 500
				},
				chartOptions: {
					legend: {
						layout: 'horizontal',
						align: 'center',
						verticalAlign: 'bottom'
					}
				}
			}]
		}

	});
	var xx = []
	var dd = []
	for (i = 0; i < 31; i++) {
		xx[i] = i + 1 + ' Jan'
		dd[i] = i
	}
	var mon = ["Jan", "Feb", "Mar", "Apr", "Mei", "Juni", "Juli", "Agu", "Sep", "Okt", "Nov", "Des"]
	var d1 = []
	var d2 = []
	var d3 = []
	for (i = 0; i < 12; i++) {
		d1[i] = Math.floor(Math.random() * 100);
		d2[i] = Math.floor(Math.random() * 100);
		d3[i] = Math.floor(Math.random() * 100);
	}
	let data_curent_month_sos = JSON.parse('<?= json_encode($curent_month_sos) ?>');
	new Highcharts.chart('sosbar1', {

		title: {
			text: 'Current Month SOS'
		},

		xAxis: {
			categories: ["Patroli", "Kejadian", "Penghuni"]
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Jumlah'
			}
		},
		tooltip: {
			headerFormat: '<tr><td style="color:black;padding:0">{point.key}: </td>',
			pointFormat: '<td style="padding:0"><b>{point.y}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		series: [{
			type: 'column',
			// color: ,
			data: [parseInt(data_curent_month_sos.patroli), parseInt(data_curent_month_sos.kejadian), parseInt(data_curent_month_sos.penghuni)],
			showInLegend: false
		}]


	});
	let data_monthly_sos = JSON.parse('<?= json_encode($monthly_sos) ?>');
	new Highcharts.chart('sosbar2', {
		chart: {
			type: 'column'
		},
		title: {
			text: 'Monthly SOS'
		},
		xAxis: {
			categories: mon
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Jumlah'
			}
		},
		legend: {
			align: 'right',
			x: -30,
			verticalAlign: 'top',
			y: 25,
			floating: true,
			backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
			borderColor: '#CCC',
			borderWidth: 1,
			shadow: false
		},
		plotOptions: {
			series: {
				stacking: 'normal'
			}
		},
		series: [{
			name: 'Patroli',
			data: data_monthly_sos.map(e => parseInt(e.patroli))
		}, {
			name: 'Kejadian',
			data: data_monthly_sos.map(e => parseInt(e.kejadian))
		}, {
			name: 'Penghuni',
			data: data_monthly_sos.map(e => parseInt(e.penghuni))
		}]
	});
</script>
<script src="<?= base_url('assets/apps/assets/plugins/openlayers/ol.js') ?>"></script>
<script>
	$('#Dashboard').addClass('mm-active');

	function callmap(param) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('dashboard/ajax/') ?>",
			data: ({
				filter: param
			}),
			beforeSend: function() {
				$('#map').html('<div style="padding-left: 50%; padding-top:10%"><div class="preloader"><div class="spinner-layer pl-green"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div></div>')
			},
			dataType: "json",
			success: function(resp) {
				createmap(resp, 'map');
			}
		});
	}

	function createmap(data, mapname) {
		var point = data.loc
		var icon = data.icon
		var addr = data.addr
		$("#" + mapname).html('<div id="popup"></div>');
		var element = document.getElementById('popup');
		var MAP = {
			myMap: null,
			layerVector: null,
			sourceVector: null,
			sourceVectorPoint: null,
			main: function() {
				this.sourceVector = new ol.source.Vector();
				this.createMarker(point, icon, addr);
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
					target: mapname,
					layers: [
						new ol.layer.Tile({
							source: new ol.source.OSM()
						}),
						this.layerVector,
					],
					view: new ol.View({
						center: ol.proj.fromLonLat([106.6885956700417, -6.321998428580317]),
						zoom: 17
					}),
					interactions: new ol.interaction.defaults({
						doubleClickZoom: false,
						dragAndDrop: false,
						dragPan: false,
						keyboardPan: false,
						keyboardZoom: false,
						mouseWheelZoom: false,
						pointer: false,
						select: false
					}),
					controls: new ol.control.defaults({
						attribution: false,
						zoom: false,
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

			createMarker: function(place, icon = '', name = '') {
				var styleMarker;
				var marker = []
				for (var i = 0; i < place.length; i++) {
					styleMarker = new ol.style.Style({
						image: new ol.style.Icon({
							anchor: [0.5, 1],
							scale: 0.05,
							src: '<?= base_url() ?>assets/apps/assets/dist/img/incident/' + icon[i] + '.png'
						})
					})
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
	callmap()

	function showmap() {
		$('#myModal').modal('show');
		$.ajax({
			type: "POST",
			url: "<?= base_url('dashboard/checkpoint/') ?>",
			dataType: "json",
			success: function(resp) {
				createmap(resp, 'cpmap');
			},
			error: function() {
				alert('Error occured');
			}
		});
	}

	function fetchDataTable(params) {
		$('#tb_absen_masuk').DataTable({
			ajax: '<?= base_url('dashboard/data_user_absen?') ?>' + params,
			responsive: true,
			autoWidth: false,
			processing: true,
			scrollX: true,
			responsive: true,
			serverside: true,
			searching: false,
			destroy: true,
			// paging: false,
			lengthChange: false,
			bInfo: false
	
		});
		$('#tb_belum_absen').DataTable({
			ajax: '<?= base_url('dashboard/data_user_unabsen?') ?>' + params,
			responsive: true,
			autoWidth: false,
			processing: true,
			serverside: true,
			scrollX: true,
			destroy: true,
			responsive: true,
			searching: false,
			// paging: false,
			lengthChange: false,
			bInfo: false
	
		});
	}
	fetchDataTable('')
</script>