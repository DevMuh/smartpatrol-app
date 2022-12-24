<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Controller {

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
		$this->load->model([
			'menu/M_menu', 
			'menu/M_user_role'
		]);
	}
	
	public function index()
	{
		$script = array(
            'script' => TRUE,
            'script_url' => 'main_script'
        );
        $menu = $this->M_menu->html_build_menu();
        
        $data = array(
            'menu' => $menu
        );
        $this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}

	public function ajax()
	{
		$id = $this->input->get('_id') ?: FALSE;
		if ($id) {
			echo json_encode($this->M_menu->fetch_single($id));
		} else {
			echo json_encode($this->M_menu->fetch_all());
		}
	}

	public function ajax_menu()
	{
		echo json_encode($this->M_menu->fetch_all());
	}

	public function user_role()
	{
		$id = $this->input->get('_id') ?: FALSE;
		if ($id) {
			echo json_encode($this->M_user_role->fetch_single($id));
		} else {
			echo json_encode($this->M_user_role->fetch_all());
		}
	}

	public function set_permission()
	{
		$status = $this->M_user_role->set_permission();
		echo json_encode($status);
	}

	public function set_menu_sequence()
	{
		$status = $this->M_menu->set_menu_sequence();
		echo json_encode($status);
	}

	public function add()
	{
		$status = $this->M_menu->tambah();
		echo json_encode($status);
	}

	public function edit()
	{
		$status = $this->M_menu->ubah();
		echo json_encode($status);
	}

	public function hapus()
	{
		$status = $this->M_menu->hapus();
		if($status == 1){
			redirect('menu');
		}
		else{
			redirect('menu');
		}
	}

	public function cek()
	{
		$table = json_decode($this->session->userdata('table_id'));
		$main = $this->session->userdata('table');
		$r = array (
			"table" => $table,
			"main" => $main
		);
		echo json_encode($r); die;
	}
	public function tes()
	{
		$table = json_decode($this->session->userdata('table_id'));
		$raw = $this->session->userdata('table');
		$data["table"] = $table;
		$data["raw"] = $raw;
		echo json_encode($data); die;
		$this->load->view('layout/sidebar_menu');
	}
}
