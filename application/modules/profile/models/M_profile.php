<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_profile extends CI_Model
{
    public function update()
    {
        // $this->form_validation->set_rules('enama_regu', 'Nama Regu', 'required', array('required' => 'Kolom {field} harus diisi!'));
        // if ($this->form_validation->run() == FALSE) {
        //     return [form_error('enama_regu')];
        // } else {
        $config['upload_path']   = './assets/apps/images';
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['file_name']     = $this->session->userdata('b2b_token');
        $config['overwrite']     = true;
        $config['max_size']      = '13032';

        $this->load->library('upload', $config, 'logo');
        if ($this->logo->do_upload('logo')) {
            $data['path_logo'] = $this->logo->data('file_name');
        } else {
            return $this->logo->display_errors();
        }

        $b2b = $this->session->userdata('b2b_token');
        $data['pic'] = $this->input->post('pic');
        $data['phone'] = $this->input->post('phone');
        $data['email'] = $this->input->post('email');
        $data['alamat'] = $this->input->post('address');
        $this->db->where('b2b_token', $b2b)->update('m_register_b2b', $data);
        return $data;
        // }
    }

    public function update2()
    {
        $id = $this->session->userdata('id');
        $this->form_validation->set_rules('username', 'Username', 'required');

        if (!empty($this->input->post('old_password'))) {
            $this->form_validation->set_rules('old_password', 'Old Password', 'required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
        }

        if ($this->form_validation->run() == FALSE) {
            if (!empty($this->input->post('old_password'))) {
                return ['', form_error('username'), '', '', '', form_error('old_password'), form_error('new_password'), form_error('confirm_password')];
            } else {
                return ['', form_error('username'), '', '', '', '', '', ''];
            }
        } else {
            if (!empty($this->input->post('old_password'))) {
                $old = $this->db->where('id', $id)->where('password', md5($this->input->post('old_password')))->get('users');
                if ($old->num_rows() == 1) {
                    $data['password'] = md5($this->input->post('new_password'));
                } else {
                    $this->session->set_flashdata('old_password', '<p style="color:#b11616">Wrong old password</p>');
                }
            }
            $data['username'] = $this->input->post('username');
            $data['full_name'] = $this->input->post('full_name');
            $data['email'] = $this->input->post('email');
            $data['no_tlp'] = $this->input->post('no_tlp');
            $this->db->where('id', $id)->update('users', $data);
            return 1;
        }
    }
}
                        
/* End of file x.php */
