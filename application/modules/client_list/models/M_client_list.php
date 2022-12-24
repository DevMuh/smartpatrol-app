<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_client_list extends CI_Model
{

    public function fetch()
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->order_by('id_', 'desc')->select(['id_', 'no_kavling', 'client_name', 'phone', 'username'])->where('flag_disable', 1)->where('b2b_token', $b2b)->get('m_client')->result();
        $data = array();
        $i = 1;
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $i;
            $temp[] = $row->no_kavling;
            $temp[] = $row->client_name;
            $temp[] = $row->username;
            $temp[] = $row->phone;
            $temp[] = "<button data-toggle='modal' data-target='#editModal' onclick='edit($row->id_)' class='btn mybt'><span class='typcn typcn-pencil'></span></button> <button data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id_)' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }
    public function tambah_client()
    {
        $this->form_validation->set_rules('no_kavling', 'No Kavling', 'required');
        $this->form_validation->set_rules('client_name', 'Client Name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required|integer|min_length[8]|max_length[15]');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('no_kavling'), form_error('client_name'), form_error('phone')];
        } else {

            $data['flag_disable'] = 1;
            $data['b2b_token'] = $this->session->userdata('b2b_token');
            $data['no_kavling'] = $this->input->post('no_kavling');
            $data['client_name'] = $this->input->post('client_name');
            $data['phone'] = $this->input->post('phone');

            $this->db->insert('m_client', $data);
            return 1;
        }
    }
    public function edit_client()
    {
        $this->form_validation->set_rules('eno_kavling', 'No Kavling', 'required');
        $this->form_validation->set_rules('eclient_name', 'Client Name', 'required');
        $this->form_validation->set_rules('ephone', 'Phone', 'required|integer|min_length[8]|max_length[15]');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('eno_kavling'), form_error('eclient_name'), form_error('ephone')];
        } else {
            $id = $this->input->post('eid');
            $data['flag_disable'] = 1;
            $data['no_kavling'] = $this->input->post('eno_kavling');
            $data['client_name'] = $this->input->post('eclient_name');
            $data['phone'] = $this->input->post('ephone');

            $this->db->where('id_', $id)->update('m_client', $data);
            return 1;
        }
    }
    public function hapus_client()
    {
        $id = $this->input->post('hid');
        $data['flag_disable'] = 0;
        $this->db->where('id_', $id)->update('m_client', $data);
        return $id;
    }
}
                        
/* End of file x.php */
