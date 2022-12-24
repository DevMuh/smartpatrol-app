<script src="<?= base_url('assets/apps/assets/plugins/modals/classie.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/modals/modalEffects.js') ?>"></script>
<script src="<?= base_url('assets/apps/assets/plugins/ckeditor/ckeditor.js')?>"></script>
<script>
    
    $('#1Kop_surat').addClass('mm-active');
    $('#subm_Master').css({'box-shadow': 'none', 'background' : 'transparent', 'color': 'white'});
    $('#2Kop_surat').css({'color': '#c81b1b', 'border-radius':'4px', 'background': 'white'});
    $(document).ready(function() {
        CKEDITOR.replace('editor1');
    });

    <?php
        if($this->session->flashdata('error_kop')){
            echo "alert('".$this->session->flashdata('error_kop')."')";
        }
    ?>

    function edit() {
        var temp = document.getElementsByTagName('tbody')[0].getElementsByTagName('tr')[0].getElementsByTagName('td')
        var a = temp[1].innerHTML;
        $('#editor1').val(a);
        console.log(a)
    }
</script>