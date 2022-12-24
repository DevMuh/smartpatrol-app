<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_user_role extends CI_Model
{
    public function fetch_all()
    {
        $this->db->order_by('roles_type', 'ASC');
        return $this->db->get('user_role')->result();
    }

    public function fetch_single($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('user_role')->row();
    }

    public function set_permission()
    {
        $id = $this->input->post('id_role');
        $tables = $this->input->post('tables');
        
        $item = $item = $this->db->where('id', $id)->get('user_role')->row();
        
        $data['id'] = $tables;
        
        $this->db->set('table_id', json_encode($data));
        $this->db->where('id', $id);
        $this->db->update('user_role');
        return 1;
    }
}
                        
/* End of file */
