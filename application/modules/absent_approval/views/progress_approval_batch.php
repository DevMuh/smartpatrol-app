<html>
<title>SMART PATROL | Progress Approval Absensi </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="<?= base_url()?>assets/apps/assets/dist/img/favicon.png">
<link href="<?= base_url()?>assets/apps/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url()?>assets/apps/assets/plugins/fontawesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url()?>assets/apps/assets/plugins/nprogress/nprogress.css" rel="stylesheet">
<link href="<?= base_url()?>assets/apps/assets/plugins/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
<style>
  
  #loading-backdrop{
        background-color: rgb(12, 12, 12);
        opacity: 0.7;
        z-index: 6;
        background-color: #8c9094;
        opacity: 0.5;
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
    .col-sm-4.tabel{
      margin: auto;
    }
    .table thead th{
      text-align: center;
    }
</style>
<script src="<?= base_url()?>assets/apps/assets/plugins/jQuery/jquery-3.4.1.min.js"></script>

<script>

  function update_progress(val,message){
   
    $('#div_loading').css('width', val+'%');
    $('#span_loading').html(message);
  }

  function table(datas) {
    
    var content = `
    <h3 style="text-align: center;
    color: red;">Data Failed!</h3>
    <table class="table">
                        <thead>
                            <tr>
                                <td>Tanggal</td>
                                <td>User</td>
                                <td>Waktu Masuk</td>
                                <td>Waktu Pulang</td>
                            </tr>
                        </thead>
                        <tbody>`

    for(i=0; i<datas.length; i++){
        content += `<tr>
                        <td>${datas[i].date}</td>
                        <td>${datas[i].full_name}</td>
                        <td>${datas[i].waktu_masuk}</td>
                        <td>${datas[i].waktu_pulang}</td>
                    </tr>`;
    }
    content += "</tbody></table>"

    $('#dt-data-failed').append(content);
  }

</script>
<body>
<br>
<br>
<br>
<br>
<br>
<br>

<div class="row">
  <div class="col-sm-4"></div>
  <div class="col-sm-4">
  <div class="panel panel-default" style="border: 1px solid #ddd; border-radius: 4px; margin-bottom: 20px; background-color: #fff; box-shadow: 0 1px 1px rgb(0 0 0 / 5%);">
  <div class="panel-body" style="padding: 15px;">

    <center>
      <h2><?= $name;?></h2>
          <?php
          $tanggal= mktime(date("m"),date("d"),date("Y"));
          echo "Date Time  : <b>".date("d-M-Y", $tanggal)."</b> ";
          date_default_timezone_set('Asia/Jakarta');
          $jam=date("H:i");
          echo "|  <b>". $jam." "."</b>";

          ?> 
    </center>
      <div class="progress">
        <div class="progress-bar progress-bar-warning" data-transitiongoal="45" aria-valuenow="45" id="div_loading" >
        </div>
      </div>
      <br> <center><span id="span_loading">Progress : </span></center>
      </div>
    </div>
    </div>
    </div>
  <div class="col-sm-4 tabel" id="dt-data-failed">

  </div>
</div>


</body>
</html>