<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_domain')) {
    function get_domain()
    {
        $CI = &get_instance();
        return preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", "$1", $CI->config->slash_item('base_url'));
    }
}
