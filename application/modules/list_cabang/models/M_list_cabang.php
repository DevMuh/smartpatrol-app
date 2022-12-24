<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_list_cabang extends CI_Model
{


    public function fetch()
    {
        $fetch = $this->db->where('parent_id', $_SESSION['b2b_token'])->where('flag_active', '1')->select([
            'id_', 'path_logo',
            'b2b_token as a', 'title_nm as b', 'tgl_join as c', 'alamat as d', 'phone as e', 'other_data->>\'hidden_feature\' as hidden_feature', 'parent_id'
        ])->get('m_register_b2b')->result();
        $data = array();
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $row->b;
            $temp[] = $row->c;
            $temp[] = $row->d;
            $temp[] = $row->e;
            // $temp[] = implode(",", json_decode($row->hidden_feature));
            $data[] = $temp;
        }
        return array('data' => $data);
    }
}
/* End of file x.php */
