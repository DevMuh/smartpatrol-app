<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_log_absen extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_register_b2b');
    }


    public function fetch($month, $year)
    {
        $b2b = $this->session->userdata('b2b_token');
        $b2b_tokens = [];
        $b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
        foreach ($b2b_with_child as $key) {
            $b2b_tokens[] = $key->b2b_token;
        }

        $where ="";

        $keyword = $this->input->get('search[value]');

        if ($keyword) {
          $where .= " AND ( 	
                    users.full_name ILIKE '%" . $keyword . "%' OR
                    l.submit_time::text ILIKE '%" . $keyword . "%' 
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

        $fetch = $this->db->query("SELECT
        l.id,
        l.date,
        l.time,
        l.status,
        l.submit_time,
        l.img_name,
        l.is_overtime,
        t_shift.shift_name,
        users.full_name
        FROM
        log_absensi l
        INNER JOIN t_shift ON t_shift.id_ = l.id_shift::integer
        INNER JOIN users ON users.id = l.user_id
        WHERE 
        l.b2b_token in ?
        AND l.date LIKE '%$month-$year%'
        $where
        ORDER BY l.submit_time DESC
        $limit $offset
        ",[$b2b_tokens])->result();

        $full_count = $this->db->query("SELECT l.id FROM log_absensi l
        INNER JOIN t_shift ON t_shift.id_ = l.id_shift::integer
        INNER JOIN users ON users.id = l.user_id
        WHERE 
        l.b2b_token in ?
        AND l.date LIKE '%$month-$year%'
        $where
        ORDER BY l.submit_time DESC
        ",[$b2b_tokens])->num_rows();

        $data = array();
        $i = 0;
        
        $status_server = false;
        if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
            $status_server = true;
        }else{
            $status_server = false;
        }

        foreach ($fetch as $row) {
            $temp = array();
            $image = $this->config->item("base_url_api") . "assets/images/absen/" . $row->img_name;
            
            // if(@getimagesize($image_cudo)){
            //     //seu código...
            // }else {
            //     $image_cudo = base_url("assets/apps/assets/dist/img/no-image.jpg");
            // }

            $new_img = "";

            if ($status_server) {
                $this->load->library('curl');
                $image_cudo = $this->config->item("base_url_server_cudo") . "assets/absen/" . $row->img_name;
                $result = $this->curl->simple_get($image_cudo);
                
                if($result != ""){
                    $new_img = $image_cudo;
                }elseif(@getimagesize($image)){
                    $new_img = $image;
                }else{
                    $new_img = base_url("assets/apps/assets/dist/img/no-image.jpg");
                }
            } else {
                if(@getimagesize($image)){
                    $new_img = $image;
                }else{
                    $new_img = base_url("assets/apps/assets/dist/img/no-image.jpg");
                }
            }

            $status = "";
            if ($row->status == 1) {
                $status = 'Masuk';
            }else if ($row->status == 2 && $row->is_overtime == 't') {
                $status = 'Lembur';
            }else {
                $status = 'Pulang';
            }
        
            //if (!$row->img_name) $image = base_url("assets/apps/assets/dist/img/no-image.jpg");
            $temp[] = $i + 1;
            if($this->session->userdata("user_roles") == 'admin' || $this->session->userdata("user_roles") == 'cudo' || $this->session->userdata("user_roles") == 'superadmin'){
                $temp[] = "<center><button data-toggle='modal' data-target='#editModal' onclick='edit($row->id)' class='btn mybt'><span class='typcn typcn-pencil'></span></button></center>";
            }
            $temp[] = $row->full_name;
            $temp[] = $row->shift_name;
            $temp[] = $row->date;
            $temp[] = $row->time;
            $temp[] = $row->submit_time;
            // $temp[] = $row->status == 1 ? 'Masuk' : 'Pulang';
            $temp[] = $status;
            //$temp[] = '<img onclick="modalImg(this)" class="myImage" style="height:50px; width: 50px;object-fit:contain" src="' . $image . '" onError="this.onerror=null;this.src=`' . $image_cudo . '`">';
            $temp[] = '<img onclick="modalImg(this)" class="myImage" style="height:50px; width: 50px;object-fit:contain" src="' . $new_img . '">';
            $i++;
            $data[] = $temp;
        }

        $payload['data'] =  $data;
        $payload['recordsTotal'] = $payload['recordsFiltered'] =  $full_count;
        return $payload;
    }

    public function get_by_id($id)
    {
        $fetch = $this->db->query("SELECT
        l.id,
        l.date,
        l.time,
        l.status,
        l.submit_time,
        l.img_name,
        l.user_id,
        l.id_shift as shift_id,
        t_shift.shift_name,
        users.full_name
        FROM
        log_absensi l
        INNER JOIN t_shift ON t_shift.id_ = l.id_shift::integer
        INNER JOIN users ON users.id = l.user_id
        WHERE 
        l.id = ?
        ",$id);

        return $fetch;
    }

    public function check_historyid_exist($id)
    {
        $sql = $this->db->query("SELECT * FROM log_absensi WHERE id = ?", $id)->num_rows();

        return $sql;
    }

    public function update($id)
    {
        $shift_id       = $this->input->post("shift_id");
        $submit_date    = $this->input->post("edit_tanggal_shift");
        $submit_time    = $this->input->post("edit_waktu_shift");
        $user_id        = $this->session->userdata('id');

        $new_submit_time = $submit_date." ".$submit_time;
        $d = strtotime($submit_date);
		$new_submit_date = date('d-m-Y',$d);

        $sql = $this->db->query("SELECT 
                                    waktu_start, 
                                    waktu_end, 
                                    shift_name,  
                                    is_same_day_shift,
                                    max_days
                                FROM t_shift WHERE id_ = ?",$shift_id)->row();

        $data = array(
            'date'              => $new_submit_date,
            'time'              => $submit_time,
            'submit_time'       => $new_submit_time,
            'id_shift'          => $shift_id,
            "start_shift"       => $sql->waktu_start,
            "end_shift"         => $sql->waktu_end,
            "shift_name"        => $sql->shift_name,
            "is_same_day_shift" => $sql->is_same_day_shift,
            "max_days"          => $sql->max_days,
            "updated_by"        => $user_id,
            "updated_at"        => date("Y-m-d H:i:s")
        );

        return $this->db->set($data)->where('id', $id)->update('log_absensi');
    }

    public function get_list_shift($user_id)
    {
        $sql_get_user = $this->db->query("SELECT * FROM users WHERE id = ?",$user_id)->row();

        //$b2b = $this->session->userdata('b2b_token');
        $b2b = $sql_get_user->b2b_token;

        $b2b_tokens = [];
        $b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
        foreach ($b2b_with_child as $key) {
            $b2b_tokens[] = $key->b2b_token;
        }

        $sql = $this->db->query("SELECT * FROM t_shift WHERE flag_enable = 1 AND b2b_token in ? ",[$b2b_tokens])->result();

        $data = array();
        foreach ($sql as $row) {
            $temp = array();
            $temp["shift_id"] = $row->id_;
            $temp["shift_name"] = $row->shift_name;
            $temp["waktu_start"] = $row->waktu_start;
            $temp["waktu_end"] = $row->waktu_end;
            array_push($data,$temp);
        }

        return $data;
    }
}
                        
/* End of file x.php */
