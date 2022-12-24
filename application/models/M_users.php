<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_users extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['M_register_b2b']);
        $this->db_users = $this->load->database('db_users', TRUE);
    }
    public function validasi_save()
    {
        return [
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'fullname',
                'label' => 'Full Name',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'user_roles',
                'label' => 'User Roles',
                'rules' => 'required|rtrim',
            ],

        ];
    }

    public function is_username_exist($username = NULL, $wihout_this_id = NULL)
    {
        $and_without_this_id = "";
        if ($wihout_this_id) {
            $and_without_this_id = " AND id != '$wihout_this_id' ";
        }
        return $this->db->query("SELECT id FROM users WHERE  username = ? " . $and_without_this_id, [$username])->num_rows();
    }

    public function is_phone_number_exist($no_tlp = NULL, $wihout_this_id = NULL)
    {
        $and_without_this_id = "";
        if ($wihout_this_id) {
            $and_without_this_id = " AND id != '$wihout_this_id' ";
        }
        return $this->db->query("SELECT id FROM users WHERE no_tlp = ? " . $and_without_this_id, [$no_tlp])->num_rows();
    }

    public function update_other_data($data, $id)
    {
        return $this->db->query("UPDATE users SET other_data = other_data || ? WHERE id = ? ", [$data, $id]);
    }

    public function get_data()
    {
        $b2b = $this->session->userdata('b2b_token');
        $b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}
        return  $this->db->query("SELECT 
        u.*,
        other_data->>'mobile_app_version' as mobile_app_version,
        u.other_data->>'pin' AS pin,
        (CASE WHEN r.flag_active::TEXT != '1' THEN '-' ELSE r.nama_regu END) as nama_regu,
        (CASE WHEN s.flag_enable::TEXT != '1' AND r.flag_active::TEXT != '1' THEN '-' ELSE s.shift_name END) as shift_name
        FROM users u 
        LEFT JOIN t_regu r ON u.regu::TEXT = r.id::TEXT  
        LEFT JOIN t_shift s ON r.id_shift::TEXT = s.id_::TEXT  
        WHERE u.b2b_token in ?
        and u.user_roles NOT IN ('cudo','superadmin')
        ",[$b2b_tokens_arr])->result();

    }

    public function fetch()
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->get_data();
        $cabang = $this->db->where('b2b_token', $b2b)->get('t_kantor_cabang')->result();
        $project = $this->db->where('b2b_token', $b2b)->get('t_project')->result();
        $data = array();
        foreach ($fetch as $row) {
            $device_info = json_decode($row->device_info, true);
            $other_data = json_decode($row->other_data, true);
            $temp = array();
            $temp[] = $row->id;
            $temp[] = $row->payroll_id ?: '-';
            $temp[] = $row->username;
            $temp[] = $row->full_name;
            $temp[] = $row->nama_regu ?: '-';
            $temp[] = $row->shift_name ?: '-';
            $temp[] = $other_data['position'];
            $temp[] = $row->user_roles;
            $temp[] = $row->no_tlp;
            $temp[] =  (strlen($device_info['X-Devicename']) > 19) ? substr($device_info['X-Devicename'], 0, 19) . '...' : $device_info['X-Devicename'];
            $temp[] = $row->mobile_app_version;
            $temp[] = $row->pin;
            if ($row->active_at == 1) {
                if (count($project) > 0) {
                    foreach ($project as $prj) {
                        if ($row->id_project == $prj->id) {
                            $temp[] = 'Project ' . $prj->nama_project;
                            break;
                        }
                    }
                } else {
                    $temp[] = 'Project';
                }
            } elseif ($row->active_at == 2) {
                if (count($cabang) > 0) {
                    foreach ($cabang as $cbg) {
                        if ($row->id_cabang == $cbg->id) {
                            $temp[] = 'Cabang ' . $cbg->nama;
                            break;
                        }
                    }
                } else {
                    $temp[] = 'Cabang';
                }
            } else {
                $temp[] = 'Pusat';
            }
            if (strtolower($row->status) == 'active') {
                $temp[] = '<div id="active" onclick="changeStatus(\'' . $row->id . ' \')">
                                <div class="switch-button switch-button-lg">
                                    <label for="item3"></label>
                                </div>
                            </div>';
            } else {
                $temp[] = '<div id="inactive" onclick="changeStatus(\'' . $row->id . ' \')">
                                <div class="switch-button switch-button-lg">
                                    <span><label for="item3"></label></span>
                                </div>
                            </div>';
            }
            $btn = $device_info ? "<button onclick='logout_manual($row->id)' title='Logout Manually?' class='btn js-logout'><span class='typcn typcn-chevron-left-outline'></span></button> " : "";
            $btn .= "<button onclick='visitModal($row->id)' title='Set Visit' class='btn mybt'><span class='fa fa-map-marker-alt'></span></button>";
            $btn .= "<button onclick='edit($row->id)' class='btn mybt'><span class='typcn typcn-pencil'></span></button> <button onclick='deleteConfirm(\"" . base_url('users/delete/') . $row->id . "\", \"" . $row->username . "\")' class='btn mybt'><span class='typcn typcn-trash'></span></button> <button onclick='generatePin($row->id)' title='Generate Pin' class='btn mybt' id='btnGenPin$row->id'><span class='fa fa-key'></span></button>";
            $temp[] =  $btn;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function save()
    {
        $post = $this->input->post();
        date_default_timezone_set("Asia/Jakarta");
        $date   = date("Y-m-d H:i:s");
        $b2b = $this->db->get_where("m_register_b2b", ['b2b_token' => $this->session->userdata('b2b_token')])->row();
        $visitb2b = [];
        $visitb2b[] = $this->db->get_where("m_register_b2b", ['b2b_token' => $b2b->parent_id])->row()->b2b_token;
        foreach ($this->db->get_where("m_register_b2b", ['parent_id' => $b2b->parent_id])->result() as $a) {
            $visitb2b[] = $a->b2b_token;
        }
        $data = [
            'payroll_id'      => $post['payroll_id'],
            'username'      => $post['username'],
            'full_name'     => $post['fullname'],
            'user_roles'    => $post['user_roles'],
            'no_tlp'    => $post['no_tlp'],
            'b2b_token'     => $this->session->userdata('b2b_token'),
            'password'      => md5($post['password']),
            'status'        => 'inactive',
            'created_at'     => $date,
            'other_data'    => json_encode([
                "org" => $post['cabb'],
                "position" => $post['position'],
                "visitb2b" => $visitb2b,
                "parent_b2b_token" => $post['user_sub_org'],
            ])
        ];
        $this->db_users->insert('users', $data);
        return $this->db->insert('users', $data);
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('users', ['id' => $id]);
    }
    public function get_by_regu($regu)
    {
        return $this->db->get_where('users', ['regu' => $regu]);
    }

    public function update($id)
    {
        $post = $this->input->post();
        $idb2bnew = $this->input->post("user_sub_org");
        $getb2b = $this->db->get_where("m_register_b2b",array('b2b_token'=>''.$idb2bnew.''))->row_array();
        $updateb2b = $getb2b["b2b_token"];
        if ($idb2bnew =="choose") {
            $updateb2b = $post["b2b"];
        }
        $data = [
            'payroll_id'  => $post['payroll_id'],
            'username'  => $post['username'],
            'full_name' => $post['fullname'],
            'user_roles' => $post['user_roles'],
            'b2b_token' => $updateb2b,
            'no_tlp' => $post['no_tlp'],
            'other_data'      => json_encode(
                [
                    "position" => $post['position'],
                    "b2b_token_previous" => $post["b2b"],
                    ]
                    )
                ];
                // var_dump($data);die;
        if ($post['password']) {
            $data['password'] = md5($post['password']);
        }
        $this->db_users->set($data)->where('id', $id)->update('users');
        return $this->db->set($data)->where('id', $id)->update('users');
    }

    public function delete($id)
    {
        $this->db_users->delete('users', ['id' => $id]);
        return $this->db->delete('users', ['id' => $id]);
    }

    public function getconf()
    {
        $b2b = $this->session->userdata('b2b_token');
        $act = $this->input->post('act');
        $data = array();
        if ($act == 1) {
            $q = $this->db->where('b2b_token', $b2b)->get('t_project')->result();
            $i = 0;
            foreach ($q as $row) {
                $data[$i]['id'] = $row->id;
                $data[$i]['nama'] = $row->nama_project;
                $i++;
            }
        } elseif ($act == 2) {
            $q = $this->db->where('b2b_token', $b2b)->get('t_kantor_cabang')->result();
            $i = 0;
            foreach ($q as $row) {
                $data[$i]['id'] = $row->id;
                $data[$i]['nama'] = $row->nama;
                $i++;
            }
        }
        return $data;
    }
}
