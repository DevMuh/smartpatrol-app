<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_kantor_cabang extends CI_Model
{

    public function validasi_save()
    {
        return [
            [
                'field' => 'kode',
                'label' => 'Kode Cabang',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'nama',
                'label' => 'Nama Cabang',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'wilayah',
                'label' => 'Wilayah',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'manager',
                'label' => 'Nama Manager',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'telepon',
                'label' => 'Telepon',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'fax',
                'label' => 'Fax',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'alamat',
                'label' => 'Alamat Detail',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'provinsi',
                'label' => 'Provinsi',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'kota',
                'label' => 'Kota/Kabupaten',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'deskripsi',
                'label' => 'Deskripsi',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'kode_pos',
                'label' => 'Kode Pos',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'longitude',
                'label' => 'Longitude',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'latitude',
                'label' => 'Latitude',
                'rules' => 'required|rtrim',
            ],
        ];
    }

    public function fetch()
    {
        $this->db->select(['t_kantor_cabang.id', 't_kantor_cabang.kode', 't_kantor_cabang.nama', 'data_provinsi.name as provinsi', 'data_kota.name as kota', 't_kantor_cabang.telepon', 't_kantor_cabang.status'])
            ->from('t_kantor_cabang')
            ->join('data_provinsi', 'data_provinsi.id=t_kantor_cabang.provinsi', 'left')
            ->join('data_kota', 'data_kota.id=t_kantor_cabang.kota', 'left')
            ->order_by("t_kantor_cabang.id", 'ASC');
        $fetch  = $this->db->get()->result();
        $data   = array();
        $i     = 1;
        foreach ($fetch as $row) {
            $idd = base64_encode($row->id);
            $idd = str_replace(['+', '/', '='], ['-', '_', ''], $idd);
            $temp = array();
            $temp[] = $row->id;
            $temp[] = $i;
            $temp[] = $row->kode;
            $temp[] = $row->nama;
            $temp[] = $row->provinsi == '' ? '-' : $row->provinsi;
            $temp[] = $row->kota == '' ? '-' : $row->kota;
            $temp[] = $row->telepon;
            if ($row->status == 1) { //display data for table
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

            if ($row->status == 1) { //display status for export data
                $temp[] = 'Active';
            } else {
                $temp[] = 'Inactive';
            }
            $temp[] = "<a href='" . base_url('kantor_cabang/edit/') . $idd . "' class='btn mybt'><span class='typcn typcn-pencil'></span></a> <button onclick='deleteConfirm(\"" . base_url('kualifikasi/change_status/') . $idd . "\", \"" . $row->nama . "\")' class='btn mybt'><span class='typcn typcn-trash'></span></button>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function save()
    {
        $post = $this->input->post();

        $data = [
            'kode'          => $post['kode'],
            'nama'          => $post['nama'],
            'wilayah'       => $post['wilayah'],
            'manager'       => $post['manager'],
            'telepon'       => $post['telepon'],
            'fax'           => $post['fax'],
            'email'         => $post['email'],
            'alamat'        => $post['alamat'],
            'provinsi'      => $post['provinsi'],
            'kota'          => $post['kota'],
            'deskripsi'     => $post['deskripsi'],
            'kode_pos'      => $post['kode_pos'],
            'longitude'     => $post['longitude'],
            'latitude'      => $post['latitude'],
            'status'        => 0
        ];

        return $this->db->insert('t_kantor_cabang', $data);
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('t_kantor_cabang', ['id' => $id]);
    }

    public function update($id)
    {
        $post = $this->input->post();
        $data = [
            'kode'          => $post['kode'],
            'nama'          => $post['nama'],
            'wilayah'       => $post['wilayah'],
            'manager'       => $post['manager'],
            'telepon'       => $post['telepon'],
            'fax'           => $post['fax'],
            'email'         => $post['email'],
            'alamat'        => $post['alamat'],
            'provinsi'      => $post['provinsi'],
            'kota'          => $post['kota'],
            'deskripsi'     => $post['deskripsi'],
            'kode_pos'      => $post['kode_pos'],
            'longitude'     => $post['longitude'],
            'latitude'      => $post['latitude'],
        ];
        return $this->db->update('t_kantor_cabang', $data, ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete('m_kualifikasi', ['id' => $id]);
    }
}
