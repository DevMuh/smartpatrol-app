<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apis extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	function CallAPI($method, $url, $data = false)
	{
		$curl = curl_init();


		switch ($method) {
			case "POST":
				curl_setopt($curl, CURLOPT_POST, true);

				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		// Optional Authentication:
		// curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// curl_setopt($curl, CURLOPT_USERPWD, "username:password");


		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);

		curl_close($curl);

		return $result;
	}

	public function get_route($schedule_type = 1)
	{
		date_default_timezone_set("Asia/Jakarta");
		$schedule_type_string = $this->handle_schedule_type_int_to_string($schedule_type);
		$hari_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
		$current_date_time = new DateTime();
		$current_time = $current_date_time->format("H:i");
		$hari_ini = $hari_hari[date("N") - 1];
		$AND = "";
		if ($schedule_type == 1) {
			$AND .= " AND LOWER(cr.otherdata->>'day') LIKE LOWER('%$hari_ini%') ";
			$AND .= " AND cr.otherdata->>'jam_mulai' = '$current_time' ";
			$AND .= " AND cr.otherdata->>'schedule_type' != 'monthly' AND cr.otherdata->>'schedule_type' != 'annualy' ";
		} else {
			$AND .= " AND cr.otherdata->>'schedule_type' = '$schedule_type_string' ";
		}

		return $this->db->query("SELECT 
			cr.b2b_token
			,current_date AS publish_date
			,to_char(CURRENT_TIMESTAMP, 'HH24:MI:SS') AS publish_time
			,cr.id_route
			,cr.cluster_name
			,count(cp.id) AS total_cp
			,cr.finish_time 
			,cr.otherdata->>'assign_to' AS assign_to 
			,cr.otherdata->>'group_id' AS regu_id 
			,cr.otherdata->>'day' AS day
			,cr.otherdata->>'schedule_type' AS schedule_type
			,cr.otherdata->>'jam_mulai' AS jam_mulai
			FROM cluster_route cr 
			LEFT JOIN check_point cp
			ON cp.b2b_token::TEXT = cr.b2b_token::TEXT 
			AND cp.cluster_route::TEXT = cr.id_route::TEXT
			WHERE  
			cr.flag_active = 1 
			AND cp.flag_disable = 1
			AND cr.otherdata->>'schedule_type' IS NOT NULL
			AND cr.otherdata->>'group_id' IS NOT NULL
			AND cr.otherdata->>'assign_to' IS NOT NULL
			AND cr.otherdata->>'jam_mulai' IS NOT NULL
			" . $AND . "
			GROUP BY cr.id_route
			")->result();
	}

	public function handle_schedule_type_int_to_string($param = 1)
	{
		switch (intval($param)) {
			case 1:
				$type = "daily";
				break;
			case 2:
				$type = "monthly";
				break;
			case 3:
				$type = "annualy";
				break;
			default:
				$type = "daily";
				break;
		}
		return $type;
	}

	public function send_patrol($type = 1)
	{
		$get = $this->get_route($type);
		$data_insert = [];
		$data_insert2 = [];
		foreach ($get as $key) {
			if (json_decode($key->assign_to))
				foreach (json_decode($key->assign_to) as $assign_to) {
					$data = [
						"assign_to" => $assign_to,
						"b2b_token" => $key->b2b_token,
						"publish_date" => $key->publish_date,
						"publish_time" => $key->publish_time,
						"id_route" => $key->id_route,
						"cluster_name" => $key->cluster_name,
						"total_cp" => $key->total_cp,
						"finish_time" => $key->finish_time,
					];
					array_push($data_insert, $data);
					$data["regu_id"] = $key->regu_id;
					array_push($data_insert2, $data);
				}
		}

		$insert_batch = $this->db->insert_batch("t_task_patrol_header", $data_insert);
		// $insert_batch = true;
		if ($insert_batch) {
			// foreach ($data_insert2 as $key) {
			// 	$key->to = $key->assign_to;
			// 	$key->type_notif = "patrol";
			// 	$data = new stdClass();
			// 	$data->topic = $key->regu_id;
			// 	$data->notification = [
			// 		"body" => "",
			// 		"Ada Task Patroli Baru!"
			// 	];
			// 	$data->data = ["data" => json_encode($key)];
			// 	echo $this->CallAPI("POST", $this->config->item('base_url_socket') . "fcm/send", json_encode($data));
			// }
			echo json_encode(["status" => true, "message" => "Success Send", "data" => $data_insert]);
		} else {
			echo json_encode(["status" => false, "message" => "Failed Send", "data" => []]);
		}
	}
}
