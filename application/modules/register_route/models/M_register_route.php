<?php

use phpDocumentor\Reflection\Types\Null_;

defined('BASEPATH') or exit('No direct script access allowed');

class M_register_route extends CI_Model
{

    public function fetch()
    {
        $b2b = $this->session->userdata('b2b_token');
        // $fetch = $this->db->order_by('id_route', 'desc')->where('b2b_token', $b2b)->where('flag_active', 1)->get('cluster_route')->result();
        $fetch = $this->db->query("SELECT c.cluster_name as cluster_name,
         c.id_route as id_route,
        c.description as description,
        c.flag_active as flag_active,
        c.b2b_token as b2b_token,
        c.otherdata->>'group_id' as group_id,
        c.otherdata->>'assign_to' as assign_to,
        c.otherdata,
        r.nama_regu as nama_regu
        FROM cluster_route c 
        LEFT JOIN t_regu r ON 
        r.id::TEXT = c.otherdata->>'group_id'::TEXT
        WHERE c.flag_active = 1 AND c.b2b_token = ? ORDER BY c.id_route DESC  ", [$b2b])->result();

        $has_geofence = $this->db->query("SELECT 
        other_data->>'area_color' as area_color, 
        other_data->>'area_lat_long' as area_lat_long 
        FROM m_register_b2b WHERE  b2b_token = ? ", [$b2b])->row();

        $data = array();
        $i = 1;
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $i;
            $temp[] = $row->cluster_name;
            $temp[] = $row->description;
            $temp[] = $row->nama_regu;
            $temp[] = $row->flag_active == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            // $temp[] = '<div class="toggle btn btn-xs btn-primary" data-toggle="toggle" style="width: 42.0117px;height: 17.8125px;border-radius: 10px;"><input type="checkbox" checked="" data-toggle="toggle" data-size="xs"><div class="toggle-group"><label class="btn btn-primary btn-xs toggle-on">On</label><label class="btn btn-light btn-xs toggle-off">Off</label><span class="toggle-handle btn btn-light btn-xs"></span></div></div>';
            $temp[] = "<button data-toggle='modal'  data-target='#editModal' onclick='edit($row->id_route,$row->group_id)' class='btn mybt' title='Edit'><span class='typcn typcn-pencil'></span></button> <button title='Hapus' data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id_route)' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $temp[] = '<button onclick="resetprev(' . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ' )" title="Atur Scheduler" type="button" style="border-radius:160; align-items:right;" class="btn mybt js-set-schedule" data-toggle="modal" data-target="#scheduler"><span class="typcn typcn-calendar"></span></button>' . "<button title='Setting Assign' onclick=\"asignModal('$row->b2b_token','$row->id_route','$row->group_id')\" class='btn mybt'><i style='' class='fa fa-users'></i></button>";
            $temp[] = "<button title='Tambah Checkpoint' data-toggle='modal' data-target='#addroute' onclick='set_cp($row->id_route," . htmlspecialchars(json_encode($has_geofence), ENT_QUOTES, 'UTF-8') . ")' class='btn mybt'><i style='color: #b11616' class='fa fa-map-marker-alt'></i></button><button title='Asign task to " . $row->nama_regu . " now?  ' onclick=\"listUser('$row->b2b_token','$row->id_route','$row->group_id')\" class='btn mybt'><i style='color: #b11616' class='fa fa-paper-plane'></i></button>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function fetchcp($route = null)
    {
        $b2b = $this->session->userdata('b2b_token');
        $query = $this->db->query("SELECT * FROM check_point WHERE b2b_token='$b2b' ORDER BY id DESC")->result();
        $poly = $this->db->query("SELECT other_data->>'area_lat_long'  as area_lat_long FROM m_register_b2b WHERE  b2b_token='" . $b2b . "'")->result();
        return array('cp' => $query, 'poly' => $poly);
    }

    public function tambah_route()
    {
        $this->form_validation->set_rules('cluster_name', 'Cluster Name', 'required');
        $this->form_validation->set_rules('group_id', 'Group', 'required');
        // $this->form_validation->set_rules('description', 'description', 'required');
        $hari_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        if ($this->form_validation->run() == FALSE) {
            return [form_error('cluster_name')];
        } else {
            $group_id = $this->input->post('group_id');
            $b2b = $this->session->userdata('b2b_token');
            $users_by_regu = $this->db->get_where("users", ['b2b_token' => $b2b, 'regu' =>  $group_id])->result();
            $assign_to = [];
            foreach ($users_by_regu as $u) {
                array_push($assign_to, $u->id);
            }
            $data['flag_active'] = 1;
            $data['b2b_token'] = $b2b;
            $data['cluster_name'] = $this->input->post('cluster_name');
            $data['description'] = $this->input->post('description');
            $jam_mulai = new DateTime();
            $data['otherdata'] = json_encode([
                "group_id" => $group_id,
                "assign_to" => $assign_to,
                "day" => $hari_hari,
                "schedule_type" => "daily",
                "jam" => ["06:00"],
                "jam_mulai" => $jam_mulai->format("H:i")
            ]);
            $this->db->insert('cluster_route', $data);
            return 1;
        }
    }
    public function edit_route()
    {
        $this->form_validation->set_rules('ecluster_name', 'Cluster Name', 'required');
        $this->form_validation->set_rules('group_id', 'Group', 'required');

        // $this->form_validation->set_rules('edescription', 'description', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('ecluster_name')];
        } else {
            $id = $this->input->post('eid');
            $flag_active = 1;
            $cluster_name = $this->input->post('ecluster_name');
            $description = $this->input->post('edescription');
            $otherdata = json_encode([
                "group_id" => $this->input->post('group_id')
            ]);
            $this->db->query("UPDATE cluster_route SET 
                otherdata = otherdata || '$otherdata',
                flag_active = '$flag_active',
                cluster_name = '$cluster_name',
                description = '$description'
              WHERE id_route = '$id' ");
            return 1;
        }
    }
    public function hapus_route()
    {
        $id = $this->input->post('hid');
        $data['flag_active'] = 0;
        $cp['cluster_route'] = null;
        $this->db->where('id_route', $id)->update('cluster_route', $data);
        $this->db->where('cluster_route', $id)->update('check_point', $cp);
        return $id;
    }

    public function updateroute($id)
    {
        $this->form_validation->set_rules('cpoint[]', 'Checkpoint', 'required');
        // $this->form_validation->set_rules('description', 'description', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('cpoint[]')];
        } else {
            foreach ($this->input->post('cpoint') as $row) {
                $data['cluster_route'] = $id;
                $this->db->where('id', $row)->update('check_point', $data);
            }
            return 1;
        }
    }

    public function scheduler()
    {
        $this->form_validation->set_rules('schedule_type', 'Schedule Type', 'required');
        // $this->form_validation->set_rules('day', 'Hari', 'required');
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
        // $this->form_validation->set_rules('interval', 'Interval', 'required');
        // $this->form_validation->set_rules('jml_kirim', 'Jumlah Kirim', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('day[]'), form_error('jam_mulai'), form_error('interval'), form_error('jml_kirim')];
        } else {
            $id = $this->input->post("scid");
            // $masuk = new DateTime($this->input->post("jam_mulai"));
            $interval = $this->input->post("interval");
            $jam_mulai = new DateTime($this->input->post("jam_mulai"));
            // $max = $this->input->post("jml_kirim");
            $schedule_type = $this->input->post("schedule_type");
            $schedule_expired = $this->input->post("schedule_expired");
            // $arrjam = ["06:00"];
            // for ($i = 0; $i < $max; $i++) {
            //     $jam = "";
            //     $masuk = $masuk->modify("+" . $interval . " hours");
            //     if ($masuk->format("H:i") == "00:00") {
            //         $jam = "23:59";
            //         $arrjam[] = $jam;
            //     } else {
            //         $jam = $masuk->format("H:i");
            //         $arrjam[] = $jam;
            //     }
            //     // echo $masuk->format("d");
            //     if ($masuk->format("d") != date("d")) {
            //         break;
            //     }
            // }
            $day = $this->input->post("day");
            // $data["interval_option"] = 1;
            $otherdata = json_encode([
                "schedule_type" => $schedule_type,
                "schedule_expired" => $schedule_expired,
                "day" => $day,
                "jam_mulai" => $jam_mulai->format("H:i"),
                // "hours" => $arrjam
            ]);
            $data["flex"] = $interval;
            $this->db->query("UPDATE cluster_route SET otherdata = otherdata || ? WHERE id_route = '$id' ", [$otherdata]);
            return 1;
        }
    }

    public function hapus_checkpoint($id)
    {
        $data['cluster_route'] = null;
        $x = $this->db->where('id', $id)->update('check_point', $data);
        return $x;
    }
}
                        
/* End of file x.php */
