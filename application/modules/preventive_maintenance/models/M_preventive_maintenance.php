<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_preventive_maintenance extends CI_Model
{

    public function fetch()
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->where('flag_active', 1)->get('preventive_maintenance')->result();
        $data = array();
        $i = 1;
        $schedule = ['', 'Daily', 'Weekly', 'Monthly'];
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $i;
            $temp[] = $row->description;
            $temp[] = $row->create_by;
            $temp[] = $schedule[$row->schedule];
            $temp[] = $row->status == 1 ? "Done" : "Open";
            $temp[] = $row->update_time;
            $temp[] = "<button data-toggle='modal' data-target='#editModal' onclick='edit($row->id)' class='btn mybt'><span class='typcn typcn-pencil'></span></button> <button data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id)' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function add_preventive_mt()
    {
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('schedule', 'Schedule', 'required');
      
        if ($this->form_validation->run() == FALSE) {
            return [form_error('description'), form_error('schedule')];
        } else {
            $data['b2b_token'] = $this->session->userdata('b2b_token');
            $data['description'] = $this->input->post('description');
            $data['schedule'] = $this->input->post('schedule');
            $data['create_by'] = $this->session->userdata('full_name');
            $data['submit_time'] = date("Y-m-d H:i:s");
            $data['flag_active'] = 1;
            $data['status'] = 0;
            $this->db->insert('preventive_maintenance', $data);
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
