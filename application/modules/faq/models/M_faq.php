<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_faq extends CI_Model
{

    public function fetch()
    {
        $fetch = $this->db->query("SELECT f.id as faq_id, q.id as qna_id,f.name as faq_name, q.question as question, q.answer as answer, q.sequence_to as sequence_to FROM tbl_qna as q LEFT JOIN tbl_faq as f ON q.faq_id = f.id")->result();
        $data = array();
        $i = 1;
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $i;
            $temp[] = $row->faq_name;
            $temp[] = $row->question;
            $temp[] = "<div class='minimize'>$row->answer</div>";
            $temp[] = "<button data-toggle='modal' data-target='#editModal' onclick='edit(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ")' class='btn mybt'><span class='typcn typcn-pencil'></span></button> <button  onclick='deleteConfirm($row->qna_id)' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function lists()
    {
        $faqs =  $this->get_faq();
        $result = [];
        foreach ($faqs as $faq) {
            $obj = new stdClass();
            $obj->faq_id = $faq->id;
            $obj->faq_name = $faq->name;
            $obj->qna = $this->db->order_by("sequence_to", "ASC")->get_where("tbl_qna", ["faq_id" => $faq->id])->result();
            $result[] = $obj;
        }
        return $result;
    }
    public function get_faq()
    {
        return  $this->db->order_by("sequence_to", "ASC")->get("tbl_faq")->result();
    }
    public function table()
    {
        return $this->db->query("SELECT f.id as faq_id, q.id as qna_id,f.name as faq_name, q.question as question, q.answer as answer FROM tbl_qna as q LEFT JOIN tbl_faq as f ON q.faq_id = f.id")->result();
    }

    public function insert($data)
    {
        return $this->db->insert('tbl_qna', $data);
    }

    public function detail($id)
    {
        return $this->db->get_where('tbl_qna', ['id' => $id])->row();
    }

    public function update($data, $id)
    {
        return $this->db->set($data)->where('id', $id)->update('tbl_qna');
    }

    public function delete($id)
    {
        return $this->db->where_in('id', $id)->delete('tbl_qna');
    }
}
