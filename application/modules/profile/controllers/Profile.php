<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MX_Controller
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
		if (!$this->session->userdata('status') || $this->session->userdata('status') != 'active') {
			redirect('', 'refresh');
		}

		$allow = false;
		$id = json_decode($this->session->userdata('table_id'));
		$tabel = $this->session->userdata('table');
		for ($i = 0; $i < count($id->id); $i++) {
			for ($j = 0; $j < count($tabel); $j++) {
				if ($this->uri->segment(1) == $tabel[$j]->link && $id->id[$i] == $tabel[$j]->id) {
					$allow = true;
				}
			}
		}

		if ($id->id[0] == 'ALL') {
			$allow = true;
		}

		$this->load->model('profile/M_profile');
	}

	public function index()
	{
		redirect('profile/user');
	}

	public function company()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$data['table'] = $this->db->where('b2b_token', $this->session->userdata('b2b_token'))->where('flag_active', 1)->get('m_register_b2b')->row();
		$this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}

	public function user($edit = '')
	{
		$b2b = $this->session->userdata('b2b_token');
		$data['id'] = $this->session->userdata('id');
		$data['table'] = $this->db->where('b2b_token', $b2b)->where('flag_active', 1)->get('m_register_b2b')->row();
		$data['user'] = $this->db->where('id', $data['id'])->where('b2b_token', $b2b)->get('users')->row();
		$this->load->view('layout/header');
		if ($edit == 'edit') {
			$this->load->view('edit', $data);
		} else {
			redirect('profile/user/edit');
		}

		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);

		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}

	public function update()
	{
		$status = $this->M_profile->update();
		redirect('profile/company');
	}

	public function update2()
	{
		$status = $this->M_profile->update2();
		echo json_encode($status);
	}
}
