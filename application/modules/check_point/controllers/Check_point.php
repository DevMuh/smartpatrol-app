<?php

use PhpOffice\PhpSpreadsheet\Shared\Date;

defined('BASEPATH') or exit('No direct script access allowed');

class Check_point extends MY_Controller
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
		$this->load->model('register_route/M_register_route', 'M_route');
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
	public function add_cp()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$this->load->view('layout/header');
		$this->load->view('addcp');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}
	public function ajax()
	{
		$data = $this->M_route->fetch();
		echo json_encode($data);
	}

	public function ajaxcp()
	{
		$idroute = $this->input->post('idroute');
		$d = $this->M_route->fetchcp($idroute == null ? null : $idroute);
		$i = 0;
		if (count($d['cp']) > 0) {
			foreach ($d['cp'] as $row) {
				if($row->cluster_route == null || $row->cluster_route == $idroute){
					$data['loc'][$i][0] = $row->cp_long;
					$data['loc'][$i][1] = $row->cp_lat;
					$i++;
					$data['addr'][] = $row->cp_id . " - " . $row->cp_name;
				}
			}
		}
		if (count($d['poly']) > 0) {
			foreach ($d['poly'] as $row) {
				$temp2 = array();
				$temp = json_decode($row->area_lat_long);
				$j = 0;
				foreach($temp as $row2){
					$temp2[$j][0] = $row2->lng;
					$temp2[$j][1] = $row2->lat;
					$j++;
				}
				$temp3[] = $temp2;
			}
		}
		$data['data'] = $d['cp'];
		$data['poly'] = $temp3;
		echo json_encode($data);
	}

	public function tambah()
	{
		$status = $this->M_route->tambah_route();
		echo json_encode($status);
	}

	public function scheduler()
	{
		$status = $this->M_route->scheduler();
		echo json_encode($status);
	}

	public function edit()
	{
		$status = $this->M_route->edit_route();
		echo json_encode($status);
	}

	public function hapus()
	{
		$status = $this->M_route->hapus_route();
		if ($status == 1) {
			redirect('register_route');
		} else {
			redirect('register_route');
		}
	}

	public function route()
	{
		$status = $this->M_route->updateroute($this->input->post('rid'));
		echo json_encode($status);
	}

	public function tomobile($b2btoken, $id)
	{
		$this->db->query("INSERT INTO t_task_patrol_header(
			b2b_token
		  ,publish_date 
			,publish_time 
			,id_route
			,cluster_name 
			,total_cp 
			,finish_time 
		)
		SELECT 
			a1.b2b_token
			,current_date as publish_date
			,to_char(CURRENT_TIMESTAMP, 'HH24:MI:SS') AS publish_time
			,a1.id_route
			,a1.cluster_name
			,count(a2.id) as total_cp
			,a1.finish_time 
		 FROM cluster_route a1 LEFT JOIN check_point a2
		 ON a2.b2b_token=a1.b2b_token AND a2.cluster_route::integer = a1.id_route
		 WHERE a1.b2b_token='$b2btoken' AND a1.id_route='$id' AND a1.flag_active =1 AND a2.flag_disable=1
		 GROUP BY a1.id_route;");
		if ($this->db->affected_rows() == 1) {
			echo 1;
		} else {
			echo 0;
		}
	}

	public function getscheduler()
    {
        $id = $this->input->post('id');
        echo $this->db->select('otherdata')->where('id_route', $id)->get('cluster_route')->row()->otherdata;
    }
}
