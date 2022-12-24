<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_task_kejadian extends CI_Model
{

    public function fetch($month, $year)
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->select(['id_', 'remark', 'kategori_name', 'no_kavling_tujuan', 'submit_date', 'b2b_token'])
            ->order_by('id_', 'desc')
            ->where('b2b_token', $b2b)
            ->where('submit_date::TEXT LIKE', "%$year-$month%")
            ->get('t_task_kejadian')->result();

        // $fetch2 = $this->db->select(['id_','username', 'id_user', 'remark', 'kategori_name', 'no_kavling_tujuan', 'submit_date'])
        //     ->from('t_task_kejadian')
        //     ->join('users', 'users.id::TEXT=t_task_kejadian.id_user')
        //     ->where('t_task_kejadian.b2b_token', $b2b)
        //     ->where('submit_date::TEXT LIKE', "%$year-$month%")
        //     ->order_by('t_task_kejadian.id_', 'desc')
        //     ->get();

        // $fetch2 = $this->db->query("
        //     SELECT t_task_kejadian.id_, users.username, t_task_kejadian.id_user, remark, t_task_kejadian.kategori_name, t_task_kejadian.no_kavling_tujuan, t_task_kejadian.submit_date 
        //     FROM t_task_kejadian 
        //     JOIN users ON users.id = t_task_kejadian.id_user::INT
        //     WHERE t_task_kejadian.b2b_token = '$b2b' AND t_task_kejadian.submit_date::TEXT LIKE '%$year-$month%' 
        //     ORDER BY t_task_kejadian.id_ DESC
        // ")->result();
            
        // echo(var_dump($fetch2));
        // echo $this->db->last_query();
        // die();
        $data = array();
        foreach ($fetch as $row) {
            $temp = array();
            $url = base_url('task_kejadian/detail/' . $row->id_);
            $temp[] = $row->kategori_name;
            $temp[] = $row->remark;
            $temp[] = $row->no_kavling_tujuan;
            $temp[] = $row->username;
            $temp[] = $row->submit_date;
            $temp[] = "<a title='Detail' target='_blank' href='$url'><i class='fa fa-info-circle'></i></a>
            <a href='#' onclick='exportTaskKejadianPdf(this); return false;' data-id='".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8')."' title='Export Task Kejadian PDF' ><i class='fa fa-file-pdf'></i></a>";
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function fetchDetail($id)
    {
        $fimage = $this->db->select('img_name as a')
            ->where('id_kejadian', $id)->get('t_task_kejadian_image')->result();

        $image = array();
        foreach ($fimage as $row) {
            $image['images'][] = $row->a;
        }
        // $fdetail = $this->db->where('id_', $id)->get('t_task_kejadian')->result_array();
        $b2b = $this->session->userdata('b2b_token');
        $fdetail =        $this->db->query("SELECT A
        .id_,
        A.no_kavling_tujuan AS block,
        A.lokasi as lokasi,
        A.submit_date,
        A.submit_time,
        A.kategori,
        A.kategori_name AS status,
        A.remark as note,
        CONCAT ( A.submit_date,' ', b.submit_time)  AS TIME,
        -- CONCAT ( '" . $this->config->item('base_url_api') . ('assets/images/kejadian_v1/') . "', b.img_name ) AS image_1,
        -- CONCAT ( '" . $this->config->item('base_url_api') . ('assets/images/kejadian_v1/') . "', c.img_name ) AS image_2,
        -- CONCAT ( '" . $this->config->item('base_url_api') . ('assets/images/kejadian_v1/') . "', d.img_name ) AS image_3
        
        b.img_name AS image_1,
        c.img_name AS image_2,
        d.img_name AS image_3	
    FROM
        t_task_kejadian AS A 
        LEFT JOIN t_task_kejadian_image b ON A.id_ = b.id_kejadian :: INTEGER AND b.img_name LIKE'%image_kejadian_1%'
        LEFT JOIN t_task_kejadian_image c ON A.id_ = c.id_kejadian :: INTEGER AND c.img_name LIKE'%image_kejadian_2%'
        LEFT JOIN t_task_kejadian_image d ON A.id_ = d.id_kejadian :: INTEGER AND d.img_name LIKE'%image_kejadian_3%'
        WHERE A.id_='" . $id . "' AND A.b2b_token='$b2b'");


        if ($fdetail->num_rows() == 0) {
            return FALSE;
        } else {
            $temp = $fdetail->row();
            $apikey = 'AIzaSyCfY9TPZ31i6nu-oTLQWjuHaIt5dbc86o4';
            $string = str_replace(" ", "+", $temp->lokasi);
            $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $string . "&key=" . $apikey;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $details_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);


            // if ($response['status'] != 'OK') {
            //     return null;
            // }

            $geometry = $response['results'][0]['geometry'];

            $longitude = $geometry['location']['lng'];
            $latitude = $geometry['location']['lat'];

            $loc = array(
                'longitude' => $longitude,
                'latitude' => $latitude
            );

            $out['data'] = $temp;
            $out['loc'] = $loc;
            return $out;
        }
    }
}
