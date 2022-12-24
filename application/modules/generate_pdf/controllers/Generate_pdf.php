<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Generate_pdf extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
	}

    public function by_link($b2b_token,$id_header,$done_time)
	{
		$data["b2b_token"] = $b2b_token;
		$data["id_header"] = $id_header;
		$data["done_time"] = $done_time;

		$this->load->view('generate_pdf_by_link', $data);
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
}