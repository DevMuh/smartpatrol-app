<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_project extends CI_Model
{

    public function fetch()
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->order_by('id', 'desc')->where('b2b_token', $b2b)->where('flag_active', 1)->get('t_project')->result();
        $data = array();
        $i = 1;
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $i;
            $temp[] = $row->kode;
            $temp[] = $row->nama_project;
            $temp[] = "<a href='".base_url('project/frm_edit/'.$row->id.'/'.$row->id_geofence)."' class='btn mybt'><span class='typcn typcn-pencil'></span></a> <button data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id)' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function tambah_project()
    {
        $this->form_validation->set_rules('nama', 'Nama Project', 'required', array('required' => 'Field %s harus diisi!'));
        $this->form_validation->set_rules('kode', 'Kode Project', 'required', array('required' => 'Field %s harus diisi!'));
        $this->form_validation->set_rules('warna', 'Warna Project', 'required', array('required' => 'Field %s harus diisi!'));
        $this->form_validation->set_rules('area', 'Area Project', 'required|min_length[3]', array('required' => 'Harap memilih area!', 'min_length' => 'Harap memilih area!'));

        if ($this->form_validation->run() == FALSE) {
            return [form_error('nama'), form_error('kode'), form_error('warna'), form_error('area')];
        } else {
            $geo['b2b_token'] = $this->session->userdata('b2b_token');
            $geo['area_name'] = $this->input->post('nama');
            $geo['area_lat_long'] = $this->input->post('area');
            $geo['area_color'] = $this->input->post('warna');
            $geo['flag_active'] = 1;
            $this->db->insert('coverage_area', $geo);

            $data['flag_active'] = 1;
            $data['b2b_token'] = $this->session->userdata('b2b_token');
            $data['nama_project'] = $this->input->post('nama');
            $data['kode'] = $this->input->post('kode');
            $data['id_geofence'] = $this->db->insert_id();
            $this->db->insert('t_project', $data);
        }
        return 1;
    }
    public function edit_project()
    {
        $this->form_validation->set_rules('nama', 'Nama Project', 'required', array('required' => 'Field %s harus diisi!'));
        $this->form_validation->set_rules('kode', 'Kode Project', 'required', array('required' => 'Field %s harus diisi!'));
        $this->form_validation->set_rules('warna', 'Warna Project', 'required', array('required' => 'Field %s harus diisi!'));
        $this->form_validation->set_rules('area', 'Area Project', 'required|min_length[3]', array('required' => 'Harap memilih area!', 'min_length' => 'Harap memilih area!'));

        if ($this->form_validation->run() == FALSE) {
            return [form_error('nama'), form_error('kode'), form_error('warna'), form_error('area')];
        } else {
            $idg = $this->input->post('geo');
            $geo['b2b_token'] = $this->session->userdata('b2b_token');
            $geo['area_name'] = $this->input->post('nama');
            $geo['area_lat_long'] = $this->input->post('area');
            $geo['area_color'] = $this->input->post('warna');
            $this->db->where('id', $idg)->update('coverage_area', $geo);

            $id = $this->input->post('id');
            $data['b2b_token'] = $this->session->userdata('b2b_token');
            $data['nama_project'] = $this->input->post('nama');
            $data['kode'] = $this->input->post('kode');
            $this->db->where('id', $id)->update('t_project', $data);
            return 1;
        }
        
    }
    public function hapus_project()
    {
        $id = $this->input->post('hid');
        $data['flag_active'] = 0;
        $sett['id_project'] = null;
        $sett['active_at'] = 0;
        $this->db->where('id', $id)->update('t_project', $data);
        $this->db->where('id_project', $id)->update('users', $sett);
        $this->db->where('id_project', $id)->update('t_regu', $sett);
        $geo['flag_active'] = 0;
        $this->db->where('id_geofence', $id)->update('coverage_area', $geo);
        return $id;
    }
}
                        
/* End of file x.php */
