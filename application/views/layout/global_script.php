<script>
    function element(index, modal = true) {
        if (modal) {
            return ".show form .form-group:eq(" + index + ")"
        } else {
            return "form .form-group:eq(" + index + ")"
        }
    }

    function subm(uri, modal = true) {
        $.ajax({
            type: "POST",
            url: "<?= base_url() ?>" + uri,
            data: $('form').serialize(),
            dataType: "json",
            beforeSend: function() {
                $('#loadd').html('<div class="page-loader-wrapper"><div class="loader"><div class="preloader"><div class="spinner-layer pl-green"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div><p>Please wait...</p></div></div>')
            },
            success: function(resp) {
                if (resp == 1) {
                    location.reload()
                } else {
                    $('#loadd').html('')
                    console.log(resp.length);

                    for (i = 0; i < resp.length; i++) {
                        console.log('0', i);

                        if (resp[i]) {
                            console.log(i);

                            $(element(i, modal) + ' p').remove()
                            $(element(i, modal)).append(resp[i]);
                        } else {
                            $(element(i, modal) + ' p').remove()
                        }
                    }
                }
            },
            error: function(data) {
                $('#loadd').html('')
                alert('Invalid input data!');
            }
        })
        return false
    }
    $("#tambah").on('click', function() {
        $('form .form-group p').remove()
        $('.form-control').val('')
    })

    function validate(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }
</script>