<html>
<title>SMART PATROL | Download Rekap Absensi </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="<?= base_url()?>assets/apps/assets/dist/img/favicon.png">
<link href="<?= base_url()?>assets/apps/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url()?>assets/apps/assets/plugins/fontawesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url()?>assets/apps/assets/plugins/nprogress/nprogress.css" rel="stylesheet">
<link href="<?= base_url()?>assets/apps/assets/plugins/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">

<script src="<?= base_url()?>assets/apps/assets/plugins/jQuery/jquery-3.4.1.min.js"></script>

<script>

  function update_progress(val,message){
   
    $('#div_loading').css('width', val+'%');
    $('#span_loading').html(message);
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
      <h2>Export <?= $name;?></h2>
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
  <div class="col-sm-4"></div>
</div>


</body>
</html>
