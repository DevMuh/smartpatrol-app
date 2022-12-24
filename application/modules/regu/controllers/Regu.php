<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Regu extends MX_Controller
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
		$this->load->model('regu/M_regu');
	}

	public function index()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$b2b = $this->session->userdata('b2b_token');
		$data['shift'] = $this->db->where('flag_enable', "1")->where('b2b_token', $b2b)->get('t_shift')->result();
		// $data["leader"] = $this->db->where('status', "active")->where('b2b_token', $b2b)->where('regu', NULL)->where('user_roles', "danru")->get('users')->result();
		$this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script', $data);
		$this->load->view('layout/global_script');
	}

	public function detail()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$b2b = $this->session->userdata('b2b_token');
		$data["regu"] = $this->db->get_where("t_regu", ["b2b_token" => $b2b, "id" => $this->input->get('q')])->row();
		$this->session->set_userdata('id_regu', $this->input->get('q'));
		$this->load->view('layout/header');
		$this->load->view('detail', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('detail_script');
	}

	public function updateRegu()
	{
		echo json_encode($this->M_regu->updateRegu());
	}

	public function add()
	{
		$status = $this->M_regu->add_regu();
		echo json_encode($status);
	}
	public function edit()
	{
		$status = $this->M_regu->edit_regu();
		echo json_encode($status);
	}
	public function hapus()
	{
		$status = $this->M_regu->hapus_regu();
		if ($status == 1) {
			redirect('regu');
		} else {
			redirect('regu');
		}
	}
	public function shift()
	{
		$status = $this->M_regu->shift();
		echo json_encode($status);
	}
	public function ajax()
	{
		$data = $this->M_regu->fetch();
		echo json_encode($data);
	}
	public function ajax2()
	{
		$data = $this->M_regu->fetch2();
		echo json_encode($data);
	}
	public function ajax3()
	{
		$data = $this->M_regu->fetch3();
		echo json_encode($data);
	}
}
