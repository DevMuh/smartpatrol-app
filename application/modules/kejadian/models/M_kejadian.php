<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_kejadian extends CI_Model
{

    public function fetch()
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->where('flag_disable', 1)->get('m_kejadian_ktg')->result();
        $data = array();
        $i = 1;
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $i;
            $temp[] = $row->kategori_name;
            $temp[] = $row->keterangan;
            $temp[] = "<button data-toggle='modal' data-target='#editModal' onclick='edit($row->id_)' class='btn mybt'><span class='typcn typcn-pencil'></span></button> <button data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id_)' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function add_kejadian()
    {
        $this->form_validation->set_rules('kategori_name', 'Nama Kategori', 'required|is_unique[m_kejadian_ktg.kategori_name]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
      
        if ($this->form_validation->run() == FALSE) {
            return [form_error('kategori_name'), form_error('keterangan')];
        } else {
            $data['flag_disable'] = 1;
            $data['b2b_token'] = $this->session->userdata('b2b_token');
            $data['kategori_name'] = $this->input->post('kategori_name');
            $data['keterangan'] = $this->input->post('keterangan');
            $this->db->insert('m_kejadian_ktg', $data);
            return 1;
        }
    }

    public function edit_kejadian()
    {
        $this->form_validation->set_rules('ekategori_name', 'Kategori Name', 'required');
        $this->form_validation->set_rules('eketerangan', 'Description', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('ekategori_name'), form_error('eketerangan')];            
        } else {
            $id = $this->input->post('eid');
            $data['flag_disable'] = 1;
            $data['kategori_name'] = $this->input->post('ekategori_name');
            $data['keterangan'] = $this->input->post('eketerangan');
            $this->db->where('id_', $id)->update('m_kejadian_ktg', $data);
            return 1;
        }
    }

    public function hapus_kejadian()
    {
        $id = $this->input->post('hid');
        $data['flag_disable'] = 0;
        $this->db->where('id_', $id)->update('m_kejadian_ktg', $data);
        return 1;
    }
}
                        
/* End of file x.php */
