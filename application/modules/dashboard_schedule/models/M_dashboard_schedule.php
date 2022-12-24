<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dashboard_schedule extends CI_Model
{
    public function get_absen_v2($day=null,$month,$year, $extra_query = "")
    {
        $b2b = $this->session->userdata('b2b_token');
        $b2b_tokens = [];
        $b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
        foreach ($b2b_with_child as $key) {
            $b2b_tokens[] = $key->b2b_token;
        }
        $b2b_tokens = "'" . implode("','", $b2b_tokens) . "'";

        $where = $extra_query;
        if($day)  $where .= " and extract(day from A.submit_time::TIMESTAMP) = '$day' ";

        $distict_date = "";
        if ($day) {
            $distict_date = "A.date,";
        }

        $keyword = $this->input->get('search[value]');
        if ($keyword) {
          $where .= " AND ( 	
                    users.full_name ILIKE '%" . $keyword . "%' OR
                    concat(A.date,' ',A.time) ILIKE '%" . $keyword . "%' OR
                    b.title_nm ILIKE '%" . $keyword . "%' OR
                    qr.name ILIKE '%" . $keyword . "%' 
                  ) ";
        }

        $limit = " ";
        $length = $this->input->get('length');
        if($length){
            $limit = " limit $length";
        }
        $start = $this->input->get('start');
        $offset ="";
        if($start){
            $offset = " offset $start";
        }

        $columns = "A.date, A.user_id,
        users.payroll_id,
        SPLIT_PART(STRING_AGG(users.user_roles,','), ',', 1) as user_roles,
        SPLIT_PART(STRING_AGG(users.other_data->>'position',','), ',', 1) as position,
        SPLIT_PART(STRING_AGG(users.full_name,','), ',', 1) as full_name,
        SPLIT_PART(STRING_AGG(t_shift.shift_name::TEXT,','), ',', 1) as shift_name,
        SPLIT_PART(STRING_AGG(t_shift.waktu_start::TEXT,','), ',', 1) as start_shift,
        SPLIT_PART(STRING_AGG(t_shift.waktu_end::TEXT,','), ',', 1) as end_shift,
        SPLIT_PART(STRING_AGG(t_shift.max_days::TEXT,','), ',', 1) as max_days,
        SPLIT_PART(STRING_AGG(t_shift.in_early::TEXT,','), ',', 1) as in_early,
        SPLIT_PART(STRING_AGG(t_shift.in_late::TEXT,','), ',', 1) as in_late,
        SPLIT_PART(STRING_AGG(t_shift.out_late::TEXT,','), ',', 1) as out_late,
        SPLIT_PART(STRING_AGG(qr.name::TEXT,','), ',', 1) as name,
        SPLIT_PART(STRING_AGG(b.title_nm::TEXT,','), ',', 1) as org_name,
        SPLIT_PART(
            STRING_AGG(CASE WHEN A.status = 1 THEN via.title_nm END::text,',' ::text ORDER BY (CASE WHEN A.status = 1 THEN A.time END)::text ASC) 
        ,',',1) 
        AS via_in,
        
        SPLIT_PART(
            STRING_AGG(CASE WHEN A.status = 1 THEN qr.name END::text,',' ::text ORDER BY (CASE WHEN A.status = 1 THEN A.time END)::text ASC) 
        ,',',1) 
        AS qr_in,

        SPLIT_PART(
            STRING_AGG(CASE WHEN A.status = 1 THEN concat(A.date,' ',A.time) END::text,',' ::text ORDER BY (CASE WHEN A.status = 1 THEN A.time END)::text ASC) 
        ,',',1) 
        AS waktu_masuk,

        SPLIT_PART(
            STRING_AGG(CASE WHEN A.status = 1 THEN A.img_name END::text,',' ::text ORDER BY (CASE WHEN A.status = 1 THEN A.img_name END)::text ASC) 
        ,',',1) 
        AS photo_in,

        --    (SELECT 
        --        MAX(concat(a2.date,' ',a2.time)) 
        --        From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
        --        WHERE a2.user_id = A.user_id AND a2.status=2
        --        AND a2.id_shift = SPLIT_PART(STRING_AGG(t_shift.id_::TEXT,','), ',', 1)
        --        AND a2.submit_time::timestamp > A.submit_time::timestamp
        --        AND convert_string_to_date_smartpatrol(a2.date)=convert_string_to_date_smartpatrol(A.date) +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
        --        LIMIT 1
        --    ) AS waktu_pulang,

        (CASE WHEN (SELECT 
			MAX(concat(a2.date,' ',a2.time)) 
			From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
			WHERE a2.user_id = A.user_id AND a2.status=2
			AND convert_string_to_date_smartpatrol(a2.date)=convert_string_to_date_smartpatrol(A.date) +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
			AND a2.id_shift::TEXT = A.id_shift::TEXT
			LIMIT 1
		) is NULL THEN (
			SELECT 
			MAX(concat(a2.date,' ',a2.time)) 
			From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
			WHERE a2.user_id = A.user_id AND a2.status=2
			AND convert_string_to_date_smartpatrol(a2.date)=convert_string_to_date_smartpatrol(A.date) + 0
			AND a2.id_shift::TEXT = A.id_shift::TEXT
			LIMIT 1
		) ELSE (
			SELECT 
			MAX(concat(a2.date,' ',a2.time)) 
			From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
			WHERE a2.user_id = A.user_id AND a2.status=2
			AND convert_string_to_date_smartpatrol(a2.date)=convert_string_to_date_smartpatrol(A.date) +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
			AND a2.id_shift::TEXT = A.id_shift::TEXT
			LIMIT 1
		) END

		) AS waktu_pulang,
        
--        concat(to_char((SELECT 
--            convert_string_to_date_smartpatrol(A.date) 
--            + (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval 
--        )::date), ' ', SPLIT_PART(STRING_AGG(t_shift.waktu_end::TEXT,','), ',', 1)) 
--        AS waktu_pulang2,

        (SELECT 
            a2.is_overtime
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            WHERE a2.user_id = A.user_id AND a2.status=2 AND a2.is_overtime = 't'
            AND convert_string_to_date_smartpatrol(a2.date)=convert_string_to_date_smartpatrol(A.date) +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
            ORDER BY a2.id DESC
            LIMIT 1
        ) AS is_overtime,

        (SELECT 
            a2.overtime_reason
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            WHERE a2.user_id = A.user_id AND a2.status=2
            AND convert_string_to_date_smartpatrol(a2.date)=convert_string_to_date_smartpatrol(A.date) +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
            ORDER BY a2.id DESC
            LIMIT 1
        ) AS overtime_reason,

        (SELECT 
            a2.img_name
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            WHERE a2.user_id = A.user_id AND a2.status=2
            AND convert_string_to_date_smartpatrol(a2.date)=convert_string_to_date_smartpatrol(A.date) +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
            LIMIT 1
        ) AS photo_out,

        (SELECT 
            via.title_nm
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            LEFT JOIN m_register_b2b as via ON via.b2b_token = a2.via
            WHERE a2.user_id = A.user_id AND a2.status=2
            AND convert_string_to_date_smartpatrol(a2.date)=convert_string_to_date_smartpatrol(A.date) +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
            LIMIT 1
        ) AS via_out,

        (SELECT 
            qr.name
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            LEFT JOIN qr ON qr.qr_id = a2.qr_id
            WHERE a2.user_id = A.user_id AND a2.status=2
            AND convert_string_to_date_smartpatrol(a2.date)=convert_string_to_date_smartpatrol(A.date) +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
            LIMIT 1
        ) AS qr_out,
        
        A.other_data->>'status_absen_schedule' as status_absen_schedule,
		A.other_data->>'status_approval' AS status_approval,
		A.other_data->>'status_off_panggil' AS status_off_panggil
        ";

        $from = "
            FROM
            log_absensi A
            INNER JOIN users ON A.user_id = users.id
            LEFT JOIN t_shift ON A.id_shift::TEXT = t_shift.id_::TEXT
            LEFT JOIN qr ON qr.qr_id = A.qr_id
            LEFT JOIN m_register_b2b as via ON via.b2b_token = A.via
            LEFT JOIN m_register_b2b as b ON b.b2b_token = A.b2b_token
            WHERE
            A.b2b_token IN ($b2b_tokens)
            AND A.status = '1'
            and extract(year from A.submit_time::TIMESTAMP) = '$year'
            and extract(month from A.submit_time::TIMESTAMP) = '$month'
            AND (A.other_data->>'status_absen_schedule'='true' OR A.other_data->>'status_absen_schedule' IS NULL)
            AND ((A.other_data->>'status_off_panggil'='false' OR A.other_data->>'status_off_panggil' IS NULL) OR (A.other_data->>'status_off_panggil'='true' AND A.other_data->>'status_approval'='true'))
            $where
            GROUP BY A.date, 
            A.submit_time, 
            users.payroll_id, 
            A.user_id,
            A.id_shift,
            (CASE WHEN t_shift.max_days is null OR t_shift.max_days::TEXT = ''  THEN 0 ELSE t_shift.max_days END),
            A.other_data->>'status_absen_schedule',
            A.other_data->>'status_approval',
            A.other_data->>'status_off_panggil'
         ORDER BY ".$distict_date." A.user_id
        ";

        $qstring = "SELECT DISTINCT ON (".$distict_date." A.user_id, A.id_shift) $columns $from $limit $offset";
        // echo $qstring; die();
        $starttime = microtime(true); // Top of page
        // log_message('error',$qstring);
        $query = $this->db->query($qstring)->result();
        // echo $qstring; die();
        // echo "<pre>"; 
        // var_dump($query);
        // die;
        log_message('error', "======================== rekap absen");
        log_message('error', $this->db->last_query());
        
        $loadtime = "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds";
        log_message('error', "======================== loadtime controller : M_Absensi method : get_absen_v2 call : db_query");
        log_message('error', $loadtime);
        $starttime = microtime(true); // Top of page
        
        $full_count = $this->db->query("SELECT DISTINCT ON (A.date, A.user_id) A.date  $from")->num_rows();


        $status_server = false;
        if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
            $status_server = true;
        }else{
            $status_server = false;
        }
        
        $no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");
        $absen_image_path = $this->config->item("base_url_api") . "assets/images/absen/";

        $all_absence = [];
        $now = date("d-M-Y H:i:s");
        foreach ($query as $row) {
            $in = explode(" ", $row->waktu_masuk);
            $out = explode(" ", $row->waktu_pulang);

            $in_date = $in ?  $in[0] : "";
            $out_date =  $out ? $out[0] : "";

            $in_time = $in ?  $in[1] : "";
            $out_time =  $out ? $out[1] : "";

            $row->work_time = "-";
            $row->start_overtime = "-";
            $row->end_overtime = "-";
            $row->late_in_time = "-";
            $row->late_out_time = "-";
            //waktu_pulang2
            if ($row->start_shift && $row->end_shift) {
                $check_in_time = new DateTime($in_time);
                $check_out_time = new DateTime($out_time);

                $start_shift = new DateTime($row->start_shift);
                $end_shift = new DateTime($row->end_shift);

                $row->max_days = $row->max_days ?: 0;
                if(strtotime($row->start_shift) >= strtotime($row->end_shift)){
                    $end_shift->modify("+$row->max_days day");
                }
                $dd_shift = $start_shift->diff($end_shift);
                $row->durasi_shift = $dd_shift->h + ($dd_shift->d*24); 

                if (!$row->in_early) $row->in_early = 0;
                if (!$row->in_late) $row->in_late = 0;
                if (!$row->out_late) $row->out_late = 0;

                // LATE IN TIME
                $start_shift_late =  clone $start_shift;
                $start_shift_late->modify("+$row->in_late minutes");
                if ($check_in_time > $start_shift_late) {
                    $late_in = $start_shift->diff($check_in_time);
                    $row->late_in_time = str_pad($late_in->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($late_in->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($late_in->s, 2, '0', STR_PAD_LEFT);  
                }

                $start_shift_early = clone $start_shift;
                $start_shift_early->modify("-$row->in_early minutes");
                // OVERTIME AWAL
                if ($check_in_time < $start_shift_early && $check_in_time > $start_shift) {
                    $d = $check_in_time->diff($start_shift);
                    $row->start_overtime = str_pad($d->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($d->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($d->s, 2, '0', STR_PAD_LEFT);  
                }

                $date_now = date_create($now);
                $date_pulang2 = date_create($row->waktu_pulang2);  
                if ($out_time != null) {
                    // WORK TIME (total)
                    $diff_total = date_diff(date_create($row->waktu_masuk),date_create($row->waktu_pulang));
                    $row->work_time = str_pad($diff_total->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff_total->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($diff_total->s, 2, '0', STR_PAD_LEFT);  

                    // OVERTIME AKHIR
                    if ($check_in_time <= $end_shift && $check_out_time > $end_shift) {
                        $d = $end_shift->diff($check_out_time);
                        $row->end_overtime = str_pad($d->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($d->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($d->s, 2, '0', STR_PAD_LEFT);  
                    }
                    $end_shift_early = clone $end_shift;
                    $end_shift_early->modify("-$row->out_late minutes");
                    // LATE OUT TIME
                    if (strtotime($check_out_time->date) < strtotime($end_shift_early->date)) {
                        $late_out = $check_out_time->diff($end_shift);
                        $row->late_out_time = str_pad($late_out->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($late_out->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($late_out->s, 2, '0', STR_PAD_LEFT);  
                    }
                } else if ($date_now >= $date_pulang2) { 
                    // WORK TIME (total)
                    $row->waktu_pulang = $row->waktu_pulang2;
                    $diff_total = date_diff(date_create($row->waktu_masuk),date_create($row->waktu_pulang2));
                    $row->work_time = str_pad($diff_total->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff_total->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($diff_total->s, 2, '0', STR_PAD_LEFT);  

                    // OVERTIME AKHIR
                    if ($check_in_time <= $end_shift && $check_out_time > $end_shift) {
                        $d = $end_shift->diff($check_out_time);
                        $row->end_overtime = str_pad($d->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($d->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($d->s, 2, '0', STR_PAD_LEFT);  
                    }
                    $end_shift_early = clone $end_shift;
                    $end_shift_early->modify("-$row->out_late minutes");
                    // LATE OUT TIME
                    if (strtotime($check_out_time->date) < strtotime($end_shift_early->date)) {
                        $late_out = $check_out_time->diff($end_shift);
                        $row->late_out_time = str_pad($late_out->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($late_out->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($late_out->s, 2, '0', STR_PAD_LEFT);  
                    }
                }
            }
            // if ($row->waktu_pulang == "") {
            //     $pulang =strtotime($row->date." ". $row->end_shift.":00");
            //     $masuk = strtotime($row->waktu_masuk);
            //     $waktupulang = date('Y-m-d H:i:s',$pulang);
            //     // $row->$waktupulang = "-";
            //     $row->waktu_masuk = date('Y-m-d H:i:s',$masuk);
            //     $datetime1 = new DateTime($row->waktu_masuk);
            //     $datetime2 = new DateTime($waktupulang);
            //     $row->waktu_pulang ="-";
            //     $diff = $datetime1->diff($datetime2);
            //     //$row->work_time = $diff->format("%h:%i:%s");
            //     $row->work_time = str_pad($diff->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($diff->s, 2, '0', STR_PAD_LEFT);
            // }
            // if ($row->photo_in) {
            //     $new_photo_in = "";
            //     if ($status_server) {
            //         $this->load->library('curl');
            //         $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen/" . $row->photo_in;
            //         $image = $absen_image_path . $row->photo_in;
            //         $result = $this->curl->simple_get($image_cudo);
                    
            //         if($result != ""){
            //             $new_photo_in = $image_cudo;
            //         }elseif(@getimagesize($image)){
            //             $new_photo_in = $image;
            //         }else{
            //             $new_photo_in = $no_image;
            //         }
            //     } else {
            //         $image = $absen_image_path . $row->photo_in;
            //         if(@getimagesize($image)){
            //             $new_photo_in = $image;
            //         }else{
            //             $new_photo_in = $no_image;
            //         }
            //     }

            //     //$row->photo_in = $absen_image_path . $row->photo_in;
            //     $row->photo_in = $new_photo_in;
            // } else {
            //     $row->photo_in = $no_image;
            // }
            // if ($row->photo_out) {
            //     $new_photo_out = "";
            //     if ($status_server) {
            //         $this->load->library('curl');
            //         $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen/" . $row->photo_out;
            //         $image = $absen_image_path . $row->photo_out;
            //         $result = $this->curl->simple_get($image_cudo);
                    
            //         if($result != ""){
            //             $new_photo_out = $image_cudo;
            //         }elseif(@getimagesize($image)){
            //             $new_photo_out = $image;
            //         }else{
            //             $new_photo_out = $no_image;
            //         }
            //     } else {
            //         $image = $absen_image_path . $row->photo_out;
            //         if(@getimagesize($image)){
            //             $new_photo_out = $image;
            //         }else{
            //             $new_photo_out = $no_image;
            //         }
            //     }

            //     //$row->photo_out = $absen_image_path . $row->photo_out;
            //     $row->photo_out = $new_photo_out;
            // } else {
            //     $row->photo_out = $no_image;
            // }
            $row->check_out_time = $out_time;
            $row->check_in_time = $in_time;
            $row->in_date = $in_date;
            $row->out_date = $out_date;
            

            array_push($all_absence, $row);
        }
        // var_dump($row);die;
        $payload['data'] =  $all_absence;
        $payload['recordsTotal'] = $payload['recordsFiltered'] =  $full_count;
        $loadtime = "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds";
        log_message('error', "======================== loadtime controller : M_Absensi method : get_absen_v2 call : loop");
        log_message('error', $loadtime);
        return $payload;
    }

    public function fetch_patroli($month, $year, $day=null)
    {
        $b2b = $this->session->userdata('b2b_token');
        $b2b_tokens = [];
        $b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
        foreach ($b2b_with_child as $key) {
            $b2b_tokens[] = $key->b2b_token;
        }
        $b2b_tokens = "'" . implode("','", $b2b_tokens) . "'";

        $fetch = $this->db->query("SELECT t_task_patrol_header.*, users.full_name FROM t_task_patrol_header left join users on users.id::varchar=t_task_patrol_header.taken_user 
        WHERE 
        -- t_task_patrol_header.b2b_token = '$b2b'
        t_task_patrol_header.b2b_token IN ($b2b_tokens)
        AND  t_task_patrol_header.publish_date::TEXT LIKE '%$year-$month-$day%'")->result_array();
        // echo $this->db->last_query(); die();
        $querytrack = $this->db->query("SELECT created_time FROM track_route WHERE b2b_token='$b2b' AND created_time::TEXT LIKE '%$year-$month-$day%'")->result_array();
        // var_dump($querytrack);die;
        $grouped_array = array();
        foreach ($fetch as $element) {
            $grouped_array[$element['taken_user']." ".$element['publish_date']][] = $element;
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
                // track route 
                $arr = [];
                foreach ($querytrack as $key => $valuetrack) {
                    $stringdate = explode(" ",$valuetrack["created_time"]);
                    // $stringtime = explode(" ",$valuetrack->created_time[0]);
                    // $stringdatedone = explode(" ",$valuetrack["created_time"]);
                    // $stringtimedone = explode(" ",$valuetrack->created_time[$counttrack]);
                    if ($value[0]['publish_date'] ==  $stringdate[0] ) {
                        array_push($arr,$stringdate);
                        // var_dump($stringdate);die;
                    }
                }
                $counttrack = count($arr)-1;
                $publish_date = $arr[0][0];
                $publish_time = $arr[0][1];
                $done_date = $arr[$counttrack][0];
                $done_time = $arr[$counttrack][1];
                // var_dump($arr);die;



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
            $url = base_url('task_patrol/checkpoint/' . $b2b_token . '/' . $id_);
            $temp[] = $cluster_name;
            $temp[] = $full_name == '' ? 'Anonim' : $full_name;
            $temp[] = $publish_date . " " . $publish_time;
            $temp[] = $done_date . " " . $done_time;
            if (!empty($done_time)) {
                $start_date = new DateTime($publish_time);
                $end_date = new DateTime($done_time);
                $dd = date_diff($end_date, $start_date);
                $temp[] = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
            } else {
                $temp[] = 0;
            }
            $temp[] = $total_cp;
            //$temp[] = $total_done;
            $temp[] = "<a title='Detail' class='btn mybt detail' target='_blank' href='$url'><span class=' badge badge-info'><i class='fa fa-map-marked'></i></span></a>
                        <a href='#' class='btn mybt detail' onclick='exportTaskPatrolPdf(this); return false;' data-id='".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8')."' title='Export Task Patrol PDF' ><span class=' badge badge-info'><i class='fa fa-file-pdf'></i></span></a>
                        <a href='#' class='btn mybt detail' onclick='exportTaskPatrolExcel(this); return false;' data-id='".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8')."' title='Export Task Patrol Excel' ><span class=' badge badge-info'><i class='fa fa-file-excel'></i></span></a>";
            $data[] = $temp;
        }
        
        // foreach ($fetch as $row) {
        //     $temp = array();
        //     $url = base_url('task_patrol/checkpoint/' . $row->b2b_token . '/' . $row->id_);
        //     $temp[] = $row->cluster_name;
        //     $temp[] = $row->full_name == '' ? 'Anonim' : $row->full_name;
        //     $temp[] = $row->publish_date . " " . $row->publish_time;
        //     $temp[] = $row->done_date . " " . $row->done_time;
        //     if (!empty($row->done_time)) {
        //         $start_date = new DateTime($row->publish_time);
        //         $end_date = new DateTime($row->done_time);
        //         $dd = date_diff($end_date, $start_date);
        //         $temp[] = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
        //     } else {
        //         $temp[] = 0;
        //     }
        //     $temp[] = $row->total_cp;
        //     $temp[] = $row->total_done;
        //     $temp[] = "<a title='Detail' class='btn mybt detail' target='_blank' href='$url'><span class=' badge badge-info'><i class='fa fa-map-marked'></i></span></a>
        //                 <a href='#' class='btn mybt detail' onclick='exportTaskPatrolPdf(this); return false;' data-id='".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8')."' title='Export Task Patrol PDF' ><span class=' badge badge-info'><i class='fa fa-file-pdf'></i></span></a>";
        //     $data[] = $temp;
        // }
        
        // echo json_encode($grouped_array);die;
        
        return array('data' => $data);
    }




    public function get_data_dashboard($y,$m,$d)
    {
        $b2b = $this->session->userdata('b2b_token');
        $tgl =$y."-".$m."-".$d;
        $donedate= "done_date = '$tgl'";
        $submitdat= "submit_date = '$tgl'";
        $datetasktamu= "start_date = '$tgl'";
        if ($d == "") {
            $donedate= "
            extract(year from done_date::date) = '$y'
            and extract(month from done_date::date) = '$m'
            ";
            $submitdat= "
            extract(year from submit_date::date) = '$y'
            and extract(month from submit_date::date) = '$m'";
            $datetasktamu= "
            extract(year from start_date::date) = '$y'
            and extract(month from start_date::date) = '$m'";
            // $tgl = $y."-".$m;
        }
        $partol_count = $this->db->query("
        SELECT
	    aa.total AS active,
	    bb.total AS complate 
        FROM
	    ( SELECT COUNT ( * ) AS total FROM t_task_patrol_header WHERE flag_process = '1' and b2b_token='$b2b') aa,
	    ( SELECT COUNT ( * ) AS total FROM t_task_patrol_header WHERE flag_process = '3' and b2b_token='$b2b' and $donedate) bb
        ")->row();        
        // var_dump($d+"asd");die;
        $alert = $this->db->query("SELECT count(*) as alert FROM t_task_patrol_detail where condition ='2' AND b2b_token = '$b2b' and $submitdat")->row();
        $critical = $this->db->query("SELECT count(*) as critical FROM t_task_kejadian WHERE b2b_token = '$b2b'")->row();
        $guest = $this->db->query("SELECT count(*) as guest FROM t_task_tamu WHERE b2b_token = '$b2b' and $datetasktamu ")->row();
        $count_task = $this->db->query("SELECT count(*) as count_task FROM t_task_patrol_header where t_task_patrol_header.flag_process !=3 AND t_task_patrol_header.b2b_token = '$b2b'")->row();
        $occupant = $this->db->query("SELECT count(*) as occupant FROM m_client where flag_disable='1' AND b2b_token = '$b2b'")->row();
        $security = $this->db->query("SELECT count(*) as securiti from users where user_roles = 'anggota' AND b2b_token = '$b2b'")->row();
        $checkpoint = $this->db->query("SELECT * from check_point where flag_disable='1' AND b2b_token = '$b2b'")->result();
        $incident = $this->db->query("SELECT count(*) as total from t_task_kejadian where b2b_token='$b2b' and $submitdat")->row();
        // var_dump("masul");die;
        
        
        $datetrunct = "
        extract(year from submit_date::date) = '$y'
        and extract(month from submit_date::date) = '$m'";
        if ($d != "") {
            $datetrunct = "submit_date = '".$y."-".$m."-".$d."'";
            
        }
// var_dump($datetrunct);die;

        $donut = $this->db->query("SELECT 
        SUM (
        CASE
        WHEN condition = '1' THEN
            1
        ELSE
            0
        END
        ) AS secured,
        SUM (
        CASE
        WHEN condition = '2' THEN
            1
        ELSE
            0
        END
        ) AS alert,
        SUM (
        CASE
        WHEN condition = '3' THEN
            1
        ELSE
            0
        END
        ) AS critical,
        SUM (
        CASE
        WHEN condition IS NOT NULL THEN
            1
        ELSE
            0
        END
        ) AS total
    
        FROM
        t_task_patrol_detail
        WHERE submit_date is not null AND b2b_token = '$b2b' 
        AND $datetrunct")->result_array();
    //    AND submit_date >= $datetrunct")->result_array();

    // var_dump($donut);die;
   

        $bar = $this->db->query("SELECT 
        SUM (
        CASE
        WHEN kategori = '1' THEN
            1
        ELSE
            0
        END
        ) AS kecelakaan,
        SUM (
        CASE
        WHEN kategori = '2' THEN
            1
        ELSE
            0
        END
        ) AS kebakaran,
        SUM (
        CASE
        WHEN kategori = '3' THEN
            1
        ELSE
            0
        END
        ) AS pencurian,
        SUM (
        CASE
        WHEN kategori = '4' THEN
            1
        ELSE
            0
        END
        ) AS kematian

        FROM
        t_task_kejadian
        WHERE submit_date is not null AND b2b_token = '$b2b' 
        AND $datetrunct")->result_array();
        // -- AND submit_date >= $datetrunct")->result_array();

        $dashboard = array();
        $dashboard['tamu'] = $guest->guest;
        $dashboard['alert'] = $alert->alert;
        $dashboard['active'] = $partol_count->active;
        $dashboard['critical'] = $critical->critical;
        $dashboard['security'] = $security->securiti;
        $dashboard['complate'] = $partol_count->complate;
        $dashboard['occupant'] = $occupant->occupant;
        $dashboard['count_task'] = $count_task->count_task;
        $dashboard['tcheckpoint'] = count($checkpoint);
        $dashboard['incident'] = $incident->total;
            // var_dump("SELECT to_char(submit_date, 'd') as mon, sum((condition = '1')::int) as secured, 
            // sum((condition = '2')::int) as warning, sum((condition = '3')::int) as critical from t_task_patrol_detail WHERE 
            // flag_process='3' and submit_date is not null  and
            // $datetrunct
            // AND b2b_token = '$b2b' group by to_char(submit_date, 'mm'), 
            // mon order by to_char(submit_date, 'mm');");die;
            // var_dump($datetrunct);die;
        $query = $this->db->query("SELECT to_char(submit_date, 'd') as mon, sum((condition = '1')::int) as secured, 
        sum((condition = '2')::int) as warning, sum((condition = '3')::int) as critical from t_task_patrol_detail WHERE 
        flag_process='3' and submit_date is not null  and
        $datetrunct
        AND b2b_token = '$b2b' group by to_char(submit_date, 'mm'), 
        mon order by to_char(submit_date, 'mm');")->result();
        $highbar = array();
        foreach ($query as $row) {
            $highbar['mon'][] = str_replace(' ', '', ucfirst($row->mon));
            $highbar['warning'][] = intval($row->warning);
            $highbar['secured'][] = intval($row->secured);
            $highbar['critical'][] = intval($row->critical);
        }


        $query = $this->db->query("SELECT to_char(submit_date, 'month') as mon,
        sum((kategori = '1')::int) as kecelakaan,
        sum((kategori = '2')::int) as kebakaran,
        sum((kategori = '3')::int) as pencurian,
        sum((kategori = '4')::int) as kematian
        from t_task_kejadian
        WHERE submit_date is not null AND $datetrunct AND b2b_token = '$b2b' 
        group by to_char(submit_date, 'mm'), mon
        order by to_char(submit_date, 'mm');")->result();

        $highline = array();
        foreach ($query as $row) {
            $highline['mon'][] = str_replace(' ', '', ucfirst($row->mon));
            $highline['kematian'][] = intval($row->kematian);
            $highline['pencurian'][] = intval($row->pencurian);
            $highline['kebakaran'][] = intval($row->kebakaran);
            $highline['kecelakaan'][] = intval($row->kecelakaan);
        }

        $this->load->library('session');


        // get per month this year
        $monthly_sos = $this->db->query("SELECT 
                        to_char(i, 'YY'), to_char(i, 'MM'),  
                        to_char(i, 'month') as month,
                        CASE WHEN sum((type = '1')::int) IS NULL THEN 0 ELSE sum((type = '1')::int) END as patroli,
                        CASE WHEN sum((type = '2')::int) IS NULL THEN 0 ELSE sum((type = '2')::int) END as kejadian,
                        CASE WHEN sum((type = '3')::int) IS NULL THEN 0 ELSE sum((type = '3')::int) END as penghuni
                        FROM generate_series(now() - INTERVAL '1 year', now(), '1 month') as i 
                        LEFT JOIN t_sos on (to_char(i, 'YY') = to_char(submit_date, 'YY') 
                        AND to_char(i, 'MM') = to_char(submit_date, 'MM') )
                        AND b2b_token = '$b2b'
                        GROUP BY 1,2,month  
                        ORDER BY to_char(i, 'MM') ")->result();

        // get this month
        foreach ($monthly_sos as $key) {
            if ($key->to_char == date("m")) {
                $curent_month_sos = $key;
                break;
            }
        }

        $summary_absen = $this->summary_absen();
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

        return array(
            'bar' => $bar[0],
            'data' => $dashboard,
            'donut' => $donut[0],
            'highbar' => $highbar,
            'highline' => $highline,
            'monthly_sos' => $monthly_sos,
            'curent_month_sos' => $curent_month_sos,
            'summary_absen' => $summary_absen,
            'stat' => $this->session->userdata('status')
        );
    }

    public function percentage($x,$y)
    {
        $percent = floatval($x)/floatval($y);
        return number_format( $percent * 100, 1 ) . '%';
    }
    public function summary_absen()
	{
		$date = $this->input->get('tanggal') ?: date("d-m-Y");
		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}
        $user = $this->db->query("SELECT  SUM(CASE WHEN u.status::TEXT = 'active' THEN 1 ELSE 0 END ) AS all FROM users u WHERE u.user_roles != 'cudo' and u.b2b_token IN ? 
        ",[$b2b_tokens_arr])->row();
        
		$absen = $this->db->query("SELECT 
        sum(case when x.status = 1 then 1 else 0 end)  as all,
		SUM(CASE WHEN av.id is not null THEN 1 ELSE 0 END ) AS event,
		SUM(CASE WHEN x.via::TEXT = x.b2b_token and x.status = '1' THEN 1 ELSE 0 END ) AS onsite,
		SUM(CASE WHEN x.via::TEXT != x.b2b_token and x.status = '1' THEN 1 ELSE 0 END ) AS via
		FROM (
			SELECT DISTINCT ON (date,status,user_id)   *
            from log_absensi 
			WHERE b2b_token IN ?
			AND date  = ?
		) x
        LEFT JOIN absen_event av ON av.qr_id = x.qr_id 
        ", [$b2b_tokens_arr,$date])->row();
        $absen_perjam = $this->db->query("SELECT count(*) from log_absensi_reguler where  b2b_token IN ?
        AND date  = ?  ",[$b2b_tokens_arr,date("Y-m-d")])->row();
		$total_absence = intval($user->all) - intval($absen->all);
		return [
			'total_attend' => $absen->all,
			'total_absence' => $total_absence,
			'total_onsite' => $absen->onsite,
			'total_event' => $absen->event,
			'total_via' => $absen->via,
			'total_perjam' => $absen_perjam->count,
			'user' => $user
		];
	}
}