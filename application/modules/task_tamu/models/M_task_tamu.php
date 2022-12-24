<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_task_tamu extends CI_Model
{

    public function fetch($id_penghuni, $month, $year)
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->where('b2b_token', $b2b);
        $fetch = $this->db->where('start_date::TEXT LIKE', "%$year-$month%");
        if ($id_penghuni != 0) {
            $fetch = $fetch->where('id_client', $id_penghuni);
        }
        $fetch = $fetch->order_by('start_date desc')->get('t_task_tamu')->result();
        $data = array();
        $i = 0;
        foreach ($fetch as $row) {
            $temp = array();
            $i++;
            $url = base_url('task_tamu/detail/' . $row->id_);
            $temp[] = $row->start_date . " " . $row->start_time;
            $dur = 0;
            // $duration = $row->end_time - $row->start_time;
            if (!empty($row->end_time)) {
                $start = new DateTime($row->start_time);
                $end = new DateTime($row->end_time);
                $dd = date_diff($end, $start);
                if ($dd->i > 0) {
                    $dur = $dd->h + 1;
                }
                $temp[] = $dur . " H";
            } else {
                $temp[] = 0;
            }
            $temp[] = $row->nama_tamu;
            $temp[] = $row->no_kavling_tujuan;
            $temp[] = $row->client_name;
            $temp[] = "<a title='Detail' target='_blank' href='$url'><i class='fa fa-user'></i></a>";
            $data[] = $temp;
        }
        return array('data' => $data);
    }
    public function fetchDetail($id)
    {
        // $fimage = $this->db->select('img_name as a')
        //     ->where('id_tamu_header', $id)->get('t_task_tamu_image')->result();
        // $image = array();
        // foreach ($fimage as $row) {
        //     $image['images'][] = $row->a;
        // }
        // $fdetail = $this->db->where('id_', $id)->get('t_task_tamu')->result_array();
        $b2b = $this->session->userdata('b2b_token');
        $fdetail = $this->db->query("SELECT a.id_,a.card_nfc,a.id_ as id_tamu,
                                    a.qrcode,
                                    a.nama_tamu,
                                    a.no_kavling_tujuan as tujuan_rumah,
                                    a.client_name,
                                    a.start_date,
                                    a.start_time,
                                    a.end_date,
                                    a.end_time,
                                    a.flag_process,
                                    a.remark,
                                    -- CONCAT('" . $this->config->item('base_url_api') . ('assets/images/tamu/foto_identitas/') . "' , b.img_name) as foto_identitas,
                                    -- CONCAT('" . $this->config->item('base_url_api') . ('assets/images/tamu/foto_pengunjung/') . "' , c.img_name) as foto_pengunjung,

                                    b.img_name as foto_identitas,
                                    c.img_name as foto_pengunjung,
                                    CONCAT(TO_CHAR(a.start_date, 'dd Mon yyyy'),' ', a.start_time) as datang,CONCAT(TO_CHAR(a.end_date, 'dd Mon yyyy'),' ', a.end_time) as kembali
                            FROM
                            t_task_tamu as a
                            LEFT JOIN t_task_tamu_image b ON b.id_tamu_header :: INTEGER = a.id_ and b.img_name LIKE '%foto_identitas%'
                            LEFT JOIN t_task_tamu_image c ON c.id_tamu_header :: INTEGER = a.id_ and c.img_name LIKE '%foto_pengunjung%'
                            WHERE a.id_ = '" . $id . "' AND a.b2b_token='$b2b' ORDER BY a.flag_process asc");

        if (($fdetail->num_rows()) == 0) {
            return FALSE;
        } else {
            return $fdetail->row();
        }
    }
}
                        
/* End of file x.php */
