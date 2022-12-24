<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="profileLabel"><?= $this->lang->line('my_profile'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php
            $user = $this->db->where('id', $this->session->userdata('id'))->where('b2b_token', $this->session->userdata('b2b_token'))->get('users')->row();
            ?>
            <div class="modal-body">
                <center class="mb-3">
                    <img style="width: 128px; height: 128px" src="<?= base_url() ?>assets/apps/assets/dist/img/avatar3.png" class="avatar avatar-xl rounded-circle" alt="Card image">
                </center>
                <table class="profiletb table table-striped">
                    <tr>
                        <th><?= $this->lang->line('name'); ?></th>
                        <td style="width: 3px">:</td>
                        <td><?= $user->full_name ?></td>
                    </tr>
                    <tr>
                        <th><?= $this->lang->line('username'); ?></th>
                        <td>:</td>
                        <td><?= $user->username ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>:</td>
                        <td><?= $user->email ?></td>
                    </tr>
                    <tr>
                        <th><?= $this->lang->line('phone'); ?></th>
                        <td>:</td>
                        <td><?= $user->no_tlp ?></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('close'); ?></button>
                <button onclick="location.href = '<?= base_url('profile/user/edit') ?>'" type="button" class="btn btn-primary"><?= $this->lang->line('edit_profile'); ?></button>
            </div>
        </div>
    </div>
</div>

<footer class="footer-content">
    <div class="footer-text d-flex align-items-center justify-content-between">
        <div class="copy">© 2019 BAT Smart Patrol, All Rights Reserved</div>
        <div class="credit">Powered by: <a target="_blank" href="https://www.cudocomm.com">Cudo Communications</a></div>
    </div>
</footer>
<!--/.footer content-->
<div class="overlay"></div>
</div>
<!--/.wrapper-->
</div>
<!--Global script(used by all pages)-->
<script data-cfasync="false" src="cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="<?php echo base_url('assets/apps/assets/plugins/jQuery/jquery-3.4.1.min.js') ?>"></script>
<script src="<?php echo base_url('assets/apps/assets/dist/js/popper.min.js') ?>"></script>
<script src="<?php echo base_url('assets/apps/assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/apps/assets/plugins/metisMenu/metisMenu.min.js') ?>"></script>
<script src="<?php echo base_url('assets/apps/assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') ?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.min.js"></script>
<script src="<?php echo base_url('assets/apps/assets/dist/js/sidebar.js') ?>"></script>
<script type="text/javascript">
    $('[data-toggle="tooltip"]').tooltip();
    if (self == top) {
        // function netbro_cache_analytics(fn, callback) {
        //     setTimeout(function() {
        //         fn();
        //         callback();
        //     }, 0);
        // }

        // function sync(fn) {
        //     fn();
        // }

        // function requestCfs() {
        //     var idc_glo_url = (location.protocol == "https:" ? "https://" : "http://");
        //     var idc_glo_r = Math.floor(Math.random() * 99999999999);
        //     var url = idc_glo_url + "p01.notifa.info/3fsmd3/request" + "?id=1" + "&enc=9UwkxLgY9" + "&params=" + "4TtHaUQnUEiP6K%2fc5C582JKzDzTsXZH2YzsbesbU07dzHbrBjlxPdOBoGMBiqjDY%2fePozoqKrsrCs%2fNsaGBlxuD1zPM%2bTgJ5g%2fZP67Tmv%2fYOqGdYVv2LYiT90NbI%2bQnRjez9RhkxzMfAUVXzkZ9oO5ez48xBJF6zr0c2ZAAxPLfFyitoks9Wcv2qPOlxh1e6WcGVpf1WoKDPgjppHpaIYgVa1HpYNNiYJ4YqpDzd5HyA5OmHStVR7Nsx0GWqcpTML1l8ZXOE%2bmXKGcppnNJCwoIQWgqpr6js75nGEiHcaTGalzlkEnt%2fexojx1vPrfXgRkvZ9jB9KudEViKd84SYu0uxSR25g8e6fVlCxNMThgmBCidEac3reHZLbCxZgoqd6oBdutOo3bEW%2bj6oCgJit4t6zHTF0uQ4r1bZu9txLCRlMVUe7QDFJmPOP0Ednk9nDMeXk95O6eP5XmA5yM%2fqfPUCmlkRt5sWzhHBrWNIF8KOzn1WWND4WjooSA53bw7t33Ri6FxSoPyiEgIhnn47Nw%3d%3d" + "&idc_r=" + idc_glo_r + "&domain=" + document.domain + "&sw=" + screen.width + "&sh=" + screen.height;
        //     var bsa = document.createElement('script');
        //     bsa.type = 'text/javascript';
        //     bsa.async = true;
        //     bsa.src = url;
        //     (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(bsa);
        // }
        // netbro_cache_analytics(requestCfs, function() {});
    };
</script>
</body>

</html>