<?php if(!defined('BASEPATH')) exit('no direct access script allowed');

if (!function_exists('get_img_to_server_other'))
{
  	function get_img_to_server_other($base_url) {
    	$ci=& get_instance();
        $ci->load->library('curl');
        $result = $ci->curl->simple_get($base_url,array(),array(CURLOPT_TIMEOUT_MS => 155));

        if ($result) {
            return true;
        }else{
            return false;
        }
  	}
}