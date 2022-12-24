<link rel="stylesheet" href="<?= base_url('assets/apps/assets/plugins/morris/morris.css') ?>">
<script src="<?= base_url('assets/apps/assets/plugins/morris/raphael.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/morris/morris.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/morris/morris.active.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/highchart/highcharts-custom.src.js') ?>"></script>

<script src="<?= base_url('assets/apps/assets/plugins/openlayers/ol.js') ?>"></script>
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

<script src="<?= base_url('assets/apps/assets/plugins/highchart/highcharts-custom.src.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script>
    let urlGetSelectDate = "<?php echo $this->config->item('base_url_api_go'); ?>api/select-option/date-schedule/"+"<?php echo $this->session->userdata('b2b_token'); ?>"
    let urlGetDataDashboard = "<?php echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-schedule"
    let urlGetDataAkumulasi = "<?php echo $this->config->item('base_url_api_go'); ?>api/absen-akumulasi/dashboard-schedule"

    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    

    $(document).ready(function() {
        var month = monthNames[today.getMonth()];
        // console.log(today);
        today = yyyy + '-' + mm + '-' + dd;
        
        var today_label = dd + ' ' + month + ', ' + yyyy;

        $('#title-akumulasi-absensi').html('Total & Akumulasi Absensi - Tanggal '+today_label);
        $('#title-sudah-absen').html('Yang sudah Absen Masuk - Tanggal '+today_label);
        $('#title-belum-absen').html('Yang belum Absen Masuk - Tanggal '+today_label);
        $('#title-patroli').html('Patroli - Tanggal '+today_label);
        $('#title-checkpoint').html('Checkpoint - Tanggal '+today_label);
        // callmap()
        
        $('#Dashboard').addClass('mm-active');
            getSelectDateSchedule(today)
            // $('#tgl_schedule').val(today)
            getDataDashboard(today,"daily")
            barChartTotalAbsen(today,"daily")
            GetSelectedMonthly(monthNames,mm,yyyy)

            var swiper = new Swiper(".mySwiper", {
                                slidesPerView: 2,
                                spaceBetween: 30,
                                pagination: {
                                    el: ".swiper-pagination",
                                    clickable: true,
                                },
                                navigation: {
                                    nextEl: ".swiper-button-next",
                                    prevEl: ".swiper-button-prev",
                                },
                            });

            // ajax untuk chart hari ini 
            $.ajax({
                url:"<?= base_url("dashboard_schedule/chart") ?>?y="+yyyy+"&m="+mm+"&d="+dd,
                method:"GET",
                dataType:"JSON",
                success:function(e){
                    let highbarmon =e.highbar.mon;
                    let highbaralert=e.highbar.alert
                    let highbarcritical=e.highbar.critical
                    // highbarsecured = e.highbar.secured
                    let highbarsecured=e.highbar.secured

                    let highlinemon = e.highline.mon
                    let highlinekebakaran = e.highline.kebakaran
                    let highlinepencurian = e.highline.pencurian
                    let highlinekecelakaan = e.highline.kecelakaan
                    let highlinekematian = e.highline.kematian

                    let current_monthpatroli = e.curent_month_sos.patroli
                    let current_monthkejadian = e.curent_month_sos.kejadian
                    let current_monthpenghuni = e.curent_month_sos.penghuni

                    let monthlypatroli = e.monthly_sos.patroli
                    let monthlykejadian = e.monthly_sos.kejadian
                    let monthlypenghuni = e.monthly_sos.penghuni
                    highbarchart(highbarmon,highbaralert,highbarcritical,highbarsecured,
                    highlinemon,
                    highlinekebakaran,
                    highlinepencurian,
                    highlinekecelakaan,
                    highlinekematian,
                    current_monthpatroli,
                    current_monthkejadian,
                    current_monthpenghuni,
                    monthlypatroli,
                    monthlykejadian,
                    monthlypenghuni
                    )
                    
                }
            });
    })

    

    function getSelectDateSchedule(today) {
        $.ajax({
          url: urlGetSelectDate,
          type: "GET",
          dataType: 'JSON',
          success: function (result) {
            var html_ = `<option value="">-- Choose Date --</option>`
            $.each(result.data,function(e,res) {
              html_+=`
                <option value="${res.tgl}">${res.tgl}</option>
              `
            })
            // console.log("today : ", today);
            $('#tgl_schedule').html(html_)
            $('#tgl_schedule').val(today)
          }
        })
    }
    function GetSelectedMonthly(monthly,mm,yyyy){
        $(".day").on('change',function(e){
            
            let value = $(this).val()
            $("#tgl_schedulethn").css("display","none")
            $("#tgl_schedulebln").css("display","none")
            $("#tgl_schedule").css("display","block")
            if (value == "monthly") {
                let date = new Date();
                let year = [];
                $("#tgl_schedulethn").css("display","block")
                $("#tgl_schedulebln").css("display","block")
                $("#tgl_schedule").css("display","none")
                let html =``;
                let htmlthn =``;
                for (let i = 0; i < monthly.length; i++) {
                    let selected = ``;
                    let getmonth = i+1;
                    if (getmonth == mm) {
                        selected = `selected`;
                    }
                    if (getmonth <= 9) {
                        getmonth = `0${i+1}`;
                    }
                    html += `<option value="${getmonth}" ${selected}>${monthly[i]}</option>`
                    
                }
                $("#tgl_schedulebln").html(html)
                for (let j = 0; j < 5; j++) {
                    year.push(date.getFullYear() -j)
                }
                let urut = year.sort()
                for (let i = 0; i < urut.length; i++) {
                        let selected = ``;
                        if (urut[i] == date.getFullYear()) {
                            selected = `selected`;
                        }
                        htmlthn += `<option value="${urut[i]}" ${selected}>${urut[i]}</option>`
                }
                $("#tgl_schedulethn").html(htmlthn)

                console.log($('#tgl_schedulebln').val());
                console.log($('#tgl_schedulethn').val());

                var tgl_param = $('#tgl_schedulethn').val()+"-"+$('#tgl_schedulebln').val();
                var date2 = new Date(tgl_param)
                var date_mm = String(date2.getMonth() + 1).padStart(2, '0'); //January is 0!
                var date_yyyy = date2.getFullYear();

                var month = monthNames[date2.getMonth()];
                var today_label = month + ', ' + date_yyyy;
        
                $('#title-akumulasi-absensi').html('Total & Akumulasi Absensi - Bulan '+today_label);
                $('#title-sudah-absen').html('Yang sudah Absen Masuk - Bulan '+today_label);
                $('#title-belum-absen').html('Yang belum Absen Masuk - Bulan '+today_label);
                $('#title-patroli').html('Patroli - Bulan '+today_label);
                $('#title-checkpoint').html('Checkpoint - Bulan '+today_label);                

                absen_masuk.ajax.url(`<?= base_url('dashboard_schedule/data_user_absen') ?>?Y=${$('#tgl_schedulethn').val()}&m=${$('#tgl_schedulebln').val()}`).load();
                absen_belum_masuk.ajax.url(`<?= base_url('dashboard_schedule/data_user_unabsen') ?>?Y=${$('#tgl_schedulethn').val()}&m=${$('#tgl_schedulebln').val()}`).load();
                absen_patroli.ajax.url(`<?= base_url('dashboard_schedule/data_absen_patroli') ?>?Y=${$('#tgl_schedulethn').val()}&m=${$('#tgl_schedulebln').val()}`).load();
                absen_checkpoint.ajax.url(`<?= base_url('dashboard_schedule/data_absen_checkpoint') ?>?Y=${$('#tgl_schedulethn').val()}&m=${$('#tgl_schedulebln').val()}`).load();

                var tgl_param = $('#tgl_schedulethn').val()+"-"+$('#tgl_schedulebln').val()
                getDataDashboard(tgl_param,"monthly")
                barChartTotalAbsen(tgl_param,"monthly")
            }
        })

    }

    function getDataDashboard(tgl, type_filter) {
        // console.log("type_filter ", type_filter);
        var formData = new FormData();

        formData.append('date', tgl);
        formData.append('b2b_token', "<?php echo $this->session->userdata('b2b_token'); ?>");
        formData.append('filter_type', type_filter);
        
        $.ajax({
          url: urlGetDataDashboard,
          type: 'POST',
          data: formData,
          async: true,
          cache: true,
          contentType: false,
          processData: false,
          beforeSend: function(){
            $("#loadingModal").removeClass("d-none");
            $("#loading-backdrop").removeClass("d-none");
            $("#bodyLoading").html("Processing ...")
          },
          success:function(i) {
            var result = i.data
            $('#value-onduty').html(result.total_users_duty+"/"+result.total_users)
            $('#total_attend_percentage').html(result.total_attend_percentage)
            $('#total_attend').html(result.total_attend+"/"+result.total_users_duty)
            $('#total_absence_percentage').html(result.total_absence_percentage)
            $('#total_absence').html(result.total_absence+"/"+result.total_users_duty)
            $('#total_patroli').html(result.total_patroli)
            $('#total_checkpoint').html(result.total_checkpoint)
            // console.log(i);
            $('.tanggal_data').html("Tanggal "+tgl)

            $("#loadingModal").addClass("d-none");
            $("#loading-backdrop").addClass("d-none");
            $("#bodyLoading").html("Processing ...")
          },
          error:function(exception){
            //sweetAlert("Perhatian",'Exeption:'+exception,"warning");
            swal("Perhatian", "Exeption:"+exception, "warning")

            $("#loadingModal").addClass("d-none");
            $("#loading-backdrop").addClass("d-none");
            $("#bodyLoading").html("Processing ...")
          }
        });
    }

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

        console.log($('.day').val());
        var filter_selected = $('.day').val();
        var filter_day = "";
        var filter_month = "";
        var filter_year = "";
        
        if (filter_selected == "daily") {
            var date_filter = $('#tgl_schedule').val();
            
            var date = new Date(date_filter);
            filter_day = String(date.getDate()).padStart(2, '0');
            filter_month = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
            filter_year = date.getFullYear();
        }else{
            //console.log("kempludddddddd");
            var date_filter = $('#tgl_schedulethn').val()+"-"+$('#tgl_schedulebln').val();
            var date2 = new Date(date_filter);
            //filter_day = String(date.getDate()).padStart(2, '0');
            filter_month = String(date2.getMonth() + 1).padStart(2, '0'); //January is 0!
            filter_year = date2.getFullYear();
        }

        console.log("day : ", filter_day, " month : ", filter_month, " year : ", filter_year);

        try {
            let type = $(this).data("type"),
                title = $(this).data("title")
            $(modal_close_only).find(".modal-title").text(title)
            $(modal_close_only).find(".modal-dialog").addClass("full")
            let	html_modal_body = await $.get(`<?=base_url("dashboard/table_user/")?>`+type)
            $(modal_close_only).find(".modal-body").html(html_modal_body)
            $('.js-table-absensi').DataTable({
                ajax: `<?= base_url('dashboard_schedule/data_user_') ?>`+type+`?Y=`+filter_year+`&m=`+filter_month+`&day=`+filter_day,
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

    var absen_masuk = $('#tb_absen_masuk').DataTable({
        ajax: '<?= base_url('dashboard_schedule/data_user_absen') ?>?Y='+yyyy+'&m='+mm+'&day='+dd,
        responsive: true,
        autoWidth: false,
        processing: true,
        scrollX: true,
        responsive: true,
        serverside: true,
        searching: false,
        // paging: false,
        lengthChange: false,
        bInfo: true

    });

    var absen_belum_masuk = $('#tb_belum_absen').DataTable({
        ajax: '<?= base_url('dashboard_schedule/data_user_unabsen') ?>?Y='+yyyy+'&m='+mm+'&day='+dd,
        responsive: true,
        autoWidth: false,
        processing: true,
        scrollX: true,
        responsive: true,
        serverside: true,
        searching: false,
        // paging: false,
        lengthChange: false,
        bInfo: true

    });

    var absen_patroli = $('#tb_absen_patroli').DataTable({
        ajax: '<?= base_url('dashboard_schedule/data_absen_patroli') ?>?Y='+yyyy+'&m='+mm+'&day='+dd,
        responsive: true,
        autoWidth: false,
        processing: true,
        scrollX: true,
        responsive: true,
        serverside: true,
        searching: false,
        // paging: false,
        lengthChange: false,
        bInfo: true

    });

    var absen_checkpoint = $('#tb_absen_checkpoint').DataTable({
        ajax: '<?= base_url('dashboard_schedule/data_absen_checkpoint') ?>?Y='+yyyy+'&m='+mm+'&day='+dd,
        responsive: true,
        autoWidth: false,
        processing: true,
        scrollX: true,
        responsive: true,
        serverside: true,
        searching: false,
        // paging: false,
        lengthChange: false,
        bInfo: true

    });

    // function changeDateSchedule() {
    $("#tgl_schedule").change(function() {
      var tgl_schedule = $('#tgl_schedule').val()
      console.log("change : ", tgl_schedule);

      var date = new Date(tgl_schedule);
      var date_dd = String(date.getDate()).padStart(2, '0');
      var date_mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
      var date_yyyy = date.getFullYear();

      var month = monthNames[date.getMonth()];
      var today_label = date_dd + ' ' + month + ', ' + date_yyyy;
        
      $.ajax({
        url:"<?= base_url("dashboard_schedule/chart") ?>?y="+date_yyyy+"&m="+date_mm+"&d="+date_dd,
        method:"GET",
        dataType:"JSON",
        success:function(e){
            let highbarmon =e.highbar.mon;
            let highbaralert=e.highbar.alert
            let highbarcritical=e.highbar.critical
            // highbarsecured = e.highbar.secured
            let highbarsecured=e.highbar.secured

            let highlinemon = e.highline.mon
            let highlinekebakaran = e.highline.kebakaran
            let highlinepencurian = e.highline.pencurian
            let highlinekecelakaan = e.highline.kecelakaan
            let highlinekematian = e.highline.kematian

            let current_monthpatroli = e.curent_month_sos.patroli
            let current_monthkejadian = e.curent_month_sos.kejadian
            let current_monthpenghuni = e.curent_month_sos.penghuni

            let monthlypatroli = e.monthly_sos.patroli
            let monthlykejadian = e.monthly_sos.kejadian
            let monthlypenghuni = e.monthly_sos.penghuni
            highbarchart(highbarmon,highbaralert,highbarcritical,highbarsecured,
            highlinemon,
            highlinekebakaran,
            highlinepencurian,
            highlinekecelakaan,
            highlinekematian,
            current_monthpatroli,
            current_monthkejadian,
            current_monthpenghuni,
            monthlypatroli,
            monthlykejadian,
            monthlypenghuni
            )
            
        }
    });


      $('#title-akumulasi-absensi').html('Total & Akumulasi Absensi - Tanggal '+today_label);
      $('#title-sudah-absen').html('Yang sudah Absen Masuk - Tanggal '+today_label);
      $('#title-belum-absen').html('Yang belum Absen Masuk - Tanggal '+today_label);
      $('#title-patroli').html('Patroli - Tanggal '+today_label);
      $('#title-checkpoint').html('Checkpoint - Tanggal '+today_label);
      console.log(`Y=${date_yyyy}&m=${date_mm}&day=${date_dd}`);
      
    // console.log("asdd",highbarsecured)
    absen_masuk.ajax.url(`<?= base_url('dashboard_schedule/data_user_absen') ?>?Y=${date_yyyy}&m=${date_mm}&day=${date_dd}`).load();
    absen_belum_masuk.ajax.url(`<?= base_url('dashboard_schedule/data_user_unabsen') ?>?Y=${date_yyyy}&m=${date_mm}&day=${date_dd}`).load();
    //  absen_belum_masuk.ajax.url(`<?= base_url('dashboard_schedule/data_user_unabsen') ?>?Y=${date_yyyy}&m=${date_mm}&day=${date_dd}`).load();
    absen_patroli.ajax.url(`<?= base_url('dashboard_schedule/data_absen_patroli') ?>?Y=${date_yyyy}&m=${date_mm}&day=${date_dd}`).load();
    absen_checkpoint.ajax.url(`<?= base_url('dashboard_schedule/data_absen_checkpoint') ?>?Y=${date_yyyy}&m=${date_mm}&day=${date_dd}`).load();
    getDataDashboard(tgl_schedule,"daily")
    barChartTotalAbsen(tgl_schedule,"daily")
    //}
})

    $(".tgl_monthly").change(function() {
        var tgl_schedulethn = $('#tgl_schedulethn').val()
        var tgl_schedulebln = $('#tgl_schedulebln').val()
        // console.log("change : ", tgl_schedulethn);
        // console.log("change : ", tgl_schedulebln);
        // console.log("jan")
        var tgl_param = $('#tgl_schedulethn').val()+"-"+$('#tgl_schedulebln').val();
        var date2 = new Date(tgl_param)
        var date_mm = String(date2.getMonth() + 1).padStart(2, '0'); //January is 0!
        var date_yyyy = date2.getFullYear();

        var month = monthNames[date2.getMonth()];
        var today_label = month + ', ' + date_yyyy;

        $('#title-akumulasi-absensi').html('Total & Akumulasi Absensi - Bulan '+today_label);
        $('#title-sudah-absen').html('Yang sudah Absen Masuk - Bulan '+today_label);
        $('#title-belum-absen').html('Yang belum Absen Masuk - Bulan '+today_label);
        $('#title-patroli').html('Patroli - Bulan '+today_label);
        $('#title-checkpoint').html('Checkpoint - Bulan '+today_label); 

        var tgl_param = tgl_schedulethn+"-"+tgl_schedulebln

        //absen_masuk.ajax.url(`<?= base_url('dashboard_schedule/data_user_absen') ?>?Y=${date_yyyy}&m=${date_mm}&day=${date_dd}`).load();

        $.ajax({
        url:"<?= base_url("dashboard_schedule/chart") ?>?y="+date_yyyy+"&m="+date_mm+"&d=",
        method:"GET",
        dataType:"JSON",
        success:function(e){
            let highbarmon =e.highbar.mon;
            let highbaralert=e.highbar.alert
            let highbarcritical=e.highbar.critical
            // highbarsecured = e.highbar.secured
            let highbarsecured=e.highbar.secured

            let highlinemon = e.highline.mon
            let highlinekebakaran = e.highline.kebakaran
            let highlinepencurian = e.highline.pencurian
            let highlinekecelakaan = e.highline.kecelakaan
            let highlinekematian = e.highline.kematian

            let current_monthpatroli = e.curent_month_sos.patroli
            let current_monthkejadian = e.curent_month_sos.kejadian
            let current_monthpenghuni = e.curent_month_sos.penghuni

            let monthlypatroli = e.monthly_sos.patroli
            let monthlykejadian = e.monthly_sos.kejadian
            let monthlypenghuni = e.monthly_sos.penghuni
            highbarchart(highbarmon,highbaralert,highbarcritical,highbarsecured,
            highlinemon,
            highlinekebakaran,
            highlinepencurian,
            highlinekecelakaan,
            highlinekematian,
            current_monthpatroli,
            current_monthkejadian,
            current_monthpenghuni,
            monthlypatroli,
            monthlykejadian,
            monthlypenghuni
            )
            
        }
    });
        absen_masuk.ajax.url(`<?= base_url('dashboard_schedule/data_user_absen') ?>?Y=${tgl_schedulethn}&m=${tgl_schedulebln}`).load();
        absen_belum_masuk.ajax.url(`<?= base_url('dashboard_schedule/data_user_unabsen') ?>?Y=${tgl_schedulethn}&m=${tgl_schedulebln}`).load();
        absen_patroli.ajax.url(`<?= base_url('dashboard_schedule/data_absen_patroli') ?>?Y=${tgl_schedulethn}&m=${tgl_schedulebln}`).load();
        absen_checkpoint.ajax.url(`<?= base_url('dashboard_schedule/data_absen_checkpoint') ?>?Y=${tgl_schedulethn}&m=${tgl_schedulebln}`).load();

        getDataDashboard(tgl_param,"monthly")
        barChartTotalAbsen(tgl_param,"monthly")
    })

    // async function barChartTotalAbsen() {
    function barChartTotalAbsen(tgl,type_filter) {
        var formData = new FormData();

        formData.append('tgl', tgl);
        formData.append('b2b_token', "<?php echo $this->session->userdata('b2b_token'); ?>");
        formData.append('filter_type', type_filter);

        $.ajax({
          url: urlGetDataAkumulasi,
          type: 'POST',
          data: formData,
          async: true,
          cache: true,
          contentType: false,
          processData: false,
          beforeSend: function(){
            $("#loadingModal").removeClass("d-none");
            $("#loading-backdrop").removeClass("d-none");
            $("#bodyLoading").html("Processing ...")
          },
          success:function(i) {
            var result = i.data
            var acc_abcense = result.acc_abcense
            var acc_attend = result.acc_attend
            var acc_late = result.acc_late
            var val_abcense = result.val_abcense
            var val_attend = result.val_attend
            var val_late = result.val_late
            var category = result.category

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
                    gridLineWidth: 0,
                    crosshair: true
                }
                // ,
                // {
                //     // "title": {
                //     //     "text": category,
                //     // },
                    
                //     gridLineWidth: 1,
                //     gridLineDashStyle: 'ShortDash',
                //     labels: {
                //         rotation: 10,
                //     },            
                //     categories: category
        
                //     // "opposite": true,
                //     // "plotLines": [
                //     //     {
                //     //         "color": "red",
                //     //         "dashStyle": "longdashdot",
                //     //         "value": 50,
                //     //         "width": 10
                //     //     }
                //     // ],
                //     // "labels": {
                //     //     "enabled": true,
                //     //     "step": 10
                //     // },
                //     // "min": 0,
                //     // "max": 100,
                //     // "categories": category,
                //     // crosshair: true
                // }
                ],
                yAxis: [{ // Primary yAxis
                    labels: {
                        format: '{value}',
                        style: {
                            color: "#FDA9FA"
                        }
                    },
                    title: {
                        text: 'Sudah Absen',
                        style: {
                            color: "#FDA9FA"
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
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    },
                    series: {
                        //groupPadding: 0,
                        pointPadding: 0
                    },
                },
                legend: {
                    layout: 'horizontal',
                    align: 'left',
                    //x: 80,
                    verticalAlign: 'bottom',
                    // y: 55,
                    // floating: true,
                    // backgroundColor:
                    //     Highcharts.defaultOptions.legend.backgroundColor || // theme
                    //     'rgba(255,255,255,0.25)'
                },
    //             series: [{
    //     name: 'x',
    //     data: [1,8,9,16],
	// 			stack: 'StackA'
    // }, {
    //     name: 'y',
    //     data: [1,7,10,15],
    //     stack: 'StackA'
    //     },{
    //     name: 'x',
    //     data: [3,6,11,14],
	// 			stack: 'StackB'
    // }, {
    //     name: 'y',
    //     data: [4,5,12,13],
    //     stack: 'StackB'
    //     },
    //      {
    //        name: '',
    //        data: [0,0,0,0,0,0,0,0],
    //        showInLegend: false,
    //        stack: 'StackB',
    //        xAxis: 1            
    //     }
    // ],
                series: [{
                    name: 'Sudah Absen',
                    type: 'column',
                    yAxis: 0,
                    data: val_attend,
                    tooltip: {
                        valueSuffix: ''
                    },
                    color: '#31C5F4'
                },
                {
                    name: 'Terlambat Masuk',
                    type: 'column',
                    yAxis: 0,
                    data: val_late,
                    tooltip: {
                        valueSuffix: ''
                    },
                    color: 'orange'
                },
                {
                    name: 'Tidak Absen',
                    type: 'column',
                    yAxis: 0,
                    data: val_abcense,
                    tooltip: {
                        valueSuffix: ''
                    },
                    color: 'red'

                },
                {
                    name: 'Akumulasi Sudah Absen',
                    type: 'spline',
                    yAxis: 1,
                    data: acc_attend,
                    // marker: {
                    //     enabled: false
                    // },
                    // dashStyle: 'shortdot',
                    tooltip: {
                        valueSuffix: ''
                    },
                    color: '#31C5F4'

                }, 
                {
                    name: 'Akumulasi Terlambat Masuk',
                    type: 'spline',
                    yAxis: 1,
                    data: acc_late,
                    // marker: {
                    //     enabled: false
                    // },
                    // dashStyle: 'shortdot',
                    tooltip: {
                        valueSuffix: ''
                    },
                    color: 'orange'

                }, 
                {
                    name: 'Akumulasi Tidak Absen',
                    type: 'spline',
                    yAxis: 1,
                    data: acc_abcense,
                    tooltip: {
                        valueSuffix: ''
                    },
                    color: 'red'
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

            $("#loadingModal").addClass("d-none");
            $("#loading-backdrop").addClass("d-none");
            $("#bodyLoading").html("Processing ...")
          },
          error:function(exception){
            //sweetAlert("Perhatian",'Exeption:'+exception,"warning");
            swal("Perhatian", "Exeption:"+exception, "warning")

            $("#loadingModal").addClass("d-none");
            $("#loading-backdrop").addClass("d-none");
            $("#bodyLoading").html("Processing ...")
          }
        });

    //   var {category,val_absen, acc_absen, val_unabsen, acc_unabsen }= await $.getJSON(urlGetDataAkumulasi)
    //   Highcharts.chart('bar-chart-total-absen', {
    //     chart: {
    //         zoomType: 'xy'
    //     },
    //     title: {
    //         text: '',
    //         align: 'left'
    //     },
    //     subtitle: {
    //         text: '',
    //         align: 'left'
    //     },
    //     xAxis: [{
    //         categories: category,
    //         crosshair: true
    //     }],
    //     yAxis: [{ // Primary yAxis
    //         labels: {
    //             format: '{value}',
    //             style: {
    //                 color: "green"
    //             }
    //         },
    //         title: {
    //             text: 'Sudah Absen',
    //             style: {
    //                 color: "green"
    //             }
    //         },
    //         opposite: true

    //     }, { // Secondary yAxis
    //         gridLineWidth: 0,
    //         title: {
    //             text: 'Tidak Absen',
    //             style: {
    //                 color: "red"
    //             }
    //         },
    //         labels: {
    //             format: '{value}',
    //             style: {
    //                 color: "red"
    //             }
    //         }

    //     }],
    //     tooltip: {
    //         shared: true
    //     },
    //     legend: {
    //         layout: 'vertical',
    //         align: 'left',
    //         x: 80,
    //         verticalAlign: 'top',
    //         y: 55,
    //         floating: true,
    //         backgroundColor:
    //             Highcharts.defaultOptions.legend.backgroundColor || // theme
    //             'rgba(255,255,255,0.25)'
    //     },
    //     series: [{
    //         name: 'Sudah Absen',
    //         type: 'column',
    //         yAxis: 0,
    //         data: val_attend,
    //         tooltip: {
    //             valueSuffix: ''
    //         }

    //     },
    //     {
    //         name: 'Tidak Absen',
    //         type: 'column',
    //         yAxis: 0,
    //         data: val_abcense,
    //         tooltip: {
    //             valueSuffix: ''
    //         }

    //     },
    //     {
    //         name: 'Akumulasi Sudah Absen',
    //         type: 'spline',
    //         yAxis: 1,
    //         data: acc_attend,
    //         // marker: {
    //         //     enabled: false
    //         // },
    //         // dashStyle: 'shortdot',
    //         tooltip: {
    //             valueSuffix: ''
    //         }

    //     }, {
    //         name: 'Akumulasi Tidak Absen',
    //         type: 'spline',
    //     yAxis: 1,
    //         data: acc_abcense,
    //         tooltip: {
    //             valueSuffix: ''
    //         }
    //     }],
    //     responsive: {
    //         rules: [{
    //             condition: {
    //                 maxWidth: 500
    //             },
    //             chartOptions: {
    //                 legend: {
    //                     floating: false,
    //                     layout: 'horizontal',
    //                     align: 'center',
    //                     verticalAlign: 'bottom',
    //                     x: 0,
    //                     y: 0
    //                 },
    //                 yAxis: [{
    //                     labels: {
    //                         align: 'right',
    //                         x: 0,
    //                         y: -6
    //                     },
    //                     showLastLabel: false
    //                 }, {
    //                     labels: {
    //                         align: 'left',
    //                         x: 0,
    //                         y: -6
    //                     },
    //                     showLastLabel: false
    //                 }, {
    //                     visible: false
    //                 }]
    //             }
    //         }]
    //     }
    //   });
    }

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
function highbarchart(highbarmon,highbarcritical, highbaralert, highbarsecured,
highlinemon,
highlinekebakaran,
highlinepencurian,
highlinekecelakaan,
highlinekematian,
current_monthpatroli,
current_monthkejadian,
current_monthpenghuni,
monthlypatroli,
monthlykejadian,
monthlypenghuni
){
$("#highbar").html("")
$("#highline").html("")
$("#sosbar1").html("")
$("#sosbar2").html("")
$("#barchart").html("")
    
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
			categories: highbarmon ,
			// categories: <= json_encode($highbar['mon']) >,
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
			data: highbarcritical,
			// data: <= json_encode($highbar['critical']) ?>,
			color: '#b81919'
		}, {
			name: '<?= $this->lang->line('alert'); ?>',
			data:highbaralert,
			// data: <= json_encode($highbar['warning']) ?>,
			color: '#ed9a00'
		}, {
			name: '<?= $this->lang->line('secured'); ?>',
			data: highbarsecured,
			// data: <= json_encode($highbar['secured']) ?>,
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
    categories: highlinemon,
    crosshair: true
},
legend: {
    layout: 'vertical',
    align: 'right',
    verticalAlign: 'middle'
},

series: [{
        name: 'Kebakaran',
        data: highlinekebakaran,
        color: 'orange'
    }, {
        name: 'Pencurian',
        data: highlinepencurian,
        color: 'blue'
    }, {
        name: 'Kecelakaan',
        data: highlinekecelakaan,
        color: 'red'
    },
    {
        name: 'Kematian',
        data: highlinekematian,
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
var ss = []
var sd = []
for (i = 0; i < 31; i++) {
ss[i] = i + 1 + ' Jan'
sd[i] = i
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
    data: [current_monthpatroli,current_monthkejadian,current_monthpenghuni],
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
    data: monthlypatroli
}, {
    name: 'Kejadian',
    data: monthlykejadian
}, {
    name: 'Penghuni',
    data: monthlypenghuni
}]
});



// chart Incident 
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
}
        


    // chart sos 
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




//    var cart = $.ajax({
//         url:"<?= base_url("dashboard_schedule/chart") ?>?date=2022-09-01",
//         method:"GET",
//         dataType:"JSON",
//         success:function(e){

//         }
//     })
</script>