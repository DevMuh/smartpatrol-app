<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<script type="text/javascript">
	$(document).ready(function(){
		$('.container-loading').fadeOut();
        $('.center-loading').fadeOut();

		$(document).on('submit','.signupForm', function(e){
			e.preventDefault()
			var data = new FormData(this)
			$.ajax({
                url         : '<?php echo base_url() ?>register',
                method      : 'POST',
                contentType : false,
                cache       : false,
                processData : false,
                data        : new FormData(this),
                beforeSend	: function( xhr ) {
                    $('.container-loading').fadeIn();
        			$('.center-loading').fadeIn();
                },
                success     : function(response) {
                	$('.container-loading').fadeOut();
        			$('.center-loading').fadeOut();
                	var type 	= JSON.parse(response).type
                	var message = JSON.parse(response).message

                	if(type=='success'){
                		$(".signupForm").trigger("reset");
                		$('.panel-login').addClass('active')
                		$('.panel-signup').removeClass('active')
                	}else{
                		$('input[name ="password"]').val('')
                	}
                	
                	Alert(type,message)
                }
            })
		})



	})

	function Alert(type, message){
        Swal.fire({
        	html : true,
            title: type,
            html : message,
            type : type,
        })
    }


</script>