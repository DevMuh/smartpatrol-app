<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kop_surat extends MX_Controller{

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
		$this->load->model('kop_surat/M_kop_surat');
	}
	
	public function index()
	{
		$script = array(
            'script' => TRUE,
            'script_url' => 'main_script'
		);
		$data['kop'] = $this->db->where('b2b_token', $this->session->userdata('b2b_token'))->get('m_kop_surat')->row();
        $this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}
	public function edit()
	{
		$status = $this->M_kop_surat->edit_kop();
		redirect('kop_surat', 'refresh');
	}
}
