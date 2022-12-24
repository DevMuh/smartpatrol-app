<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_approval extends CI_Model
{

    public function fetch()
    {
        $fetch  = $this->db->select(['id_','title_nm', 'pic', 'tgl_join', 'flag_active'])->get('m_register_b2b')->result();

        $data = array();
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $row->id_;
            $temp[] = "<a href='' onclick='detail($row->id_)' data-target='#modalDetail' data-toggle='modal' class='btn btn-sm btn-primary'> Show</a>";
            $temp[] = $row->title_nm;
            $temp[] = $row->pic;
            $temp[] = $row->tgl_join;
            if($row->flag_active==1){
                $temp[] = "<a href='' onclick='confirmChangeStatus($row->id_,\"$row->title_nm\")' data-target='#modalChange' data-toggle='modal' class='btn btn-sm btn-success'> Active</a>" ;
            }else{
                $temp[] = "<a href='' onclick='confirmChangeStatus($row->id_,\"$row->title_nm\")' data-target='#modalChange' data-toggle='modal' class='btn btn-sm btn-secondary'>Not Active</a>";
            }
            $data[] = $temp;
        }
        return array('data' => $data);
    }
    
}
