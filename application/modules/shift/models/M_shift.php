<?php

use PhpOffice\PhpSpreadsheet\Shared\Date;

defined('BASEPATH') or exit('No direct script access allowed');

class M_shift extends CI_Model
{

    public function fetch()
    {
        $fetch = $this->data();
        $data = [];
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $row->kode_shift;
            $temp[] = $row->shift_name;
            $temp[] = $row->waktu_start;
            $temp[] = $row->waktu_end;
            $temp[] = $row->in_early ?: 0;
            $temp[] = $row->in_late ?: 0;
            $temp[] = $row->out_late ?: 0;
            $temp[] = $row->is_same_day_shift == "f" ? "Different Day" : "Same Day";
            $temp[] = $row->durasi . ' Jam';
            $temp[] = $row->flag_enable == 1 ? 'Active' : 'Inactive';
            $temp[] = "<button data-toggle='modal' data-target='#editModal' onclick='edit($row->id_)' class='btn mybt'><span class='typcn typcn-pencil'></span></button>";
            $temp[] = "<button data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id_, event)' class='btn mybt'><span style='font-size:18px' class='typcn typcn-power'></span></button>";
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function data()
    {
        $b2b = $this->session->userdata('b2b_token');
        // return $this->db->where('b2b_token', $b2b)->order_by('waktu_start', 'asc')->get('t_shift')->result();
        return $this->db->where(array('b2b_token' => $b2b, 'flag_enable' => 1))->order_by('waktu_start', 'asc')->get('t_shift')->result();
    }

    public function data_v2($id_user)
    {
        $user = $this->db->where('id', $id_user)->get('users')->row();
        // return $this->db->where('b2b_token', $user->b2b_token)->order_by('waktu_start', 'asc')->get('t_shift')->result();
        return $this->db->where(array('b2b_token' => $user->b2b_token, 'flag_enable' => 1))->order_by('waktu_start', 'asc')->get('t_shift')->result();
    }

    public function select2()
    {
        $fetch = $this->data();
        $res = [];
        foreach ($fetch as $key => $value) {
            $res[] = array(
                'id' => $value->id_,
                'text' => $value->shift_name
            );
        }
        return $res;
    }

    public function select2_v2($id_user = false)
    {
        $fetch = !$id_user ? $this->data() : $this->data_v2($id_user);
        $res = [];
        foreach ($fetch as $key => $value) {
            $res[] = array(
                'id' => $value->id_,
                'text' => $value->shift_name
            );
        }
        return $res;
    }

    public function add_shift()
    {
        $this->form_validation->set_rules('shift_name', 'Nama Shift', 'required', array('required' => 'Kolom {field} harus diisi!'));
        $this->form_validation->set_rules('jam_masuk', 'Jam Masuk', 'required', array('required' => 'Kolom {field} harus diisi!'));
        $this->form_validation->set_rules('jam_pulang', 'Jam Pulang', 'required', array('required' => 'Kolom {field} harus diisi!'));

        if ($this->form_validation->run() == FALSE) {
            return [form_error('shift_name'), form_error('jam_masuk'), form_error('jam_pulang')];
        } else {
            $data['flag_enable'] = 1;
            $data['kode_shift'] = $this->input->post('kode_shift');
            $data['shift_name'] = $this->input->post('shift_name');
            $data['b2b_token'] = $this->session->userdata('b2b_token');
            $data['waktu_start'] = $this->input->post('jam_masuk');
            $data['waktu_end'] = $this->input->post('jam_pulang');
            $data['in_early'] = $this->input->post('in_early');
            $data['in_late'] = $this->input->post('in_late');
            $data['out_late'] = $this->input->post('out_late');
            $data['day'] = json_encode($this->input->post('day'));
            $start =  new DateTime($data['waktu_start']);
            $end =  new DateTime($data['waktu_end']);
            if (strtotime($data["waktu_start"]) > strtotime($data["waktu_end"])) {
                $end = $end->modify('+1 day');
            }
            $data['durasi'] = date_diff($start, $end)->h;
            $data['is_same_day_shift'] = strtotime($data["waktu_start"]) > strtotime($data["waktu_end"]) ? "f" : "t";
            $this->db->insert('t_shift', $data);
            return 1;
        }
    }

    public function edit_shift()
    {
        $this->form_validation->set_rules('eshift_name', 'Nama Shift', 'required', array('required' => 'Kolom {field} harus diisi!'));
        $this->form_validation->set_rules('ejam_masuk', 'Jam Masuk', 'required', array('required' => 'Kolom {field} harus diisi!'));
        $this->form_validation->set_rules('ejam_pulang', 'Jam Pulang', 'required', array('required' => 'Kolom {field} harus diisi!'));

        if ($this->form_validation->run() == FALSE) {
            return [form_error('eshift_name'), form_error('ejam_masuk'), form_error('ejam_pulang')];
        } else {
            $id = $this->input->post('eid');
            $data['flag_enable'] = 1;
            $data['shift_name'] = $this->input->post('eshift_name');
            $data['waktu_start'] = $this->input->post('ejam_masuk');
            $data['waktu_end'] = $this->input->post('ejam_pulang');
            $data['in_early'] = $this->input->post('ein_early');
            $data['in_late'] = $this->input->post('ein_late');
            $data['out_late'] = $this->input->post('eout_late');
            $start =  new DateTime($data['waktu_start']);
            $end =  new DateTime($data['waktu_end']);
            if (strtotime($data["waktu_start"]) > strtotime($data["waktu_end"])) {
                $end = $end->modify('+1 day');
            }
            $data['durasi'] = date_diff($start, $end)->h;
            $data['is_same_day_shift'] = strtotime($data["waktu_start"]) > strtotime($data["waktu_end"]) ? "f" : "t";
            $this->db->where('id_', $id)->update('t_shift', $data);
            return 1;
        }
    }

    public function hapus_shift()
    {
        $id = $this->input->post('hid');
        $stat = $this->input->post('stat');
        $data['flag_enable'] = $stat;
        $this->db->where('id_', $id)->update('t_shift', $data);
        return 1;
    }

    public function generate()
    {
        $b2b = $this->session->userdata('b2b_token');
        $this->form_validation->set_rules('kode_shift', 'Kode Shift', 'required', array('required' => 'Kolom {field} harus diisi!'));
        $this->form_validation->set_rules('day[]', 'Hari', 'required', array('required' => 'Pilih hari masuk!'));
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required', array('required' => 'Kolom {field} harus diisi!'));
        // $this->form_validation->set_rules('durasi', 'Durasi', 'required', array('required' => 'Kolom {field} harus diisi!'));
        // $this->form_validation->set_rules('jml_shift', 'Jumlah Shift', 'required', array('required' => 'Kolom {field} harus diisi!'));

        if ($this->form_validation->run() == FALSE) {
            return [form_error('kode_shift'), form_error('day[]'), form_error('jam_mulai'), form_error('durasi'), form_error('jml_shift')];
        } elseif ($this->db->where('b2b_token', $b2b)->where('kode_shift', $this->input->post('kode_shift'))->get('t_shift')->num_rows() != 0) {
            return ['<p style="color: #b11616">Kode shift tidak boleh sama!</p>'];
        } else {
            $masuk = new DateTime($this->input->post('jam_mulai'));
            $data['flag_enable'] = 1;
            $data['day'] = json_encode($this->input->post('day'));
            $data['durasi'] = $this->input->post('durasi');
            $data['b2b_token'] = $b2b;
            $data['kode_shift'] = $this->input->post('kode_shift');

            for ($i = 1; $i <= $this->input->post('jml_shift'); $i++) {
                $data['shift_name'] = $this->input->post('kode_shift') . ' ' . $i;
                $data['waktu_start'] = $masuk->format("H:i") == '00:00' ? '23:59' : $masuk->format("H:i");
                $masuk = $masuk->modify("+" . $data['durasi'] . " hours");
                $data['waktu_end'] = $masuk->format("H:i") == '00:00' ? '23:59' : $masuk->format("H:i");
                $this->db->insert('t_shift', $data);
            }
            return 1;
        }
    }
}
