<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_register_b2b extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db_users = $this->load->database('db_users', TRUE);
    }

    public function fetch()
    {
        $fetch = $this->db->query("SELECT
            a1.id_, 
            a1.path_logo,
            a1.b2b_token as a,
            a1.title_nm as b,
            a1.tgl_join as c,
            a1.alamat as d,
            a1.phone as e,
            a1.other_data->>'hidden_feature' as hidden_feature,
            a1.other_data->>'status_schedule' as status_schedule,
            a1.parent_id,
            a1.level,
            a1.domain,
            a2.title_nm as parent_title
            FROM m_register_b2b a1
            LEFT JOIN m_register_b2b a2
            ON a1.parent_id = a2.b2b_token
            WHERE
            a1.flag_active::TEXT = '1'
        ")->result();
        $data = array();
        foreach ($fetch as $row) {
            $image_cudo = $this->config->item("base_url_server_cudo") . "assets/b2b/" . $row->path_logo;
            if(@getimagesize($image_cudo)){
                
            }else {
                $image_cudo = base_url("assets/apps/assets/dist/img/no-image.jpg");
            }
            if ($row->status_schedule == "true") {
                $status_schedule = "Yes";
            }else{
                $status_schedule = "No";
            }
            $row->path_logo_ref = $image_cudo;
            $temp = array();
            $url = base_url('register_b2b/detail/' . $row->a);
            if ($row->level == '1') $row->level_string = 'Pusat';
            if ($row->level == '2') $row->level_string = 'Cabang';
            if ($row->level == '3') $row->level_string = 'Project';
            $temp[] = $row->b;
            $temp[] = $row->level_string;
            $temp[] = $row->parent_title;
            $temp[] = $row->c;
            //$temp[] = $row->d;
            $temp[] = $row->domain;
            $temp[] = $row->e;
            $temp[] = implode(",", json_decode($row->hidden_feature));
            $temp[] = $status_schedule;
            $temp[] = "<button data-toggle='modal' data-target='#editModal' class='btn mybt' onclick='edit(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ")' ><span class='typcn typcn-pencil'></span></button> 
            <button data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id_)' class='btn mybt'><span class='typcn typcn-trash'></span></button> 
            <a target='_blank' href='$url' class='btn mybt'><i class='fa fa-info-circle'></i></a>";
            //$temp[] = "<a target='_blank' href='$url'><i class='fa fa-info-circle'></i></a>";
            $data[] = $temp;
        }
        return array('data' => $data);
    }
    public function fetchDetail($id)
    {
        $fetch = $this->db->where('flag_active', '1')->where('b2b_token', $id)->select([
            'id_route as id',
            'cluster_name as a', 'flag_active as b', 'interval_option as c', 'description as d'
        ])->get('cluster_route')->result();
        $data = array();
        $i = 0;
        foreach ($fetch as $row) {
            $temp = array();
            $i++;
            $temp[] = $i;
            $temp[] = $row->a;
            $temp[] = $row->b == 1 ? 'Active' : 'Idle';
            if ($row->c == 1) {
                $temp[] = 'Jam';
            } else if ($row->c == 2) {
                $temp[] = 'Week';
            } else if ($row->c == 3) {
                $temp[] = 'Month';
            } else {
                $temp[] = 'Idle';
            }
            $temp[] = $row->d;
            $temp[] = "<button data-toggle='modal' data-target='#editModal' onclick='edit($row->id)' class='btn mybt'><span class='typcn typcn-pencil'></span></button> <button data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id)' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function add_cluster()
    {
        $this->form_validation->set_rules('cluster_name', 'Title Name', 'required');
        $this->form_validation->set_rules('flag_active', 'Flag', 'required');
        $this->form_validation->set_rules('interval_option', 'Interval', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('cluster_name'), form_error('flag_active'), form_error('interval_option'), form_error('description')];
        } else {
            $data['b2b_token'] = $this->input->post('b2b');
            $data['cluster_name'] = $this->input->post('cluster_name');
            $data['interval_option'] = $this->input->post('flag_active');
            $data['flag_active'] = $this->input->post('flag_active');
            $data['description'] = $this->input->post('description');

            $this->db->insert('cluster_route', $data);
            return 1;
        }
    }

    public function edit_cluster()
    {
        $this->form_validation->set_rules('cluster_name', 'Title Name', 'required');
        $this->form_validation->set_rules('flag_active', 'Flag', 'required');
        $this->form_validation->set_rules('interval_option', 'Interval', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('cluster_name'), form_error('flag_active'), form_error('interval_option'), form_error('description')];
        } else {
            $id = $this->input->post('eid');
            $data['cluster_name'] = $this->input->post('cluster_name');
            $data['flag_active'] = $this->input->post('flag_active');
            $data['interval_option'] = $this->input->post('flag_active');
            $data['description'] = $this->input->post('description');

            $this->db->where('id_route', $id)->update('cluster_route', $data);
            return 1;
        }
    }

    public function hapus_cluster()
    {
        $id = $this->input->post('did');
        $data['flag_active'] = 0;
        $this->db->where('id_route', $id)->update('cluster_route', $data);
        return 1;
    }

    public function add_b2b()
    {

        $config['upload_path']   = './assets/apps/images';
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['file_name']     = date("Ymdhis");
        $config['overwrite']     = true;
        $config['max_size']      = '13032';

        $this->load->library('upload', $config, 'logo');
        if ($this->logo->do_upload('logo')) {
            $data['path_logo'] = $this->logo->data('file_name');
        }

        // $this->form_validation->set_rules('title_nm', 'Name', 'required');
        // $this->form_validation->set_rules('alamat', 'Address', 'required');
        // $this->form_validation->set_rules('phone', 'Phone', 'required|integer|min_length[8]|max_length[15]');

        // if ($this->form_validation->run() == FALSE) {
        //     return [form_error('title_nm'), form_error('alamat'), form_error('phone')];
        // } else {

        $data['title_nm'] = $this->input->post('title_nm');
        $data['flag_active'] = 1;
        $data['alamat'] = $this->input->post('alamat');
        $data['phone'] = $this->input->post('phone');
        $data['domain'] = $this->input->post('domain');
        $data['other_data'] = json_encode([
            'hidden_feature' =>  $this->input->post('hidden_feature') ? explode(",", $this->input->post('hidden_feature')) : null,
            'status_schedule' => $this->input->post('status_schedule') ? true : false
        ]);
        $data['level'] = $this->input->post('level');
        if ($data['level'] == 1) {
            $data['parent_id'] = null;
        } elseif ($data['level'] == 2) {
            $data['parent_id'] = $this->input->post('pusat');
        } elseif ($data['level'] == 3) {
            $data['parent_id'] = $this->input->post('cabang');
        }
        $this->db_users->insert('m_register_b2b', $data);
        $this->db->insert('m_register_b2b', $data);
        return 1;
        // }
    }

    public function edit_b2b()
    {
        $config['upload_path']   = './assets/apps/images';
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['file_name']     = date("Ymdhis");
        $config['overwrite']     = true;
        $config['max_size']      = '13032';

        $this->load->library('upload', $config, 'logo');
        if ($this->logo->do_upload('logo')) {
            $data['path_logo'] = $this->logo->data('file_name');
        }

        // $this->form_validation->set_rules('etitle_nm', 'Name', 'required');
        // $this->form_validation->set_rules('ealamat', 'Address', 'required');
        // $this->form_validation->set_rules('ephone', 'Phone', 'required|integer|min_length[8]|max_length[15]');

        // if ($this->form_validation->run() == FALSE) {
        //     return [form_error('etitle_nm'), form_error('ealamat'), form_error('ephone')];
        // } else {
        $id = $this->input->post('eid');
        $detail_b2b = $this->db->get_where("m_register_b2b", ['id_' => $id])->row();
        $other_data = json_decode($detail_b2b->other_data, true);
        $data['title_nm'] = $this->input->post('etitle_nm');
        $data['alamat'] = $this->input->post('ealamat');
        $data['phone'] = $this->input->post('ephone');
        $data['domain'] = $this->input->post('edomain');
        $other_data["hidden_feature"] = $this->input->post('hidden_feature') ? explode(",", $this->input->post('hidden_feature')) : null;
        $other_data["status_schedule"] = $this->input->post('status_schedule') ? true : false;
        $data['other_data'] = json_encode($other_data);
        $data['level'] = $this->input->post('elevel');
        if ($data['level'] == 1) {
            $data['parent_id'] = null;
        } elseif ($data['level'] == 2) {
            $data['parent_id'] = $this->input->post('epusat');
        } elseif ($data['level'] == 3) {
            $data['parent_id'] = $this->input->post('ecabang');
        }
        $this->db_users->set($data)->where('id_', $id)->update('m_register_b2b');
        return $this->db->set($data)->where('id_', $id)->update('m_register_b2b');

        // }
    }



    public function hapus_b2b()
    {
        $id = $this->input->post('did');
        $data['flag_active'] = 0;
        $this->db_users->where('id_', $id)->update('m_register_b2b', $data);
        $this->db->where('id_', $id)->update('m_register_b2b', $data);
        return 1;
    }

    public function edit_feature()
    {
        # code...
    }

    public function subB2B($parent_id = null)
    {
        $query_choose_b2b = $this->db->where('flag_active', '1')->order_by("title_nm", "ASC")->get_where("m_register_b2b", ["parent_id" => $parent_id])->result();
        $arr = array();
        foreach ($query_choose_b2b as $row) {
            $arr[] = $row;
            $sub = $this->subB2B($row->b2b_token);
            if ($sub) {
                foreach ($sub as $s) {
                    $arr[] = $s;
                }
            }
        }
        return $arr;
    }

    public function b2b_with_child($b2b)
    {
        $b2b = $this->db->get_where("m_register_b2b", ["b2b_token" => $b2b])->row();
        $subB2B = $this->subB2B($b2b->b2b_token);
        $subB2B[] = $b2b;
        return $subB2B;
    }
}
/* End of file x.php */
