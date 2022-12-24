<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log_absen_offline extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		//$this->load->model('log_absen/M_log_absen');
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

	public function ajax($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");
		$data = $this->M_log_absen->fetch($month, $year);
		echo json_encode($data);
	}
}