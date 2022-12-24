<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Approval extends MY_Controller
{



	public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model('M_approval');
	}

	public function index()
	{
		$script = array(
			'script' 	 => TRUE,
			'script_url' => 'main_script'
		);
		$this->load->view('layout/header');
		$this->load->view('main');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}
	public function ajax()
	{
		$data = $this->M_approval->fetch();

		echo json_encode($data);
	}

	public function change_status($id)
	{
		$get 		= $this->input->get();
		$cek		= $this->db->get_where('m_register_b2b', ['id_' => $id])->result_array();
		$status		= $cek[0]['flag_active'];
		$cekUsers 	= $this->db->get_where('users', ['email' => $cek[0]['email']])->result_array(); //check in table user 

		if(count($cekUsers)!=0){
			if ($status == 0) {
				$this->db->update('m_register_b2b', ['flag_active' => 1], ['id_' => $id]);
			} else {
				$this->db->update('m_register_b2b', ['flag_active' => 0], ['id_' => $id]);
			}
		}else{
			date_default_timezone_set("Asia/Jakarta");
			// save data in table users
			$email 		= $cek[0]['email'];
			$arr 		= explode("@", $email, 2);
			$username 	= $arr[0];
			$data = [
				'username' 		=> $username,
				'password'		=> $cek[0]['password'],
				'full_name'		=> $cek[0]['pic'],
				'user_roles'	=> 'admin',
				'token'			=> 'abc123',
				'jwt_access'	=> 'xxx.yyy.zzz',
				'status'		=> 'active',
				'created_at'	=> date("Y-m-d h:i:s"),
				'b2b_token'		=> $cek[0]['b2b_token'],
				'email'			=> $cek[0]['email'],
			];
			$this->db->insert('users', $data);


			// update status table m_register
			$this->db->update('m_register_b2b', ['flag_active' => 0], ['id_' => $id]); //rubah status
		}

		if ($get['ajax'] == true) {
			echo json_encode('success');
		} else {
			redirect('approval', 'refresh');
		}
	}


	public function get_by_id()
	{
		$data = $this->db->get_where('m_register_b2b', ['id_' => $this->input->get('id')])->row();
		echo json_encode($data);
	}
}
