<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/sweetalert/sweetalert.min.js') ?>"></script>
<!-- <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="https://datatables.net/release-datatables/extensions/FixedColumns/css/fixedColumns.bootstrap4.css">

<script src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>

<script>
    let formScheduleAbsensi = $("#formScheduleAbsensi")
    let editformScheduleAbsensi = $("#editformScheduleAbsensi")
    let urlAddSchedule = "<?= base_url() ?>schedule_absensi/create"
    let urlUpdateSchedule = "<?= base_url() ?>schedule_absensi/update"
    let urlDelScheduleAbsensi = "<?= base_url() ?>schedule_absensi/delete"
    let urlProcessUpload = "<?php echo $this->config->item('base_url_api_go'); ?>import-schedule/show-data"
    let urlProcessSaveData = "<?php echo $this->config->item('base_url_api_go'); ?>import-schedule/save-data"
    let urlProcessSaveDataColumn = "<?php echo $this->config->item('base_url_api_go'); ?>import-schedule/save-data-column"
    let formTable = $("#new_table_form")

    

    $(document).ready(function() {
        var table_row = $('#tb_schedule').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            ajax: '<?= base_url('schedule_absensi/ajax') ?>'
        });

        var table_column = $('#tb_schedule_column').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverside: true,
            "scrollX": "true",
            fixedColumns: {
                leftColumns: 2
            },
            ajax: '<?= base_url('schedule_absensi/ajax_column') ?>'
        });

        $(".js-filter").change(function() {
            var month = $(".month").val()
            var year = $(".year").val()
            table_column.ajax.url(`<?= base_url('schedule_absensi/ajax_column') ?>/${month}/${year}`).load();
            table_row.ajax.url(`<?= base_url('schedule_absensi/ajax') ?>/${month}/${year}`).load();
        })

        $('#btnRow').hide();
        $('#btnColumn').show();

        // $('#tb_schedule_wrapper').fadeIn();
        // $('#tb_schedule_column_wrapper').fadeIn();

        // $('#pills-home-tab').click(function() {
        //     $('#tb_schedule_wrapper').fadeIn();
        //     $('#tb_schedule_column_wrapper').hide();

        //     $('#btnRow').show();
        //     $('#btnColumn').hide();
        // })
        // $('#pills-profile-tab').click(function() {
        //     $('#tb_schedule_column_wrapper').fadeIn();
        //     $('#tb_schedule_wrapper').hide();

        //     $('#btnRow').hide();
        //     $('#btnColumn').show();
        // })

        $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
          if (e.target.hash == '#pills-home') {
            table_row.columns.adjust().draw()
            $('#btnRow').show();
            $('#btnColumn').hide();
          }else{
            table_column.columns.adjust().draw()
            $('#btnRow').hide();
            $('#btnColumn').show();
          }
        })

        getSelectUser()
        getSelectShift()
    });

    function getSelectUser() {
        $.ajax({
          url: '<?= base_url('schedule_absensi/get_option_user') ?>',
          type: "GET",
          success: function (result) {
            let response = JSON.parse(result)
            var html_ = `<option value="">--Choose User--</option>`
            $.each(response,function(e,res) {
              html_+=`
                <option value="${res.id}">${res.full_name}</option>
              `
            })
            $('#user_id').html(html_)
            $('#edit_user_id').html(html_)
          }
        })
    }

    function getSelectShift() {
      $.ajax({
        url: '<?= base_url('schedule_absensi/get_option_shift') ?>',
        type: "GET",
        success: function (result) {
          let response = JSON.parse(result)
          var html_ = `<option value="">--Choose Shift--</option>`
          $.each(response,function(e,res) {
            html_+=`
              <option value="${res.id_}">${res.shift_name} (${res.waktu_start} - ${res.waktu_end})</option>
            `
          })
          $('#shift_id').html(html_)
          $('#edit_shift_id').html(html_)
        }
      })
    }

    function edit(data) {
      //console.log(data)
      $('#id').val(data.id)
      $('#edit_schedule_date').val(data.date)
      $('#edit_user_id').val(data.user_id)
      $('#edit_shift_id').val(data.shift_id)
    }

    function save(type) {
      //var act = $('#act').val();
      var act = type;

      const arrErrorInput = handleValidation(act)
      if (arrErrorInput.length == 0) {
        //console.log("asasasa");
        
        if (act == "create") {
          var url = urlAddSchedule;
          var formData = new FormData(formScheduleAbsensi[0]);

          var buttons = $('#btnSave')
        } else {
          var url = urlUpdateSchedule; 
          var formData = new FormData(editformScheduleAbsensi[0]);

          var buttons = $('#btnEditSave')
        }

        var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving ... `  
        $(buttons).prop('disabled', true)
        $(buttons).html(loader)

        $.ajax({
          type: "POST",
          url: url,
          xhr: function() {
              var myXhr = $.ajaxSettings.xhr();
              return myXhr;
          },
          dataType: "json",
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          success: function (resp) {
              if (resp.status) {
                  console.log(resp);
                  swal("Success", resp.message, "success")
                  
                  //let buttons = $('#btnSave')
                  $(buttons).prop('disabled', false)
                  $(buttons).html("Save")

                  window.location.reload()
              }else{
                  swal("Upss!!!", resp.message, "warning")
                  
                  var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving ... `
                  //let buttons = $('#btnSave')
                  $(buttons).prop('disabled', true)
                  $(buttons).html(loader)
              }                
          },
          beforeSend: function(){
              var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving ... `
              //let buttons = $('#btnSave')
              $(buttons).prop('disabled', true)
              $(buttons).html(loader)
          },
          error: function (resp) {
              swal("Upss!!!", resp.message, "error")

              var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving ... `
              //let buttons = $('#btnSave')
              $(buttons).prop('disabled', true)
              $(buttons).html(loader)
          }
        }); 
      }
    }

    function hapus(id) {
      swal({
          title: "Are you sure ?",
          text: "Delete Schedule Absensi!",
          icon: "warning",
          showCancelButton: true
        },
        function(willAdd) {
          if(willAdd){
              console.log("delete");
              var formData = new FormData();
              formData.set("schedule_id", id)

              var url = urlDelScheduleAbsensi;

              $.ajax({
                  type: "POST",
                  url: url,
                  xhr: function() {
                      var myXhr = $.ajaxSettings.xhr();
                      return myXhr;
                  },
                  data: formData,
                  dataType: "json",
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function (resp) {
                      // swal({
                      //     text: "Success Delete Connection",
                      //     icon: "success",
                      // })
                      // window.location.reload()

                      if (resp.status) {
                        swal("Success", resp.message, "success")
                        window.location.reload()
                      }else{
                        swal("Upss!!!", resp.message, "warning")
                      }
                      
                  },
                  error: function (resp) {
                      swal("Upss!!!", resp.message, "error")
                  }
              });
          }
        })
    }

    $("#schedule_date").on('change', function(){
        $("#schedule_date").removeClass('is-invalid')
    })

    $("#user_id").on('change', function(){
        $("#user_id").removeClass('is-invalid')
    })

    $("#shift_id").on('change', function(){
        $("#shift_id").removeClass('is-invalid')
    })

    function handleValidation(type) {
        const schedule_date = $('input[name="schedule_date"]')
        const user_id = $('#user_id')
        const shift_id = $('#shift_id')

        const edit_schedule_date = $('input[name="edit_schedule_date"]')
        const edit_user_id = $('#edit_user_id')
        const edit_shift_id = $('#edit_shift_id')

        let arrErrorInput = []
        if (type == 'create') {
          // schedule_date
          if (schedule_date.val() == "") {
              arrErrorInput.push({ input_el: schedule_date, message: "Schedule Date is required" })
          }
          
          // user_id
          if (user_id.val() == "") {
              arrErrorInput.push({ input_el: user_id, message: "User ID is required" })
          }

          // shift_id
          if (shift_id.val() == "") {
              arrErrorInput.push({ input_el: shift_id, message: "Shift ID is required" })
          }
        } else if (type == 'update') {
          // schedule_date
          if (edit_schedule_date.val() == "") {
              arrErrorInput.push({ input_el: edit_schedule_date, message: "Schedule Date is required" })
          }
          
          // user_id
          if (edit_user_id.val() == "") {
              arrErrorInput.push({ input_el: edit_user_id, message: "User ID is required" })
          }

          // shift_id
          if (edit_shift_id.val() == "") {
              arrErrorInput.push({ input_el: edit_shift_id, message: "Shift ID is required" })
          }
        }

        arrErrorInput.forEach(el => {
            const inputEl = el.input_el
            const invalidFeedbackEl = $('.invalid-feedback', inputEl.parent())
            const message = el.message
            inputEl.addClass('is-invalid')
            invalidFeedbackEl.html(message)
        })
        return arrErrorInput
    }

    function processupload(event) {
      if (!event || !event.target || !event.target.files || event.target.files.length === 0) {
        return;
      }
      const name = event.target.files[0].name;
      const lastDot = name.lastIndexOf('.');
      const ext = name.substring(lastDot + 1);

      if (ext == "xlsx") {
        //var form = $('form')[0]; // You need to use standard javascript object here
        //var formData = new FormData(form);
        var formData = new FormData();
        // Attach file
        formData.append('files', $('input[type=file]')[0].files[0]);
        formData.append('type', "row")
        formData.append('b2b_token', "<?php echo $this->session->userdata('b2b_token'); ?>")
        
        $.ajax({
          url: urlProcessUpload,
          type: 'POST',
          data: formData,
          async: true,
          cache: true,
          contentType: false,
          processData: false,
          // beforeSend: function(){
          //     $("#loadingModal").removeClass("d-none");
          //     $("#loading-backdrop").removeClass("d-none");
          //     $("#bodyLoading").html("Loading Prepare render data excel ...")
          // },
          success:function(i) {
            openmodal(i)
          },
          
          // complete: function(){
          //     $("#loadingModal").removeClass("d-none");
          //     $("#loading-backdrop").removeClass("d-none");
          //     //window.location.reload() 
          //     $("#bodyLoading").html("Loading Rendering data excel ...") 
          // },
          error:function(exception){
            //sweetAlert("Perhatian",'Exeption:'+exception,"warning");
            swal("Perhatian", "Exeption:"+exception, "warning")
          }
        });
      }else{
        swal({
            title: "File yang anda masukkan bukan tipe Excel",
            icon: "error",
            timer: 4000
          })
      }
    }

    function processuploadcolumn(event) {
      if (!event || !event.target || !event.target.files || event.target.files.length === 0) {
        return;
      }
      const name = event.target.files[0].name;
      const lastDot = name.lastIndexOf('.');
      const ext = name.substring(lastDot + 1);

      if (ext == "xlsx") {
        var formData = new FormData();
        // Attach file
        formData.append('files', $('#exampleFormControlFile2')[0].files[0]);
        formData.append('type', "column")
        formData.append('b2b_token', "<?php echo $this->session->userdata('b2b_token'); ?>")

        console.log("column");
        
        $.ajax({
          url: urlProcessUpload,
          type: 'POST',
          data: formData,
          async: true,
          cache: true,
          contentType: false,
          processData: false,
          // beforeSend: function(){
          //     $("#loadingModal").removeClass("d-none");
          //     $("#loading-backdrop").removeClass("d-none");
          //     $("#bodyLoading").html("Loading Prepare render data excel ...")
          // },
          success:function(i) {
            openmodalColumn(i)
          },
          
          // complete: function(){
          //     $("#loadingModal").removeClass("d-none");
          //     $("#loading-backdrop").removeClass("d-none");
          //     //window.location.reload() 
          //     $("#bodyLoading").html("Loading Rendering data excel ...") 
          // },
          error:function(exception){
            //sweetAlert("Perhatian",'Exeption:'+exception,"warning");
            swal("Perhatian", "Exeption:"+exception, "warning")
          }
        });
      }else{
        swal({
            title: "File yang anda masukkan bukan tipe Excel",
            icon: "error",
            timer: 4000
        })
      }
    }

    function openmodal(datas) {
      console.log(datas);
      resp = `<div class="table-responsive">
                <table class='table mb-0'>
                  <thead>
                    <tr>
                      <th>Calendar Day (MM/DD/YYYY)</th>
                      <th>User</th>
                      <th>Shift Name</th>
                    </tr>
                  </thead>
                  <tbody>
                `;

      var newdatas = datas.datas_excel;
      var no = 1;
      for (const property in newdatas) {
        tr = newdatas[property];

        var tgl_val
        //console.log("calender day : ", tr[0]);
        if (tr['tgl'].length < 10) {
          tgl_val = "0"+tr['tgl']
        }else{
          tgl_val = tr['tgl']
        }

        var date = new Date(tgl_val);
        var newstr = ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear();

        resp += `<tr class="all-data">
                    <td>
                      <input type="text" name="tgl[]" id="tgl`+no+`" value="`+newstr+`" class="form-control " onkeyup="myFunction('`+no+`')">
                      <div class="invalid-feedback"></div>
                      <div id="suggestion-tgl`+no+`"></div>
                    </td>
                    <td>
                      <input type="text" name="full_name[]" id="full_name`+no+`" value="`+tr['full_name']+`" class="form-control " onkeyup="myFunctionUser('`+no+`')">
                      <input type="hidden" name="user_id[]" id="user_id`+no+`" value="`+tr['user_id']+`" class="form-control ">
                      <div class="invalid-feedback"></div>
                      <div id="suggestion-full_name`+no+`"></div>
                    </td>
                    <td>
                      <input type="text" name="kode_shift[]" id="kode_shift`+no+`" value="`+tr['kode_shift']+`" class="form-control " onkeyup="myFunctionShift('`+no+`')">
                      <input type="hidden" name="shift_id[]" id="shift_id`+no+`" value="`+tr['shift_id']+`" class="form-control ">
                      <div class="invalid-feedback"></div>
                      <div id="suggestion-kode_shift`+no+`"></div>
                    </td>   
                  </tr>`;
        no++;
      }

      resp += `</tbody></table></div>`;

      document.getElementById("content-here").innerHTML = resp
      document.getElementById("labelTotalRow").innerHTML = "Total Data : "+newdatas.length+" Rows"
      document.getElementById("type_import").value = "row"

      $('#new-table-modal').modal('show');
    }

    function openmodalColumn(datas) {
      console.log(datas);

      var total_header = datas.datas_header.length;
      var datas_header = datas.datas_header;
      var newdatas = datas.datas_excel;
      var datas_month = datas.datas_month;

      resp = `<div class="table-responsive">
                <table class='table table-bordered table-striped table-sm nowrap no-footer datatables-global mb-0' style="font-size:12px;margin-top: 0px !important;" width="100%">
                  <thead>
                    <!--<tr>
                      
                      <th rowspan="2" width="20%">Nama Karyawan</th>
                      <th colspan="`+total_header+`" width="80%">Tanggal</th>
                    </tr>-->
                    <tr>
                      <th width="20%">Nama Karyawan</th>
                    `;

                    var no =1;
                    for (let index = 0; index < datas_header.length; index++) {
                      const element = datas_header[index];
                      resp += `<th>Tanggal `+element[no]+`</th>`;

                      no++;
                    }

      resp += `           
                    </tr>   
                  </thead>
                  <tbody>
                `;

                  var urut = 1;
                  for (let i = 0; i < newdatas.length; i++) {
                    const element = newdatas[i];
                    var fields = element[1].split('_');
                    resp += `<tr class="all-data">
                              
                              <td>
                                <input type="text" name="full_name[]" id="full_name`+urut+`" value="`+fields[0]+`" class="form-control " onkeyup="myFunctionUser('`+urut+`')">
                                <input type="hidden" name="user_id[]" id="user_id`+urut+`" value="`+fields[1]+`" class="form-control">
                                <input type="hidden" name="total_column" id="total_column" value="`+datas_header.length+`" class="form-control">
                                <input type="hidden" name="datas_month" id="datas_month" value="`+datas_month+`" class="form-control">
                                <div class="invalid-feedback"></div>
                                <div id="suggestion-user_id`+urut+`"></div>
                              </td>`;
                              var no1 = 2;
                              var urut2 = 1;
                              for (let index = 0; index < datas_header.length; index++) {
                                const col = datas_header[index];
                                const element2 = newdatas[i][no1];
                                var fields2 = element2.split('_');                                 
                                // console.log(element2);
                                // console.log("no : ",no1);
                                //resp += `<th>`+element[no1]+`</th>`;
                                resp += `<td class="all-column">
                                          <input type="text" name="tgl`+urut+`_`+urut2+`" id="tgl`+urut+`_`+urut2+`" value="`+fields2[0]+`" class="form-control " onkeyup="myFunctionShift2('`+urut+`','`+urut2+`')">
                                          <input type="hidden" name="shift_id`+urut+`_`+urut2+`" id="shift_id`+urut+`_`+urut2+`" value="`+fields2[1]+`" class="form-control">
                                          <div class="invalid-feedback"></div>
                                          <div id="suggestion-shift_id`+urut+`"></div>
                                        </td>`;

                                no1++;
                                urut2++;
                              }
                    urut++;
                    resp += `</tr>`;
                  }
      
      resp += `</tbody></table></div>`;

      document.getElementById("content-here").innerHTML = resp
      document.getElementById("labelMonth").innerHTML = "Bulan : "+datas_month;
      document.getElementById("labelTotalRow").innerHTML = "Total Data : "+newdatas.length+" Rows"
      document.getElementById("type_import").value = "column"

      $('#new-table-modal').modal('show');
    }

    $("#save_new_table").on("click", function () {
      //console.log($('#type_import').val());
      var type_import = $('#type_import').val();

      const arrErrorInput = handleValidationImport(type_import)      
      //const arrErrorInput = handleValidationImport()

      if (arrErrorInput.length == 0) {
        console.log("save data");
        var formData = new FormData(formTable[0]);
        formData.set("b2b_token", "<?php echo $this->session->userdata('b2b_token'); ?>")
        formData.set("created_by", "<?php echo $this->session->userdata('id'); ?>")

        if (type_import == "row") {
          var url = urlProcessSaveData;  
        }else{
          var url = urlProcessSaveDataColumn;
        }
        

        var loader = `<i class="fa fa-spinner fa-spin"></i> Simpan Data...`;
        $('#save_new_table').html(loader);
        $('#save_new_table').prop('disabled', true);

        $.ajax({
              type: "POST",
              url: url,
              xhr: function() {
                  var myXhr = $.ajaxSettings.xhr();
                  return myXhr;
              },
              data: formData,
              cache: false,
              contentType: false,
              processData: false,
              success: function (resp) {
                if (resp.status) {
                  console.log(resp);
                  swal("Success", resp.message, "success")
                  
                  let buttons = $('#save_new_table')
                  $(buttons).prop('disabled', false)
                  $(buttons).html("Save")

                  window.location.reload()
                }else{
                    swal("Upss!!!", resp.message, "warning")
                    
                    var loader = `<i class="fa fa-spinner fa-spin"></i>&nbsp; Simpan Data... `
                    let buttons = $('#save_new_table')
                    $(buttons).prop('disabled', true)
                    $(buttons).html(loader)
                }
              },
              beforeSend: function(){
                var loader = `<i class="fa fa-spinner fa-spin"></i> Simpan Data...`;
                $('#save_new_table').html(loader);
                $('#save_new_table').prop('disabled', true);
              },
              error: function (resp) {
                swal("Upss!!!", resp.message, "warning")

                var loader = `<i class="fa fa-spinner fa-spin"></i> Simpan Data...`;
                $('#save_new_table').html(loader);
                $('#save_new_table').prop('disabled', true);
              }
          });
      }else{
        
        arrErrorInput.forEach(el => {
          console.log("error, "+el);
        })
      }
    })

    function handleValidationImport(type_import) {
      var numItems = $('.all-data').length
      var numColumns = $('.all-column').length

      var no = 1;
      let arrErrorInput = []
      for (let index = 0; index < numItems; index++) {
        if (type_import == "row") {
          const tgl = $('#tgl'+no)
          const full_name = $('#full_name'+no)
          const kode_shift = $('#kode_shift'+no)

          const dateReg = RegExp(/^\d{2}[/]\d{2}[/]\d{4}$/gi)

          if (tgl.val() == "") {
            arrErrorInput.push({ input_el: tgl, message: "Tanggal is required" })
          } else if (!dateReg.test(tgl.val())) {
          //} else if (tgl.val().match(dateReg)) {
            arrErrorInput.push({ input_el: tgl, message: "Tanggal is not valid" })
          }

          // full_name
          if (full_name.val() == "") {
            arrErrorInput.push({ input_el: full_name, message: "User is required" })
          }

          // kode_shift
          if (kode_shift.val() == "") {
            arrErrorInput.push({ input_el: kode_shift, message: "Kode Shift is required" })
          } 
        }else{
          const full_name = $('#full_name'+no)
          
          var no2 = 1;
          for (let j = 0; j < numColumns; j++) {
            const tgl = $('#tgl'+no+'_'+no2)

            // tgl
            if (tgl.val() == "") {
              arrErrorInput.push({ input_el: tgl, message: "Tanggal "+no2+" is required" })
            }

            no2++;
          }

          // full_name
          if (full_name.val() == "") {
            arrErrorInput.push({ input_el: full_name, message: "User is required" })
          }
        }

        no++;
      }

      arrErrorInput.forEach(el => {
          const inputEl = el.input_el
          const invalidFeedbackEl = $('.invalid-feedback', inputEl.parent())
          const message = el.message
          inputEl.addClass('is-invalid')
          invalidFeedbackEl.html(message)
      })
      return arrErrorInput
    }

    function myFunction(id) {
      var x = document.getElementById("tgl"+id).value;

      if (x) {
        const dateReg = RegExp(/^\d{2}[/]\d{2}[/]\d{4}$/gi)

        if (!dateReg.test(x)) {
          const tgl = $('#tgl'+id)
          let arrErrorInput = []
          arrErrorInput.push({ input_el: tgl, message: "Tanggal is not valid" })

          arrErrorInput.forEach(el => {
              const inputEl = el.input_el
              const invalidFeedbackEl = $('.invalid-feedback', inputEl.parent())
              const message = el.message
              inputEl.addClass('is-invalid')
              invalidFeedbackEl.html(message)
          })
        }else{
          $("#tgl"+id).removeClass('is-invalid')
        }
      }
    }

    function myFunctionUser(id) {
      var x = document.getElementById("full_name"+id).value;

      if (x) {
        $("#full_name"+id).removeClass('is-invalid')
      }
    }

    function myFunctionShift(id) {
     var x = document.getElementById("kode_shift"+id).value;

      if (x) {
        $("#kode_shift"+id).removeClass('is-invalid')
      }
    }

    function myFunctionShift2(id,id2) {
     var x = document.getElementById("tgl"+id+"_"+id2).value;

      if (x) {
        $("#tgl"+id+"_"+id2).removeClass('is-invalid')
      }
    }

    $("#filterDate").append(`
        <div class="col-md-4">
          <div class="dt-buttons" id="btnRow"> 
            <a href="<?= base_url('assets/template-import/template-import-row.xlsx') ?>" target="_blank" class="btn btn-success remove-radius mb-2" title="Download Template Row Import">
                <span class="typcn typcn-download"></span> Template Row
            </a>
            <a class="btn btn-primary remove-radius mb-2" title="Import Template Row" style="color: white;">
                <label for="exampleFormControlFile1" style="margin-bottom: 0;"><span class="typcn typcn-upload"></span> Import Row</label>
                <input type="file" class="upload up" name="userfile" id="exampleFormControlFile1" onchange="processupload(event);" style="display: none"/>
            </a> 
          </div>

          <div class="dt-buttons" id="btnColumn"> 
            <a href="<?= base_url('assets/template-import/template-import-column.xlsx') ?>" target="_blank" class="btn btn-success remove-radius mb-2" title="Download Template Row Import">
                <span class="typcn typcn-download"></span> Template Column
            </a>
            <a class="btn btn-primary remove-radius mb-2" title="Import Template Column" style="color: white;">
                <label for="exampleFormControlFile2" style="margin-bottom: 0;"><span class="typcn typcn-upload"></span> Import Column</label>
                <input type="file" class="upload up" name="userfile2" id="exampleFormControlFile2" onchange="processuploadcolumn(event);" style="display: none"/>
            </a> 
          </div>
        </div>
        <div class="col-md-5">
            <div class=" row  mb-0   ">
                <label class="col-md-2 right mr-2  mt-2 ">Filter :&nbsp;</label>
                <select class="month form-control js-filter form-control-sm mr-1 col-md">
                    <?php for ($m = 1; $m <= 12; $m++) {
                        $month_label = date('F', mktime(0, 0, 0, $m, 1));
                    ?>
                        <option <?= date("m", strtotime($month_label)) == date("m") ? 'selected' : '' ?> value="<?= date("m", strtotime($month_label)); ?>"><?= $month_label; ?></option>
                    <?php } ?>
                </select>
                <select class="year form-control js-filter form-control-sm col-md">
                    <?php
                    $year = date('Y');
                    $min = $year - 1;
                    $max = $year;
                    for ($i = $max; $i >= $min; $i--) { ?>
                        <option value='<?= $i ?>'><?= $i ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-3"></div>
    `);
</script>