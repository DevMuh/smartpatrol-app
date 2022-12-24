<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_register_route extends CI_Model
{

    public function fetch()
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->order_by('id_route', 'desc')->where('b2b_token', $b2b)->where('flag_active', 1)->get('cluster_route')->result();
        $data = array();
        $i = 1;
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $i;
            $temp[] = $row->cluster_name;
            $temp[] = $row->description;
            $temp[] = $row->flag_active == 1 ? 'Active' : 'Inactive';
            // $temp[] = '<div class="toggle btn btn-xs btn-primary" data-toggle="toggle" style="width: 42.0117px;height: 17.8125px;border-radius: 10px;"><input type="checkbox" checked="" data-toggle="toggle" data-size="xs"><div class="toggle-group"><label class="btn btn-primary btn-xs toggle-on">On</label><label class="btn btn-light btn-xs toggle-off">Off</label><span class="toggle-handle btn btn-light btn-xs"></span></div></div>';
            $temp[] = "<button data-toggle='modal' data-target='#editModal' onclick='edit($row->id_route)' class='btn mybt' title='Edit'><span class='typcn typcn-pencil'></span></button> <button title='Hapus' data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id_route)' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $temp[] = '<button onclick="resetprev(' . $row->id_route . ')" title="Atur Scheduler" type="button" style="border-radius:160; align-items:right;" class="btn mybt" data-toggle="modal" data-target="#scheduler"><span class="typcn typcn-calendar"></span></button></br>';
            $temp[] = "<button title='Tambah Checkpoint' data-toggle='modal' data-target='#addroute' onclick='route($row->id_route)' class='btn mybt'><i style='color: #b11616' class='fa fa-map-marker-alt'></i></button><button title='Sign task to mobile' onclick=\"tomobile('$row->b2b_token','$row->id_route')\" class='btn mybt'><i style='color: #b11616' class='fa fa-arrow-right'></i></button>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function fetchcp($route = null)
    {
        $b2b = $this->session->userdata('b2b_token');
        $query = $this->db->query("SELECT * FROM check_point WHERE b2b_token='$b2b'")->result();
        $poly = $this->db->query("SELECT * FROM coverage_area WHERE flag_active=1 AND b2b_token='" . $b2b . "'")->result();
        return array('cp' => $query, 'poly' => $poly);
    }

    public function tambah_route()
    {
        $this->form_validation->set_rules('cluster_name', 'Cluster Name', 'required');
        // $this->form_validation->set_rules('description', 'description', 'required');
        if ($this->form_validation->run() == FALSE) {
            return [form_error('cluster_name')];
        } else {
            $data['flag_active'] = 1;
            $data['b2b_token'] = $this->session->userdata('b2b_token');
            $data['cluster_name'] = $this->input->post('cluster_name');
            $data['description'] = $this->input->post('description');

            $this->db->insert('cluster_route', $data);
            return 1;
        }
    }
    public function edit_route()
    {
        $this->form_validation->set_rules('ecluster_name', 'Cluster Name', 'required');
        // $this->form_validation->set_rules('edescription', 'description', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('ecluster_name')];
        } else {
            $id = $this->input->post('eid');
            $data['flag_active'] = 1;
            $data['cluster_name'] = $this->input->post('ecluster_name');
            $data['description'] = $this->input->post('edescription');

            $this->db->where('id_route', $id)->update('cluster_route', $data);
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
        $this->form_validation->set_rules('day[]', 'Hari', 'required');
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
        $this->form_validation->set_rules('interval', 'Interval', 'required');
        $this->form_validation->set_rules('jml_kirim', 'Jumlah Kirim', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('day[]'), form_error('jam_mulai'), form_error('interval'), form_error('jml_kirim')];
        } else {
            $id = $this->input->post("scid");
            $masuk = new DateTime($this->input->post("jam_mulai"));
            $interval = $this->input->post("interval");
            $max = $this->input->post("jml_kirim");
            $arrjam = [];
            for ($i = 0; $i < $max; $i++) {
                $jam = "";
                $masuk = $masuk->modify("+" . $interval . " hours");
                if ($masuk->format("H:i") == "00:00") {
                    $jam = "23:59";
                    $arrjam[] = $jam;
                } else {
                    $jam = $masuk->format("H:i");
                    $arrjam[] = $jam;
                }
                // echo $masuk->format("d");
                if ($masuk->format("d") != date("d")) {
                    break;
                }
            }
            $day = $this->input->post("day");
            $otherData = array("day" => $day, "hours"=> $arrjam);
            $data["interval_option"] = 1;
            $data["otherdata"] = json_encode($otherData);
            $data["flex"] = $interval;
            $this->db->where("id_route", $id)->update("cluster_route", $data);
            return 1;
        }
    }
}
                        
/* End of file x.php */
