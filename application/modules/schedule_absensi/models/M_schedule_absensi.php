<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_schedule_absensi extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db_users = $this->load->database('db_users', TRUE);
    }

    public function fetch($month, $year)
    {
        $b2b = $this->session->userdata('b2b_token');
        $b2b_tokens = [];
        $b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
        foreach ($b2b_with_child as $key) {
            $b2b_tokens[] = $key->b2b_token;
        }

        $fetch = $this->db->query("SELECT 
            a.*,
            TO_CHAR(a.date, 'mm/dd/yyyy') AS schedule_date,
            b.kode_shift,
            b.shift_name,
            b.waktu_start AS start_shift,
            b.waktu_end AS end_shift,
            c.full_name AS full_name
        FROM m_schedule_absensi a
        LEFT JOIN t_shift b ON a.shift_id=b.id_ 
        LEFT JOIN users c ON a.user_id=c.id
        WHERE a.b2b_token in ? AND a.other_data->>'is_deleted' != 'true'
        AND extract(year from date::TIMESTAMP) = '$year'
        AND extract(month from date::TIMESTAMP) = '$month'
        ",[$b2b_tokens])->result();

        $data = array();
        foreach ($fetch as $row) {
            //$h_plus4 = date("Y-m-d", strtotime("+ 4 day"));
            $h_plus4 = date("Y-m-d");

            $display = "";
            if ($row->date <= $h_plus4) {
                $display = "display:none";
            }

            $temp = array();
            $temp[] = $row->date;
            $temp[] = $row->full_name;
            $temp[] = $row->kode_shift;
            $temp[] = $row->shift_name;
            $temp[] = $row->start_shift;
            $temp[] = $row->end_shift;
            $temp[] = "<center><button data-toggle='modal' data-target='#editModal' class='btn mybt' onclick='edit(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ")' style='".$display."'><span class='typcn typcn-pencil'></span></button> 
            <button data-toggle='modal' data-target='#hapusModal' onclick='hapus(".$row->id.")' class='btn mybt' style='".$display."'><span class='typcn typcn-trash'></span></button> 
            </center>";
            //$temp[] = "<a target='_blank' href='$url'><i class='fa fa-info-circle'></i></a>";
            $data[] = $temp;
        }
        return array('data' => $data);
    }
    public function fetch2($month, $year)
    {
        $b2b = $this->session->userdata('b2b_token');
        $b2b_tokens = [];
        $b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
        foreach ($b2b_with_child as $key) {
            $b2b_tokens[] = $key->b2b_token;
        }

        $fetch = $this->db->query("SELECT 
            a.*,
            TO_CHAR(a.date, 'mm/dd/yyyy') AS schedule_date,
            b.kode_shift,
            b.shift_name,
            b.waktu_start AS start_shift,
            b.waktu_end AS end_shift,
            c.full_name AS full_name
        FROM m_schedule_absensi a
        LEFT JOIN t_shift b ON a.shift_id=b.id_ 
        LEFT JOIN users c ON a.user_id=c.id
        WHERE a.b2b_token in ? AND a.other_data->>'is_deleted' != 'true'
        AND extract(year from a.date::TIMESTAMP) = '$year'
        AND extract(month from a.date::TIMESTAMP) = '$month'
        ",[$b2b_tokens])->result();

        return $fetch;
    }
    public function fetch_column($month, $year)
    {
        //$year = date("Y");
        $month1 = date('n');
        //$month1 = date('m');

        $b2b = $this->session->userdata('b2b_token');
        $b2b_tokens = [];
        $b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
        foreach ($b2b_with_child as $key) {
            $b2b_tokens[] = $key->b2b_token;
        }

        $fetch = $this->fetch2($month, $year);

        // echo '<pre>';
        // print_r($fetch);
        // echo '</pre>';

        // $data_tgl = $this->db->query("SELECT 
        //                 date
        //             FROM m_schedule_absensi
        //             WHERE b2b_token in ? AND other_data->>'is_deleted' != 'true'
        //             AND extract(year from date::TIMESTAMP) = '$year'
        //             AND extract(month from date::TIMESTAMP) = '$month1'
        //             GROUP BY date ORDER BY date ASC
        //         ",[$b2b_tokens])->result();

        $data_user = $this->db->query("SELECT 
                        a.user_id,
	                    c.full_name AS full_name
                    FROM m_schedule_absensi a
                    LEFT JOIN users c ON a.user_id=c.id
                    WHERE a.b2b_token in ? AND a.other_data->>'is_deleted' != 'true'
                    AND extract(year from a.date::TIMESTAMP) = '$year'
                    AND extract(month from a.date::TIMESTAMP) = '$month'
                    GROUP BY a.user_id, c.full_name 
                    ORDER BY 
                    a.user_id
                    --, c.full_name 
                    ASC
                ",[$b2b_tokens])->result();

        $data = array();
        $no = 1;
        $key = 1;
        foreach ($data_user as $value) {
            $user = $value->full_name;
            //echo $user;

            $temp = array();
            $temp[] = $no;
            $temp[] = $user;

            $d=cal_days_in_month(CAL_GREGORIAN,$month1,$year);

            for ($i=1; $i <= $d; $i++) { 
                $j = $this->countDigits($i);

                if ($j < 2) {
                    $tgl = "0".$i;
                }else{
                    $tgl = $i;
                }

                $data_shift = '<center><span class="badge badge-danger">Off</span></center>';

                foreach ($fetch as $val) {
                    $newDate = substr($val->date,8);
                    if ($user == $val->full_name && $tgl == $newDate) {
                        //$temp[] = $val->date." ".$val->shift_name." ".$tgl;
                        $data_shift = '<center><span class="badge badge-primary">'.$val->kode_shift.'</span></center>';                        
                    }
                    $key++;
                }
                
                $temp[] = $data_shift;
                
            }
            $data[] = $temp;
            $no++;
        }

        return array('data' => $data);

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';

        // $d=cal_days_in_month(CAL_GREGORIAN,$month,$year);

        // for ($i=1; $i <= $d; $i++) { 
        //     $j = $this->countDigits($i);

        //     if ($j < 2) {
        //         $tgl = "0".$i;
        //     }else{
        //         $tgl = $i;
        //     }

        //     foreach ($fetch as $row) {
        //         echo '<pre>';
        //         print_r($row);
        //         echo '</pre>';
        //     }
        // }
    }
    function countDigits($MyNum){
        $MyNum = (int)$MyNum;
        if($MyNum != 0)
          return 1 + $this->countDigits($MyNum/10);
        else
          return 0;
    }
}