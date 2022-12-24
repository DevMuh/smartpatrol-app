<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dashboard_schedule_mobile extends CI_Model
{
public function user($user_id){
    $query = $this->db->query("SELECT full_name as fullname, other_data->>'position' as position FROM users WHERE id='$user_id'")->row_array();
    return $query;
}
public function checkpointm($user_id,$date){
    $query = $this->db->query("SELECT
										a.*,
										b.full_name
									FROM log_absensi_reguler a
									LEFT JOIN users b ON b.id = a.user_id::int
									WHERE  user_id = '$user_id'
									AND a.date = '".$date."'")->result();
                                    // var_dump($this->db->last_query());die;
                                    return $query;
}

public function fetch($user_id,$month, $year, $day=null)
{
    $fetch = $this->db->query("SELECT t_task_patrol_header.*, users.full_name FROM t_task_patrol_header left join users on users.id::varchar=t_task_patrol_header.taken_user 
    WHERE t_task_patrol_header.taken_user='926'
    AND  t_task_patrol_header.publish_date::TEXT LIKE '%$year-$month-$day%'")->result_array();
    // var_dump($this->db->last_query());die;
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
    return array('data' => $data);
}
public function ketetapan($day=null,$month,$year, $userid){
    
    
    $where = "AND user_id='$userid'";
        // if($day)  $where .= " and extract(day from A.submit_time::TIMESTAMP) = '$day' ";
        
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
        SPLIT_PART(STRING_AGG(t_shift.shift_name::TEXT,','), ',', 1) as shift_name,
        SPLIT_PART(STRING_AGG(t_shift.waktu_start::TEXT,','), ',', 1) as start_shift,
        SPLIT_PART(STRING_AGG(t_shift.waktu_end::TEXT,','), ',', 1) as end_shift,
        SPLIT_PART(STRING_AGG(t_shift.max_days::TEXT,','), ',', 1) as max_days,
        SPLIT_PART(STRING_AGG(t_shift.in_early::TEXT,','), ',', 1) as in_early,
        SPLIT_PART(STRING_AGG(t_shift.in_late::TEXT,','), ',', 1) as in_late,
        SPLIT_PART(STRING_AGG(t_shift.out_late::TEXT,','), ',', 1) as out_late,
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
        WHERE A.status != '2'
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
            ORDER BY A.date, A.user_id
            ";
            $qstring = "SELECT DISTINCT ON (A.date, A.user_id, A.id_shift) $columns $from $limit $offset";
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
        
        // $no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");
        // $absen_image_path = $this->config->item("base_url_api") . "assets/images/absen/";

        $all_absence = [];
        $now = date("d-M-Y H:i:s");
        foreach ($query as $row) {
            $in = explode(" ", $row->waktu_masuk);
            $out = explode(" ", $row->waktu_pulang);

            // $in_date = $in ?  $in[0] : "";
            // $out_date =  $out ? $out[0] : "";

            $in_time = $in ?  $in[1] : "";
            $out_time =  $out ? $out[1] : "";

            // $row->work_time = "-";
            // $row->start_overtime = "-";
            // $row->end_overtime = "-";
            $row->late_in_time = "-";
            // $row->late_out_time = "-";
            //waktu_pulang2
            if ($row->start_shift && $row->end_shift) {
                $check_in_time = new DateTime($in_time);

                $start_shift = new DateTime($row->start_shift);
                // $end_shift = new DateTime($row->end_shift);

                $row->max_days = $row->max_days ?: 0;
                // if(strtotime($row->start_shift) >= strtotime($row->end_shift)){
                //     $end_shift->modify("+$row->max_days day");
                // }
                // $dd_shift = $start_shift->diff($end_shift);
                // $row->durasi_shift = $dd_shift->h + ($dd_shift->d*24); 

                // if (!$row->in_early) $row->in_early = 0;
                // if (!$row->in_late) $row->in_late = 0;
                // if (!$row->out_late) $row->out_late = 0;

                // LATE IN TIME
                $start_shift_late =  clone $start_shift;
                $start_shift_late->modify("+$row->in_late minutes");
                if ($check_in_time > $start_shift_late) {
                    $late_in = $start_shift->diff($check_in_time);
                    $row->late_in_time = str_pad($late_in->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($late_in->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($late_in->s, 2, '0', STR_PAD_LEFT);  
                }

                // $start_shift_early = clone $start_shift;
                // $start_shift_early->modify("-$row->in_early minutes");
                // // OVERTIME AWAL
                // if ($check_in_time < $start_shift_early && $check_in_time > $start_shift) {
                //     $d = $check_in_time->diff($start_shift);
                //     $row->start_overtime = str_pad($d->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($d->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($d->s, 2, '0', STR_PAD_LEFT);  
                // }

                // $date_now = date_create($now);
                // $date_pulang2 = date_create($row->waktu_pulang2);  
                // if ($out_time != null) {
                //     // WORK TIME (total)
                //     $diff_total = date_diff(date_create($row->waktu_masuk),date_create($row->waktu_pulang));
                //     $row->work_time = str_pad($diff_total->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff_total->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($diff_total->s, 2, '0', STR_PAD_LEFT);  

                //     // OVERTIME AKHIR
                //     if ($check_in_time <= $end_shift && $check_out_time > $end_shift) {
                //         $d = $end_shift->diff($check_out_time);
                //         $row->end_overtime = str_pad($d->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($d->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($d->s, 2, '0', STR_PAD_LEFT);  
                //     }
                //     $end_shift_early = clone $end_shift;
                //     $end_shift_early->modify("-$row->out_late minutes");
                //     // LATE OUT TIME
                //     if (strtotime($check_out_time->date) < strtotime($end_shift_early->date)) {
                //         $late_out = $check_out_time->diff($end_shift);
                //         $row->late_out_time = str_pad($late_out->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($late_out->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($late_out->s, 2, '0', STR_PAD_LEFT);  
                //     }
                // } else if ($date_now >= $date_pulang2) { 
                //     // WORK TIME (total)
                //     $row->waktu_pulang = $row->waktu_pulang2;
                //     $diff_total = date_diff(date_create($row->waktu_masuk),date_create($row->waktu_pulang2));
                //     $row->work_time = str_pad($diff_total->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff_total->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($diff_total->s, 2, '0', STR_PAD_LEFT);  

                //     // OVERTIME AKHIR
                //     if ($check_in_time <= $end_shift && $check_out_time > $end_shift) {
                //         $d = $end_shift->diff($check_out_time);
                //         $row->end_overtime = str_pad($d->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($d->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($d->s, 2, '0', STR_PAD_LEFT);  
                //     }
                //     $end_shift_early = clone $end_shift;
                //     $end_shift_early->modify("-$row->out_late minutes");
                //     // LATE OUT TIME
                //     if (strtotime($check_out_time->date) < strtotime($end_shift_early->date)) {
                //         $late_out = $check_out_time->diff($end_shift);
                //         $row->late_out_time = str_pad($late_out->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($late_out->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($late_out->s, 2, '0', STR_PAD_LEFT);  
                //     }
                // }
            }
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
            // $row->check_out_time = $out_time;
            // $row->check_in_time = $in_time;
            // $row->in_date = $in_date;
            // $row->out_date = $out_date;
            

            array_push($all_absence, $row->late_in_time);
        }
        $payload['data'] =  $all_absence;
        $payload['recordsTotal'] = $payload['recordsFiltered'] =  $full_count;
        $loadtime = "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds";
        log_message('error', "======================== loadtime controller : M_Absensi method : get_absen_v2 call : loop");
        log_message('error', $loadtime);
        return $payload;
}
public function countabsen($day,$month,$year,$user_id){
    $query = $this->db->query("SELECT count(id) AS total
	FROM m_schedule_absensi
	WHERE user_id = '$user_id'
	and extract(year from date::TIMESTAMP) = '$year'
	and extract(month from date::TIMESTAMP) = '$month'")->row_array();
    return $query;
}
}
?>