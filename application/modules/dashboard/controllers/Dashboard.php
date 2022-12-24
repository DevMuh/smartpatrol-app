<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MX_Controller
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
		$this->load->model(["M_dashboard","M_absensi","M_register_b2b"]);
	}

	public function index()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);

		$data = $this->M_dashboard->get_data_dashboard();
		// echo json_encode($data);
		$this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}

	public function table_user($type = null)
	{
		switch ($type) {
			case 'absen':
				$this->load->view('table_absen');
				break;
			case 'unabsen':
				$this->load->view('table_unabsen');
				break;
			case 'per_hour':
				$this->load->view('table_absen_per_hour');
				break;
			case 'event':
				$this->load->view('table_absen_event');
				break;
			default:
				$this->load->view('table_unabsen');
				break;
		}
	}

	public function data_user_absen()
	{
		$day = $this->input->get('day') ? $this->input->get('day') : date("d");
		$month = $this->input->get('m') ? $this->input->get('m') : date("m");
		$year = $this->input->get('Y') ? $this->input->get('Y') : date("Y");
		
		$data = $this->M_absensi->get_absen_v2($day,$month,$year);
		$dt = [];
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
		echo json_encode(["data"=>$dt]);
	}
	public function data_user_unabsen()
	{
		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}
        $query =  $this->db->query("SELECT 
									u.*,
									b.title_nm,
									(CASE WHEN r.flag_active::TEXT != '1' THEN '-' ELSE r.nama_regu END) as nama_regu,
									(CASE WHEN s.flag_enable::TEXT != '1' AND r.flag_active::TEXT != '1' THEN '-' ELSE s.shift_name END) as shift_name
									FROM users u 
									LEFT JOIN t_regu r ON u.regu::TEXT = r.id::TEXT  
									LEFT JOIN t_shift s ON r.id_shift::TEXT = s.id_::TEXT  
									LEFT JOIN m_register_b2b b ON b.b2b_token::TEXT = u.b2b_token::TEXT  
									WHERE u.b2b_token in ?
									and u.user_roles NOT IN ('cudo','superadmin')
									and u.status::TEXT = 'active'
									and u.id not in (select distinct user_id from log_absensi where DATE(submit_time) = current_date)
								",[$b2b_tokens_arr])->result();
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

	public function get_absen_summary()
	{
		$summary_absen = $this->M_dashboard->summary_absen();
		$summary_absen['total_user'] = $summary_absen['user']->all ?: 0;
		$summary_absen['total_attend'] = $summary_absen['total_attend'] ?: 0;
		$summary_absen['total_attend_percentage'] = $this->percentage($summary_absen['total_attend'],$summary_absen['total_user']);
        $summary_absen['total_absence'] = $summary_absen['total_absence'] ?: 0;
        $summary_absen['total_absence_percentage'] = $this->percentage($summary_absen['total_absence'],$summary_absen['total_user']);
		$summary_absen['total_onsite_percentage'] = $this->percentage($summary_absen['total_onsite'],$summary_absen['total_user']);
		$summary_absen['total_onsite'] = $summary_absen['total_onsite'] ?: 0;
		$summary_absen['total_via'] = $summary_absen['total_via'] ?: 0;
		$summary_absen['total_perjam'] = $summary_absen['total_perjam'] ?: 0;
		$summary_absen['total_event'] = $summary_absen['total_event'] ?: 0;
		echo json_encode($summary_absen);
	}
	
	function percentage($x,$y)
    {
        $percent = floatval($x)/floatval($y);
        return number_format( $percent * 100, 1 ) . '%';
    }
	
	public function data_user_per_hour()
	{
		$date = date("Y-m-d");
		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}
		$this->db->join('qr', 'log_absensi_reguler.qr_id = qr.qr_id', "left");
		$query = $this->db->query("SELECT * FROM log_absensi_reguler a
		left join qr on a.qr_id = qr.qr_id
		left join users u  on u.id::text = a.user_id
		WHERE 
		a.date = ?
		and a.b2b_token in ?
		order by a.submit_time asc",[$date,$b2b_tokens_arr])->result();
		$data = [];
		$no = 1;
		foreach ($query as $key) {
			$temp = [];
			$temp[] = $key->full_name;
			$temp[] = date("H:i:s", strtotime($key->submit_time));
			$temp[] = $key->lat;
			$temp[] = $key->long;
			$temp[] = $key->name;
			$data[] = $temp;
			$no++;
		}
		echo json_encode(["data" => $data]);
	}
	public function data_user_event()
	{
		$day = date("d");
		$month = date("m");
		$year = date("Y");
		$data = $this->M_absensi->get_absen_event($day,$month,$year);
		$dt = [];
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
		echo json_encode(["data"=>$dt]);
	}
	public function data_user_all()
	{
		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}
        $query =  $this->db->query("SELECT 
									u.*,
									b.title_nm,
									(CASE WHEN r.flag_active::TEXT != '1' THEN '-' ELSE r.nama_regu END) as nama_regu,
									(CASE WHEN s.flag_enable::TEXT != '1' AND r.flag_active::TEXT != '1' THEN '-' ELSE s.shift_name END) as shift_name
									FROM users u 
									LEFT JOIN t_regu r ON u.regu::TEXT = r.id::TEXT  
									LEFT JOIN t_shift s ON r.id_shift::TEXT = s.id_::TEXT  
									LEFT JOIN m_register_b2b b ON b.b2b_token::TEXT = u.b2b_token::TEXT  
									WHERE u.b2b_token in ?
									and u.user_roles NOT IN ('cudo','superadmin')
									and u.status::TEXT = 'active'
								",[$b2b_tokens_arr])->result();
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
	
	public function absen_akumulasi()
	{
		echo json_encode($this->M_dashboard->absen_akumulasi());
	}

	public function cek_session()
	{
		echo json_encode($this->session->userdata());
	}

	public function ajax()
	{
		if (!empty($this->input->post('filter'))) {
			$data = $this->M_dashboard->getlocation($this->input->post('filter'));
		} else {
			$data = $this->M_dashboard->getlocation(0);
		}
		echo json_encode($data);
	}

	public function checkpoint()
	{
		echo json_encode($this->M_dashboard->getcp());
	}
	
}
