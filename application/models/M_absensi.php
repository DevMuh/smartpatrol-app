<?php

use PhpOffice\PhpSpreadsheet\Shared\Date;

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class M_absensi extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_register_b2b');
    }

    function checktime($time)
    {
        $time = explode(":", $time);
        $hour = (int) $time[0];
        $min = (int) $time[1];
        $sec = (int) $time[2];
        if ($hour < 0 || $hour > 23 || !is_numeric($hour)) {
            return false;
        }
        if ($min < 0 || $min > 59 || !is_numeric($min)) {
            return false;
        }
        if ($sec < 0 || $sec > 59 || !is_numeric($sec)) {
            return false;
        }
        return true;
    }

    function get_last_id()
    {
        $temp = $this->db->select('id')->order_by('id', 'DESC')->limit(1)->get('log_absensi')->row();
        return ((int) $temp->id);
    }

    public function add_claim()
    {
        $this->form_validation->set_rules('petugas', 'Petugas', 'required');
        $this->form_validation->set_rules('shift', 'Shift', 'required');
        $this->form_validation->set_rules('status', 'Jenis Absen', 'required');
        $this->form_validation->set_rules('submit_time', 'Tanggal dan Waktu', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('petugas'), form_error('shift'), form_error('submit_time')];
        } else {
            
            $raw = $this->input->post('submit_time');
            $submit_time = date( "Y-m-d H:i:s", strtotime($raw) );
            $date = date("d-m-Y", strtotime($raw));
            $time = date("H:i:s", strtotime($raw));
            
            $petugas = $this->db->get_where('users', array('id' => $this->input->post('petugas')), 1, 0)->row();
            $shift = $this->db->get_where('t_shift', array('id_' => $this->input->post('shift')), 1, 0)->row();

            $data['id'] = $this->get_last_id() + 1;
            $data['user_id'] = $petugas->id;
            $data['b2b_token'] = $petugas->b2b_token;
            $data['submit_time'] = $submit_time;
            $data['date'] = $date;
            $data['time'] = $time;
            $data['id_shift'] = $shift->id_;
            $data['start_shift'] = $shift->waktu_start;
            $data['end_shift'] = $shift->waktu_end;
            $data['shift_name'] = $shift->shift_name;
            $data['status'] = $this->input->post('status');
            $data['is_same_day_shift'] = $this->input->post('is_overtime') ? FALSE : TRUE;
            $data['is_overtime'] = $this->input->post('is_overtime') ? TRUE : FALSE;
            $data['overtime_reason'] = $this->input->post('overtime_reason') ?: NULL;
            $data['claimed_by'] = $this->session->userdata('id');
            $data['claimed_at'] = date("Y-m-d H:i:s");

            if ($shift->kode_shift == 'Off Panggil') {
                $other_data = array(
                    "status_absen_schedule" => true,
                    "status_off_panggil"    => true,
                );

                $data['other_data'] = json_encode($other_data);
            }
            
            $this->db->insert('log_absensi', $data);
            return 1;
        }
    }

    public function fetch($month, $year)
    {
        $b2b = $this->session->userdata('b2b_token');
        $b2b_tokens = [];
        $b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
        foreach ($b2b_with_child as $key) {
            $b2b_tokens[] = $key->b2b_token;
        }

        $b2b_tokens = "'" . implode("','", $b2b_tokens) . "'";
        $fetch = $this->db->query("SELECT
        A.id,
        A.date,
        A.time,
        A.status,
        A.img_name,
        A.user_id as user_id,
        A.qr_id,
        A.is_overtime,
        t_shift.durasi as durasi_shift,
        users.full_name,
        t_shift.waktu_start,
        t_shift.waktu_end,
        t_shift.shift_name,
        t_shift.max_days,
        t_shift.in_early,
        t_shift.in_late,
        t_shift.out_late,
        via.title_nm as via_name,
        qr.name as qr_name,
        b.title_nm as org_name
        FROM
        log_absensi A
        INNER JOIN users ON A.user_id = users.id
        LEFT JOIN t_shift ON A.id_shift::TEXT = t_shift.id_::TEXT
        LEFT JOIN qr ON qr.qr_id = A.qr_id
        LEFT JOIN m_register_b2b as via ON via.b2b_token = A.via
        LEFT JOIN m_register_b2b as b ON b.b2b_token = A.b2b_token
        WHERE
        A.b2b_token IN ($b2b_tokens)
        AND  A.date LIKE '%$month-$year%'
        ORDER BY
        A.submit_time ASC")->result();

        $no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");
        $absen_image_path = $this->config->item("base_url_api") . "assets/images/absen/";
        $absen_image_path_cudo = $this->config->item("base_url_server_cudo") . "assets/absen/";
        $data_in = [];
        $i = 0;
        foreach ($fetch as $key) {
            $temp = new stdClass();
            if ($key->status == 1) {
                $temp->i = $i++;

                $image = $absen_image_path . $key->img_name;
                $image_cudo = $absen_image_path_cudo . $key->img_name;
                if (!$key->img_name) $image = $no_image;
                $temp->photo_in = $image;
                $temp->photo_in_cudo = $image_cudo;
                $temp->photo_out = $no_image;
                $temp->id = $key->id;
                $temp->user_id = $key->user_id;
                $temp->org_name = $key->org_name;
                $temp->profileurl = $key->profileurl;
                $temp->full_name = $key->full_name;
                $temp->position = $key->position;
                $temp->is_overtime = $key->is_overtime;
                $temp->date = $key->date;
                $temp->in_early = $key->in_early;
                $temp->in_late = $key->in_late;
                $temp->durasi_shift = $key->durasi_shift;
                $temp->via_in = $key->via_name;
                $temp->via_out = "---";
                $temp->qr_in = $key->qr_name;
                $temp->qr_out = "---";
                $temp->start_overtime = "---";
                $temp->end_overtime = "---";
                $temp->shift_in_name = $key->shift_name;
                $temp->shift_out_name = "---";
                $temp->max_days = $key->max_days;
                $temp->start_shift = $key->waktu_start;
                $temp->end_shift = $key->waktu_end;
                $temp->check_in_time = $key->time;
                $temp->check_out_time = '---';
                $temp->duration = null;
                $check_in_time = new DateTime($temp->check_in_time);
                $start_shift = new DateTime($temp->start_shift);
                $end_shift = new DateTime($temp->end_shift);
                $temp->is_out_of_shift_time = true;
                $temp->shift_hour = "---";
                $temp->work_time = "---";
                $temp->late_in_time = "---";
                $temp->late_out_time = "---";

                $temp->color = "yellow";
                if ($check_in_time <= $end_shift) {
                    $temp->color = "green";
                    $temp->is_out_of_shift_time = false;
                }
                array_push($data_in, $temp);
            }
        }

        $known = array();
        $data_in_filtered = array_filter(
            $data_in,
            function ($val) use (&$known) {
                $unique = $known[$val->date] == $val->user_id ? false : true;
                $known[$val->date] = $val->user_id;
                return $unique;
            }
        );

        usort($fetch, function ($first, $second) {
            return $first->id < $second->id;
        });

        $all_absence = [];
        foreach ($data_in_filtered as $row) {
            foreach ($fetch as $row2) {
                if (
                    $row2->status == 2
                    && $row->is_overtime != 't'
                    && $row->date == $row2->date
                    && $row->user_id == $row2->user_id
                ) {
                    $image = $absen_image_path . $row2->img_name;
                    if (!$row2->img_name) $image = $no_image;

                    $check_in_time = new DateTime($row->check_in_time);
                    $check_out_time = new DateTime($row2->time);

                    $start_shift = new DateTime($row->start_shift);
                    $end_shift = new DateTime($row->end_shift);

                    if (!$row->in_early) $row->in_early = 0;
                    if (!$row->in_late) $row->in_late = 0;
                    if (!$row->out_late) $row->out_late = 0;

                    $start_shift_early = $start_shift;
                    $start_shift_early->modify("-$row->in_early minutes");

                    $start_shift_late = $start_shift;
                    $start_shift_late->modify("+$row->in_late minutes");

                    $end_shift_early = $end_shift;
                    $end_shift_early->modify("-$row->out_late minutes");

                    // WORK TIME (total)
                    $diff_total = $check_in_time->diff($check_out_time);
                    $row->work_time = $diff_total->h . 'j, ' . $diff_total->i . 'm';

                    if ($row->start_shift && $row->end_shift) {
                        // OVERTIME AWAL
                        if ($check_in_time < $start_shift_early && $check_in_time > $start_shift) {
                            $d = $start_shift->diff($check_in_time);
                            $row->start_overtime = $d->h . 'j';
                        }

                        // OVERTIME AKHIR
                        if ($check_in_time <= $end_shift && $check_out_time > $end_shift) {
                            $d = $end_shift->diff($check_out_time);
                            $row->end_overtime = $d->h . 'j';
                        }

                        // LATE IN TIME
                        if ($check_in_time > $start_shift_late) {
                            $late_in = $start_shift->diff($check_in_time);
                            $row->late_in_time = $late_in->h . ' j, ' . $late_in->i . ' m';
                        }
                        // LATE OUT TIME
                        if ($check_out_time < $end_shift_early) {
                            $late_out = $check_out_time->diff($end_shift);
                            $row->late_out_time = $late_out->h . ' j, ' . $late_out->i . ' m';
                        }
                    }

                    $row->color = "red";
                    $row->photo_out = $image;
                    $row->check_out_time = $row2->time;
                    $row->via_out = $row2->via_name;
                    $row->qr_out = $row2->qr_name;
                    $row->shift_out_name = $row2->shift_name;
                }
            }
            array_push($all_absence, $row);
        }

        usort($all_absence, function ($first, $second) {
            return $first->i < $second->i;
        });

        $datatable = [];
        foreach ($all_absence as $row) {
            $temp = [];
            $temp[] = date("d-m-Y", strtotime($row->date));
            $temp[] = $row->full_name;
            $temp[] = $row->org_name;
            $temp[] = $row->shift_in_name;
            $temp[] = $row->durasi_shift . ' Jam';
            $temp[] = $row->start_shift;
            $temp[] = $row->end_shift;
            $temp[] = $row->check_in_time;
            $temp[] = $row->check_out_time;
            $temp[] = $row->late_in_time;
            $temp[] = $row->late_out_time;
            $temp[] = $row->work_time;
            $temp[] = $row->start_overtime;
            $temp[] = $row->end_overtime;
            $temp[] = $row->via_in;
            $temp[] = $row->via_out;
            $temp[] = $row->qr_in;
            $temp[] = $row->qr_out;
            $temp[] = "<img class='myImage' style='object-fit:cover' onclick='modalImg(this)'  width='50' height='50' src='" .  $row->photo_in . "' onError='this.onerror=null;this.src=`" . $row->photo_in_cudo . "`'>";
            $temp[] = "<img class='myImage' style='object-fit:cover' onclick='modalImg(this)'  width='50' height='50' src='" . $row->photo_out . "' onError='this.onerror=null;this.src=`" . $row->photo_in_cudo . "`'>";
            $temp[] = "<button data-toggle='modal'  data-target='#detailModal' onclick='detail(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . " )' class='btn mybt detail' title='Detail'><span class=' badge badge-info'>Detail </span></button> ";
            $datatable[] = $temp;
        }

        return array('data' => $datatable);
    }

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
            AND A.status != '2'
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
            if ($row->waktu_pulang == "") {
                $pulang =strtotime($row->date." ". $row->end_shift.":00");
                $masuk = strtotime($row->waktu_masuk);
                $waktupulang = date('Y-m-d H:i:s',$pulang);
                // $row->$waktupulang = "-";
                $row->waktu_masuk = date('Y-m-d H:i:s',$masuk);
                $datetime1 = new DateTime($row->waktu_masuk);
                $datetime2 = new DateTime($waktupulang);
                $row->waktu_pulang ="-";
                $diff = $datetime1->diff($datetime2);
                //$row->work_time = $diff->format("%h:%i:%s");
                $row->work_time = str_pad($diff->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($diff->s, 2, '0', STR_PAD_LEFT);
            }
            if ($row->photo_in) {
                $new_photo_in = "";
                if ($status_server) {
                    $this->load->library('curl');
                    $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen/" . $row->photo_in;
                    $image = $absen_image_path . $row->photo_in;
                    $result = $this->curl->simple_get($image_cudo);
                    
                    if($result != ""){
                        $new_photo_in = $image_cudo;
                    }elseif(@getimagesize($image)){
                        $new_photo_in = $image;
                    }else{
                        $new_photo_in = $no_image;
                    }
                } else {
                    $image = $absen_image_path . $row->photo_in;
                    if(@getimagesize($image)){
                        $new_photo_in = $image;
                    }else{
                        $new_photo_in = $no_image;
                    }
                }

                //$row->photo_in = $absen_image_path . $row->photo_in;
                $row->photo_in = $new_photo_in;
            } else {
                $row->photo_in = $no_image;
            }
            if ($row->photo_out) {
                $new_photo_out = "";
                if ($status_server) {
                    $this->load->library('curl');
                    $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen/" . $row->photo_out;
                    $image = $absen_image_path . $row->photo_out;
                    $result = $this->curl->simple_get($image_cudo);
                    
                    if($result != ""){
                        $new_photo_out = $image_cudo;
                    }elseif(@getimagesize($image)){
                        $new_photo_out = $image;
                    }else{
                        $new_photo_out = $no_image;
                    }
                } else {
                    $image = $absen_image_path . $row->photo_out;
                    if(@getimagesize($image)){
                        $new_photo_out = $image;
                    }else{
                        $new_photo_out = $no_image;
                    }
                }

                //$row->photo_out = $absen_image_path . $row->photo_out;
                $row->photo_out = $new_photo_out;
            } else {
                $row->photo_out = $no_image;
            }
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

    public function get_absen_v3($day=null,$month,$year, $extra_query = "")
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
        SPLIT_PART(STRING_AGG(users.full_name,','), ',', 1) as full_name,
        SPLIT_PART(STRING_AGG(b.title_nm::TEXT,','), ',', 1) as org_name,

        SPLIT_PART(STRING_AGG(t_shift.shift_name::TEXT,','), ',', 1) as shift_name,
        SPLIT_PART(STRING_AGG(t_shift.waktu_start::TEXT,','), ',', 1) as start_shift,
        SPLIT_PART(STRING_AGG(t_shift.waktu_end::TEXT,','), ',', 1) as end_shift,
        SPLIT_PART(STRING_AGG(t_shift.max_days::TEXT,','), ',', 1) as max_days,
        SPLIT_PART(STRING_AGG(t_shift.in_early::TEXT,','), ',', 1) as in_early,
        SPLIT_PART(STRING_AGG(t_shift.in_late::TEXT,','), ',', 1) as in_late,
        SPLIT_PART(STRING_AGG(t_shift.out_late::TEXT,','), ',', 1) as out_late,

        SPLIT_PART(
            STRING_AGG(CASE WHEN A.status = 1 THEN via.title_nm END::text,',' ::text ORDER BY (CASE WHEN A.status = 1 THEN A.time END)::text ASC) 
        ,',',1) 
        AS via_in,

        (SELECT 
            via.title_nm
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            LEFT JOIN m_register_b2b as via ON via.b2b_token = a2.via
            WHERE a2.user_id = A.user_id AND a2.status=2
            AND convert_string_to_date_smartpatrol(a2.date)=convert_string_to_date_smartpatrol(A.date) +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
            LIMIT 1
        ) AS via_out,

        SPLIT_PART(
            STRING_AGG(CASE WHEN A.status = 1 THEN concat(A.date,' ',A.time) END::text,',' ::text ORDER BY (CASE WHEN A.status = 1 THEN A.time END)::text ASC) 
        ,',',1) 
        AS waktu_masuk,

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
            AND A.status != '2'
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


        // $status_server = false;
        // if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
        //     $status_server = true;
        // }else{
        //     $status_server = false;
        // }
        
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
            if ($row->waktu_pulang == "") {
                $pulang =strtotime($row->date." ". $row->end_shift.":00");
                $masuk = strtotime($row->waktu_masuk);
                $waktupulang = date('Y-m-d H:i:s',$pulang);
                // $row->$waktupulang = "-";
                $row->waktu_masuk = date('Y-m-d H:i:s',$masuk);
                $datetime1 = new DateTime($row->waktu_masuk);
                $datetime2 = new DateTime($waktupulang);
                $row->waktu_pulang ="-";
                $diff = $datetime1->diff($datetime2);
                //$row->work_time = $diff->format("%h:%i:%s");
                $row->work_time = str_pad($diff->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($diff->s, 2, '0', STR_PAD_LEFT);
            }
            if ($row->photo_in) {
                //$new_photo_in = "";
                // if ($status_server) {
                //     $this->load->library('curl');
                //     $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen/" . $row->photo_in;
                //     $image = $absen_image_path . $row->photo_in;
                //     $result = $this->curl->simple_get($image_cudo);
                    
                //     if($result != ""){
                //         $new_photo_in = $image_cudo;
                //     }elseif(@getimagesize($image)){
                //         $new_photo_in = $image;
                //     }else{
                //         $new_photo_in = $no_image;
                //     }
                // } else {
                //     $image = $absen_image_path . $row->photo_in;
                //     if(@getimagesize($image)){
                //         $new_photo_in = $image;
                //     }else{
                //         $new_photo_in = $no_image;
                //     }
                // }

                $row->photo_in = $absen_image_path . $row->photo_in;
                // $row->photo_in = $new_photo_in;
            } else {
                $row->photo_in = $no_image;
            }
            if ($row->photo_out) {
                // $new_photo_out = "";
                // if ($status_server) {
                //     $this->load->library('curl');
                //     $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen/" . $row->photo_out;
                //     $image = $absen_image_path . $row->photo_out;
                //     $result = $this->curl->simple_get($image_cudo);
                    
                //     if($result != ""){
                //         $new_photo_out = $image_cudo;
                //     }elseif(@getimagesize($image)){
                //         $new_photo_out = $image;
                //     }else{
                //         $new_photo_out = $no_image;
                //     }
                // } else {
                //     $image = $absen_image_path . $row->photo_out;
                //     if(@getimagesize($image)){
                //         $new_photo_out = $image;
                //     }else{
                //         $new_photo_out = $no_image;
                //     }
                // }

                $row->photo_out = $absen_image_path . $row->photo_out;
                //$row->photo_out = $new_photo_out;
            } else {
                $row->photo_out = $no_image;
            }
            $row->check_out_time = $out_time;
            $row->check_in_time = $in_time;
            $row->in_date = $in_date;
            $row->out_date = $out_date;
            

            array_push($all_absence, $row);
        }
        $payload['data'] =  $all_absence;
        $payload['recordsTotal'] = $payload['recordsFiltered'] =  $full_count;
        $loadtime = "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds";
        log_message('error', "======================== loadtime controller : M_Absensi method : get_absen_v2 call : loop");
        log_message('error', $loadtime);
        return $payload;
    }

    public function get_absen_event($day,$month,$year)
    {
        // get_absen_v2
        $extra_query = " AND A.qr_id IN (
            SELECT DISTINCT qr_id FROM absen_event 
            WHERE create_date = '$year-$month-$day'
        )";
        return $this->get_absen_v2($day, $month, $year, $extra_query);
    }

    public function get_absen_off_panggil($day=null,$month,$year, $extra_query = "")
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
        --        AND TO_DATE(a2.date,'DD-MM-YYYY')=TO_DATE(A.date,'DD-MM-YYYY') +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
        --        LIMIT 1
        --    ) AS waktu_pulang,

        (CASE WHEN (SELECT 
			MAX(concat(a2.date,' ',a2.time)) 
			From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
			WHERE a2.user_id = A.user_id AND a2.status=2
			AND TO_DATE(a2.date,'DD-MM-YYYY')=TO_DATE(A.date,'DD-MM-YYYY') +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
			AND a2.id_shift::TEXT = A.id_shift::TEXT
			LIMIT 1
		) is NULL THEN (
			SELECT 
			MAX(concat(a2.date,' ',a2.time)) 
			From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
			WHERE a2.user_id = A.user_id AND a2.status=2
			AND TO_DATE(a2.date,'DD-MM-YYYY')=TO_DATE(A.date,'DD-MM-YYYY') + 0
			AND a2.id_shift::TEXT = A.id_shift::TEXT
			LIMIT 1
		) ELSE (
			SELECT 
			MAX(concat(a2.date,' ',a2.time)) 
			From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
			WHERE a2.user_id = A.user_id AND a2.status=2
			AND TO_DATE(a2.date,'DD-MM-YYYY')=TO_DATE(A.date,'DD-MM-YYYY') +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
			AND a2.id_shift::TEXT = A.id_shift::TEXT
			LIMIT 1
		) END

		) AS waktu_pulang,
        
        concat(to_char((SELECT 
            TO_DATE(A.date,'DD-MM-YYYY') 
            + (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval 
        )::date, 'DD-MM-YYYY'), ' ', SPLIT_PART(STRING_AGG(t_shift.waktu_end::TEXT,','), ',', 1)) 
        AS waktu_pulang2,

        (SELECT 
            a2.is_overtime
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            WHERE a2.user_id = A.user_id AND a2.status=2 AND a2.is_overtime = 't'
            AND TO_DATE(a2.date,'DD-MM-YYYY')=TO_DATE(A.date,'DD-MM-YYYY') +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
            ORDER BY a2.id DESC
            LIMIT 1
        ) AS is_overtime,

        (SELECT 
            a2.overtime_reason
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            WHERE a2.user_id = A.user_id AND a2.status=2
            AND TO_DATE(a2.date,'DD-MM-YYYY')=TO_DATE(A.date,'DD-MM-YYYY') +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
            ORDER BY a2.id DESC
            LIMIT 1
        ) AS overtime_reason,

        (SELECT 
            a2.img_name
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            WHERE a2.user_id = A.user_id AND a2.status=2
            AND TO_DATE(a2.date,'DD-MM-YYYY')=TO_DATE(A.date,'DD-MM-YYYY') +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
            LIMIT 1
        ) AS photo_out,

        (SELECT 
            via.title_nm
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            LEFT JOIN m_register_b2b as via ON via.b2b_token = a2.via
            WHERE a2.user_id = A.user_id AND a2.status=2
            AND TO_DATE(a2.date,'DD-MM-YYYY')=TO_DATE(A.date,'DD-MM-YYYY') +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
            LIMIT 1
        ) AS via_out,

        (SELECT 
            qr.name
            From log_absensi a2 LEFT JOIN t_shift sf ON a2.id_shift::TEXT = sf.id_::TEXT
            LEFT JOIN qr ON qr.qr_id = a2.qr_id
            WHERE a2.user_id = A.user_id AND a2.status=2
            AND TO_DATE(a2.date,'DD-MM-YYYY')=TO_DATE(A.date,'DD-MM-YYYY') +  (max(CASE WHEN t_shift.max_days IS NULL OR t_shift.max_days::TEXT = '' THEN 0 ELSE t_shift.max_days END)::text || ' days')::interval
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
            AND A.status != '2'
            and extract(year from A.submit_time::TIMESTAMP) = '$year'
            and extract(month from A.submit_time::TIMESTAMP) = '$month'
            AND (A.other_data->>'status_absen_schedule'='true' OR A.other_data->>'status_absen_schedule' IS NULL)
            AND A.other_data->>'status_off_panggil'='true'
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
            if ($row->photo_in) {
                $new_photo_in = "";
                if ($status_server) {
                    $this->load->library('curl');
                    $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen/" . $row->photo_in;
                    $image = $absen_image_path . $row->photo_in;
                    $result = $this->curl->simple_get($image_cudo);
                    
                    if($result != ""){
                        $new_photo_in = $image_cudo;
                    }elseif(@getimagesize($image)){
                        $new_photo_in = $image;
                    }else{
                        $new_photo_in = $no_image;
                    }
                } else {
                    $image = $absen_image_path . $row->photo_in;
                    if(@getimagesize($image)){
                        $new_photo_in = $image;
                    }else{
                        $new_photo_in = $no_image;
                    }
                }

                //$row->photo_in = $absen_image_path . $row->photo_in;
                $row->photo_in = $new_photo_in;
            } else {
                $row->photo_in = $no_image;
            }
            if ($row->photo_out) {
                $new_photo_out = "";
                if ($status_server) {
                    $this->load->library('curl');
                    $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen/" . $row->photo_out;
                    $image = $absen_image_path . $row->photo_out;
                    $result = $this->curl->simple_get($image_cudo);
                    
                    if($result != ""){
                        $new_photo_out = $image_cudo;
                    }elseif(@getimagesize($image)){
                        $new_photo_out = $image;
                    }else{
                        $new_photo_out = $no_image;
                    }
                } else {
                    $image = $absen_image_path . $row->photo_out;
                    if(@getimagesize($image)){
                        $new_photo_out = $image;
                    }else{
                        $new_photo_out = $no_image;
                    }
                }

                //$row->photo_out = $absen_image_path . $row->photo_out;
                $row->photo_out = $new_photo_out;
            } else {
                $row->photo_out = $no_image;
            }
            $row->check_out_time = $out_time;
            $row->check_in_time = $in_time;
            $row->in_date = $in_date;
            $row->out_date = $out_date;
            

            array_push($all_absence, $row);
        }
        $payload['data'] =  $all_absence;
        $payload['recordsTotal'] = $payload['recordsFiltered'] =  $full_count;
        $loadtime = "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds";
        log_message('error', "======================== loadtime controller : M_Absensi method : get_absen_v2 call : loop");
        log_message('error', $loadtime);
        return $payload;
    }

    public function fetch_off_panggil($month, $year)
    {
        $day = $this->input->get('day') ?: null;
        $payload = $this->get_absen_off_panggil($day,$month,$year);
        $datatable = [];
        foreach ($payload["data"] as $row) {
            $dateformat = date("Y-m-d", strtotime($row->date));
            $check_data = $this->db->query("
                            SELECT count(id) as total FROM log_absensi_reguler WHERE 
                            user_id='".$row->user_id."' AND 
                            submit_time::date = '".$dateformat."'
                        ")->row();

            $color = "";
            if ($check_data->total > 0) {
                $color = "badge-danger";
            }else{
                $color = "badge-info";
            }

            $temp = [];
            
            $temp[] = date("d-m-Y", strtotime($row->date));
            $temp[] = $row->payroll_id ?: '-';
            $temp[] = $row->full_name;
            $temp[] = $row->org_name;
            $temp[] = $row->shift_name;
            $temp[] = $row->durasi_shift ? $row->durasi_shift  . ' Jam' : "-";
            $temp[] = $row->start_shift;
            $temp[] = $row->end_shift;
            $temp[] = $row->waktu_masuk;
            $temp[] = $row->waktu_pulang;
            $temp[] = $row->late_in_time;
            $temp[] = $row->late_out_time;
            $temp[] = $row->work_time;
            $temp[] = $row->start_overtime;
            $temp[] = $row->end_overtime;
            $temp[] = $row->via_in;
            $temp[] = $row->via_out;
            $temp[] = $row->qr_in;
            $temp[] = $row->qr_out;
            $temp[] = ($row->is_overtime == "t") ? "Lembur" : "Tidak lembur";;
            $temp[] = $row->overtime_reason;
            $temp[] = "<img class='myImage' style='object-fit:cover' onclick='modalImg(this)'  width='50' height='50' src='" .  $row->photo_in . "'>";
            $temp[] = "<img class='myImage' style='object-fit:cover' onclick='modalImg(this)'  width='50' height='50' src='" . $row->photo_out . "'>";

            // $action = "<button data-toggle='modal'  data-target='#detailModal' onclick='detail(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . " )' class='btn mybt detail' title='Detail'><span class=' badge badge-info'><i class='fa fa-info-circle'></i> </span></button>
            //             <button onclick='exportPdf(this); return false;' class='btn mybt detail' title='Export Checkpoint PDF' data-id='".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8')."'><span class=' badge ".$color."'><i class='fa fa-file-pdf'></i> </span></button>
            //             ";
            
            $action = ($row->status_approval == "t") ? "Lembur" : "Tidak lembur";            
            $temp[] = $action;
            $datatable[] = $temp;
        }
        $payload["data"] = $datatable;
        return $payload;
    }

    public function fetch_v2($month, $year)
    {
        
        $day = $this->input->get('day') ?: null;
        $payload = $this->get_absen_v2($day,$month,$year);
        $datatable = [];
        foreach ($payload["data"] as $row) {
            // $url_cudo = explode($this->config->item("base_url_api")."assets/images/",$row->photo_in);
            // $url_cudo = $this->config->item("base_url_server_cudo")."assets/".$url_cudo[1];
            // if(@getimagesize($url_cudo)){
            //     //seu código...
            // }else {
            //     $url_cudo = base_url("assets/apps/assets/dist/img/no-image.jpg");
            // }
            $dateformat = date("Y-m-d", strtotime($row->date));
            $check_data = $this->db->query("
                            SELECT count(id) as total FROM log_absensi_reguler WHERE 
                            user_id='".$row->user_id."' AND 
                            submit_time::date = '".$dateformat."'
                        ")->row();

            $color = "";
            if ($check_data->total > 0) {
                $color = "badge-danger";
            }else{
                $color = "badge-info";
            }

            $temp = [];
            
            $temp[] = date("d-m-Y", strtotime($row->date));
            $temp[] = $row->payroll_id ?: '-';
            $temp[] = $row->full_name;
            $temp[] = $row->org_name;
            $temp[] = $row->shift_name;
            $temp[] = $row->durasi_shift ? $row->durasi_shift  . ' Jam' : "-";
            $temp[] = $row->start_shift;
            $temp[] = $row->end_shift;
            $temp[] = $row->waktu_masuk;
            $temp[] = $row->waktu_pulang;
            $temp[] = $row->late_in_time;
            $temp[] = $row->late_out_time;
            $temp[] = $row->work_time;
            $temp[] = $row->start_overtime;
            $temp[] = $row->end_overtime;
            $temp[] = $row->via_in;
            $temp[] = $row->via_out;
            $temp[] = $row->qr_in;
            $temp[] = $row->qr_out;
            $temp[] = ($row->is_overtime == "t") ? "Lembur" : "Tidak lembur";;
            $temp[] = $row->overtime_reason;
            // $temp[] = "<img class='myImage' style='object-fit:cover' onclick='modalImg(this)'  width='50' height='50' src='" .  $row->photo_in . "' data-d='" .  $aya . "' onError='this.onerror=null;this.src=`" . $url_cudo . "`'>";
            // $temp[] = "<img class='myImage' style='object-fit:cover' onclick='modalImg(this)'  width='50' height='50' src='" . $row->photo_out . "' onError='this.onerror=null;this.src=`" . $url_cudo . "`'>";
            $temp[] = "<img class='myImage' style='object-fit:cover' onclick='modalImg(this)'  width='50' height='50' src='" .  $row->photo_in . "'>";
            $temp[] = "<img class='myImage' style='object-fit:cover' onclick='modalImg(this)'  width='50' height='50' src='" . $row->photo_out . "'>";

            $action = "<button data-toggle='modal'  data-target='#detailModal' onclick='detail(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . " )' class='btn mybt detail' title='Detail'><span class=' badge badge-info'><i class='fa fa-info-circle'></i> </span></button>
                        <button onclick='exportPdf(this); return false;' class='btn mybt detail' title='Export Checkpoint PDF' data-id='".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8')."'><span class=' badge ".$color."'><i class='fa fa-file-pdf'></i> </span></button>
                        ";
            //$temp[] = "<button onclick='exportPayrollPdf(this); return false;' class='btn mybt detail' title='Export Payroll PDF' data-id='".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8')."'><span class=' badge badge-danger'><i class='fa fa-credit-card'></i> </span></button>";
            $temp[] = $action;
            $datatable[] = $temp;
        }
        $payload["data"] = $datatable;
        return $payload;
    }

    public function fetch_v2_export($month, $year)
    {
        $day = $this->input->get('day') ?: null;
        $starttime = microtime(true); // Top of page
        $payload = $this->get_absen_v2($day,$month,$year);
        $loadtime = "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds";
        log_message('error', "======================== loadtime controller : M_Absensi method : fetch_v2_export call : get_absen_v2");
        log_message('error', $loadtime);
        $datatable = [];
        foreach ($payload["data"] as $row) {
            $temp = [];
            
            // $temp[] = date("d-m-Y", strtotime($row->date));
            $temp[] = $row->payroll_id ?: '-';
            $temp[] = $row->full_name;
            $temp[] = $row->org_name;
            $temp[] = $row->shift_name;
            $temp[] = $row->durasi_shift ? $row->durasi_shift  . ' Jam' : "-";
            $temp[] = $row->start_shift;
            $temp[] = $row->end_shift;
            $temp[] = $row->waktu_masuk;
            $temp[] = $row->waktu_pulang;
            $temp[] = $row->late_in_time;
            $temp[] = $row->late_out_time;
            $temp[] = $row->work_time;
            $temp[] = $row->start_overtime;
            $temp[] = $row->end_overtime;
            $temp[] = $row->via_in;
            $temp[] = $row->via_out;
            $temp[] = ($row->is_overtime == "t") ? "Lembur" : "Tidak lembur";;
            $temp[] = $row->overtime_reason;
            $datatable[] = $temp;
        }
        $payload["data"] = $datatable;
        return $payload;
    }

    public function fetch_v3_export($day=null, $month, $year)
    {
        $day_param = $day ?: null;
        $starttime = microtime(true); // Top of page
        $payload = $this->get_absen_v3($day_param,$month,$year);
        //$payload = $this->get_absen_v2($day_param,$month,$year);
        $loadtime = "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds";
        log_message('error', "======================== loadtime controller : M_Absensi method : fetch_v2_export call : get_absen_v2");
        log_message('error', $loadtime);
        $datatable = [];
        foreach ($payload["data"] as $row) {
            $temp = [];
            
            // $temp[] = date("d-m-Y", strtotime($row->date));
            $temp[] = $row->payroll_id ?: '-';
            $temp[] = $row->full_name;
            $temp[] = $row->org_name;
            $temp[] = $row->shift_name;
            $temp[] = $row->durasi_shift ? $row->durasi_shift  . ' Jam' : "-";
            $temp[] = $row->start_shift;
            $temp[] = $row->end_shift;
            $temp[] = $row->waktu_masuk;
            $temp[] = $row->waktu_pulang;
            $temp[] = $row->late_in_time;
            $temp[] = $row->late_out_time;
            $temp[] = $row->work_time;
            $temp[] = $row->start_overtime;
            $temp[] = $row->end_overtime;
            $temp[] = $row->via_in;
            $temp[] = $row->via_out;
            $temp[] = ($row->is_overtime == "t") ? "Lembur" : "Tidak lembur";;
            $temp[] = $row->overtime_reason;
            $datatable[] = $temp;
        }
        $payload["data"] = $datatable;
        return $payload;
    }

    public function fetch_dt_log_absen_reguler($month,$year)
    {
        $date = "$year-$month-" . date('d');
        $b2b = $this->session->userdata('b2b_token');
        $b2b_tokens = [];
        $b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
        foreach ($b2b_with_child as $key) {
            $b2b_tokens[] = $key->b2b_token;
        }
        $b2b_tokens = "'" . implode("','", $b2b_tokens) . "'";
        
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
            and extract(year from a.submit_time::TIMESTAMP) = '$year'
            and extract(month from a.submit_time::TIMESTAMP) = '$month'
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


    public function fetch_event($month, $year)
    {
        $day = $this->input->get('day') ?: date("d");
        $this->db->join('users', 'users.id = absen_event.create_by', 'left');
        $this->db->join('m_register_b2b', 'm_register_b2b.b2b_token = absen_event.via', 'left');
        $fetch = $this->db->get_where("absen_event", ["absen_event.b2b_token" => $this->session->userdata("b2b_token"), "absen_event.create_date::TEXT LIKE" =>  "%$year-$month-$day%"])->result();
        $data = array();

        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $row->qr_id;
            $temp[] = $row->desc;
            $temp[] = $row->title_nm;
            $temp[] = $row->full_name;
            $temp[] = $row->create_date;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function fetch_checkpoint($month, $year)
    {
        $day = $this->input->get('day') ?: date("d");
        $fetch =  $this->db->query("
                        SELECT 
                            log_absensi_reguler.*,
                            users.full_name,
                            qr.name as qr_name
                        FROM log_absensi_reguler
                        LEFT JOIN qr ON log_absensi_reguler.qr_id = qr.qr_id
                        LEFT JOIN users ON log_absensi_reguler.user_id::INTEGER = users.id
                        WHERE 
                        -- log_absensi_reguler.user_id = '".$this->session->userdata("id")."' AND 
                        log_absensi_reguler.b2b_token = '".$this->session->userdata("b2b_token")."'
                        AND log_absensi_reguler.submit_time::TEXT LIKE '%$year-$month-$day%'
                        ORDER BY log_absensi_reguler.submit_time ASC
                    ")->result();

        $data = array();

        $status_server = false;
        if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
            $status_server = true;
        }else{
            $status_server = false;
        }
        
        $no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");
        $absen_image_path = $this->config->item("base_url_api") . "assets/images/absen_checkpoint/";

        foreach ($fetch as $row) {
            $new_photo = "";
            if ($row->photo) {
                //2022-07-04 15:29:00
                if ($row->submit_time >= '2022-07-04 15:29:00') { 
                    $photo_name = $row->photo;
                } else {
                    $time = strtotime($row->submit_time);
                    $newformat = date('Y-m-d',$time);
                    $newformat2 = date('His',$time);

                    $path_file = $row->b2b_token ."/" . $row->user_id . "/" . $newformat . "/" . $newformat2 . "/";
                    $photo_name = $path_file."photo_".$row->user_id."_".$row->qr_id.".jpg";
                }

                if ($status_server) {
                    $this->load->library('curl');
                    $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen_checkpoint/" . $photo_name;
                    $image = $absen_image_path . $photo_name;
                    $result = $this->curl->simple_get($image_cudo);
                    
                    if($result != ""){
                        $new_photo = $image_cudo;
                    }elseif(@getimagesize($image)){
                        $new_photo = $image;
                    }else{
                        $new_photo = $no_image;
                    }
                } else {
                    $image = $absen_image_path . $photo_name;
                    if(@getimagesize($image)){
                        $new_photo = $image;
                    }else{
                        $new_photo = $no_image;
                    }
                }
            } else {
                $new_photo = $no_image;
            }

            $new_selfie = "";
            if ($row->selfie) {

                if ($row->submit_time >= '2022-07-04 15:29:00') { 
                    $selfie_name = $row->selfie;
                } else {
                    $time = strtotime($row->submit_time);
                    $newformat = date('Y-m-d',$time);
                    $newformat2 = date('His',$time);
    
                    $path_file = $row->b2b_token ."/" . $row->user_id . "/" . $newformat . "/" . $newformat2 . "/";
                    $selfie_name = $path_file."selfie_".$row->user_id."_".$row->qr_id.".jpg";
                }

                if ($status_server) {
                    $this->load->library('curl');
                    $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen_checkpoint/" . $selfie_name;
                    $image = $absen_image_path . $selfie_name;
                    $result = $this->curl->simple_get($image_cudo);
                    
                    if($result != ""){
                        $new_selfie = $image_cudo;
                    }elseif(@getimagesize($image)){
                        $new_selfie = $image;
                    }else{
                        $new_selfie = $no_image;
                    }
                } else {
                    $image = $absen_image_path . $selfie_name;
                    if(@getimagesize($image)){
                        $new_selfie = $image;
                    }else{
                        $new_selfie = $no_image;
                    }
                }
            } else {
                $new_selfie = $no_image;
            }

            $temp = array();
            $temp[] = $row->full_name;
            $temp[] = $row->qr_name;
            $temp[] = $row->date;
            $temp[] = date("H:i:s", strtotime($row->submit_time));
            $temp[] = "<a href='#'>".$row->lat ."</a> / <a href='#'>". $row->long."</a>";
            $temp[] = '<img class="myImage" style="object-fit:cover" onclick="modalImg(this)" width="50" height="50" src="'.$new_photo.'">';
            $temp[] = '<img class="myImage" style="object-fit:cover" onclick="modalImg(this)" width="50" height="50" src="'.$new_selfie.'">';
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function fetch_checkpoint_export($userid, $date)
    {
        $fetch =  $this->db->query("
                        SELECT 
                            log_absensi_reguler.*,
                            users.full_name,
                            qr.name as qr_name
                        FROM log_absensi_reguler
                        LEFT JOIN qr ON log_absensi_reguler.qr_id = qr.qr_id
                        LEFT JOIN users ON log_absensi_reguler.user_id::INTEGER = users.id
                        WHERE 
                        log_absensi_reguler.user_id = '".$userid."' AND 
                        log_absensi_reguler.b2b_token = '".$this->session->userdata("b2b_token")."'
                        AND log_absensi_reguler.submit_time::date = '$date'
                        ORDER BY log_absensi_reguler.submit_time DESC
                    ")->result();

        $data = array();

        $status_server = false;
        if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
            $status_server = true;
        }else{
            $status_server = false;
        }
        
        $no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");
        $absen_image_path = $this->config->item("base_url_api") . "assets/images/absen_checkpoint/";

        $no = 1;
        foreach ($fetch as $row) {
            $new_photo = "";
            $label_new_photo = "";
            if ($row->photo) {
                $time = strtotime($row->submit_time);
                $newformat = date('Y-m-d',$time);
                $newformat2 = date('His',$time);

                if ($row->submit_time >= '2022-07-04 15:29:00') { 
                    $photo_name = $row->photo;
                } else {
                    $path_file = $row->b2b_token ."/" . $row->user_id . "/" . $newformat . "/" . $newformat2 . "/";
                    $photo_name = $path_file."photo_".$row->user_id."_".$row->qr_id.".jpg";
                }
                
                $label = "photo_".$row->user_id."_".$row->qr_id."_".$newformat."_".$newformat2.".jpg";

                if ($status_server) {
                    $this->load->library('curl');
                    $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen_checkpoint/" . $photo_name;
                    $image = $absen_image_path . $photo_name;
                    $result = $this->curl->simple_get($image_cudo);
                    
                    if($result != ""){
                        $new_photo = $image_cudo;
                        $label_new_photo = $label;
                    }elseif(@getimagesize($image)){
                        $new_photo = $image;
                        $label_new_photo = $label;
                    }else{
                        $new_photo = $no_image;
                        $label_new_photo = "no image available";
                    }
                } else {
                    $image = $absen_image_path . $photo_name;
                    if(@getimagesize($image)){
                        $new_photo = $image;
                        $label_new_photo = $label;
                    }else{
                        $new_photo = $no_image;
                        $label_new_photo = "no image available";
                    }
                }
            } else {
                $new_photo = $no_image;
                $label_new_photo = "no image available";
            }

            $new_selfie = "";
            $label_new_selfie = "";
            if ($row->selfie) {
                $time = strtotime($row->submit_time);
                $newformat = date('Y-m-d',$time);
                $newformat2 = date('His',$time);

                if ($row->submit_time >= '2022-07-04 15:29:00') { 
                    $selfie_name = $row->selfie;
                } else {
                    $path_file = $row->b2b_token ."/" . $row->user_id . "/" . $newformat . "/" . $newformat2 . "/";
                    $selfie_name = $path_file."selfie_".$row->user_id."_".$row->qr_id.".jpg";
                }

                $label = "selfie_".$row->user_id."_".$row->qr_id."_".$newformat."_".$newformat2.".jpg";

                if ($status_server) {
                    $this->load->library('curl');
                    $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen_checkpoint/" . $selfie_name;
                    $image = $absen_image_path . $selfie_name;
                    $result = $this->curl->simple_get($image_cudo);
                    
                    if($result != ""){
                        $new_selfie = $image_cudo;
                        $label_new_selfie = $label;
                    }elseif(@getimagesize($image)){
                        $new_selfie = $image;
                        $label_new_selfie = $label;
                    }else{
                        $new_selfie = $no_image;
                        $label_new_selfie = "no image available";
                    }
                } else {
                    $image = $absen_image_path . $selfie_name;
                    if(@getimagesize($image)){
                        $new_selfie = $image;
                        $label_new_selfie = $label;
                    }else{
                        $new_selfie = $no_image;
                        $label_new_selfie = "no image available";
                    }
                }
            } else {
                $new_selfie = $no_image;
                $label_new_selfie = "no image available";
            }

            $new_photo_base64 = "";
            if($new_photo){
                $url = $new_photo;
                $new_photo_base64 = 'data:image/png;base64,' . base64_encode(fetch($url));
            }

            $new_selfie_base64 = "";
            if($new_selfie){
                $url_selfie = $new_selfie;
                $new_selfie_base64 = 'data:image/png;base64,' . base64_encode(fetch($url_selfie));
            }

            $duration = "";
            $last_date_time = "";
            if ($no==1) {
                $duration = "-";
                $last_date_time = $row->submit_time;
            }else if ($no == count($fetch)){
                $start = new DateTime($last_date_time);
                $end = new DateTime($row->submit_time);

                $dd = $start->diff($end);
                $duration = str_pad($dd->H, 2, '0', STR_PAD_LEFT) . ':' . str_pad($dd->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($dd->s, 2, '0', STR_PAD_LEFT);
            }else{
                $start = new DateTime($last_date_time);
                $end = new DateTime($row->submit_time);

                $dd = $start->diff($end);
                $duration = str_pad($dd->H, 2, '0', STR_PAD_LEFT) . ':' . str_pad($dd->i, 2, '0', STR_PAD_LEFT)  . ':' . str_pad($dd->s, 2, '0', STR_PAD_LEFT);

                $last_date_time = $row->submit_time;
            }

            $temp = array();
            $temp['full_name'] = $row->full_name;
            $temp['qr_name'] = $row->qr_name;
            $temp['date'] = $row->date;
            $temp['time'] = date("H:i:s", strtotime($row->submit_time));
            $temp['lat'] = $row->lat;
            $temp['long'] = $row->long;
            $temp['photo'] = $new_photo_base64;
            $temp['label_photo'] = $label_new_photo;
            $temp['selfie'] = $new_selfie_base64;
            $temp['label_selfie'] = $label_new_selfie;
            $temp['duration'] = $duration;
            $data[] = $temp;
            $no++;
        }
        return $data;
    }

    public function all_summary($month = null, $year = null)
    {
        $day = $this->input->get('day') ?: date("d");
        $b2b = $this->session->userdata('b2b_token');
        $b2b_tokens = [];
        $b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
        foreach ($b2b_with_child as $key) {
            $b2b_tokens[] = $key->b2b_token;
        }

        $b2b_tokens = "'" . implode("','", $b2b_tokens) . "'";

        $query =  $this->db->query("SELECT 
								u.full_name as name,
								u.no_tlp as phone,
								SUM(CASE WHEN A.status = 1 THEN 1 ELSE 0 END ) count_in,
								SUM(CASE WHEN A.status = 2 AND A.is_overtime = 't' THEN 1 ELSE 0 END ) count_overtime
								FROM (SELECT DISTINCT ON (date,status,user_id) * from log_absensi ) A
								INNER JOIN users u ON A.user_id::TEXT = u.id::TEXT 
								WHERE
								A.date iLIKE '%$month-$year-$day%'
                                AND A.b2b_token IN ($b2b_tokens)
								GROUP BY A.user_id, u.full_name,u.no_tlp
								ORDER BY u.full_name asc
                                ")->result();
        $d = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $arr = [];
        foreach ($query as $key) {
            $t = [];
            $t[] = $key->name;
            $t[] = $key->phone;
            $t[] = $key->count_in;
            $t[] = $key->count_overtime;
            $t[] = $d - $key->count_in;
            $arr[] = $t;
        }
        return $arr;
    }
}
                        
/* End of file x.php */
