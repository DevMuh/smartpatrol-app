<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Latest_position extends MY_Controller {


	public function __construct()
	{
		parent::__construct();
		if($this->M_global->realip() == 'ID'){
			$this->lang->load('information_lang', 'indonesia');
		} else {
			$this->lang->load('information_lang', 'english');
		}
		$this->load->model('M_latest_position', 'model');
	}


	public function index()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$data["id"] = $_GET['id'];
		$this->load->view('layout/header');
		$this->load->view('main');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script', $data);
		$this->load->view('layout/global_script');
	}

	public function get_longlat($id){
		$get = $this->model->get_longlat($id)->result();
		echo json_encode($get);
	}

}

/* End of file latest_position.php */
/* Location: ./application/modules/latest_position/controllers/latest_position.php */