<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_kop_surat extends CI_Model
{
    public function edit_kop()
    {
        $config['upload_path']         = './assets/apps/images';
        $config['allowed_types']     = 'jpeg|jpg|png';
        $config['file_name']        = $this->session->userdata('b2b_token');
        $config['overwrite']        = true;
        $config['max_size']          = '13032';

        $islogo = '';
        $logo = '';
        $field = '';
        $this->load->library('upload', $config, 'logo');
        if ($this->logo->do_upload('logo')) {
            $islogo = $this->logo->data('file_name');
            $logo = ", logo = '" . $islogo . "'";
            $field = ", logo";
            $islogo = ", '$islogo'";
        }

        $kop = $this->input->post('kop');
        $b2b = $this->session->userdata('b2b_token');
        if ($kop == null) {
            $this->session->set_flashdata('error_kop', 'Field kop tidak boleh kosong!');
        } else {
            $this->db->query("INSERT INTO m_kop_surat 
            (kop, b2b_token$field)
            VALUES ('$kop', '$b2b'$islogo)
            ON CONFLICT (b2b_token)
            DO UPDATE SET kop = '$kop'" . $logo);
            return 1;
        }
    }
}
                        
/* End of file x.php */
