<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_schedule_mobile extends MX_Controller
{
    public function __construct()
	{
		parent::__construct();
		// $this->M_global->firstload();
		$this->load->model(["M_dashboard","M_absensi","M_register_b2b","task_patrol/M_task_patrol","dashboard_schedule_mobile/M_dashboard_schedule_mobile"]);
    }

    public function index()
	{
        $script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);

        $data['data'] = '';
		$data["user_id"] = $this->input->get('user_id') ;
		$day = $this->input->get('day') ? $this->input->get('day') : date("d");
		$month = $this->input->get('m') ? $this->input->get('m') : date("m");
		$year = $this->input->get('Y') ? $this->input->get('Y') : date("Y");
		$data["date"] = $year."-".$month."-".$day;
        // $this->load->view('layout/header');
		$this->load->view('main_schedule', $data);
		// $this->load->view('layout/footer', $script);
		// $this->load->view('main_script');
    }
	public function user(){
		$user_id = $this->input->post("user");
		$day = $this->input->post('day') ? $this->input->post('day') : date("d");
		$month = $this->input->post('m') ? $this->input->post('m') : date("m");
		$year = $this->input->post('Y') ? $this->input->post('Y') : date("Y");

		$date = $year."-".$month."-".$day;
		$data["data"] = $this->M_dashboard_schedule_mobile->user($user_id);
		echo json_encode($data);
	}
	public function datauser_absen(){
		// $user_id ='88';
		// $user_id = $this->uri->segment(3);
		// $date = $this->uri->segment(4);
		$user_id = $this->input->post('user_id') ;
		$day = $this->input->post('day') ? $this->input->post('day') : date("d");
		$month = $this->input->post('m') ? $this->input->post('m') : date("m");
		$year = $this->input->post('Y') ? $this->input->post('Y') : date("Y");
		
		$date = $year."-".$month."-".$day;
		$user_id = $this->input->get('user_id') ;
		$day = $this->input->get('day') ? $this->input->get('day') : date("d");
		$month = $this->input->get('m') ? $this->input->get('m') : date("m");
		$year = $this->input->get('Y') ? $this->input->get('Y') : date("Y");
		$date = "2022-09-02";
		$day = "21";
		$month = "09";
		$year = "2022";
		$date = $year."-".$month."-".$day;
		
		var_dump($user_id,$day,$month,$year);die;

// var_dump("masss",$user_id);die;
		$data = $this->M_absensi->get_absenkehadiran($day,$month,$year,$user_id);
		$datacheckpoint = $this->M_dashboard_schedule_mobile->checkpointm($user_id,$date);
		$datapatroli = $this->M_dashboard_schedule_mobile->fetch($user_id,$month,$year);
		// var_dump($datacheckpoint) ; die();
		$dt = [];
		// if ($data["data"]) {
			$counttepatwaktu = 0;
			if ($data["late_in_time"] == ""){
				$counttepatwaktu = $counttepatwaktu+1;
			}
				$temp = [];
				$temp["kehadiran"] = count($data["data"]);
				// $temp["tepat_waktu"] = $counttepatwaktu;
				// $temp["patroli"] = count($datapatroli);
			// 	$temp[] = $key->position?:$key->user_roles;
			// 	$temp[] = $key->org_name;
			// 	$temp[] = $key->check_in_time;
			// 	$temp[] = $key->check_out_time;
			// 	$temp[] = $key->qr_in;
			// 	$temp[] = $key->qr_out;
				$dt[] = $temp;
			// }
		// }
		// echo json_encode(["data"=>$dt]);
		echo json_encode($datapatroli);
	}
	public function ketetapan_waktu(){
		
		$user_id = $this->input->get('user_id');
		$day = $this->input->get('day');
		$month = $this->input->get('month');
		// var_dump($month);die;
		$year = date("Y");
		$data = $this->M_dashboard_schedule_mobile->ketetapan($day,$month,$year,$user_id);
		$datacountabsen = $this->M_dashboard_schedule_mobile->countabsen($day,$month,$year,$user_id);
		$lateintime = $data["data"];
		$countlate = 0;
		$dataarr =[];
		for ($i=0; $i < count($lateintime) ; $i++) {
			if ($lateintime[$i] == "-") {
				$countlate = $countlate+1;
			}
		}
		$persen = $this->persen($countlate,$datacountabsen["total"]);
		if (is_nan($persen)) {
			$persen = 0;
		}
		$dt = array(
			"total"=>number_format($persen,2,".",".")
		);
		// var_dump($countlate);die;
		echo json_encode($dt);
	}
	private function persen($a,$b){
		$percent = (float)$a / (float)$b;		
		$calculate = $percent * 100;
		return $calculate;
	}
}
?>