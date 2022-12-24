<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_list extends MY_Controller{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model('client_list/M_client_list', 'M_client');
	}
	public function index()
	{
		$script = array(
            'script' => TRUE,
            'script_url' => 'main_script'
        );
        $this->load->view('layout/header');
		$this->load->view('main');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}
	public function ajax()
	{
		//$data = $this->load->model('M_client_list');
		$data = $this->M_client->fetch();
		echo json_encode($data);
	}

	public function tambah()
	{
		$status = $this->M_client->tambah_client();
		echo json_encode($status);
	}

	public function edit()
	{
		$status = $this->M_client->edit_client();
		echo json_encode($status);
	}

	public function hapus()
	{
		$status = $this->M_client->hapus_client();
		if($status == 1){
			redirect('client_list');
		}
		else{
			redirect('client_list');
		}
	}
}
