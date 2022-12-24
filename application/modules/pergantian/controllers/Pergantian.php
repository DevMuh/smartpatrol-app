<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pergantian extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model('M_pergantian', 'model');
	}

	public function index(){
		$script = array(
			'script' 	 => TRUE,
			'script_url' => 'main_script'
		);
		$this->load->view('layout/header');
		$this->load->view('main');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}

	public function ajax(){
		$data = $this->model->fetch();
		echo json_encode($data);
	}

	public function post(){
		$script = array(
			'script' 	 => TRUE,
			'script_url' => 'main_script'
		);

		$this->load->view('layout/header');
		$this->load->view('post');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}

	public function anggota_no_replacement(){ 
		$data = $this->model->anggota_not_repleacement();
		echo json_encode($data);
	}

	public function ganti(){ //get data anggota selain regu anggota itu sendiri
		$regu_id = $this->input->post('regu_id');
		$data = $this->db->select(['id', 'nama_regu'])
						 ->from('t_regu')
						 ->where('id !=', $regu_id)
						 ->where('b2b_token ', $_SESSION['b2b_token'])
						 ->get()
						 ->result();
		echo json_encode($data); 
	}

	public function pindah(){
		$id  	= $this->input->post('id');
		$data 	= ['regu_sementara'=>$this->input->post('grup_id')];
		$update =  $this->db->update('users', $data , ['id'=> $id]);

		if ($update == true) {
			echo json_encode('success');
		} else {
			redirect('approval', 'refresh');
		}
	}


}

/* End of file Pergantian.php */
/* Location: ./application/modules/pergantian/controllers/Pergantian.php */