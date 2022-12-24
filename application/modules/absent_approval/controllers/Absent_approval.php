<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absent_approval extends MY_Controller
{

    public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		//$this->load->model(array('M_schedule_absensi','M_Users'));
		$this->b2b = $this->session->userdata('b2b_token');
	}
	public function index()
	{
		// if ($this->session->userdata("user_roles") != "chief") {
		// 	echo '<script>history.go(-2)</script>';
		// }
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
    public function ajax()
	{
		$schedule = $this->api_dt_absensi_approval($this->b2b);
		$i = 1;
		$data = array();
		foreach ($schedule->data as $row) {
	$temp = array();
	
	$status = "";
	$status2 = "";
	if ($row->status_approval) {
		$status = '';
		$status2 = '<center><span class="badge badge-success">Approved</span></center>';
	}else{
		$status = "<center><input type='checkbox' name='approve[]' class='form-control checkbox_check' data-idplng='". $row->log_pulang_id ."' data-idmasuk='". $row->log_masuk_id ."' id='approved'></center>";
		// $status2 = "<center><a href='#' class='btn mybt detail' data-toggle='modal' data-target='#verificationModal' onclick='verification(".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').")' title='Approve Absensi'><span class='badge badge-success'><img src='".base_url()."assets/apps/images/check-mark.png'></span></a></center>";
		$status2 = "<center><a href='#' class='btn mybt detail' data-toggle='modal' data-target='#verificationModal' onclick='verification(".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').")' title='Approve Absensi'><span class='badge badge-success' style='background-color: white; box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;'><img src='".base_url()."assets/apps/images/check-mark.png' style='width: 20px;'></img></span></a></center>";
	}
	
	$temp[] = $status;
	$temp[] = $row->date;
	$temp[] = $row->full_name;
	$temp[] = $row->waktu_masuk;
	$temp[] = $row->waktu_pulang;
	$temp[] = $row->shift_name;
	$temp[] = $row->start_shift;
	$temp[] = $row->end_shift;
	//$temp[] = "<center><a href='#' class='btn mybt detail' data-toggle='modal' data-target='#verificationModal' onclick='verification(".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').")' title='Approve Absensi'><span class='badge badge-danger'><i class='fa fa-check'></i> ".$row->status_approval."</span></a></center>";
	$temp[] = $status2;
	$i++;
	$data[] = $temp;
}

$payload['data'] =  $data;
$payload['recordsTotal'] = count($data);
// $payload['draw']=$this->input->post("draw");
// $payload['recordsFiltered']= 10000;
// var_dump("masuk");die;
echo json_encode($payload);
// die;
	}
	public function api_dt_absensi_approval($b2b_token)
	{
		/* API URL */
        $url = $this->config->item('base_url_api_go').'absen-approval/web-dt';
   
        /* Init cURL resource */
        $ch = curl_init($url);
   
        /* Array Parameter Data */
        $data = [
                    'b2b_token'       => $b2b_token, 
                ];
   
        /* pass encoded JSON string to the POST fields */
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            
        /* set return type json */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
        /* execute request */
        $result = curl_exec($ch);
             
        /* close cURL resource */
        curl_close($ch);

        $datas = json_decode($result);

        return $datas;
	}
	public function verification()
	{
		$log_id_masuk = $this->input->post("log_id_masuk");
		$log_id_pulang = $this->input->post("log_id_pulang");

		if ($log_id_masuk == "") {
			$payload['status'] = false;
			$payload['message'] = "Log ID Masuk is required";

			echo json_encode($payload);
			return;
		}

		if ($log_id_pulang == "") {
			$payload['status'] = false;
			$payload['message'] = "Log ID Pulang is required";

			echo json_encode($payload);
			return;
		}

		$data = array(
			'status_approval' => true
		);

		$new_data = json_encode($data);

		/// update log masuk
		$update = $this->db->query(
			"UPDATE log_absensi SET other_data = other_data || ? WHERE id = ? ",
			[$new_data, $log_id_masuk]
		);

		/// update log pulang
		$update2 = $this->db->query(
			"UPDATE log_absensi SET other_data = other_data || ? WHERE id = ? ",
			[$new_data, $log_id_pulang]
		);

		if ($update && $update2) {
			$payload['status'] = true;
			$payload['message'] = "Success Approving data Absensi";

			echo json_encode($payload);
		}else{
			$payload['status'] = false;
			$payload['message'] = "Error, Approving data Absensi";

			echo json_encode($payload);
		}
	}

	public function verification_batch()
	{
		$uid = $this->input->get('uid');
		$sess_data = $this->session->userdata("bulk_approval_".$uid);
		
		$log_id_masuk = $sess_data->log_id_masuk;
		$log_id_pulang = $sess_data->log_id_pulang;

		$data['name']  = 'Progress Approval Absensi';

		echo $this->load->view('absent_approval/progress_approval_batch', $data, true);
		echo "<script>  update_progress(1,'Fetching Data...')        </script>";
        $this->flush_buffer();

		$this->data_approval_batch($log_id_masuk,$log_id_pulang);
	}

	public function prepare_data_verification_batch()
	{
		$data_log_id_masuk = $this->input->post('log_id_masuk');
		$data_log_id_pulang = $this->input->post('log_id_plng');
		
		$temp = new stdClass();
		$temp->log_id_masuk = $data_log_id_masuk;
		$temp->log_id_pulang = $data_log_id_pulang;

		$uid = uniqid();

		$this->session->set_userdata([
			'bulk_approval_'.$uid => $temp,
		]);

		$payload['status'] = true;
		$payload['data'] = $uid;

		echo json_encode($payload);
	}

	public function data_approval_batch($log_id_masuk,$log_id_pulang)
	{
		$array_log_id_masuk = explode(',', $log_id_masuk);
		$array_log_id_pulang = explode(',', $log_id_pulang);

		if (count($array_log_id_masuk) != count($array_log_id_pulang)) {
			echo "<script>  update_progress(10,'Error Fetching Data, Total Jam Masuk tidak sama dengan Total Jam Pulang')        </script>";
			return;
		}

		$data_insert = [];
		$data_update_log_id_masuk = [];
		$data_update_log_id_pulang = [];
		$all_data_log_id = [];
		$data_failed = [];
		for ($i=0; $i < count($array_log_id_masuk); $i++) { 
			$row_log_masuk = $array_log_id_masuk[$i];
			$row_log_pulang = $array_log_id_pulang[$i];

			if (!empty($row_log_masuk) && !empty($row_log_pulang)) {
				$temp = new stdClass();
				$temp->log_id_masuk = $row_log_masuk;
				$temp->log_id_pulang = $row_log_pulang;

				array_push($data_insert, $temp);
				array_push($data_update_log_id_masuk, $row_log_masuk);
				array_push($data_update_log_id_pulang, $row_log_pulang);
				array_push($all_data_log_id, $row_log_masuk, $row_log_pulang);
			}else{
				array_push($data_failed, $row_log_masuk);
			}
		}

		$new_all_log_id = implode(", ", $all_data_log_id); 
		$new_all_data_failed = implode(", ", $data_failed);

		///// select all data failed
		$select_data_failed = $this->db->query("
									SELECT 
										a.date,
										b.full_name,
										a.submit_time as waktu_masuk,
										'-' as waktu_pulang
									FROM log_absensi a
									LEFT JOIN users b ON a.user_id=b.id
									WHERE a.id IN (".$new_all_data_failed.") 
							")->result();

		///// select all id log with $new_all_log_id
		$select_masuk_pulang = $this->db->query("SELECT 
											*, 
											other_data->>'status_off_panggil' AS status_off_panggil,
											other_data->>'status_absen_schedule' AS status_absen_schedule,
											other_data->>'status_approval' AS status_approval 
										FROM log_absensi 
										WHERE id IN (".$new_all_log_id.") 
										AND (other_data->>'status_approval'='false' OR other_data->>'status_approval' is null)")->result();
		//echo $this->db->last_query();
		echo "<script>  update_progress(25,'Preparing Data (fill to memory holder)...')        </script>";
        $this->flush_buffer();

		$all_prepare_data_update = [];
		foreach ($select_masuk_pulang as $value) {
			array_push($all_prepare_data_update, $value->id);
		}
		$update_all_log_id = implode(", ", $all_prepare_data_update);

		$data = array(
			'status_approval' => true
		);

		$new_data = json_encode($data);

		/// update status approval
		$update = $this->db->query(
			"UPDATE log_absensi SET other_data = other_data || ? WHERE id IN (".$update_all_log_id.")",
			[$new_data]
		);

		echo "<script>  
					update_progress(100,'Approval Absensi Success!')
					table(".json_encode($select_data_failed).")
				</script>";
        $this->flush_buffer();
	}

	protected function flush_buffer()
    {
        ob_flush();
        flush();
        ob_end_flush();
        ob_start();
    }
}