<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_Qr extends CI_Model
{


    public function validasi_save()
    {
        return [
            [
                'field' => 'name',
                'label' => 'name',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'qr_id',
                'label' => 'qr_id',
                'rules' => 'required|rtrim',
            ],

        ];
    }

    public function is_qr_exist($qr = NULL, $wihout_this_id = NULL)
    {
        $and_without_this_id = "";
        if ($wihout_this_id) {
            $and_without_this_id = " AND id != '$wihout_this_id' ";
        }
        $b2b = $this->session->userdata('b2b_token');
        return $this->db->query("SELECT id FROM qr WHERE b2b_token = ? AND qr_id = ? " . $and_without_this_id, [$b2b, $qr])->num_rows();
    }

    public function fetch()
    {
        $b2b_token = $this->session->userdata('b2b_token');
        $query = $this->db->get_where('qr', ['b2b_token' => $b2b_token])->result();
        $data = [];
        foreach ($query as $row) {
            $temp = [];
            $temp[] = $row ? $row->name : "";
            $temp[] = $row ? $row->qr_id : "";
            $temp[] = '<img class="myImage" onclick="modalImg(this)" width="50" height="50" src="https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=' . $row->qr_id . '&choe=UTF-8" title="' . $row->qr_id . '" />';
            $temp[] = "<button title='Print'  onclick='PrintImage(\"https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=$row->qr_id&choe=UTF-8\",\"$row->name\")' class='btn mybt'><span class='badge badge-info'>Print</span></button> <button data-toggle='modal'  data-target='#editModal' onclick='edit(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ")' class='btn mybt' title='Edit'><span class='badge badge-warning'>Edit</span></button><button onclick='deleteConfirm(\"$row->id\")' class='btn mybt'><span class='badge badge-danger'>Delete</span></button> ";
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function insert($data)
    {
        return $this->db->insert('qr', $data);
    }

    public function detail($id)
    {
        return $this->db->get_where('qr', ['id' => $id])->row();
    }

    public function update($data, $id)
    {
        return $this->db->set($data)->where('id', $id)->update('qr');
    }

    public function delete($id)
    {
        return $this->db->where_in('id', $id)->delete('qr');
    }
}
