<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_latest_position extends CI_Model {

	

	public function get_longlat($id){
		$get = $this->db->select(['latest_lat', 'latest_long'])
						->from('users')
						->where('id', $id)
						->get();
		
		return $get;
	}

}

/* End of file M_latest_position.php */
/* Location: ./application/modules/latest_position/models/M_latest_position.php */