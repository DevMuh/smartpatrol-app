<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Project extends MY_Controller
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
		$this->load->model('project/M_project');
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

	public function detail()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$this->load->view('layout/header');
		$this->load->view('edit');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}

	public function ajax()
	{
		$data = $this->M_project->fetch();
		echo json_encode($data);
	}

	public function tambah()
	{
		$status = $this->M_project->tambah_project();
		echo json_encode($status);
	}

	public function frm_tambah()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$this->load->view('layout/header');
		$this->load->view('post');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');	
	}

	public function frm_edit($id, $geo)
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$b2b = $this->session->userdata('b2b_token');
		$data['table'] = $this->db->query('SELECT
		t_project.nama_project,
		t_project.kode,
		coverage_area.area_lat_long,
		coverage_area.area_color
		FROM
		t_project
		INNER JOIN coverage_area ON coverage_area."id" = t_project.id_geofence
		WHERE
		t_project.flag_active = 1 AND
		t_project.b2b_token = \''.$b2b.'\' AND
		t_project.id = \''.$id.'\'
		')->row();
		$data['id'] = $id;
		$data['geo'] = $geo;
		$this->load->view('layout/header');
		$this->load->view('edit', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');	
	}

	public function all_map()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$data['table'] = $this->db->where('flag_active', 1)->where('b2b_token', $this->session->userdata('b2b_token'))->get('coverage_area')->result();
		$this->load->view('layout/header');
		$this->load->view('mapprj', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');	
	}

	public function edit()
	{
		$status = $this->M_project->edit_project();
		echo json_encode($status);
	}

	public function hapus()
	{
		$status = $this->M_project->hapus_project();
		if ($status == 1) {
			redirect('project');
		} else {
			redirect('project');
		}
	}

	public function tes()
	{
		$q = $this->db->get('coverage_area')->result();
		$a = json_decode('[{ "lat" : -6.325315991732241, "lng": 106.66711869967662 },{ "lat": -6.327638272818565, "lng": 106.661890802063 },{ "lat": -6.334347535047795, "lng": 106.66229652819823 },{ "lat": -6.335312756910834, "lng": 106.6727971563264 },{ "lat": -6.328203037084078, "lng": 106.67225389891826 }]');
		$b = "{ lat: -6.321349145993621, lng: 106.68982091677867 },{ lat: -6.32239181649742, lng: 106.68352013555909 },{ lat: -6.331617713699608, lng: 106.68645786700438 },{ lat: -6.331687216566517, lng: 106.69270987605296 },{ lat: -6.325089296410607, lng: 106.69298201018535 }";
		// echo str_replace(array('lat', 'lng'), array('"lat"', '"lng"'), $b);
		echo json_encode(json_decode("[".str_replace(array('lat', 'lng'), array('"lat"', '"lng"'), $b)."]"));
	}

}
