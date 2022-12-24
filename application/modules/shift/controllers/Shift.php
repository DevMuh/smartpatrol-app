<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shift extends MY_Controller
{

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
		$this->load->model('shift/M_shift');
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

	public function generate()
	{
		$status = $this->M_shift->generate();
		echo json_encode($status);
	}

	public function add()
	{
		$status = $this->M_shift->add_shift();
		echo json_encode($status);
	}
	public function edit()
	{
		$status = $this->M_shift->edit_shift();
		echo json_encode($status);
	}
	public function hapus()
	{
		$status = $this->M_shift->hapus_shift();
		if ($status == 1) {
			redirect('shift');
		} else {
			redirect('shift');
		}
	}

	public function ajax()
	{
		$data = $this->M_shift->fetch();
		echo json_encode($data);
	}

	public function select2()
	{
		$id_user  = $_GET['id_user'] ?: false;
		$data = $this->M_shift->select2_v2($id_user);
		echo json_encode($data);
	}
}
