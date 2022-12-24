<script src="<?=base_url('assets/apps/assets/plugins/datatables/dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js')?>"></script>
<script>
    // $('#1Approval').addClass('mm-active');
    $('#1Approval').addClass('mm-active');
    $('#subm').css({'box-shadow': 'none', 'background' : 'transparent', 'color': 'white'});
    $('#2Approval').css({'color': '#c81b1b', 'border-radius':'4px', 'background': 'white'});

	$(document).ready(function() {
        

        var table = $('#tb_approval').DataTable({
                        order        : [ 0, "desc" ],
                        columnDefs: [{   
                                "targets": [0],
                                "searchable": false
                            }],
                        responsive   : true,
                        autoWidth    : false,
                        processing   : true,
                        serverside   : true,
                        ajax         : '<?=base_url('approval/ajax')?>'
                    });        
    }); //end document ready

        function confirmChangeStatus(id, name)
        {
            // $('#change').attr('onclick','change('+id+')');
            $('#change').attr("onclick", "changeStatusFromDetail("+id+", 'table')")
            $('#change').removeClass('change_status_from_detail')
            $('#askChangeStatus').html('Are you sure want to change status <b>'+name+'</b> ?')
            $('#modalChange').modal();
        }

        function change(id)
        {
            console.log('fungsi merubah')
        }

        function detail(id)
        {
            $('#modalDetail').modal();
            $.ajax({
                url     : '<?=base_url($this->uri->segment(1))?>/get_by_id',
                method  : 'GET',
                data    : 'id='+id,
                beforeSend  : function( xhr ) {
                    $('#container-loading').addClass('d-flex')
                    $('#loading').fadeIn()
                    $('#content-detail').fadeOut()
                    $('#modal_status').fadeOut()
                },
                success : function(response){
                    $('#modal_status').fadeIn()
                    $('#container-loading').removeClass('d-flex')
                    $('#container-loading').fadeOut()
                    $('#loading').fadeOut()
                    $('#content-detail').fadeIn(1000)
                    var logo = JSON.parse(response).path_logo
                    var doc  = JSON.parse(response).path_doc
                    var status = JSON.parse(response).flag_active
                    var id = JSON.parse(response).id_
                    var name= JSON.parse(response).pic


                    $('#company').html(JSON.parse(response).title_nm)
                    $('#pic').html(JSON.parse(response).pic)
                    $('#email').html(JSON.parse(response).email)
                    $('#phone').html(JSON.parse(response).phone)
                    $('#address').html(JSON.parse(response).alamat)
                    if(doc!=null){
                        $('#document').html('<a href="" id="openPdf" data-name="'+doc+'"><img src="<?=base_url()?>assets/apps/assets/document.png" width="40px"></a>')

                    }else{
                        $('#document').html('<span class="badge badge-pill badge-danger">No Document</span>')
                    }

                    if(logo!=null){
                        $('#image').html('<a href="" id="openImage" data-name="'+logo+'"><img src="<?=base_url()?>assets/apps/images/'+logo+'" width="150px"></a>')
                    }else{
                        $('#image').html('<img src="<?=base_url()?>assets/apps/assets/no_image.png" width="150px">')
                    }

                    if(status!=1){
                        $('#modal_status').html("<a href='$url' onclick='confirmChangeStatusModal("+id+",\"\")' data-target='#modalChange' data-toggle='modal' class='btn btn-sm btn-secondary'>Not Active</a>")
                    }else{
                        $('#modal_status').html("<a href='' onclick='confirmChangeStatusModal("+id+",\"\")' data-target='#modalChange' data-toggle='modal' class='btn btn-sm btn-success'> Active</a>")
                    }
                    
                }
            })
        }


        $(document).on('click', '#openImage', function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#modalDetail').modal('hide')
            $('#showImage').modal({backdrop: 'static'})
            $('#modalImage').attr('src', '<?=base_url()?>assets/apps/images/'+name)
        })

        $(document).on('click', '#closeImage', function() {
                $('#showImage').modal('hide')
                $('#modalDetail').modal('show')
        })


        $(document).on('click', '#openPdf', function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#modalDetail').modal('hide')
            $('#showPdf').modal({backdrop:'static'})
            var viewer = $('#show_document');
            PDFObject.embed('<?=base_url()?>assets/apps/document/'+name, viewer);
        })

        $(document).on('click', '#closePdf', function() {
                $('#showPdf').modal('hide')
                $('#modalDetail').modal('show')
        })


        function confirmChangeStatusModal(id, name)
        {
            $('#modalDetail').modal('hide');
            $('#change').attr('onclick', 'changeStatusFromDetail('+id+')');
            $('#askChangeStatus').html('Are you sure want to change status <b>'+name+'</b> ?')
            $('#change').addClass('change_status_from_detail');
            $('#modalChange').modal();
        }


        function changeStatusFromDetail(id, from){
            $.ajax({
                url     : '<?=base_url('approval/change_status/')?>'+id+'?ajax=true',
                method  : 'POST',
                dataType: 'JSON',
                success : function(response){
                    if(from=="table"){
                        $('#tb_approval').DataTable().ajax.reload( null, false )
                        $('#modalChange').modal('hide')
                    }else{
                        $('#modalDetail').modal('show')
                        $('#modalChange').modal('hide')
                        $('#tb_approval').DataTable().ajax.reload()
                        detail(id)
                    }
                }
            })
        }

</script>