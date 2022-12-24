<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_global extends CI_Model
{

    public function realip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return "IN";
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

        return $details->country;
    }

    public function firstload()
    {

        if (!$this->input->cookie('remember_me', TRUE)) {
            if (!$this->session->userdata('b2b_token') || !$this->session->userdata('status') || $this->session->userdata('status') != 'active') {
                redirect('', 'refresh');
            }
        }

        $allow = false;
        $table_id = json_decode($this->session->userdata('table_id'))->id;
        $menus = $this->session->userdata('table');
        foreach ($menus as $m) {
            if (stripos($m->link, $this->uri->segment(1)) !== FALSE || stripos($m->link, $this->uri->segment(2)) !== FALSE) {
                if (in_array($m->id, $table_id)) {
                    $allow = true;
                    break;
                }
            }
        }
        if ($table_id[0] == 'ALL') {
            $allow = true;
        }
        if (!$allow) {
            redirect('not_found', 'refresh');
        } else {
            if ($this->realip() == 'ID') {
                $this->lang->load('information_lang', 'indonesia');
            } else {
                $this->lang->load('information_lang', 'english');
            }
        }
    }
}
                        
/* End of file x.php */
