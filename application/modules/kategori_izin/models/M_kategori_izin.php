<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_kategori_izin extends CI_Model
{

    public function validasi_save() 
    {   
        return [
            [
                'field' => 'kode',
                'label' => 'Kode Kategori',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'nama',
                'label' => 'Nama Kategori',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'deskripsi',
                'label' => 'Deskripsi',
                'rules' => 'required|rtrim',
            ],

        ];
    }

    public function fetch()
    {
        $fetch  = $this->db->select(['id', 'nama', 'kode', 'deskripsi'])->get('m_kategori_izin')->result(); 
        $data   = array();
        $i     = 1;
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $row->id;
            $temp[] = $i;
            $temp[] = $row->kode;
            $temp[] = $row->nama;
            $temp[] = $row->deskripsi;
            $temp[] = "<button onclick='edit($row->id)' class='btn mybt'><span class='typcn typcn-pencil'></span></button> <button onclick='deleteConfirm(\"".base_url('kategori_izin/delete/').$row->id."\", \"".$row->nama."\")' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function save(){
        $post = $this->input->post();

        $data = [
            'kode'       => $post['kode'],
            'nama'       => $post['nama'],
            'deskripsi'  => $post['deskripsi'],
        ];

        return $this->db->insert('m_kategori_izin', $data);
    }

    public function get_by_id($id){
        return $this->db->get_where('m_kategori_izin', ['id'=>$id]);
    }

    public function update($id){
        $post = $this->input->post();
        $data =[
            'kode'      => $post['kode'],
            'nama'      => $post['nama'],
            'deskripsi' => $post['deskripsi'],
        ];
        return $this->db->update('m_kategori_izin', $data, ['id'=>$id]);
    }

    public function delete($id){
        return $this->db->delete('m_kategori_izin', ['id'=>$id]);
    }
    
}
