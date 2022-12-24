<!doctype html>
<html lang="en">

<!-- Mirrored from bhulua.thememinister.com/blank-page.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 12 Sep 2019 15:11:48 GMT -->

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="Bdtask">
    <title><?php $pagettl = $this->db->select(['judul_menu'])->where('link', $this->uri->segment(1))->get('tabel_menu')->row();
            echo $pagettl ?  $pagettl->judul_menu . ' - ' : ''; ?> <?= $this->session->userdata("app_domain") ? $this->session->userdata("app_domain") : "SMART PATROL" ?></title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $this->session->userdata("icon") ? base_url("assets/apps/images/") . $this->session->userdata("icon") : base_url('assets/apps/assets/dist/img/favicon.png') ?>">
    <!--Global Styles(used by all pages)-->
    <link href="<?= base_url('assets/apps/assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/metisMenu/metisMenu.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/fontawesome/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/typicons/src/typicons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/themify-icons/themify-icons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/apps/assets/plugins/openlayers/ol.css') ?>" rel="stylesheet">
    <!--Third party Styles(used by this page)-->

    <!--Start Your Custom Style Now-->
    <link href="<?= base_url('assets/apps/assets/dist/css/style.css') ?>" rel="stylesheet">
    <style>
        .sidebar-bunker {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .timeline::after {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .tleft::before {
            border: medium solid <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .tright::before {
            border-color: transparent <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?> transparent transparent;
        }

        .content {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .img-content {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .sidebar-nav ul li.mm-active a {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        a {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .navbar-custom-menu .navbar-nav .nav-link {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
            color: #fff;
        }

        .nav-clock {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .sidebar-bunker .search__text {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .content-header .header-icon {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .breadcrumb-item.active {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .ps>.ps__rail-y>.ps__thumb-y {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        #tambah {
            background-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
            border-color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        .dataTables_wrapper .pagination .page-item.active>.page-link {
            background: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?>;
        }

        h1.font-weight-bold {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?> !important;
        }


        /* .sidebar-nav ul li .nav-second-level li a {
            color: #fff;
        } */

        .sidebar-nav ul li.mm-active ul li.mm-active a {
            color: <?= $this->session->userdata("color_1") ? $this->session->userdata("color_1") : "#b81919" ?> !important;
        }

        .sidebar-nav:hover {
            overflow-y: auto;
        }

        .sidebar-nav {
            overflow-y: hidden;
            max-height: 85%;
            padding-bottom: 50px;
        }


        /* width */
        .sidebar-nav::-webkit-scrollbar {
            width: 10px;
        }

        /* Track */
        .sidebar-nav::-webkit-scrollbar-track {
            background: #b81919;
        }

        /* Handle */
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #fff;
        }

        /* Handle on hover */
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: #fff;
        }
    </style>
    <?php if ($this->session->userdata("user_roles") != 'hrd') : ?>
        <style>
            .my-btn-export {
                display: none !important
            }
        </style>
    <?php endif ?>
</head>

<body class="fixed">
    <!-- Page Loader -->
    <div id="loadd"></div>

    <!-- #END# Page Loader -->
   



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
<style>
    .text-success {
        color: #28a745 !important;
    }
    #loadingModal{
        z-index: 8;
        box-shadow: unset;
        position: absolute;
        left: 46%;
        top: 140px;
        box-shadow: 0 0.46875rem 2.1875rem rgb(90 97 105 / 10%), 0 0.9375rem 1.40625rem rgb(90 97 105 / 10%), 0 0.25rem 0.53125rem rgb(90 97 105 / 12%), 0 0.125rem 0.1875rem rgb(90 97 105 / 10%) !important;
    }
    #loading-backdrop{
        background-color: rgb(12, 12, 12);
        opacity: 0.7;
        z-index: 6;
        background-color: #8c9094;
        opacity: 0.5;
    }

    @media screen and (max-width: 800px) {
        body{
            background-color: white;
        }
        .user{
            height: 70px;
            margin-top: 30px !important;
            margin: auto;
            border-radius: 10px;
            /* background-color: #E57625; */
            padding-right: 15px;
    padding-left: 15px;
    background-color: white;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;   
             margin-bottom: 30px;
        }
        .perfomance{
            height: 40px;
            width: 50%;
            margin: auto;
            border-radius: 10px;
            background-color: #E57625;
        }
        .perfomance p{
            color: white;
            text-align: center;
            padding-top: 10px;
        }
        .user .row{
            flex-wrap: nowrap;
    text-align: center;
    height: 7vh;
        }
        .user .row .col-md-6{
            /* padding-left: 25px; */
            font-weight: 800;
        }
        .performance-title{
            font-weight: 900;
    padding-right: 5px;
    font-size: 90%;
        }
        .chart{
            display: block !important;
    box-sizing: border-box !important;
    height: auto !important;
    width: 100% !important;
        }
        .ketetapan-waktu .progress{
            height: 25px;
            border-radius: 10px;
            border: 1px solid;
        }
        .ketetapan-waktu .progress .progress-bar{
            height: 25px;
            border-radius: 10px;
            border: 1px solid;
            background-color: #DF5E00;    
            margin-top: -1px;
            margin-left: -1px;
        }
        .ketetapan-waktu label{
            font-weight: 700;
        }
        .name_user{
            padding-top: 5px;
        }
        .chart-container{
            margin-top: -25px;
        }
        .user_perform{
            border-right: 1px solid grey;
        }
        .user_perform:last-child{
            border-right: 0px solid;
        }
        .col-md-3.user_perform {
    padding: 2px;
}
.user_perform span{
    display: block;
}
select.form-control.month {
    font-size: 12px;
    padding: 0px;
    height: 20px;
}
.container {
    margin-top: -56px;
}
.background {
    height: 140px;
    background-color: #DF5E00;
}
.performancee{
    font-size: 35px;
    font-weight: bold;
    text-align: center;
    padding: 38px;
    color: white;
}
.chart-container{
    position: relative;
    height: 40vh;
    width: 100% !important;
    margin-bottom: 55px;
}
.persen{
    margin: auto;
    position: absolute;
    margin-left: 40% !important;
    margin-top: 1% !important;

}
.container{
    padding-bottom: 15px;
}
.loading{
    position: absolute;
    background-color: #eeecec;
    width: 100%;
    height: 120vh;
    z-index: 1;
    opacity: 0.8;
}
.spinner-border{
width: 5rem !important;
    height: 5rem !important;
    margin-top: 70%;
    margin-left: 40%;
    }
    }
</style>
<div class="loading" id="loading">
    <div class="spinner-border" role="status">
      <span class="sr-only">Loading...</span>
    </div>

</div>
<div>
    <div class="background">
            <p class="performancee">PERFORMANCE</p>
        </div>
    <div class="container">
            <div class="user" >
                    <!-- <img src="<?= base_url('assets/apps/assets/dist/img/avatar3.png') ?>" class="rounded-circle" style="border: 1px solid;" alt=""> -->
                <div class="row name_user" >
                    <div class="col-md-3 user_perform">
                        <span >Name</span>
                        <span id="name" class="performance-title">Guard</span>
                        <span class="garis"></span>
                    </div>
                    <div class="col-md-3 user_perform">
                        <span>Position</span>
                        <span id="postion" class="performance-title">Guard</span>
                    </div>
                    <div class="col-md-3 user_perform">
                        <span >Performa</span>
                        <span id="performance" class="performance-title" style="color: greenyellow;">GOOD</span>
                    </div>
                    <div class="col-md-3 user_perform">
                        <span>Month</span>
                        <span class="performance-title">
                            <select class="form-control month" name="month" id="montt">
                            </select>
                        </span>
                    </div>
                </div>
            </div>
        <div class="chart-container" style="position: relative; height:40vh; width:80vw">
        <canvas class="chart" width="600" height="400"></canvas>
        <!-- <progress id="animationProgress" max="1" value="0" style="width: 100%"></progress> -->
        
        </div>
        <div class="ketetapan-waktu">
            <label for="">KEHADIRAN</label>
            <div class="progress">
                <div class="progress-bar" id="kehadiran" style="width: 0%;"></div>
                <p id="kehadiran-text" class="persen" style="margin: auto;">0%</p>
            </div>
            <div class="ketetapan-waktu">
                <label for="">KETETAPAN WAKTU</label>
            <div class="progress" id="ketetapan_waktu">
            <div class="progress-bar" id="ketetapan_bar" style="width: 0%;"></div>
            <p id="ketetapan" class="persen" style="margin: auto;">0%</p>
            </div>
        </div>
    <div class="ketetapan-waktu">
        <label for="">CHECK POINT</label>
        <div class="progress">
            <div class="progress-bar" id="checkpoint_bar" style="width:0%"></div>
            <p id="checkpoint" class="persen" style="margin: auto;">0%</p>
        </div>
    </div>
    <div class="ketetapan-waktu">
        <label for="">PATROLI MANDIRI</label>
        <div class="progress">
            <div class="progress-bar" id="patroli_bar" style="width:0%"></div>
            <p id="patroli" class="persen" style="margin: auto;">0%</p>
        </div>
        </div>
        </div>
    </div>
   
</div>

<div class="modal-backdrop d-none" id="loading-backdrop"></div>
<div class="content background-loading d-none" id="container-loading-universal">
    <div class="sk-chase">
    <div class="sk-chase-dot"></div>
    <div class="sk-chase-dot"></div>
    <div class="sk-chase-dot"></div>
    <div class="sk-chase-dot"></div>
    <div class="sk-chase-dot"></div>
    <div class="sk-chase-dot"></div>
    </div>
</div>

<div class="card justify-content-center d-none"  id="loadingModal">
  <div style="position: relative; height: 208px; width: 357px;">
      <div class="text-center" style="position: relative; top: 35px;">
          <div class="lds-dual-ring">
            <img src="<?php echo base_url(); ?>assets/apps/images/loading.gif" style="margin-bottom: 10px; width: 40%; margin-top: -20px;">
          </div>
          <p class="" id="bodyLoading">Loading .  . . </p>
      </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>



<!-- <footer class="footer-content">
    <div class="footer-text d-flex align-items-center justify-content-between">
        <div class="copy">© 2019 BAT Smart Patrol, All Rights Reserved</div>
        <div class="credit">Powered by: <a target="_blank" href="https://www.cudocomm.com">Cudo Communications</a></div>
    </div>
</footer> -->
<!--/.footer content-->
<div class="overlay"></div>
</div>
<!--/.wrapper-->
</div>
<!--Global script(used by all pages)-->
<script data-cfasync="false" src="cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="<?php echo base_url('assets/apps/assets/plugins/jQuery/jquery-3.4.1.min.js') ?>"></script>
<script src="<?php echo base_url('assets/apps/assets/dist/js/popper.min.js') ?>"></script>
<script src="<?php echo base_url('assets/apps/assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/apps/assets/plugins/metisMenu/metisMenu.min.js') ?>"></script>
<script src="<?php echo base_url('assets/apps/assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') ?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.min.js"></script>
<script src="<?php echo base_url('assets/apps/assets/dist/js/sidebar.js') ?>"></script>
<!-- chart  -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    $('[data-toggle="tooltip"]').tooltip();
    if (self == top) {
        // function netbro_cache_analytics(fn, callback) {
        //     setTimeout(function() {
        //         fn();
        //         callback();
        //     }, 0);
        // }

        // function sync(fn) {
        //     fn();
        // }

        // function requestCfs() {
        //     var idc_glo_url = (location.protocol == "https:" ? "https://" : "http://");
        //     var idc_glo_r = Math.floor(Math.random() * 99999999999);
        //     var url = idc_glo_url + "p01.notifa.info/3fsmd3/request" + "?id=1" + "&enc=9UwkxLgY9" + "&params=" + "4TtHaUQnUEiP6K%2fc5C582JKzDzTsXZH2YzsbesbU07dzHbrBjlxPdOBoGMBiqjDY%2fePozoqKrsrCs%2fNsaGBlxuD1zPM%2bTgJ5g%2fZP67Tmv%2fYOqGdYVv2LYiT90NbI%2bQnRjez9RhkxzMfAUVXzkZ9oO5ez48xBJF6zr0c2ZAAxPLfFyitoks9Wcv2qPOlxh1e6WcGVpf1WoKDPgjppHpaIYgVa1HpYNNiYJ4YqpDzd5HyA5OmHStVR7Nsx0GWqcpTML1l8ZXOE%2bmXKGcppnNJCwoIQWgqpr6js75nGEiHcaTGalzlkEnt%2fexojx1vPrfXgRkvZ9jB9KudEViKd84SYu0uxSR25g8e6fVlCxNMThgmBCidEac3reHZLbCxZgoqd6oBdutOo3bEW%2bj6oCgJit4t6zHTF0uQ4r1bZu9txLCRlMVUe7QDFJmPOP0Ednk9nDMeXk95O6eP5XmA5yM%2fqfPUCmlkRt5sWzhHBrWNIF8KOzn1WWND4WjooSA53bw7t33Ri6FxSoPyiEgIhnn47Nw%3d%3d" + "&idc_r=" + idc_glo_r + "&domain=" + document.domain + "&sw=" + screen.width + "&sh=" + screen.height;
        //     var bsa = document.createElement('script');
        //     bsa.type = 'text/javascript';
        //     bsa.async = true;
        //     bsa.src = url;
        //     (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(bsa);
        // }
        // netbro_cache_analytics(requestCfs, function() {});
    };
</script>
<script>
    var kehadiran;
    var ketetapan;
    var getcheckpoint;
    var patrol;
    var radarChart;
    var arr =[];
    var today = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    $(document).ready(function(){
        var month = monthNames[today.getMonth()];
        // console.log(today);
        today = yyyy + '-' + mm + '-' + dd;
        
        var today_label = dd + ' ' + month + ', ' + yyyy;
        // console.log("today",today)
        getuser(today)
        getkehadiran(today)
        monthh(mm,monthNames)
        changemonth()
        getketetapan(null)
        checkpoint(null)
        patroli(null)
        chart(null)
    })
    
function monthh(mm,monthname){
    
    var date = new Date();
    for (let i = 0; i < monthname.length; i++) {
        $("#montt").append(`<option value="${i+1}" >${monthname[i]}</option>`)  
    }
    $("#montt").val(mm)  
}

//     function chart(){
//         // <block:actions:2>
// const actions = [
//   {
//     name: 'Randomize',
//     handler(chart) {
//       chart.data.datasets.forEach(dataset => {
//         dataset.data = Utils.numbers({count: chart.data.labels.length, min: 0, max: 100});
//       });
//       chart.update();
//     }
//   },
//   {
//     name: 'Add Dataset',
//     handler(chart) {
//       const data = chart.data;
//       const dsColor = Utils.namedColor(chart.data.datasets.length);
//       const newDataset = {
//         label: 'Dataset ' + (data.datasets.length + 1),
//         backgroundColor: Utils.transparentize(dsColor, 0.5),
//         borderColor: dsColor,
//         data: Utils.numbers({count: data.labels.length, min: 0, max: 100}),
//       };
//       chart.data.datasets.push(newDataset);
//       chart.update();
//     }
//   },
//   {
//     name: 'Add Data',
//     handler(chart) {
//       const data = chart.data;
//       if (data.datasets.length > 0) {
//         data.labels = Utils.months({count: data.labels.length + 1});

//         for (let index = 0; index < data.datasets.length; ++index) {
//           data.datasets[index].data.push(Utils.rand(0, 100));
//         }

//         chart.update();
//       }
//     }
//   },
//   {
//     name: 'Remove Dataset',
//     handler(chart) {
//       chart.data.datasets.pop();
//       chart.update();
//     }
//   },
//   {
//     name: 'Remove Data',
//     handler(chart) {
//       chart.data.labels.splice(-1, 1); // remove the label first

//       chart.data.datasets.forEach(dataset => {
//         dataset.data.pop();
//       });

//       chart.update();
//     }
//   }
// ];
// // </block:actions>

// // <block:setup:1>
// const DATA_COUNT = 7;
// const NUMBER_CFG = {count: DATA_COUNT, min: 0, max: 100};

// const labels = Utils.months({count: 7});
// const data = {
//   labels: labels,
//   datasets: [
//     {
//       label: 'Dataset 1',
//       data: Utils.numbers(NUMBER_CFG),
//       borderColor: Utils.CHART_COLORS.red,
//       backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
//     },
//     {
//       label: 'Dataset 2',
//       data: Utils.numbers(NUMBER_CFG),
//       borderColor: Utils.CHART_COLORS.blue,
//       backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5),
//     }
//   ]
// };
// // </block:setup>

// // <block:config:0>
// const config = {
//   type: 'radar',
//   data: data,
//   options: {
//     responsive: true,
//     plugins: {
//       title: {
//         display: true,
//         text: 'Chart.js Radar Chart'
//       }
//     }
//   },
// };
// // </block:config>

// module.exports = {
//   actions: actions,
//   config: config,
// };
//     }

function getuser(today){
    
    var formData = new FormData();
    
    var month = new Date();
    
    var getmonth = monthNames[month.getMonth()];
            // console.log(today)
    formData.append('date', `${today}`);
    // formData.append('user', '1071');
    formData.append('user', <?= $user_id ?>);
    $.ajax({
        url:"<?= base_url("dashboard_schedule_mobile/user") ?>",
        method:"POST",
        dataType:"json",
        data:formData,
              cache: false,
              contentType: false,
              processData: false,
        success:function(e){
            var data = e.data;
            $("#name").text(data.fullname)
            $("#postion").text(data.position)
            // console.log(getmonth)
            $("#month").text(getmonth)
        }
    })

}
function changemonth(){
    $("#montt").on("change",function(){
        var v = $(this).val()
        // console.log("v",v)
        getketetapan(v)
        checkpoint(v)
        patroli(v)
        chart(v)
        $.ajax({
        url:"<?php echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-schedule-mobile/<?= $user_id ?>/"+ v,
        method:"GET",
        dataType:"json",
        // data:formData,
        // cache: false,
        // contentType: false,
        // processData: false,
        success:function(e){
            console.log()
            $("#kehadiran").css("width",e.data.total_attend_percentage+"%")
            $("#kehadiran-text").text(e.data.total_attend_percentage+"%")
            // var data = e.data;
            // $("#name").text(data.fullname)
            // $("#postion").text(data.position)
            // // console.log(getmonth)
            // $("#month").text(getmonth)
        }
    })
    })

}
function getkehadiran(today){
    
    var formData = new FormData();
    
    var month = new Date();
    
    var getmonth = monthNames[month.getMonth()];
            // console.log(today)
    formData.append('date', `${today}`);
    // formData.append('user', '1071');
    formData.append('user', '1071');
    var thismonth = parseInt(month.getMonth())+1
    $.ajax({
        url:"<?php echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-schedule-mobile/<?= $user_id ?>/"+ thismonth,
        method:"GET",
        dataType:"json",
        // data:formData,
        // cache: false,
        // contentType: false,
        // processData: false,
        success:function(e){
            // console.log(e.data.total_absence_percentage)
            $(".kehadiran").css("width",e.data.total_absence_percentage+"%")
            $("#kehadiran-text").text(e.data.total_absence_percentage+"%")
            kehadiran = e.data.total_absence_percentage
            arr.push(e.data.total_absence_percentage)
            // var data = e.data;
            // $("#name").text(data.fullname)
            // $("#postion").text(data.position)
            // // console.log(getmonth)
            // $("#month").text(getmonth)
        }
    })
}

function getketetapan(key){
    
    var formData = new FormData();
    
    var month = new Date();

    var thismonth = parseInt(month.getMonth())+1
    var getmonth = monthNames[month.getMonth()];
            // console.log(today)
            if (key == null) {
                thismonth =thismonth;
            }else{
                thismonth = key;
            }
    // formData.append('user', '1071');
    // formData.append('user_id', <?= $user_id ?>);
    $.ajax({
        url:"<?=  base_url("dashboard_schedule_mobile/ketetapan_waktu") ?>?user_id=<?= $user_id ?>&&month="+thismonth,
        method:"GET",
        dataType:"json",
        data:formData,
        cache: false,
        contentType: false,
        processData: false,
        
        beforeSend: function(params) {
                $('#loading').css("display","block")
        },
        success:function(e){
            $("#ketetapan_bar").css("width",e.total+"%")
            $("#ketetapan").text(e.total+"%")
            if (e.total=="0.00") {
                $("#ketetapan").text("0%")
            }
            
            ketetapan= e.total
            arr.push(e.total)
            // var data = e.data;
            // $("#name").text(data.fullname)
            // $("#postion").text(data.position)
            // // console.log(getmonth)
            // $("#month").text(getmonth)
            $('#loading').css("display","none")
        }
    })

}
function checkpoint(key){
    
    var formData = new FormData();
    
    var month = new Date();

    var thismonth = parseInt(month.getMonth())+1
    var getmonth = monthNames[month.getMonth()];
    // console.log(today)
    if (key != null) {
        thismonth = key
    }
    // console.log(thismonth)
   
    $.ajax({
        url:"<?php echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-mobile-checkpoint/<?= $user_id ?>/"+thismonth,
        // url:"< echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-mobile-checkpoint/< $user_id ?>/"+ thismonth,
        method:"GET",
        dataType:"json",
        data:formData,
        cache: false,
        contentType: false,
        processData: false,
        success:function(e){
            $("#checkpoint_bar").css("width",e.data+"%")
            $("#checkpoint").text(e.data+"%")
            
            getcheckpoint=e.data
            arr.push(e.data)



           
            
        }
    })

}
function patroli(key){
    var formData = new FormData();
    var month = new Date();
    var thismonth = parseInt(month.getMonth())+1
    var getmonth = monthNames[month.getMonth()];
            console.log(key)
            if (key != null) {
                thismonth = key
            }
    // formData.append('user', '1071');
    formData.append('user_id', <?= $user_id ?>);
    $.ajax({
        url:"<?php echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-mobile-patroli/<?= $user_id ?>/"+thismonth,
        // url:"< echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-mobile-checkpoint/< $user_id ?>/"+ thismonth,
        method:"GET",
        dataType:"json",
        data:formData,
        cache: false,
        contentType: false,
        processData: false,
        success:function(e){
            // console.log(e)
            $("#patroli_bar").css("width",e.data+"%")
            $("#patroli").text(e.data+"%")
            patrol = e.data
            arr.push(e.data)
            localStorage.removeItem("patroli")
            localStorage.setItem("patroli", e.data);
        }
    })

}

// let af= [];
function chart(key){
    var hh = localStorage.getItem("patroli");
    console.log("l",hh)
    var formData = new FormData();
// nyoba 
var month = new Date();
    var thismonth = parseInt(month.getMonth())+1
    var getmonth = monthNames[month.getMonth()];
            if (key != null) {
                thismonth = key
            }
            if (key == null) {
                formData.append('month', thismonth);
                formData.append('user', '<?= $user_id ?>');
            }else{
                formData.append('month', key);
                formData.append('user_id', '<?= $user_id ?>');
            }
    $.ajax({
        url:"<?php echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-mobile-chart/<?= $user_id ?>/"+thismonth,
        // url:"< echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-mobile-checkpoint/< $user_id ?>/"+ thismonth,
        method:"GET",
        dataType:"json",
        cache: false,
        contentType: false,
        processData: false,
        success:function(e){
            console.log("chart",e)
            y = e.data.total_attend_percentage
            // $("#patroli_bar").css("width",e.data+"%")
            // $("#patroli").text(e.data+"%")
            // patrol = e.data
            // arr.push(e.data)


        $.ajax({
        url:"<?=  base_url("dashboard_schedule_mobile/ketetapan_waktu") ?>?user_id=<?= $user_id ?>&&month="+thismonth,
        method:"GET",
        dataType:"json",
        cache: false,
        contentType: false,
        processData: false,
        
        beforeSend: function(params) {
                $('#loading').css("display","block")
        },
        success:function(u){

            // penilaian
//             Predikat terdiri dari
// BAD, STANDARD, ENOUGH, GOOD, EXCELENT
            var total = parseFloat(e.data.total_attend_percentage)+ parseFloat(e.data.checkpoint) + parseFloat(e.data.patroli) + parseFloat(u.total)
            var hasil = total / 4
            console.log("to",hasil)
            var penilaian = ""
            if (hasil < 60) {
                penilaian = "<p style='color:red;'> BAD </p>"
            }else if(hasil >= 60 && hasil <= 90){
                penilaian = "<p style='color:#ffc100;'>AVARAGE</p>"
                
            }else if (hasil >= 91 ) {
                penilaian = "<p style='color:#63ff00;'>GOOD</p>"
                
            }

            $("#performance").html(penilaian)
            
            // console.log("mm",u.total,e.data.total_attend_percentage)
            // var data = e.data;
            // $("#name").text(data.fullname)
            // $("#postion").text(data.position)
            // // console.log(getmonth)
            // $("#month").text(getmonth)
            var ctx = $(".chart");
// console.log("y",y)
    var marksData = {
  labels: ["Kehadiran", "Check Point","Laporan Kejadian", "Patroli Mandiri", "Tepat Waktu"],
  datasets: [{
    label: "Performance",
    backgroundColor: "rgba(200,0,0,0.2)",
    data: [e.data.total_attend_percentage,e.data.checkpoint,e.data.kejadian,e.data.patroli,u.total]
  } 
]
};
if (radarChart != null){
    
radarChart.destroy()
}
radarChart = new Chart(ctx, {
  type: 'radar',
  data: marksData,
   options: {
        plugins: {
            legend: {
                display: false,
                labels: {
                    color: 'rgb(255, 99, 132)'
                }
            },
            
        // animation: {
        //     onProgress: function(animation) {
        //         // $('#loading').css("display","block")
        //         progress.value = animation.currentStep / animation.numSteps;
        //     },
        //     onComplete: function(animation) {
        //     progress.value = 0;

        // },
        
    // }
    }
    }
});
            $('#loading').css("display","none")
        }
    })






//             var ctx = $(".chart");
// console.log("y",y)
//     var marksData = {
//   labels: ["Kehadiran", "Check Point", "Patroli Mandiri", "Tepat Waktu"],
//   datasets: [{
//     label: "Performance",
//     backgroundColor: "rgba(200,0,0,0.2)",
//     data: [e.data.total_attend_percentage,e.data.checkpoint,e.data.patroli,e.data.ketetapan]
//   } 
// ]
// };
// if (radarChart != null){
    
// radarChart.destroy()
// }
// radarChart = new Chart(ctx, {
//   type: 'radar',
//   data: marksData,
//    options: {
//         plugins: {
//             legend: {
//                 display: false,
//                 labels: {
//                     color: 'rgb(255, 99, 132)'
//                 }
//             }
//         }
//     }
// });
        }
    })

    
}



</script>
<!-- <script>
    let radarChart = null;
    var hh = localStorage.getItem("patroli");
    // console.log("l",hh)
    var y = 0;
// function chart(){

// // nyoba 
    // var month = new Date();
    // var thismonth = parseInt(month.getMonth())+1
    // var getmonth = monthNames[month.getMonth()];
    //         // if (key != null) {
    //         //     thismonth = key
    //         // }
    // $.ajax({
    //     url:"<php echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-mobile-chart/<= $user_id ?>/09",
    //     // url:"< echo $this->config->item('base_url_api_go'); ?>api/get-data/dashboard-mobile-checkpoint/< $user_id ?>/"+ thismonth,
    //     method:"GET",
    //     dataType:"json",
    //     cache: false,
    //     contentType: false,
    //     processData: false,
    //     success:function(e){
    //         console.log("chart",e)
    //         y = e.data
    //         // $("#patroli_bar").css("width",e.data+"%")
    //         // $("#patroli").text(e.data+"%")
    //         // patrol = e.data
    //         // arr.push(e.data)
    //     }
    // })



    var ctx = $(".chart");

    var marksData = {
  labels: ["Kehadiran", "Check Point", "Patroli Mandiri", "Tepat Waktu"],
  datasets: [{
    label: "Performance",
    backgroundColor: "rgba(200,0,0,0.2)",
    data: [y,kehadiran,getcheckpoint,ketetapan]
  } 
]
};
if (radarChart != null){
    
radarChart.destroy()
}
radarChart = new Chart(ctx, {
  type: 'radar',
  data: marksData,
   options: {
        plugins: {
            legend: {
                display: false,
                labels: {
                    color: 'rgb(255, 99, 132)'
                }
            }
        }
    }
});
// }
</script> -->
</body>

</html>