<script src="<?=base_url('assets/apps/assets/plugins/datatables/dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js')?>"></script>
<script>
    // $('#1Approval').addClass('mm-active');
    $('#1Approval').addClass('mm-active');
    $('#subm').css({'box-shadow': 'none', 'background' : 'transparent', 'color': 'white'});
    $('#2Approval').css({'color': '#c81b1b', 'border-radius':'4px', 'background': 'white'});

    $(document).ready(function() {
        

        var table = $('#tb_data').DataTable({ //anggotas replacement
                        order        : [ 0, "asc" ],
                        columnDefs: [{   
                                "targets": [0],
                                "searchable": false,
                                "visible":false
                            }],
                        responsive   : true,
                        autoWidth    : false,
                        processing   : true,
                        serverside   : true,
                        ajax         : '<?=base_url('pergantian/ajax')?>'
                    });

                });


    $(document).ready(function(){
        var table = $('#tb_post').DataTable({ //anggota no replacement
                        order        : [ 0, "desc" ],
                        columnDefs: [{   
                                "targets": [0],
                                "searchable": false,
                                "visible":false
                            }],
                        responsive   : true,
                        autoWidth    : false,
                        processing   : true,
                        serverside   : true,
                        ajax         : '<?=base_url('pergantian/anggota_no_replacement')?>'
                    });
                })


    function pindah(id, regu){
        $('#modalGanti').modal('show')
        $.ajax({
            url         : '<?=base_url($this->uri->segment(1).'/ganti')?>',
            method      : 'POST',
            cache       : false,
            processData : false,
            data        : "id="+id+"&regu_id="+regu,
            success     : function(response){
                $('#container-loading').removeClass('d-flex')
                $('#container-loading').fadeOut()
                $('#loading').fadeOut()
                $('#content-detail').fadeIn(1000)

                $('#idAnggota').val(id)
                
                var toJson  = JSON.parse(response);
                var myOptions = {
                    val1 : 'text1',
                    val2 : 'text2'
                };

                toJson.forEach(function(item, index) {
                    $("#select").append("<option value='"+item.id+"'>"+item.nama_regu+"</option>")
                })


            }
        })
    }



        $(document).on('submit', '#formPindah', function(e){
            e.preventDefault();
            $.ajax({
                url         :'<?=base_url($this->uri->segment(1).'/pindah')?>',
                method      : 'POST',
                contentType : false,
                cache       : false,
                processData : false,
                data        : new FormData(this),
                beforeSend: function( xhr ) {
                    $('#loading').addClass('loading')
                },
                success     : function(response){
                    $('#loading').removeClass('loading')    
                    $('#modalGanti').modal('hide')  
                    $('#tb_post').DataTable().ajax.reload( null, false )
                }
            })
        })


        
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });


</script>