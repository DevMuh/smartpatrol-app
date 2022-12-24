<script>
    $('#Guest').addClass('mm-active');
    $('.myImage').on('click', function() {
        $('#myModal').modal('show');
        // console.log($(this).attr('src'))
        var src = $(this).attr('src')
        $('#img_modal').attr('src', src)
    })
</script>