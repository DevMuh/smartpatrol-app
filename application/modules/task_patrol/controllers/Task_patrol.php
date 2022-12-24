<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task_patrol extends MY_Controller
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
		$this->load->model('task_patrol/M_task_patrol');
	}

	public function index()
	{
		// $str = "a','b','c";
		// $encode = urlencode(utf8_encode($str));
		// $decode = utf8_decode(urldecode($encode));

		// echo $encode;
		// echo "<br>";
		// echo $decode;
		// die();


		$b2b_token = $this->session->userdata('b2b_token');
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);

		$count_security = $this->db->query("SELECT 
			SUM(CASE WHEN u.user_roles::TEXT = 'anggota' OR u.user_roles::TEXT = 'danru' 						 THEN 1 ELSE 0 END ) AS total,
			SUM(CASE WHEN u.user_roles::TEXT = 'anggota' OR u.user_roles::TEXT = 'danru' AND u.status = 'active' THEN 1 ELSE 0 END ) AS active 
			FROM users u
			WHERE  u.b2b_token = ?
			", [$b2b_token])->row();

		$count_security_on = $this->db->query("SELECT 
			COUNT(DISTINCT ph.taken_user) AS on_route,
			SUM(CASE WHEN  r.otherdata->>'schedule_type' IS NOT NULL  THEN 1 ELSE 0 END) AS on_schedule,
			SUM(CASE WHEN  r.otherdata->>'schedule_type' IS NULL 	  THEN 1 ELSE 0 END) AS manual	
			FROM users u
				JOIN t_task_patrol_header ph ON
			ph.taken_user::TEXT = u.id::TEXT
				JOIN cluster_route r ON
			ph.id_route::TEXT = r.id_route::TEXT
			WHERE  u.b2b_token = ? 
			GROUP BY ph.taken_user 
			", [$b2b_token])->row();

		$count_checkpoint = $this->db->query("SELECT                         
			COUNT(c.*) AS total,
			SUM(CASE WHEN c.flag_disable::TEXT  = '1' 				THEN 1 ELSE 0 END) AS active,
			SUM(CASE WHEN c.cluster_route IS NOT NULL 				THEN 1 ELSE 0 END) AS on_route,
			SUM(CASE WHEN r.otherdata->>'schedule_type' IS NOT NULL THEN 1 ELSE 0 END) AS on_schedule,
			SUM(CASE WHEN r.otherdata->>'schedule_type' IS NULL 	THEN 1 ELSE 0 END) AS manual	
			FROM check_point c
			LEFT JOIN cluster_route r ON
			r.id_route::TEXT = c.cluster_route::TEXT
			WHERE  c.b2b_token = ?	
			", [$b2b_token])->row();

		$count_route = $this->db->query("SELECT                         
			COUNT(*) AS total,
			SUM(CASE WHEN flag_active::TEXT	= '1' 	  			  THEN 1 ELSE 0 END) AS active,
			SUM(CASE WHEN otherdata->>'schedule_type' IS NOT NULL THEN 1 ELSE 0 END) AS on_schedule,
			SUM(CASE WHEN otherdata->>'schedule_type' IS NULL     THEN 1 ELSE 0 END) AS manual	
			FROM cluster_route WHERE  b2b_token = ?	
			", [$b2b_token])->row();

		$total_route_not_have_checkpoint = $this->db->query("SELECT 
			COUNT(*) as total_route_not_have_checkpoint
			FROM cluster_route 
			WHERE b2b_token = '$b2b_token'
			AND id_route::TEXT NOT IN (SELECT DISTINCT cluster_route::TEXT FROM check_point WHERE b2b_token = '$b2b_token'AND cluster_route IS NOT NULL)
			")->row()->total_route_not_have_checkpoint;

		$count_schedule = $this->db->query("SELECT                         
			SUM(CASE WHEN otherdata->>'schedule_type' IS NOT NULL THEN 1 ELSE 0 END) AS total,
			SUM(CASE WHEN otherdata->>'schedule_type' = 'daily'   THEN 1 ELSE 0 END) AS daily,
			SUM(CASE WHEN otherdata->>'schedule_type' = 'weekly'  THEN 1 ELSE 0 END) AS weekly,
			SUM(CASE WHEN otherdata->>'schedule_type' = 'monthly' THEN 1 ELSE 0 END) AS monthly,
			SUM(CASE WHEN otherdata->>'schedule_type' = 'annual'  THEN 1 ELSE 0 END) AS annual	
			FROM cluster_route WHERE  b2b_token = ?
			", [$b2b_token])->row();

		$data["total_security"] = $count_security->total;
		$data["total_checkpoint"] = $count_checkpoint->total;
		$data["total_route"] = $count_route->total;
		$data["total_schedule"] = $count_schedule->total;

		$data["color_security"] = "black";
		$data["color_schedule"] = "orange";
		$data["color_route"] = "green";
		$data["color_checkpoint"] = "red";

		$data["detail_security_summary"] = [
			[
				'label' => "On Route",
				'icon' => "fas fa fa-shield-alt",
				'value' => $count_security_on->on_route,
				'total' => $count_security->total,
				'color' => $data["color_security"]
			],
			[
				'label' => "Active",
				'icon' => "fas fa fa-shield-alt",
				'value' => $count_security->active,
				'total' => $count_security->total,
				'color' => $data["color_security"]
			],
			[
				'label' => "On Schedule",
				'icon' => "fas fa fa-shield-alt",
				'value' => 0,
				'total' => $count_security->total,
				'color' => $data["color_security"]
			],
			[
				'label' => "Manual",
				'icon' => "fas fa fa-shield-alt",
				'value' => 0,
				'total' => $count_security->total,
				'color' => $data["color_security"]
			],
		];
		$data["detail_checkpoint_summary"] = [
			[
				'label' => "CP Active",
				'icon' => "fas fa fa-map-marker",
				'value' => $count_checkpoint->active,
				'total' => $count_checkpoint->total,
				'color' => $data["color_checkpoint"]
			],
			[
				'label' => "On Route",
				'icon' => "fas fa fa-map-marker",
				'value' => $count_checkpoint->on_route,
				'total' => $count_checkpoint->total,
				'color' => $data["color_checkpoint"]
			],
			[
				'label' => "On Schedule",
				'icon' => "fas fa fa-map-marker",
				'value' => $count_checkpoint->on_schedule,
				'total' => $count_checkpoint->total,
				'color' => $data["color_checkpoint"]
			],
			[
				'label' => "Manual",
				'icon' => "fas fa fa-map-marker",
				'value' => $count_checkpoint->manual,
				'total' => $count_checkpoint->total,
				'color' => $data["color_checkpoint"]
			],
		];
		$data["detail_route_summary"] = [
			[
				'label' => "Route Active",
				'icon' => "fas fa fa-map",
				'value' => $count_route->active,
				'total' => $count_route->total,
				'color' => $data["color_route"]
			],
			[
				'label' => "Unoccupied",
				'icon' => "fas fa fa-map",
				'value' => $total_route_not_have_checkpoint,
				'total' => $count_route->total,
				'color' => $data["color_route"]
			],
			[
				'label' => "On Schedule",
				'icon' => "fas fa fa-map",
				'value' => $count_route->on_schedule,
				'total' => $count_route->total,
				'color' => $data["color_route"]
			],
			[
				'label' => "Manual",
				'icon' => "fas fa fa-map",
				'value' => $count_route->manual,
				'total' => $count_route->total,
				'color' => $data["color_route"]
			],
		];
		$data["detail_schedule_summary"] = [
			[
				'label' => "Daily",
				'icon' => "fas fa fa-clock",
				'value' => $count_schedule->daily,
				'total' => NULL,
				'color' => $data["color_schedule"]
			],
			[
				'label' => "Weekly",
				'icon' => "fas fa fa-clock",
				'value' => $count_schedule->weekly,
				'total' => NULL,
				'color' => $data["color_schedule"]
			],
			[
				'label' => "Monthly",
				'icon' => "fas fa fa-clock",
				'value' => $count_schedule->monthly,
				'total' => NULL,
				'color' => $data["color_schedule"]
			],
			[
				'label' => "Annual",
				'icon' => "fas fa fa-clock",
				'value' => $count_schedule->annual,
				'total' => NULL,
				'color' => $data["color_schedule"]
			],
		];
		$this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}
	public function checkpoint($b2b_token, $id)
	{		
		// cek status server storage
		$status_server = false;
        if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
            $status_server = true;
        }else{
            $status_server = false;
        }
		// $id = $this->input->get('cpid');
		$get_data = $this->db->query("
		SELECT ttph.*,u.*,mb2b.*,ttph.id_ as idnya  FROM t_task_patrol_header ttph
		LEFT JOIN users u on ttph.taken_user::INTEGER=u.id
		LEFT JOIN m_register_b2b mb2b on mb2b.b2b_token=ttph.b2b_token
		WHERE ttph.id_='" . $id . "'")->row();
		

		$get_data_list_task = $this->db->query("SELECT t_task_patrol_header.*,id_ as id,total_cp, users.full_name FROM t_task_patrol_header left join users on users.id::varchar=t_task_patrol_header.taken_user 
        WHERE t_task_patrol_header.b2b_token = '".$get_data->b2b_token."'
        AND  t_task_patrol_header.publish_date::TEXT = '".$get_data->publish_date."' AND t_task_patrol_header.taken_user='".$get_data->taken_user."'")->result();

        $querytrack = $this->db->query("SELECT created_time FROM track_route WHERE b2b_token='".$get_data->b2b_token."' AND created_time::TEXT LIKE '%".$get_data->publish_date."%'")->result_array();
		
		$arr = [];
		foreach ($get_data_list_task as $key => $value) {
			if ($value->total_cp == 1) {
			foreach ($querytrack as $key => $valuetrack) {
					$stringdate = explode(" ",$valuetrack["created_time"]);
                    if ($value->publish_date ==  $stringdate[0] ) {
						array_push($arr,$stringdate);
                        // var_dump($stringdate);die;
                    }
				}
			}
		}

		// var_dump("arr",$get_data_list_task);die;
		// echo '<pre>';
		// print_r($get_data_list_task);
		// echo '</pre>';
		// die();
		if ($arr > 0) {
			$counttrack = count($arr)-1;
			$get_data_list_task[0]->publish_date = $arr[0][0];
			$get_data_list_task[0]->publish_time = $arr[0][1];
			$get_data_list_task[0]->done_date = $arr[$counttrack][0];
			$get_data_list_task[0]->done_time = $arr[$counttrack][1];
			// echo '<pre>';
			// print_r($get_data_list_task);
			// echo '</pre>';
			// die();
		}
		$data['data_list_task'] = $get_data_list_task;

		$allIdTaskPatrol = array();
		foreach ($get_data_list_task as $value) {
			array_push($allIdTaskPatrol, $value->id_);
		}

		$list = implode("', '", $allIdTaskPatrol);
		//echo $list; die();
		$get_data->all_id_task_patrol = $list;
		
		$data['nama_orang'] = $get_data;
		
		$get_photo = $this->db->query("SELECT img_name AS foto_orang from t_task_patrol_image where img_status='0' and id_header='" . $id . "'")->row();
		$no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");
		$new_foto_orang = "";
		$base_url_photo_detail = "";
		if ($status_server) {
			$this->load->library('curl');
			$image_cudo = $this->config->item("base_url_server_cudo") . "assets/selfie/" . $get_photo->foto_orang;
			$image = $this->config->item('base_url_api') . "assets/images/selfie/" . $get_photo->foto_orang;
			$result = $this->curl->simple_get($image_cudo);
			//$base_url_photo_detail = $this->config->item("base_url_server_cudo") . "assets/cp/";
			
			if($result != ""){
				$new_foto_orang = $image_cudo;
			}elseif(@getimagesize($image)){
				$new_foto_orang = $image;
			}else{
				$new_foto_orang = $no_image;
			}
		} else {
			$image = $this->config->item('base_url_api') . "assets/images/selfie/" . $get_photo->foto_orang;
			//$base_url_photo_detail = $this->config->item("base_url_api") . "assets/images/cp/";
			if(@getimagesize($image)){
				$new_foto_orang = $image;
			}else{
				$new_foto_orang = $no_image;
			}
		}
		$data['foto_orang_nya'] = $new_foto_orang;
		// $data['foto_orang_nya'] = $this->db->query("SELECT CONCAT ( '" . $this->config->item('base_url_api') . ('assets/images/selfie/') . "' ,img_name ) AS foto_orang from t_task_patrol_image where img_status='0' and id_header='" . $id . "'")->row();
		// $data['foto_orang_nya_cudo'] = $this->db->query("SELECT CONCAT ( '" . $this->config->item('base_url_server_cudo') . ('assets/selfie/') . "' ,img_name ) AS foto_orang from t_task_patrol_image where img_status='0' and id_header='" . $id . "'")->row();
		// echo json_encode($datas['foto_orang']);
		// die();
		$data['details'] = $this->db->query("SELECT
			tpd.cp_id,
			CONCAT ( TO_CHAR( tpd.submit_date, 'dd Mon yyyy' ), ' ', tpd.submit_time ) AS created_date,
			tpd.cp_lat,
			tpd.cp_long,
			tpd.cp_name,
			tpd.cp_nfc,
			tpd.cp_qr,
			tpd.flag_process,
			tpd.remark AS note,
			-- CONCAT ('" . $this->config->item('base_url_api') . ('assets/images/cp/') . "' , tpi1.img_name ) AS image_1,
			-- CONCAT ( '" . $this->config->item('base_url_api') . ('assets/images/cp/') . "' , tpi2.img_name ) AS image_2,
			-- CONCAT ( '" . $this->config->item('base_url_api') . ('assets/images/cp/') . "' , tpi3.img_name ) AS image_3,

			-- CONCAT ('" . $base_url_photo_detail . "' , tpi1.img_name ) AS image_1,
			-- CONCAT ( '" . $base_url_photo_detail . "' , tpi2.img_name ) AS image_2,
			-- CONCAT ( '" . $base_url_photo_detail . "' , tpi3.img_name ) AS image_3,

			tpi1.img_name AS image_1,
			tpi2.img_name AS image_2,
			tpi3.img_name AS image_3,

		CASE
				
				WHEN tpd.flag_process = 0 THEN
				'Disable' 
				WHEN tpd.flag_process = 1 THEN
				'Open' 
				WHEN tpd.flag_process = 2 THEN
				'Booked' 
				WHEN tpd.flag_process = 3 THEN
				'Done' 
			END AS flag_process_string 
		FROM
			t_task_patrol_detail tpd
			JOIN t_task_patrol_image tpi1 ON tpd.id_ = tpi1.id_header :: INTEGER 
			AND tpi1.img_name LIKE'%image_patrol_1%'
			JOIN t_task_patrol_image tpi2 ON tpd.id_ = tpi2.id_header :: INTEGER 
			AND tpi2.img_name LIKE'%image_patrol_2%'
			JOIN t_task_patrol_image tpi3 ON tpd.id_ = tpi3.id_header :: INTEGER 
			AND tpi3.img_name LIKE'%image_patrol_3%'
		WHERE
			tpd.b2b_token = '" . $b2b_token . "' 
			AND ( tpd.flag_process = '1' OR tpd.flag_process = '3' OR tpd.flag_process = '2' ) 
			-- AND tpd.id_header = '" . $id . "'
			AND tpd.id_header IN ('".$list."')
		")->result();

		// $cekmap = $this->db->select('map_image')->where('id_', $id)->get('t_task_patrol_header')->row();
		// if (empty($cekmap->map_image)) {
		// 	$data['map'] = 0;
		// } else {
		$data['map'] = 1;
		// }
		if ($id == '' || count($data['details']) == 0) {
			$data['code'] = 404;
			$data['id'] = $id;
		} else {
			$data['code'] = 200;
			$data['id'] = 0;
		}
		// var_dump(json_encode($data));die;

		$this->load->view('layout/header');
		$this->load->view('checkpoint', $data);
		$this->load->view('layout/footer');
		$this->load->view('main_script');
	}
	public function detail($id)
	{
		$data = $this->M_task_patrol->fetchDetail($id);
		if (!$data) {
			$data['code'] = 404;
		} else {
			$data['code'] = 200;
		}
		$this->load->view('layout/header');
		$this->load->view('detail', $data);
		$this->load->view('layout/footer');
	}
	public function ajax($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");
		$data = $this->M_task_patrol->fetch($month, $year);
		echo json_encode($data);
	}
	public function ajaxcp($id)
	{
		$data = $this->M_task_patrol->fetchCp($id);
		echo json_encode($data);
	}

	public function export_pdf_json($b2b_token, $id, $done_time)
	{
		$task_patrol_header = $this->db->query("
		SELECT ttph.*,u.*,mb2b.*,ttph.id_ as idnya  FROM t_task_patrol_header ttph
		LEFT JOIN users u on ttph.taken_user::INTEGER=u.id
		LEFT JOIN m_register_b2b mb2b on mb2b.b2b_token=ttph.b2b_token
		WHERE ttph.id_='" . $id . "'")->row();

		$get_data_list_task = $this->db->query("SELECT t_task_patrol_header.*, users.full_name FROM t_task_patrol_header left join users on users.id::varchar=t_task_patrol_header.taken_user 
        WHERE t_task_patrol_header.b2b_token = '".$task_patrol_header->b2b_token."'
        AND  t_task_patrol_header.publish_date::TEXT = '".$task_patrol_header->publish_date."' AND t_task_patrol_header.taken_user='".$task_patrol_header->taken_user."'")->result();

		$allIdTaskPatrol = array();
		foreach ($get_data_list_task as $value) {
			array_push($allIdTaskPatrol, $value->id_);
		}

		$list = implode("', '", $allIdTaskPatrol);

		$no=1;
		$dur="-";
		$last_start_date="";
		$start_time="";

		$list_all_task = array();

		foreach ($get_data_list_task as $value) { 
			if ($no == 1) {
				if (!empty($value->done_time)) {
					$start_date = new DateTime($value->publish_time);
					$end_date = new DateTime($value->done_time);
					$dd = date_diff($end_date, $start_date);
					$dur = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
					$last_start_date = $value->done_time;
					$start_time = $value->publish_time;
				} else {
					$dur = 0;
				}
			}else if($no == count($get_data_list_task)) {
				if (!empty($last_start_date)) {
					$start_date = new DateTime($last_start_date);
					$end_date = new DateTime($value->done_time);
					$dd = date_diff($end_date, $start_date);
					$dur = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
					$start_time = $last_start_date;
				} else {
					$dur = 0;
				}
			}else{
				if (!empty($last_start_date)) {
					$start_date = new DateTime($last_start_date);
					$end_date = new DateTime($value->done_time);
					$dd = date_diff($end_date, $start_date);
					$dur = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
					$start_time = $last_start_date;
				} else {
					$dur = 0;
				}
			}

			$data = array(
				'cluster_name' => $value->cluster_name,
				'full_name' => $value->full_name,
				'start' => $value->publish_date . " " . $start_time,
				'end' => $value->done_date . " " . $value->done_time,
				'duration' => $dur
			);

			$no++;

			array_push($list_all_task, $data);
		}

		$get_photo = $this->db->query("SELECT img_name AS foto_orang from t_task_patrol_image where img_status='0' and id_header='" . $id . "'")->row();
		$no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");
		$new_foto_orang = "";
		$base_url_photo_detail = "";

		$task_patrol_detail = $this->db->query("
			SELECT
				tpd.cp_id,
				CONCAT ( TO_CHAR( tpd.submit_date, 'dd Mon yyyy' ), ' ', tpd.submit_time ) AS created_date,
				tpd.cp_lat,
				tpd.cp_long,
				tpd.cp_name,
				tpd.cp_nfc,
				tpd.cp_qr,
				tpd.flag_process,
				tpd.remark AS note,
				tpi1.img_name AS image_1,
				tpi2.img_name AS image_2,
				tpi3.img_name AS image_3,

			CASE
					WHEN tpd.flag_process = 0 THEN
					'Disable' 
					WHEN tpd.flag_process = 1 THEN
					'Open' 
					WHEN tpd.flag_process = 2 THEN
					'Booked' 
					WHEN tpd.flag_process = 3 THEN
					'Done' 
				END AS flag_process_string 
			FROM
				t_task_patrol_detail tpd
				JOIN t_task_patrol_image tpi1 ON tpd.id_ = tpi1.id_header :: INTEGER 
				AND tpi1.img_name LIKE'%image_patrol_1%'
				JOIN t_task_patrol_image tpi2 ON tpd.id_ = tpi2.id_header :: INTEGER 
				AND tpi2.img_name LIKE'%image_patrol_2%'
				JOIN t_task_patrol_image tpi3 ON tpd.id_ = tpi3.id_header :: INTEGER 
				AND tpi3.img_name LIKE'%image_patrol_3%'
			WHERE
				tpd.b2b_token = '" . $b2b_token . "' 
				AND ( tpd.flag_process = '1' OR tpd.flag_process = '3' OR tpd.flag_process = '2' ) 
				-- AND tpd.id_header = '" . $id . "'
				AND tpd.id_header IN ('".$list."')
			")->result();

		// cek status server storage
		$status_server = false;
        if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
            $status_server = true;
        }else{
            $status_server = false;
        }

		if ($status_server) {
			$this->load->library('curl');
			$image_cudo = $this->config->item("base_url_server_cudo") . "assets/selfie/" . $get_photo->foto_orang;
			$image = $this->config->item('base_url_api') . "assets/images/selfie/" . $get_photo->foto_orang;
			$result = $this->curl->simple_get($image_cudo);
			//$base_url_photo_detail = $this->config->item("base_url_server_cudo") . "assets/cp/";
			
			if($result != ""){
				$new_foto_orang = $image_cudo;
			}elseif(@getimagesize($image)){
				$new_foto_orang = $image;
			}else{
				$new_foto_orang = $no_image;
			}
		} else {
			$image = $this->config->item('base_url_api') . "assets/images/selfie/" . $get_photo->foto_orang;
			//$base_url_photo_detail = $this->config->item("base_url_api") . "assets/images/cp/";
			if(@getimagesize($image)){
				$new_foto_orang = $image;
			}else{
				$new_foto_orang = $no_image;
			}
		}

		$task_patrol_photo = $new_foto_orang;

		$new_selfie_base64 = "";
		if($task_patrol_photo){
			$url_selfie = $task_patrol_photo;
			$new_selfie_base64 = 'data:image/png;base64,' . base64_encode(fetch($url_selfie));
		}

		$duration = "";
		//if (!empty($task_patrol_header->done_time)) {
		if (!empty($done_time)) {
			$start_date = new DateTime($task_patrol_header->publish_time);
			//$end_date = new DateTime($task_patrol_header->done_time);
			$end_date = new DateTime($done_time);
			$dd = date_diff($end_date, $start_date);
			$duration = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
		} else {
			$duration = 0;
		}

		$new_task_header = new stdClass();
		$new_task_header->cluster_name = $task_patrol_header->cluster_name;
		$new_task_header->username = $task_patrol_header->username;
		$new_task_header->publish_date = $task_patrol_header->publish_date;
		$new_task_header->publish_time = $task_patrol_header->publish_time;
		$new_task_header->done_date = $task_patrol_header->done_date;
		//$new_task_header->done_time = $task_patrol_header->done_time;
		$new_task_header->done_time = $done_time;
		$new_task_header->duration = $duration;
		//$new_task_header->total_cp = $task_patrol_header->total_cp;
		$new_task_header->total_cp = count($list_all_task);
		$new_task_header->total_done = $task_patrol_header->total_done;

		$new_task_patrol_detail = array();
		//for ($i=0; $i < count($task_patrol_detail); $i++) {
		foreach ($task_patrol_detail as $val) {
			
			$new_img1_base64 = "";
			$new_img1_label = "";
			if ($val->image_1) {
				$time = strtotime($val->created_date);
                $newformat = date('YmdHis',$time);

				$new_image_1 = "";
				if ($status_server) {
					$this->load->library('curl');
					$image_cudo = $this->config->item("base_url_server_cudo") . "assets/cp/" . $val->image_1;
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_1;
					$result = $this->curl->simple_get($image_cudo);
					
					$label = "img1_".$val->cp_id."_".$newformat.".jpg";
					
					if($result != ""){
						$new_image_1 = $image_cudo;
						$new_img1_label = $label;
					}elseif(@getimagesize($image)){
						$new_image_1 = $image;
						$new_img1_label = $label;
					}else{
						$new_image_1 = $no_image;
						$new_img1_label = "no image available";
					}
				} else {
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_1;

					$label = "img1_".$val->cp_id."_".$newformat.".jpg";

					if(@getimagesize($image)){
						$new_image_1 = $image;
						$new_img1_label = $label;
					}else{
						$new_image_1 = $no_image;
						$new_img1_label = "no image available";
					}
				}
				
				$new_img1_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_1));
			} else {
				$new_image_1 = $no_image;
				$new_img1_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_1));
				$new_img1_label = "no image available";
			}

			$new_img2_base64 = "";
			$new_img2_label = "";
			if ($val->image_2) {
				$time = strtotime($val->created_date);
                $newformat = date('YmdHis',$time);

				$new_image_2 = "";
				if ($status_server) {
					$this->load->library('curl');
					$image_cudo = $this->config->item("base_url_server_cudo") . "assets/cp/" . $val->image_2;
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_2;
					$result = $this->curl->simple_get($image_cudo);
					
					$label = "img2_".$val->cp_id."_".$newformat.".jpg";
					
					if($result != ""){
						$new_image_2 = $image_cudo;
						$new_img2_label = $label;
					}elseif(@getimagesize($image)){
						$new_image_2 = $image;
						$new_img2_label = $label;
					}else{
						$new_image_2 = $no_image;
						$new_img2_label = "no image available";
					}
				} else {
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_2;

					$label = "img2_".$val->cp_id."_".$newformat.".jpg";

					if(@getimagesize($image)){
						$new_image_2 = $image;
						$new_img2_label = $label;
					}else{
						$new_image_2 = $no_image;
						$new_img2_label = "no image available";
					}
				}
				
				$new_img2_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_2));
			} else {
				$new_image_2 = $no_image;
				$new_img2_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_2));
				$new_img2_label = "no image available";
			}

			$new_img3_base64 = "";
			$new_img3_label = "";
			if ($val->image_3) {
				$time = strtotime($val->created_date);
                $newformat = date('YmdHis',$time);

				$new_image_3 = "";
				if ($status_server) {
					$this->load->library('curl');
					$image_cudo = $this->config->item("base_url_server_cudo") . "assets/cp/" . $val->image_3;
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_3;
					$result = $this->curl->simple_get($image_cudo);
					
					$label = "img3_".$val->cp_id."_".$newformat.".jpg";
					
					if($result != ""){
						$new_image_3 = $image_cudo;
						$new_img3_label = $label;
					}elseif(@getimagesize($image)){
						$new_image_3 = $image;
						$new_img3_label = $label;
					}else{
						$new_image_3 = $no_image;
						$new_img3_label = "no image available";
					}
				} else {
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_3;

					$label = "img3_".$val->cp_id."_".$newformat.".jpg";

					if(@getimagesize($image)){
						$new_image_3 = $image;
						$new_img3_label = $label;
					}else{
						$new_image_3 = $no_image;
						$new_img3_label = "no image available";
					}
				}
				
				$new_img3_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_3));
			} else {
				$new_image_3 = $no_image;
				$new_img3_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_3));
				$new_img3_label = "no image available";
			}
			
			$data = array(
				'cp_id' 		=> $val->cp_id,
				'cp_name' 		=> $val->cp_name,
				'note' 			=> $val->note,
				'label_img1' 	=> $new_img1_label,
				'base64_img1' 	=> $new_img1_base64,
				'label_img2' 	=> $new_img2_label,
				'base64_img2' 	=> $new_img2_base64,
				'label_img3' 	=> $new_img3_label,
				'base64_img3' 	=> $new_img3_base64,
			);

			array_push($new_task_patrol_detail, $data);
		}

		$new_id = base64_encode($list);
		//$new_id = urlencode(utf8_encode($list));
		//$websiteURL = $this->config->item('base_url_api') . "cli_trigger/maps2/".$id;
		$websiteURL = $this->config->item('base_url_api') . "cli_trigger/maps2/".$new_id;
        $opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
        //Basically adding headers to the request
        $context = stream_context_create($opts);
        $api_response = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$websiteURL&screenshot=true", false, $context);
		$result = json_decode($api_response, true);
		$map_screen_shoot = $result['lighthouseResult']['audits']['full-page-screenshot']['details']['screenshot']['data'];

		$new_map = "";
		if ($map_screen_shoot) {
			$new_map = $map_screen_shoot;
		}else{
			$new_map = 'data:image/png;base64,' . base64_encode(fetch($no_image));
		}

		$new_data = array(
						'task_patrol_header'	=> $new_task_header,
						'list_all_task' 		=> $list_all_task, 
						'task_patrol_detail' 	=> $new_task_patrol_detail,
						'task_patrol_photo' 	=> $new_selfie_base64,
						'map_screen_shoot' 		=> $new_map,
					);
		
		if ($new_data) {
			echo json_encode([
				"status" => true,
				"data" => $new_data
			]);
		} else {
			echo json_encode([
				"status" => false,
				"data" => $new_data
			]);
		}
	}

	public function export_excel_json($b2b_token, $id, $done_time)
	{
		$task_patrol_header = $this->db->query("
		SELECT ttph.*,u.*,mb2b.*,ttph.id_ as idnya  FROM t_task_patrol_header ttph
		LEFT JOIN users u on ttph.taken_user::INTEGER=u.id
		LEFT JOIN m_register_b2b mb2b on mb2b.b2b_token=ttph.b2b_token
		WHERE ttph.id_='" . $id . "'")->row();

		$get_data_list_task = $this->db->query("SELECT t_task_patrol_header.*, users.full_name FROM t_task_patrol_header left join users on users.id::varchar=t_task_patrol_header.taken_user 
        WHERE t_task_patrol_header.b2b_token = '".$task_patrol_header->b2b_token."'
        AND  t_task_patrol_header.publish_date::TEXT = '".$task_patrol_header->publish_date."' AND t_task_patrol_header.taken_user='".$task_patrol_header->taken_user."'")->result();

		$allIdTaskPatrol = array();
		foreach ($get_data_list_task as $value) {
			array_push($allIdTaskPatrol, $value->id_);
		}

		$list = implode("', '", $allIdTaskPatrol);

		$task_patrol_detail = $this->db->query("
			SELECT
				tpd.cp_id,
				CONCAT ( TO_CHAR( tpd.submit_date, 'dd Mon yyyy' ), ' ', tpd.submit_time ) AS created_date,
				tpd.cp_lat,
				tpd.cp_long,
				tpd.cp_name,
				tpd.cp_nfc,
				tpd.cp_qr,
				tpd.flag_process,
				tpd.remark AS note,
				tpi1.img_name AS image_1,
				tpi2.img_name AS image_2,
				tpi3.img_name AS image_3,

			CASE
					WHEN tpd.flag_process = 0 THEN
					'Disable' 
					WHEN tpd.flag_process = 1 THEN
					'Open' 
					WHEN tpd.flag_process = 2 THEN
					'Booked' 
					WHEN tpd.flag_process = 3 THEN
					'Done' 
				END AS flag_process_string 
			FROM
				t_task_patrol_detail tpd
				JOIN t_task_patrol_image tpi1 ON tpd.id_ = tpi1.id_header :: INTEGER 
				AND tpi1.img_name LIKE'%image_patrol_1%'
				JOIN t_task_patrol_image tpi2 ON tpd.id_ = tpi2.id_header :: INTEGER 
				AND tpi2.img_name LIKE'%image_patrol_2%'
				JOIN t_task_patrol_image tpi3 ON tpd.id_ = tpi3.id_header :: INTEGER 
				AND tpi3.img_name LIKE'%image_patrol_3%'
			WHERE
				tpd.b2b_token = '" . $b2b_token . "' 
				AND ( tpd.flag_process = '1' OR tpd.flag_process = '3' OR tpd.flag_process = '2' ) 
				-- AND tpd.id_header = '" . $id . "'
				AND tpd.id_header IN ('".$list."')
			")->result();

		$no=1;
		$dur="-";
		$last_start_date="";
		$start_time="";

		$list_all_task = array();

		foreach ($get_data_list_task as $value) { 
			if ($no == 1) {
				if (!empty($value->done_time)) {
					$start_date = new DateTime($value->publish_time);
					$end_date = new DateTime($value->done_time);
					$dd = date_diff($end_date, $start_date);
					$dur = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
					$last_start_date = $value->done_time;
					$start_time = $value->publish_time;
				} else {
					$dur = 0;
				}
			}else if($no == count($get_data_list_task)) {
				if (!empty($last_start_date)) {
					$start_date = new DateTime($last_start_date);
					$end_date = new DateTime($value->done_time);
					$dd = date_diff($end_date, $start_date);
					$dur = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
					$start_time = $last_start_date;
				} else {
					$dur = 0;
				}
			}else{
				if (!empty($last_start_date)) {
					$start_date = new DateTime($last_start_date);
					$end_date = new DateTime($value->done_time);
					$dd = date_diff($end_date, $start_date);
					$dur = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
					$start_time = $last_start_date;
				} else {
					$dur = 0;
				}
			}

			$data = array(
				'cluster_name' => $value->cluster_name,
				'full_name' => $value->full_name,
				'start' => $value->publish_date . " " . $start_time,
				'end' => $value->done_date . " " . $value->done_time,
				'duration' => $dur
			);

			$no++;

			array_push($list_all_task, $data);
		}

		$duration = "";
		if (!empty($done_time)) {
			$start_date = new DateTime($task_patrol_header->publish_time);
			//$end_date = new DateTime($task_patrol_header->done_time);
			$end_date = new DateTime($done_time);
			$dd = date_diff($end_date, $start_date);
			$duration = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
		} else {
			$duration = 0;
		}

		$new_task_header = new stdClass();
		$new_task_header->cluster_name = $task_patrol_header->cluster_name;
		$new_task_header->username = $task_patrol_header->username;
		$new_task_header->publish_date = $task_patrol_header->publish_date;
		$new_task_header->publish_time = $task_patrol_header->publish_time;
		$new_task_header->done_date = $task_patrol_header->done_date;
		//$new_task_header->done_time = $task_patrol_header->done_time;
		$new_task_header->done_time = $done_time;
		$new_task_header->duration = $duration;
		//$new_task_header->total_cp = $task_patrol_header->total_cp;
		$new_task_header->total_cp = count($list_all_task);
		$new_task_header->total_done = $task_patrol_header->total_done;

		$get_photo = $this->db->query("SELECT img_name AS foto_orang from t_task_patrol_image where img_status='0' and id_header='" . $id . "'")->row();
		$no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");
		$new_foto_orang = "";

		$status_server = false;
        if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
            $status_server = true;
        }else{
            $status_server = false;
        }

		if ($status_server) {
			$this->load->library('curl');
			$image_cudo = $this->config->item("base_url_server_cudo") . "assets/selfie/" . $get_photo->foto_orang;
			$image = $this->config->item('base_url_api') . "assets/images/selfie/" . $get_photo->foto_orang;
			$result = $this->curl->simple_get($image_cudo);
			//$base_url_photo_detail = $this->config->item("base_url_server_cudo") . "assets/cp/";
			
			if($result != ""){
				$new_foto_orang = $image_cudo;
			}elseif(@getimagesize($image)){
				$new_foto_orang = $image;
			}else{
				$new_foto_orang = $no_image;
			}
		} else {
			$image = $this->config->item('base_url_api') . "assets/images/selfie/" . $get_photo->foto_orang;
			//$base_url_photo_detail = $this->config->item("base_url_api") . "assets/images/cp/";
			if(@getimagesize($image)){
				$new_foto_orang = $image;
			}else{
				$new_foto_orang = $no_image;
			}
		}

		$task_patrol_photo = $new_foto_orang;

		$new_task_patrol_detail = array();
		//for ($i=0; $i < count($task_patrol_detail); $i++) {
		foreach ($task_patrol_detail as $val) {			
			$new_img1_base64 = "";
			$new_img1_label = "";
			if ($val->image_1) {
				$time = strtotime($val->created_date);
                $newformat = date('YmdHis',$time);

				$new_image_1 = "";
				if ($status_server) {
					$this->load->library('curl');
					$image_cudo = $this->config->item("base_url_server_cudo") . "assets/cp/" . $val->image_1;
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_1;
					$result = $this->curl->simple_get($image_cudo);
					
					$label = "img1_".$val->cp_id."_".$newformat.".jpg";
					
					if($result != ""){
						$new_image_1 = $image_cudo;
						$new_img1_label = $label;
					}elseif(@getimagesize($image)){
						$new_image_1 = $image;
						$new_img1_label = $label;
					}else{
						$new_image_1 = $no_image;
						$new_img1_label = "no image available";
					}
				} else {
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_1;

					$label = "img1_".$val->cp_id."_".$newformat.".jpg";

					if(@getimagesize($image)){
						$new_image_1 = $image;
						$new_img1_label = $label;
					}else{
						$new_image_1 = $no_image;
						$new_img1_label = "no image available";
					}
				}
				
				//$new_img1_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_1));
				$new_img1_base64 = $new_image_1;
			} else {
				$new_image_1 = $no_image;
				//$new_img1_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_1));
				$new_img1_base64 = $new_image_1;
				$new_img1_label = "no image available";
			}

			$new_img2_base64 = "";
			$new_img2_label = "";
			if ($val->image_2) {
				$time = strtotime($val->created_date);
                $newformat = date('YmdHis',$time);

				$new_image_2 = "";
				if ($status_server) {
					$this->load->library('curl');
					$image_cudo = $this->config->item("base_url_server_cudo") . "assets/cp/" . $val->image_2;
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_2;
					$result = $this->curl->simple_get($image_cudo);
					
					$label = "img2_".$val->cp_id."_".$newformat.".jpg";
					
					if($result != ""){
						$new_image_2 = $image_cudo;
						$new_img2_label = $label;
					}elseif(@getimagesize($image)){
						$new_image_2 = $image;
						$new_img2_label = $label;
					}else{
						$new_image_2 = $no_image;
						$new_img2_label = "no image available";
					}
				} else {
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_2;

					$label = "img2_".$val->cp_id."_".$newformat.".jpg";

					if(@getimagesize($image)){
						$new_image_2 = $image;
						$new_img2_label = $label;
					}else{
						$new_image_2 = $no_image;
						$new_img2_label = "no image available";
					}
				}
				
				//$new_img2_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_2));
				$new_img2_base64 = $new_image_2;
			} else {
				$new_image_2 = $no_image;
				//$new_img2_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_2));
				$new_img2_base64 = $new_image_2;
				$new_img2_label = "no image available";
			}

			$new_img3_base64 = "";
			$new_img3_label = "";
			if ($val->image_3) {
				$time = strtotime($val->created_date);
                $newformat = date('YmdHis',$time);

				$new_image_3 = "";
				if ($status_server) {
					$this->load->library('curl');
					$image_cudo = $this->config->item("base_url_server_cudo") . "assets/cp/" . $val->image_3;
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_3;
					$result = $this->curl->simple_get($image_cudo);
					
					$label = "img3_".$val->cp_id."_".$newformat.".jpg";
					
					if($result != ""){
						$new_image_3 = $image_cudo;
						$new_img3_label = $label;
					}elseif(@getimagesize($image)){
						$new_image_3 = $image;
						$new_img3_label = $label;
					}else{
						$new_image_3 = $no_image;
						$new_img3_label = "no image available";
					}
				} else {
					$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_3;

					$label = "img3_".$val->cp_id."_".$newformat.".jpg";

					if(@getimagesize($image)){
						$new_image_3 = $image;
						$new_img3_label = $label;
					}else{
						$new_image_3 = $no_image;
						$new_img3_label = "no image available";
					}
				}
				
				//$new_img3_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_3));
				$new_img3_base64 = $new_image_3;
			} else {
				$new_image_3 = $no_image;
				//$new_img3_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_3));
				$new_img3_base64 = $new_image_3;
				$new_img3_label = "no image available";
			}
			
			$data = array(
				'cp_id' 		=> $val->cp_id,
				'cp_name' 		=> $val->cp_name,
				'note' 			=> $val->note,
				'label_img1' 	=> $new_img1_label,
				'base64_img1' 	=> $new_img1_base64,
				'label_img2' 	=> $new_img2_label,
				'base64_img2' 	=> $new_img2_base64,
				'label_img3' 	=> $new_img3_label,
				'base64_img3' 	=> $new_img3_base64,
			);

			array_push($new_task_patrol_detail, $data);
		}

		$new_id = base64_encode($list);
		//$new_id = urlencode(utf8_encode($list));
		//$websiteURL = $this->config->item('base_url_api') . "cli_trigger/maps2/".$id;
		$websiteURL = $this->config->item('base_url_api') . "cli_trigger/maps2/".$new_id;
        // $opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
        // //Basically adding headers to the request
        // $context = stream_context_create($opts);
        // $api_response = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$websiteURL&screenshot=true", false, $context);
		// $result = json_decode($api_response, true);
		// $map_screen_shoot = $result['lighthouseResult']['audits']['full-page-screenshot']['details']['screenshot']['data'];
		$map_screen_shoot = $websiteURL;

		$new_map = "";
		if ($map_screen_shoot) {
			$new_map = $map_screen_shoot;
		}else{
			//$new_map = 'data:image/png;base64,' . base64_encode(fetch($no_image));
			$new_map = $no_image;
		}

		$new_data = array(
			'task_patrol_header'	=> $new_task_header,
			'list_all_task' 		=> $list_all_task, 
			'task_patrol_detail' 	=> $new_task_patrol_detail,
			'task_patrol_photo' 	=> $task_patrol_photo,
			'map_screen_shoot' 		=> $new_map,
		);

		if ($new_data) {
			echo json_encode([
				"status" => true,
				"data" => $new_data
			]);
		} else {
			echo json_encode([
				"status" => false,
				"data" => $new_data
			]);
		}
	}

	public function export_excel_all_taskpatrol_json($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");

		$b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->query("SELECT t_task_patrol_header.*, users.full_name FROM t_task_patrol_header left join users on users.id::varchar=t_task_patrol_header.taken_user 
        WHERE t_task_patrol_header.b2b_token = '$b2b'
        AND  t_task_patrol_header.publish_date::TEXT LIKE '%$year-$month%'")->result_array();

        $grouped_array = array();
		$group_header_id = array();
        foreach ($fetch as $element) {
            $grouped_array[$element['taken_user']." ".$element['publish_date']][] = $element;
			$group_header_id[] = $element['id_']; 
        }

		$data = array();
        foreach ($grouped_array as $key => $value) {
            $id_ = "";
            $b2b_token = "";
            $cluster_name = "";
            $full_name = "";
            $publish_date = "";
            $publish_time = "";
            $done_date = "";
            $done_time = "";
            $total_cp = "";
            $total_done = "";

            $row = $value[0];

            if (count($value) == 1) {
                $id_ = $value[0]['id_'];
                $b2b_token = $value[0]['b2b_token'];
                $cluster_name = $value[0]['cluster_name'];
                $full_name = $value[0]['full_name'];  
                $publish_date = $value[0]['publish_date'];
                $publish_time = $value[0]['publish_time'];
                $done_date = $value[0]['done_date'];
                $done_time = $value[0]['done_time'];
                $total_cp = $value[0]['total_cp'];
                $total_done = $value[0]['total_done'];
                //echo $value[0]['cluster_name'];
                $row['done_time'] = $done_time;
            }else if (count($value) > 1) {
                $sum_total_cp = count($value);

                $id_ = $value[0]['id_'];
                $b2b_token = $value[0]['b2b_token'];
                $cluster_name = $value[0]['cluster_name'];
                $full_name = $value[0]['full_name'];  
                $publish_date = $value[0]['publish_date'];
                $publish_time = $value[0]['publish_time'];
                $done_date = $value[count($value)-1]['done_date'];
                $done_time = $value[count($value)-1]['done_time'];
                $total_cp = $sum_total_cp;
                $total_done = $value[0]['total_done'];
                
                $row['done_time'] = $done_time;
            }

            $temp = array();
            $temp['id'] = $id_;
			$temp['cluster_name'] = $cluster_name;
            $temp['full_name'] = $full_name == '' ? 'Anonim' : $full_name;
            $temp['start_date'] = $publish_date . " " . $publish_time;
            $temp['end_date'] = $done_date . " " . $done_time;
            if (!empty($done_time)) {
                $start_date = new DateTime($publish_time);
                $end_date = new DateTime($done_time);
                $dd = date_diff($end_date, $start_date);
                $temp['duration'] = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
            } else {
                $temp['duration'] = 0;
            }
            $temp['total_cp'] = $total_cp;
			$temp['link_download_pdf'] = base_url()."generate_pdf/by_link/".$b2b_token."/".$id_."/".$done_time;
            
			$data[] = $temp;
        }

		$list_header_id = implode("', '", $group_header_id);

		$task_patrol_detail = $this->db->query("
			SELECT
				tpd.id_header,
				tpd.cp_id,
				CONCAT ( TO_CHAR( tpd.submit_date, 'dd Mon yyyy' ), ' ', tpd.submit_time ) AS created_date,
				tpd.cp_lat,
				tpd.cp_long,
				tpd.cp_name,
				tpd.cp_nfc,
				tpd.cp_qr,
				tpd.flag_process,
				tpd.remark AS note,
				tpi1.img_name AS image_1,
				tpi2.img_name AS image_2,
				tpi3.img_name AS image_3,

			CASE
					WHEN tpd.flag_process = 0 THEN
					'Disable' 
					WHEN tpd.flag_process = 1 THEN
					'Open' 
					WHEN tpd.flag_process = 2 THEN
					'Booked' 
					WHEN tpd.flag_process = 3 THEN
					'Done' 
				END AS flag_process_string 
			FROM
				t_task_patrol_detail tpd
				JOIN t_task_patrol_image tpi1 ON tpd.id_ = tpi1.id_header :: INTEGER 
				AND tpi1.img_name LIKE'%image_patrol_1%'
				JOIN t_task_patrol_image tpi2 ON tpd.id_ = tpi2.id_header :: INTEGER 
				AND tpi2.img_name LIKE'%image_patrol_2%'
				JOIN t_task_patrol_image tpi3 ON tpd.id_ = tpi3.id_header :: INTEGER 
				AND tpi3.img_name LIKE'%image_patrol_3%'
			WHERE
				tpd.b2b_token = '" . $b2b_token . "' 
				AND ( tpd.flag_process = '1' OR tpd.flag_process = '3' OR tpd.flag_process = '2' ) 
				AND tpd.id_header IN ('".$list_header_id."')
			")->result();

		$status_server = false;
		if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
			$status_server = true;
		}else{
			$status_server = false;
		}

		$newdata = array();
		foreach ($task_patrol_detail as $val) {		
			// foreach ($data as $value) {		
			// 	if ($val->header_id == $value->id) {
					$new_img1_base64 = "";
					$new_img1_label = "";
					if ($val->image_1) {
						$time = strtotime($val->created_date);
						$newformat = date('YmdHis',$time);

						$new_image_1 = "";
						if ($status_server) {
							$this->load->library('curl');
							$image_cudo = $this->config->item("base_url_server_cudo") . "assets/cp/" . $val->image_1;
							$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_1;
							$result = $this->curl->simple_get($image_cudo);
							
							$label = "img1_".$val->cp_id."_".$newformat.".jpg";
							
							if($result != ""){
								$new_image_1 = $image_cudo;
								$new_img1_label = $label;
							}elseif(@getimagesize($image)){
								$new_image_1 = $image;
								$new_img1_label = $label;
							}else{
								$new_image_1 = $no_image;
								$new_img1_label = "no image available";
							}
						} else {
							$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_1;

							$label = "img1_".$val->cp_id."_".$newformat.".jpg";

							if(@getimagesize($image)){
								$new_image_1 = $image;
								$new_img1_label = $label;
							}else{
								$new_image_1 = $no_image;
								$new_img1_label = "no image available";
							}
						}
						
						//$new_img1_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_1));
						$new_img1_base64 = $new_image_1;
					} else {
						$new_image_1 = $no_image;
						//$new_img1_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_1));
						$new_img1_base64 = $new_image_1;
						$new_img1_label = "no image available";
					}

					$new_img2_base64 = "";
					$new_img2_label = "";
					if ($val->image_2) {
						$time = strtotime($val->created_date);
						$newformat = date('YmdHis',$time);

						$new_image_2 = "";
						if ($status_server) {
							$this->load->library('curl');
							$image_cudo = $this->config->item("base_url_server_cudo") . "assets/cp/" . $val->image_2;
							$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_2;
							$result = $this->curl->simple_get($image_cudo);
							
							$label = "img2_".$val->cp_id."_".$newformat.".jpg";
							
							if($result != ""){
								$new_image_2 = $image_cudo;
								$new_img2_label = $label;
							}elseif(@getimagesize($image)){
								$new_image_2 = $image;
								$new_img2_label = $label;
							}else{
								$new_image_2 = $no_image;
								$new_img2_label = "no image available";
							}
						} else {
							$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_2;

							$label = "img2_".$val->cp_id."_".$newformat.".jpg";

							if(@getimagesize($image)){
								$new_image_2 = $image;
								$new_img2_label = $label;
							}else{
								$new_image_2 = $no_image;
								$new_img2_label = "no image available";
							}
						}
						
						//$new_img2_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_2));
						$new_img2_base64 = $new_image_2;
					} else {
						$new_image_2 = $no_image;
						//$new_img2_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_2));
						$new_img2_base64 = $new_image_2;
						$new_img2_label = "no image available";
					}

					$new_img3_base64 = "";
					$new_img3_label = "";
					if ($val->image_3) {
						$time = strtotime($val->created_date);
						$newformat = date('YmdHis',$time);

						$new_image_3 = "";
						if ($status_server) {
							$this->load->library('curl');
							$image_cudo = $this->config->item("base_url_server_cudo") . "assets/cp/" . $val->image_3;
							$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_3;
							$result = $this->curl->simple_get($image_cudo);
							
							$label = "img3_".$val->cp_id."_".$newformat.".jpg";
							
							if($result != ""){
								$new_image_3 = $image_cudo;
								$new_img3_label = $label;
							}elseif(@getimagesize($image)){
								$new_image_3 = $image;
								$new_img3_label = $label;
							}else{
								$new_image_3 = $no_image;
								$new_img3_label = "no image available";
							}
						} else {
							$image = $this->config->item('base_url_api') . "assets/images/cp/" . $val->image_3;

							$label = "img3_".$val->cp_id."_".$newformat.".jpg";

							if(@getimagesize($image)){
								$new_image_3 = $image;
								$new_img3_label = $label;
							}else{
								$new_image_3 = $no_image;
								$new_img3_label = "no image available";
							}
						}
						
						//$new_img3_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_3));
						$new_img3_base64 = $new_image_3;
					} else {
						$new_image_3 = $no_image;
						//$new_img3_base64 = 'data:image/png;base64,' . base64_encode(fetch($new_image_3));
						$new_img3_base64 = $new_image_3;
						$new_img3_label = "no image available";
					}

					$temp = array();
					$temp['id'] = $val->id_header;
					// $temp['cluster_name'] = $value->cluster_name;
					// $temp['full_name'] = $value->full_name;
					// $temp['start_date'] = $value->start_date;
					// $temp['end_date'] = $value->end_date;
					// $temp['duration'] = $value->duration;
					// $temp['total_cp'] = $value->total_cp;
					$temp['label_img1'] 	= $new_img1_label;
					$temp['base64_img1'] 	= $new_img1_base64;
					$temp['label_img2'] 	= $new_img2_label;
					$temp['base64_img2'] 	= $new_img2_base64;
					$temp['label_img3'] 	= $new_img3_label;
					$temp['base64_img3'] 	= $new_img3_base64;

					$newdata[] = $temp;
			// 	}
			// }
		}

		//$data = $this->M_task_patrol->fetch($month, $year);
		//$merged_array = array_merge($data, $newdata);

		$new_data = array(
			'list_all_task' 	=> $data,
			// 'list_attachment' 	=> $newdata,
		);

		if ($new_data) {
			echo json_encode([
				"status" => true,
				"data" => $new_data
			]);
		} else {
			echo json_encode([
				"status" => false,
				"data" => $new_data
			]);
		}
	}

	public function test_map()
	{
		$websiteURL = $this->config->item('base_url_api') . "cli_trigger/maps2/22691";
        $opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
        //Basically adding headers to the request
        $context = stream_context_create($opts);
        $api_response = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$websiteURL&screenshot=true", false, $context);
		//var_dump($api_response);
		$result = json_decode($api_response, true);
        // //screenshot data
        // $screenshot = $result['screenshot']['data'];
		echo $result['lighthouseResult']['audits']['full-page-screenshot']['details']['screenshot']['data'];
	}
}
