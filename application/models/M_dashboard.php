<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_dashboard extends CI_Model
{

    public function __construct()
	{
		parent::__construct();
		$this->b2b = $this->session->userdata("b2b_token");
		$this->load->model(['M_register_b2b','M_absensi']);
	}

    private function getlonglat($location)
    {
        $apikey = 'AIzaSyCfY9TPZ31i6nu-oTLQWjuHaIt5dbc86o4';
        $string = str_replace(" ", "+", urlencode($location));
        $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $string . "&key=" . $apikey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);

        // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
        if ($response['status'] != 'OK') {
            return array(0, 0);
        }

        $geometry = $response['results'][0]['geometry'];

        if ($geometry['location']['lng'] == null) {
            $longitude = 0;
        } else {
            $longitude = $geometry['location']['lng'];
        }
        if ($geometry['location']['lat'] == null) {
            $latitude = 0;
        } else {
            $latitude = $geometry['location']['lat'];
        }

        $array = array($longitude, $latitude);

        return $array;
    }

    public function get_data_dashboard()
    {
        $b2b = $this->session->userdata('b2b_token');

        $partol_count = $this->db->query("
        SELECT
	    aa.total AS active,
	    bb.total AS complate 
        FROM
	    ( SELECT COUNT ( * ) AS total FROM t_task_patrol_header WHERE flag_process = '1' and b2b_token='$b2b') aa,
	    ( SELECT COUNT ( * ) AS total FROM t_task_patrol_header WHERE flag_process = '3' and b2b_token='$b2b' and done_date = current_date) bb
        ")->row();
        $alert = $this->db->query("SELECT count(*) as alert FROM t_task_patrol_detail where condition ='2' AND b2b_token = '$b2b' and submit_date = current_date")->row();
        $critical = $this->db->query("SELECT count(*) as critical FROM t_task_kejadian WHERE b2b_token = '$b2b'")->row();
        $guest = $this->db->query("SELECT count(*) as guest FROM t_task_tamu WHERE b2b_token = '$b2b' and start_date = current_date")->row();
        $count_task = $this->db->query("SELECT count(*) as count_task FROM t_task_patrol_header where t_task_patrol_header.flag_process !=3 AND t_task_patrol_header.b2b_token = '$b2b'")->row();
        $occupant = $this->db->query("SELECT count(*) as occupant FROM m_client where flag_disable='1' AND b2b_token = '$b2b'")->row();
        $security = $this->db->query("SELECT count(*) as securiti from users where user_roles = 'anggota' AND b2b_token = '$b2b'")->row();
        $checkpoint = $this->db->query("SELECT * from check_point where flag_disable='1' AND b2b_token = '$b2b'")->result();
        $incident = $this->db->query("SELECT count(*) as total from t_task_kejadian where b2b_token='$b2b' and submit_date = current_date")->row();


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
        AND submit_date >= date_trunc('month', current_date)")->result_array();

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
        AND submit_date >= date_trunc('month', current_date)")->result_array();

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

        $query = $this->db->query("SELECT to_char(submit_date, 'month') as mon,
        sum((condition = '1')::int) as secured,
        sum((condition = '2')::int) as warning,
        sum((condition = '3')::int) as critical
        from t_task_patrol_detail
        WHERE flag_process='3' and submit_date is not null AND submit_date >= date_trunc('year', current_date) AND b2b_token = '$b2b' 
        group by to_char(submit_date, 'mm'), mon
        order by to_char(submit_date, 'mm');")->result();

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
        WHERE submit_date is not null AND submit_date >= date_trunc('year', current_date) AND b2b_token = '$b2b' 
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

    public function getlocation($filter)
    {
        $b2b = $this->session->userdata('b2b_token');
        $filter = (int) $filter;
        // $arrfill = [
        //     "date_part( 'month', submit_date ) = date_part( 'month', current_date )",
        //     "date_part( 'month', submit_date ) = date_part( 'month', current_date )-1",
        //     "date_part( 'month', submit_date ) <= date_part( 'month', current_date )-2",
        //     "date_part( 'month', submit_date ) <= date_part( 'month', current_date )-5",
        // ];
        $query = $this->db->query("SELECT id_, lokasi, kategori
        FROM
        t_task_kejadian
        WHERE submit_date is not null AND b2b_token = '$b2b' 
        AND submit_date >= date_trunc( 'month', CURRENT_DATE - INTERVAL '$filter months' )")->result();
        $loc = array();
        if (count($query) == 0) {
            return array("loc" => [0, 0], "addr" => '', "icon" => 'no');
        }
        foreach ($query as $row) {
            $t = $this->getlonglat($row->lokasi);
            $loc['loc'][] = $t;
            $loc['addr'][] = $row->lokasi . ' <a target="_blank" href="' . base_url('task_kejadian/detail/' . $row->id_) . '"> Detail</a>';
            if ($t[0] == 0) {
                $loc['icon'][] = 'no';
            } else {
                switch ($row->kategori) {
                    case '1':
                        $icon = 'accident';
                        break;
                    case '2':
                        $icon = 'fire';
                        break;
                    case '3':
                        $icon = 'steal';
                        break;
                    case '4':
                        $icon = 'dead';
                        break;
                    default:
                        $icon = 'no';
                        break;
                }

                $loc['icon'][] = $icon;
            }
        }
        return $loc;
    }

    public function getcp()
    {
        $query = $this->db->query("SELECT *
        FROM
        check_point
        WHERE flag_disable='1'")->result();

        $loc = array();
        foreach ($query as $row) {
            $langlat[0] = $row->cp_long;
            $langlat[1] = $row->cp_lat;
            $loc['loc'][] = $langlat;
            $loc['addr'][] = $row->cp_id . ' - ' . $row->cp_name;
            $loc['icon'][] = 'marker';
        }
        return $loc;
    }


    public function absen_akumulasi()
    {
        $date = date("d-m-Y");
		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
        }
        $user = $this->db->query("SELECT  SUM(CASE WHEN u.status::TEXT = 'active' THEN 1 ELSE 0 END ) AS all FROM users u WHERE  u.b2b_token IN ? 
        and u.user_roles NOT IN ('cudo','superadmin')
         ",[$b2b_tokens_arr])->row();

        $absen = $this->db->query("SELECT 	
                                        x.date, 
                                sum(case when x.status = 1 then 1 else 0 end) 
                                from  (
                                    SELECT DISTINCT ON (date,status,user_id) * from log_absensi 
                                    WHERE 	
                                    b2b_token IN ?
                                and extract(year from submit_time::TIMESTAMP) = ?
                                AND extract(month from submit_time::TIMESTAMP) = ? 
                                ) x 
                                GROUP BY 
                                        date
                                ORDER BY date asc ",[$b2b_tokens_arr,date("Y"),date("m")])->result();

        $category = [];
        $val_absen = [];
        $val_unabsen = [];
        $acc_absen = [];//acumulation
        $acc_unabsen = [];//acumulation
        foreach ($absen as $key) {
           $category[] = $key->date;
           $val_absen[] = (int) $key->sum;
           $unabsen = (int)$user->all - (int)$key->sum;
            $val_unabsen[] = (int) $unabsen;
            if($acc_absen){
                $acc_absen[] =(int)$acc_absen[count($acc_absen)-1] + (int)$key->sum;
            }else{
                $acc_absen[] =(int) $key->sum;
            }
            if($acc_unabsen){
                $acc_unabsen[] =(int)$acc_unabsen[count($acc_unabsen)-1] + (int)$unabsen;
            }else{
                $acc_unabsen[] =(int) $unabsen;
            }
        }
        return compact("category","val_absen","acc_absen","val_unabsen","acc_unabsen");
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

    /* End of file x.php */
}
