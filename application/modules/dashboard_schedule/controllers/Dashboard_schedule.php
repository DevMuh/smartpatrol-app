<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_schedule extends MX_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->M_global->firstload();
		$this->load->model(["M_dashboard","dashboard_schedule/M_dashboard_schedule","M_absensi","M_register_b2b","task_patrol/M_task_patrol"]);
    }

    public function index()
	{
        $script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);

        $data['data'] = '';

		$data = $this->M_dashboard->get_data_dashboard();
        $this->load->view('layout/header');
		$this->load->view('main_schedule', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
    }
	public function chart(){
		$y = $this->input->get("y");
		$m = $this->input->get("m");
		$d = $this->input->get("d");
		// var_dump($y,$m,$d);die;
		$data = $this->M_dashboard_schedule->get_data_dashboard($y,$m,$d);
		echo json_encode($data);
	}

	public function data_user_absen()
	{
		$day = $this->input->get('day') ? $this->input->get('day') : "";
		$month = $this->input->get('m') ? $this->input->get('m') : date("m");
		$year = $this->input->get('Y') ? $this->input->get('Y') : date("Y");

		$date = $year."-".$month."-".$day;

		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}

		$sql = "";
		if ($day) {
			$sql = "SELECT
						*
					FROM m_schedule_absensi
					WHERE b2b_token IN ? 
					AND date = '".$date."'";
		}else{
			$sql = "SELECT DISTINCT ON (user_id)
						user_id
					FROM m_schedule_absensi
					WHERE b2b_token IN ?
					AND extract(month from date::DATE) = '".$month."' 
					AND extract(year from date::DATE) = '".$year."' 
					GROUP BY user_id";
		}

		$query = $this->db->query($sql,[$b2b_tokens_arr])->result();

		$extra_query = "";
		if ($query) {
			$user_id_arr = [];
			foreach ($query as $key) {
				$user_id_arr[] = $key->user_id;
			}
			$extra_query = " AND A.user_id IN ('" . implode("','", $user_id_arr) . "')";
			
			$data = $this->M_dashboard_schedule->get_absen_v2($day,$month,$year,$extra_query);
		}
		
		//$data = $this->M_absensi->get_absen_v2($day,$month,$year,$extra_query);
		
		
		//echo $this->db->last_query(); die();
		$dt = [];
		if ($data["data"]) {
			foreach ($data["data"] as $key) {
				$temp = [];
				$temp[] = $key->full_name;
				$temp[] = $key->position?:$key->user_roles;
				$temp[] = $key->org_name;
				$temp[] = $key->check_in_time;
				$temp[] = $key->check_out_time;
				$temp[] = $key->qr_in;
				$temp[] = $key->qr_out;
				$dt[] = $temp;
			}
		}
		echo json_encode(["data"=>$dt]);
	}

	public function data_user_unabsen()
	{
		$day = $this->input->get('day') ? $this->input->get('day') : "";
		$month = $this->input->get('m') ? $this->input->get('m') : date("m");
		$year = $this->input->get('Y') ? $this->input->get('Y') : date("Y");

		$date = $year."-".$month."-".$day;

		$distict_date = "date,";
		$distict_schedule = "distinct on (user_id)";
		$where_schedule = "AND extract(month from a.date) = '".$month."' AND extract(year from a.date) = '".$year."'";
		$where_distict = "extract(month from submit_time) = '".$month."' AND extract(year from submit_time) = '".$year."'";
        if ($day) {
            $distict_date = "";
			$distict_schedule = "";
			$where_schedule = "AND a.date = '".$date."'";
			$where_distict = "DATE(submit_time) = '".$date."'";
        }
		
		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}

		$query =  $this->db->query("SELECT 
										".$distict_schedule."
										b.*,
										b.full_name,
										c.title_nm,
										(CASE WHEN r.flag_active::TEXT != '1' THEN '-' ELSE r.nama_regu END) as nama_regu
										,
										(CASE WHEN s.flag_enable::TEXT != '1' AND r.flag_active::TEXT != '1' THEN '-' ELSE s.shift_name END) as shift_name
									FROM m_schedule_absensi a
									LEFT JOIN users b ON a.user_id = b.id
									LEFT JOIN m_register_b2b c ON c.b2b_token::TEXT = a.b2b_token::TEXT
									LEFT JOIN t_regu r ON b.regu::TEXT = r.id::TEXT  
									LEFT JOIN t_shift s ON a.shift_id::TEXT = s.id_::TEXT
									WHERE a.b2b_token in ? 
									".$where_schedule."
									and b.id not in (
										select distinct on (".$distict_date."status,user_id,id_shift) 
											user_id 
										from log_absensi 
										where ".$where_distict." 
										and status = 1
										AND (other_data->>'status_absen_schedule'='true' OR other_data->>'status_absen_schedule' IS NULL)
            							AND ((other_data->>'status_off_panggil'='false' OR other_data->>'status_off_panggil' IS NULL) OR (other_data->>'status_off_panggil'='true' AND other_data->>'status_approval'='true'))
									)
		 						",[$b2b_tokens_arr])->result();
			//echo $this->db->last_query(); die();
		  $data = array();
		  foreach ($query as $row) {
			  $device_info = json_decode($row->device_info, true);
			  $other_data = json_decode($row->other_data, true);
			  $temp = array();
			  $temp[] = $row->full_name;
			  $temp[] = $row->title_nm;
			  $temp[] = $other_data['position']?:$row->user_roles;
			  $temp[] = $row->nama_regu;
			  $temp[] = $row->shift_name;
			  $temp[] =  (strlen($device_info['X-Devicename']) > 19) ? substr($device_info['X-Devicename'], 0, 19) . '...' : $device_info['X-Devicename'];
			  $data[] = $temp;
		  }
		  echo json_encode(['data' => $data]);
	}

	public function data_user_patroli()
	{
		$day = $this->input->get('day') ? $this->input->get('day') : "";
		$month = $this->input->get('m') ? $this->input->get('m') : date("m");
		$year = $this->input->get('Y') ? $this->input->get('Y') : date("Y");

		// $data = $this->M_task_patrol->fetch($month, $year, $day);
		$data = $this->M_dashboard_schedule->fetch_patroli($month, $year, $day);
		echo json_encode($data);
	}

	public function data_absen_patroli()
	{
		$day = $this->input->get('day') ? $this->input->get('day') : "";
		$month = $this->input->get('m') ? $this->input->get('m') : date("m");
		$year = $this->input->get('Y') ? $this->input->get('Y') : date("Y");

		// $data = $this->M_task_patrol->fetch($month, $year, $day);
		$data = $this->M_dashboard_schedule->fetch_patroli($month, $year, $day);
		echo json_encode($data);
	}

	public function data_absen_checkpoint()
	{
		$day = $this->input->get('day') ? $this->input->get('day') : "";
		$month = $this->input->get('m') ? $this->input->get('m') : date("m");
		$year = $this->input->get('Y') ? $this->input->get('Y') : date("Y");

		$date = $year."-".$month."-".$day;

		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}

		$query = $this->db->query("SELECT
										a.*,
										b.full_name
									FROM log_absensi_reguler a
									LEFT JOIN users b ON b.id = a.user_id::int
									WHERE a.b2b_token IN ? 
									AND 
									-- a.date = '".$date."'
									a.date::TEXT LIKE '%".$date."%'
									",[$b2b_tokens_arr])->result();
		// echo $this->db->last_query(); die();
		$data = [];
		foreach ($query as $key) {
			$temp = [];
			$temp[] = $key->full_name;
			$temp[] = $key->lat ." - ".$key->long;
			$temp[] = $key->submit_time;
			$temp[] = $key->remark;
			$data[] = $temp;
		}
		echo json_encode(["data"=>$data]);
	}

	public function data_user_onduty()
	{
		$day = $this->input->get('day') ? $this->input->get('day') : "";
		$month = $this->input->get('m') ? $this->input->get('m') : date("m");
		$year = $this->input->get('Y') ? $this->input->get('Y') : date("Y");

		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}

		$where_day = "";
		if ($day) {
			$where_day = "AND extract(day from a.date::DATE) = '".$day."'";
		}

		$query = $this->db->query("SELECT DISTINCT ON (a.user_id)
										a.user_id,
										b.full_name,
										b.other_data,
										b.user_roles,
										b.device_info,
										c.title_nm,
										(CASE WHEN r.flag_active::TEXT != '1' THEN '-' ELSE r.nama_regu END) as nama_regu,
										s.shift_name
									FROM m_schedule_absensi a
									LEFT JOIN users b ON a.user_id = b.id
									LEFT JOIN m_register_b2b c ON c.b2b_token::TEXT = a.b2b_token::TEXT  
									LEFT JOIN t_regu r ON b.regu::TEXT = r.id::TEXT  
									LEFT JOIN t_shift s ON a.shift_id::TEXT = s.id_::TEXT
									WHERE a.b2b_token IN ?
									AND extract(month from a.date::DATE) = '".$month."' 
									AND extract(year from a.date::DATE) = '".$year."' 
									".$where_day."
									GROUP BY user_id
									,b.full_name
									,b.other_data
									,b.user_roles
									,b.device_info
									,c.title_nm
									,(CASE WHEN r.flag_active::TEXT != '1' THEN '-' ELSE r.nama_regu END)
									,s.shift_name",[$b2b_tokens_arr])->result();;
		// echo $this->db->last_query(); die();
		$data = array();
		foreach ($query as $row) {
			$device_info = json_decode($row->device_info, true);
			$other_data = json_decode($row->other_data, true);
			$temp = array();
			$temp[] = $row->full_name;
			$temp[] = $row->title_nm;
			$temp[] = $other_data['position']?:$row->user_roles;
			$temp[] = $row->nama_regu;
			$temp[] = $row->shift_name;
			$temp[] =  (strlen($device_info['X-Devicename']) > 19) ? substr($device_info['X-Devicename'], 0, 19) . '...' : $device_info['X-Devicename'];
			$data[] = $temp;
		}
		echo json_encode(['data' => $data]);
	}

	public function data_user_per_hour()
	{
		$day = $this->input->get('day') ? $this->input->get('day') : "";
		$month = $this->input->get('m') ? $this->input->get('m') : date("m");
		$year = $this->input->get('Y') ? $this->input->get('Y') : date("Y");

		$date = $year."-".$month."-".$day;

		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}

		$query = $this->db->query("SELECT
										a.*,
										b.full_name
									FROM log_absensi_reguler a
									LEFT JOIN users b ON b.id = a.user_id::int
									WHERE a.b2b_token IN ? 
									AND 
									-- a.date = '".$date."'
									a.date::TEXT LIKE '%".$date."%'
									",[$b2b_tokens_arr])->result();
		// echo $this->db->last_query(); die();
		$data = [];
		foreach ($query as $key) {
			$temp = [];
			// $temp[] = $key->full_name;
			// $temp[] = $key->lat ." - ".$key->long;
			// $temp[] = $key->submit_time;
			// $temp[] = $key->remark;

			$temp[] = $key->full_name;
			$temp[] = date("H:i:s", strtotime($key->submit_time));
			$temp[] = $key->lat;
			$temp[] = $key->long;
			$temp[] = $key->name;
			$data[] = $temp;
		}
		echo json_encode(["data"=>$data]);
	}
}