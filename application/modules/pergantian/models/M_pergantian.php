<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pergantian extends CI_Model {

	var $table = 'users';

	public function fetch(){
        $fetch  = $this->db->select(['id','username', 'full_name', 'user_roles', 'regu', 'regu_sementara'])
        					->from($this->table)
        					->where('users.regu_sementara !=', null)
        					->where('users.regu_sementara !=', '0')
        					->order_by("id",'ASC')
        					->get()
        					->result();
        $data = array();
        $i     = 1;

        foreach ($fetch as $row) {

            $temp = array();
            $temp[] = $row->id;
            $temp[] = $i;
            $temp[] = $row->username;
            $temp[] = $row->full_name;
            $temp[] = $row->user_roles;
            $temp[] = $row->regu;
            $temp[] = $row->regu_sementara;
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }


    public function anggota_not_repleacement(){
    	$fetch = $this->db->select(['id','username', 'full_name', 'user_roles', 'regu', 'regu_sementara'])
									->from('users')
		        					->where('users.regu_sementara ', null)
		        					->where('users.b2b_token ', $_SESSION['b2b_token'])
		        					->where('users.user_roles !=', 'admin')
		        					->or_where('users.regu_sementara ', '0')
		        					->order_by("id",'DESC')
		        					->get()
									->result();
		$data  	= array();
		$no 	= 1;

		foreach ($fetch as $value) {
			$temp = array();
			$temp[] = $value->id;
			$temp[] = $no;
			$temp[] = $value->username;
			$temp[] = $value->full_name;
			$temp[] = $value->user_roles;
			$temp[] = $value->regu;
			$temp[] = "<button onclick='pindah($value->id, $value->regu)' class='btn btn-sm btn-info'>Pindah</button>";
			$no++;
			$data[] = $temp;
		}
		return array('data'=>$data);
    }


}

/* End of file M_pergantian.php */
/* Location: ./application/modules/pergantian/models/M_pergantian.php */