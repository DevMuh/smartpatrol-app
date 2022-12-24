<script src="<?=base_url('assets/apps/assets/plugins/datatables/dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/apps/assets/plugins/datatables/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('assets/apps/assets/plugins/modals/classie.js')?>"></script>
<script src="<?=base_url('assets/apps/assets/plugins/modals/modalEffects.js')?>"></script>
<script>
	// <?php 
    //     if(validation_errors()){
    //         echo validation_errors();
    //     } else {
    //         echo "a";
    //     }
    // ?>
	
    $('#1Incident').addClass('mm-active');
    $('#subm_Master').css({'box-shadow': 'none', 'background' : 'transparent', 'color': 'white'});
    $('#2Incident').css({'color': '#c81b1b', 'border-radius':'4px', 'background': 'white'});
	$(document).ready(function() {
        $('#tb_kejadian').DataTable({
			responsive   : true,
            autoWidth    : false,
            processing   : true,
            serverside   : true,
            ajax         : '<?=base_url('project_progress/ajax')?>'
        });
	});


	function edit(id){
        $('form .form-group p').remove()
		document.getElementById('editId').value = id
		$('.btn').on('click', function(){
			$(this).addClass('btns');
			var temp = document.getElementsByClassName('btns')[0].offsetParent.parentElement.getElementsByTagName('td')
			var kategori = temp[1].innerHTML;
			var keterangan = temp[2].innerHTML;
			$('input[name=ekategori_name]').val(kategori);
			$('textarea[name=eketerangan]').val(keterangan);
			$(this).removeClass('btns');
		})
		
	}
	function hapus(id) {
        document.getElementById('hid').value = id
        $('.btn').on('click', function() {
            $(this).addClass('btns');
            var temp = document.getElementsByClassName('btns')[0].offsetParent.parentElement.getElementsByTagName('td')
            var dt = temp[1].innerHTML;
            $("#deltitle").html("Delete incident "+dt+"?")
            // $('input[name=ecluster_name]').val(cluster);
            $(this).removeClass('btns');
        })
    }
</script>