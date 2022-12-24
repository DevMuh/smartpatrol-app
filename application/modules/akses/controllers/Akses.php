<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses extends MX_Controller{

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
		$this->load->model('akses/M_akses');
	}
	
	public function index()
	{
		$script = array(
            'script' => TRUE,
            'script_url' => 'main_script'
		);

		$data['role'] = $this->session->userdata('user_roles');

		$data['table'] = $this->db->order_by('id', 'asc')->get('tabel_menu')->result();
		$data['select'] = $this->db->order_by('id', 'asc')->where_not_in('roles_type', 'admin')->where('flag_active', '1')->get('user_role')->result();

		$select_permission = $this->db->query("SELECT additional_flag->>'permission' as permission FROM user_role WHERE additional_flag->>'permission' IS NOT NULL AND roles_type='".$data['role']."'")->result();

		$list_permission = [];
		foreach ($select_permission as $value) {
			$new_permission = json_decode($value->permission);

			for ($i=0; $i < count($new_permission); $i++) { 
				array_push($list_permission, $new_permission[$i]);				
			}
		}
		// echo '<pre>';
		// print_r($list_permission);
		// echo '</pre>';
		// die();

		$data['list_permission'] = $list_permission;
		// $data['select'] = $this->db->query("SELECT roles_type FROM user_role WHERE roles_type NOT LIKE '%admin%' AND flag_active = '1")->result();
        $this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}
	public function add()
	{
		$status = $this->M_akses->add_akses();
		// $status['id'] = $this->input->post('table[]');
		echo json_encode($status);
	}
	public function edit()
	{
		$status = $this->M_akses->edit_akses();
		echo json_encode($status);
	}
	public function hapus()
	{
		$status = $this->M_akses->hapus_akses();
		if($status == 1){
			redirect('akses');
		}
		else{
			redirect('akses');
		}
	}
	public function ajax()
	{
		$data = $this->M_akses->fetch();
		echo json_encode($data);
	}
	public function ajaxuser()
	{
		$data = $this->M_akses->fetchuser();
		echo json_encode($data);	
	}
}
