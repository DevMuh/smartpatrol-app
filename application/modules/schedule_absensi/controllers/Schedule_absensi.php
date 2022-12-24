<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Schedule_absensi extends MY_Controller
{

    public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model(array('M_schedule_absensi','M_users'));
		$this->b2b = $this->session->userdata('b2b_token');
	}
	public function index()
	{
		// if ($this->session->userdata("user_roles") != "cudo") {
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
    public function ajax($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");

		$data = $this->M_schedule_absensi->fetch($month, $year);
		echo json_encode($data);
	}
	public function ajax_column($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");

		$data = $this->M_schedule_absensi->fetch_column($month,$year);
		echo json_encode($data);
	}
    public function get_option_user()
	{
		$get_all_user = $this->M_users->get_data();
		echo json_encode($get_all_user);
	}
	public function get_option_shift()
	{
		$b2b = $this->session->userdata('b2b_token');
        $data = $this->db->where(array('b2b_token' => $b2b, 'flag_enable' => 1, 'kode_shift != '=> 'Off Panggil'))->order_by('waktu_start', 'asc')->get('t_shift')->result();
		echo json_encode($data);
	}
	public function create()
	{
		$schedule_date = $this->input->post("schedule_date");
		$user_id = $this->input->post("user_id");
		$shift_id = $this->input->post("shift_id");
		$b2b = $this->session->userdata('b2b_token');

		if ($schedule_date == "") {
			$payload['status'] = false;
			$payload['message'] = "Schedule Date is required";

			echo json_encode($payload);
			return;
		}

		if ($user_id == "") {
			$payload['status'] = false;
			$payload['message'] = "User is required";

			echo json_encode($payload);
			return;
		}

		if ($shift_id == "") {
			$payload['status'] = false;
			$payload['message'] = "Shift is required";

			echo json_encode($payload);
			return;
		}

		$date = date("Y-m-d", strtotime($schedule_date));

		$other_data = array(
			'created_by' => $this->session->userdata('id'),
			'created_at' => date("Y-m-d H:i:s"),
			'is_deleted' => false
		);

		$data['date'] = $date;
		$data['user_id'] = $user_id;
		$data['shift_id'] = $shift_id;
		$data['b2b_token'] = $b2b;
		$data['other_data'] = json_encode($other_data);

		$insert = $this->db->insert('m_schedule_absensi', $data);

		if ($insert) {
			$payload['status'] = true;
			$payload['message'] = "Success Create Schedule Absensi";

			echo json_encode($payload);
		}else{
			$payload['status'] = false;
			$payload['message'] = "Error, Create Schedule Absensi";

			echo json_encode($payload);
		}
	}
	public function update()
	{
		$id = $this->input->post("id");
		$schedule_date = $this->input->post("edit_schedule_date");
		$user_id = $this->input->post("edit_user_id");
		$shift_id = $this->input->post("edit_shift_id");
		$b2b = $this->session->userdata('b2b_token');

		if ($schedule_date == "") {
			$payload['status'] = false;
			$payload['message'] = "Schedule Date is required";

			echo json_encode($payload);
			return;
		}

		if ($user_id == "") {
			$payload['status'] = false;
			$payload['message'] = "User is required";

			echo json_encode($payload);
			return;
		}

		if ($shift_id == "") {
			$payload['status'] = false;
			$payload['message'] = "Shift is required";

			echo json_encode($payload);
			return;
		}

		$date = date("Y-m-d", strtotime($schedule_date));

		$other_data = array(
			'updated_by' => $this->session->userdata('id'),
			'updated_at' => date("Y-m-d H:i:s")
		);

		$update = $this->db->query("UPDATE m_schedule_absensi 
						SET other_data = other_data || '".json_encode($other_data)."',
						date = ? , user_id = ? , b2b_token = ? , shift_id = ? 
                        WHERE id = ?
                        ", array($date, $user_id, $b2b, $shift_id, $id));

		if ($update) {
			$payload['status'] = true;
			$payload['message'] = "Success Update Schedule Absensi";

			echo json_encode($payload);
		}else{
			$payload['status'] = false;
			$payload['message'] = "Error, Update Schedule Absensi";

			echo json_encode($payload);
		}
	}
	public function delete()
	{
		$id = $this->input->post("schedule_id");

		$other_data = array(
			'is_deleted' => true
		);

		$delete = $this->db->query("UPDATE m_schedule_absensi 
						SET other_data = other_data || '".json_encode($other_data)."'
                        WHERE id = ?
                        ", array($id));

		if ($delete) {
			$payload['status'] = true;
			$payload['message'] = "Success Delete Schedule Absensi";

			echo json_encode($payload);
		}else{
			$payload['status'] = false;
			$payload['message'] = "Error, Delete Schedule Absensi";

			echo json_encode($payload);
		}
	}
	// public function process_upload()
	// {
	// 	$type = $this->input->post("type");
	// 	$b2b_token = $this->b2b;

	// 	$config['upload_path']="./assets/uploaded"; //path folder file upload
    //     $config['allowed_types']='xlsx'; //type file yang boleh di upload
    //     $config['encrypt_name'] = TRUE; //enkripsi file name upload
         
    //     $this->load->library('upload',$config); //call library upload 
    //     if($this->upload->do_upload("fileupload")){ //upload file
	// 		// echo '<pre>';
	// 		// print_r($this->upload->data());
	// 		// echo '</pre>';
	// 		// die();
    //         // $data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
 
    //         // $judul= $this->input->post('judul'); //get judul image
    //         // $image= $data['upload_data']['file_name']; //set file name ke variable image
             
    //         // $result= $this->m_upload->simpan_upload($judul,$image); //kirim value ke model m_upload
    //         // echo json_decode($result);

	// 		$upload_data = $this->upload->data();
	// 		// echo $upload_data['full_path'];
	// 		// die();

	// 		/* API URL */
	// 		$url = $this->config->item('base_url_api_go').'import-schedule/show-data';
   
	// 		/* Init cURL resource */
	// 		$ch = curl_init($url);
	   
	// 		/* Array Parameter Data */
	// 		$data = [
	// 					'b2b_token'       => $b2b_token,
	// 					'type'			  => $type,
	// 					'files' 	  	  => "@$upload_data" 
	// 				];

	// 		$headers = array("Content-Type:multipart/form-data");
	// 		$options = array(
	// 			CURLOPT_URL => $url,
	// 			CURLOPT_HEADER => true,
	// 			CURLOPT_POST => 1,
	// 			CURLOPT_HTTPHEADER => $headers,
	// 			CURLOPT_POSTFIELDS => $data,
	// 			CURLOPT_INFILESIZE => $upload_data['file_size'],
	// 			CURLOPT_RETURNTRANSFER => true
	// 		);
	   
	// 		/* pass encoded JSON string to the POST fields */
	// 		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	// 		curl_setopt_array($ch, $options);
	
	// 		//Create a POST array with the file in it
	// 		// $postData = array(
	// 		// 	'file_excel' => $upload_data['full_path'],
	// 		// );
	// 		// curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				
	// 		/* set return type json */
	// 		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				
	// 		/* execute request */
	// 		redirect($url, "auto");
	// 		return;
	// 		// return;
	// 		// die();
	// 		$result = curl_exec($ch);
				 
	// 		/* close cURL resource */
	// 		curl_close($ch);

	// 		echo '<pre>';
	// 		print_r($result);
	// 		echo '</pre>';
	// 		die();
	
	// 		$datas = json_decode($result);
	
	// 		$payload['status'] = true;
	// 		$payload['datas'] = $datas;
	
	// 		echo json_encode($payload);
    //     }

	// 	die();

	// 	/* API URL */
    //     $url = $this->config->item('base_url_api_go').'import-schedule/show-data';
   
    //     /* Init cURL resource */
    //     $ch = curl_init($url);
   
    //     /* Array Parameter Data */
    //     $data = [
    //                 'b2b_token'       => $b2b_token,
	// 				'type'			  => $type, 
    //             ];
   
    //     /* pass encoded JSON string to the POST fields */
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	// 	//Create a POST array with the file in it
	// 	$postData = array(
	// 		'testData' => '@/path/to/file.txt',
	// 	);
	// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            
    //     /* set return type json */
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
    //     /* execute request */
    //     $result = curl_exec($ch);
             
    //     /* close cURL resource */
    //     curl_close($ch);

    //     $datas = json_decode($result);

	// 	$payload['status'] = true;
	// 	$payload['datas'] = $datas;

	// 	echo json_encode($payload);
	// }
}