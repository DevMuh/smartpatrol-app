<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_regu extends CI_Model
{

    public function fetch()
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->query('SELECT
        "public".t_regu."id",
        "public".t_regu.nama_regu,
        "public".t_shift.shift_name,
        "public".t_shift.waktu_start,
        "public".t_shift.waktu_end,
        "public".t_regu.flag_active,
        "public".t_regu.id_cabang,
        "public".t_regu.id_project,
        "public".t_regu.active_at
        FROM
        "public".t_regu
        LEFT JOIN "public".t_shift ON "public".t_shift.id_::text = "public".t_regu.id_shift::TEXT
        WHERE "public".t_regu.b2b_token = \'' . $b2b . '\'
         AND "public".t_regu.flag_active = \'1\' ORDER BY "public".t_regu."id" ASC
        ')->result();
        $cabang = $this->db->where('b2b_token', $b2b)->get('t_kantor_cabang')->result();
        $project = $this->db->where('b2b_token', $b2b)->get('t_project')->result();
        $data = array();
        $i = 1;

        foreach ($fetch as $row) {
            $temp = array();
            $url = base_url('regu/detail?q=' . $row->id);
            $temp[] = $i;
            $temp[] = $row->nama_regu;
            $temp[] = $row->shift_name;
            $temp[] = $row->waktu_start;
            $temp[] = $row->waktu_end;
            $temp[5] = "-";
            if ($row->active_at == 1) {
                if ($project) {
                    foreach ($project as $prj) {
                        if ($row->id_project == $prj->id) {
                            $temp[5] = 'Project ' . $prj->nama_project;
                            break;
                        }
                    }
                } else {
                    $temp[5] = 'Project';
                }
            } else if ($row->active_at == 2) {
                if ($cabang) {
                    foreach ($cabang as $cbg) {
                        if ($row->id_cabang == $cbg->id) {
                            $temp[5] = 'Cabang ' . $cbg->nama;
                            break;
                        }
                    }
                } else {
                    $temp[5] = 'Cabang';
                }
            } else {
                $temp[5] = 'Pusat';
            }
            $temp[] = $row->flag_active == 1 ? 'Active' : 'Inactive';
            $temp[] = "<button data-toggle='modal' data-target='#editModal' onclick='edit($row->id)' class='btn mybt'><span class='typcn typcn-pencil'></span></button> <button data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id)' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $temp[] = "<button onclick='changeAc($row->id)' class='btn mybt'><i style='color: #b11616' class='fa fa-cog'></i></button> <a data-toggle='tooltip' title='Ganti anggota regu' target='_blank' href='$url'>&nbsp;&nbsp;&nbsp;<i class='fa fa-users'></i></a>";
            $i++;
            $data[] = $temp;
        }

        return array('data' => $data);
    }

    public function fetch2()
    {
        $b2b_token = $this->session->userdata('b2b_token');
        $get_b2b_with_parent =  modules::load('register_b2b/register_b2b')->get_b2b_with_parent($b2b_token);
        $b2b = [];
        foreach ($get_b2b_with_parent as $key) {
            if ($key->b2b_token !== $b2b_token) {
                array_push($b2b, $key->b2b_token);
            }
        }
        $b2b[] = $b2b_token;
        $this->db->join('m_register_b2b', 'm_register_b2b.b2b_token = users.b2b_token', 'left');
        $this->db->join('t_regu', 't_regu.id = users.regu', 'LEFT');
        $query = $this->db->select("*, users.id as user_id")
            ->where("(users.user_roles = 'danru' OR users.user_roles = 'anggota' )")
            ->where('users.regu', null)
            ->or_where('t_regu.flag_active !=', '1')
            ->where("users.b2b_token IN ('" . implode("','", $b2b) . "')")
            ->get('users')->result();
        $data = array();
        foreach ($query as $row) {
            $temp = array();
            $temp[] = $row->user_id;
            $temp[] = $row->full_name;
            $temp[] = $row->user_roles;
            $temp[] = $row->no_tlp;
            $temp[] = $row->title_nm;
            $temp[] = '<td><button class="btn mybt" onclick="addR($(this).parent()[0])"><i class="fa fa-arrow-right"></i></button></td>';
            $data[] = $temp;
        }
        return array("data" => $data);
    }

    public function fetch3()
    {
        $this->db->join('m_register_b2b', 'm_register_b2b.b2b_token = users.b2b_token', 'INNER');
        $this->db->join('t_regu', 't_regu.id = users.regu', 'INNER');
        $query = $this->db->select("*, users.id as user_id")->get_where('users', ['users.regu' => $this->session->userdata('id_regu'), 't_regu.flag_active' => '1'])->result();
        $data = array();
        foreach ($query as $row) {
            $temp = array();
            $temp[] = $row->user_id;
            $temp[] = $row->full_name;
            $temp[] = $row->user_roles;
            $temp[] = $row->no_tlp;
            $temp[] = $row->title_nm;
            $temp[] = '<td><button class="btn mybt" onclick="removeR($(this).parent()[0])"><i class="fa fa-arrow-left"></i></button></td>';
            $data[] = $temp;
        }
        return array("data" => $data);
    }

    public function updateRegu()
    {
        $now = $this->input->post('now');
        $now = json_decode($now);
        $delete = $this->input->post('delete');
        $delete = json_decode($delete);
        $regu = $this->db->where('id', $this->session->userdata('id_regu'))->get('t_regu')->row();
        foreach ($now as $row) {
            $data['active_at'] = $regu->active_at;
            $data['id_cabang'] = $regu->id_cabang;
            $data['id_project'] = $regu->id_project;
            // $data['leader'] = $regu->leader;
            $data['regu'] = $this->session->userdata('id_regu');
            $this->db->set($data)->where('id', $row)->update('users');
        }
        foreach ($delete as $row) {
            $data['regu'] = null;
            $data['active_at'] = null;
            $data['id_cabang'] = null;
            $data['id_project'] = null;
            $data['leader'] = null;
            $this->db->set($data)->where('id', $row)->update('users');
        }
        return 1;
    }

    public function add_regu()
    {
        $this->form_validation->set_rules('nama_regu', 'Nama Regu', 'required', array('required' => 'Kolom {field} harus diisi!'));
        // $this->form_validation->set_rules('leader', 'Leader', 'required', array('required' => 'Kolom {field} harus diisi!'));
        $this->form_validation->set_rules('shift_regu', 'Shift', 'required', array('required' => 'Kolom {field} harus diisi!'));

        if ($this->form_validation->run() == FALSE) {
            return [form_error('nama_regu'), form_error('leader'), form_error('shift_regu')];
        } else {
            $data['flag_active'] = 1;
            $data['b2b_token'] = $this->session->userdata('b2b_token');
            $data['nama_regu'] = $this->input->post('nama_regu');
            $data['id_shift'] = $this->input->post('shift_regu');
            // $data['leader'] = $this->input->post('leader');
            $this->db->insert('t_regu', $data);
            // $last_id = $this->db->insert_id();
            // $this->db->where('id', $data['leader'])->update("users", ["regu" => $last_id]);
            return 1;
        }
    }

    public function edit_regu()
    {

        $this->form_validation->set_rules('enama_regu', 'Nama Regu', 'required', array('required' => 'Kolom {field} harus diisi!'));
        $this->form_validation->set_rules('flag_active', 'Status', 'required', array('required' => 'Kolom {field} harus diisi!'));

        if ($this->form_validation->run() == FALSE) {
            return [form_error('enama_regu'), form_error('flag_active')];
        } else {
            $id = $this->input->post('eid');
            $data['flag_active'] = $this->input->post('flag_active');
            $data['nama_regu'] = $this->input->post('enama_regu');
            $this->db->where('id', $id)->update('t_regu', $data);
            // $detail_regu = $this->db->get_where('t_regu', ['id' => $id])->row();
            // $this->db->where('id', $detail_regu->leader)->update("users", ["regu" => $id]);
            return 1;
        }
    }

    public function hapus_regu()
    {
        $id = $this->input->post('hid');
        $data['flag_active'] = 0;
        $this->db->where('id', $id)->update('t_regu', $data);
        $this->db->where('regu', $id)->update('users', ['regu' => null]);
        return 1;
    }

    public function shift()
    {

        $id = $this->input->post('sid');

        $st = 0;

        $datau['active_at'] = $this->input->post('mode');
        if ($datau['active_at'] == 1) {
            $datau['id_project'] = $this->input->post('par');
            $data['id_project'] = $this->input->post('par');
            $data['active_at'] = $this->input->post('mode');
            $this->db->where('regu', $id)->update('users', $datau);
        } elseif ($datau['active_at'] == 2) {
            $datau['id_cabang'] = $this->input->post('par');
            $data['id_cabang'] = $this->input->post('par');
            $data['active_at'] = $this->input->post('mode');
            $this->db->where('regu', $id)->update('users', $datau);
        } elseif ($datau['active_at'] == 0) {
            $data['active_at'] = null;
            $this->db->where('regu', $id)->update('users', $datau);
        } else {
            $st++;
        }

        if ($this->input->post('shift_regu') != 0) {
            $data['id_shift'] = $this->input->post('shift_regu');
            $this->db->where('id', $id)->update('t_regu', $data);
        } elseif ($datau['active_at'] != 3) {
            $this->db->where('id', $id)->update('t_regu', $data);
        } else {
            $st++;
        }

        if ($st != 0 && $st != 1) {
            return ['', "<p style='color: #b11616'>Tidak ada perubahan!</p>"];
        } else {
            return 1;
        }
    }
}
                        
/* End of file x.php */
