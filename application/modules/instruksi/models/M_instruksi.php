<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_instruksi extends CI_Model
{

    var $table = 't_instruksi';

    public function validasi_save()
    {
        return [
            [
                'field' => 'perihal',
                'label' => 'Perihal',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'id_kategori_instruksi',
                'label' => 'Tingkat Urgensi',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'detail',
                'label' => 'Detail Instruksi',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'mulai',
                'label' => 'Tanggal Mulai',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'selesai',
                'label' => 'Tanggal Selesai',
                'rules' => 'required|rtrim',
            ]
        ];
    }

    public function fetch()
    {
        $fetch  = $this->db->select(['t_instruksi.id', 'm_kategori_instruksi.nama', 't_instruksi.detail_instruksi', 't_instruksi.tanggal_kirim', 't_instruksi.pengirim', 't_instruksi.tanggal_mulai', 't_instruksi.tanggal_selesai', 't_instruksi.lampiran', 't_instruksi.regu_id', 't_instruksi.perihal', 't_instruksi.feedback'])
            ->from($this->table)
            ->join('m_kategori_instruksi', 'm_kategori_instruksi.id=t_instruksi.id_kategori_instruksi', 'left')
            ->where('t_instruksi.b2b_token', $_SESSION['b2b_token'])
            ->order_by("id", 'DESC')
            ->get()
            ->result();
        $data = array();
        $i     = 1;
        $b2b = $_SESSION['b2b_token'];
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $row->id;
            $temp[] = $i;
            $temp[] = $row->nama;
            $temp[] = $row->tanggal_kirim;
            $temp[] = $row->perihal;
            $temp[] = $row->pengirim;
            $temp[] = $row->tanggal_mulai;
            $temp[] = $row->tanggal_selesai;
            if ($row->feedback == 1) {
                $temp[] = "Yes";
            } else {
                $temp[] = "No";
            }
            $temp[] = "<a href='' onclick='detail($row->id, \"$b2b\")' data-target='#modalDetail' data-toggle='modal' class='btn btn-sm btn-primary'> Show</a>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function save()
    {
        $post = $this->input->post();
        date_default_timezone_set("Asia/Jakarta");
        $b2b = $_SESSION['b2b_token'];
        $anggota = array();
        $subscribe = $_POST['customRadio'];
        if ($subscribe == 1) {
            $anggota = $this->input->post('anggota');
        } elseif ($subscribe == 2) {
            $idregu = $this->input->post('regu');
            foreach ($idregu as $row) {
                $leader = false;
                $regu = $this->db->where('regu', $row)->where('b2b_token', $b2b)->get('users')->result();
                foreach ($regu as $row2) {
                    if (!$leader) {
                        $anggota[] = $row2->leader;
                        $leader = true;
                    }
                    $anggota[] = $row2->id;
                }
            }
        } elseif ($subscribe == 3) {
            $idshift = $this->input->post('shift');
            $idregu = array();
            foreach ($idshift as $row) {
                $shift = $this->db->where('id_shift', $row)->where('b2b_token', $b2b)->get('t_regu')->result();
                foreach ($shift as $row2) {
                    $idregu[] = $row2->id;
                }
            }
            foreach ($idregu as $row) {
                $leader = false;
                $regu = $this->db->where('regu', $row)->where('b2b_token', $b2b)->get('users')->result();
                foreach ($regu as $row2) {
                    if (!$leader) {
                        $anggota[] = $row2->leader;
                        $leader = true;
                    }
                    $anggota[] = $row2->id;
                }
            }
        }

        if ($_FILES['image']['name']) { //if update and upload

            $upload = $this->upload();
            if ($upload['type'] == TRUE) {
                $data = [
                    'perihal'               => $post['perihal'],
                    'id_kategori_instruksi' => $post['id_kategori_instruksi'],
                    'detail_instruksi'      => $post['detail'],
                    'tanggal_kirim'         => date('Y-m-d H:i:s'),
                    'pengirim'              => $_SESSION['username'],
                    'tanggal_mulai'         => $post['mulai'],
                    'tanggal_selesai'       => $post['selesai'],
                    'feedback'              => $post['feedback'],
                    'b2b_token'             => $_SESSION['b2b_token'],
                    'lampiran'              => $upload['name'],
                ];
                $this->db->insert($this->table, $data);
                $id = $this->db->insert_id();

                foreach ($anggota as $value) {
                    $this->db->insert('instruksi_anggota', ['instruksi_id' => $id, 'anggota_id' => $value]);
                }

                return TRUE;
            } else { //failed upload
                return FALSE;
            }
        } else {
            $data = [
                'perihal'               => $post['perihal'],
                'id_kategori_instruksi' => $post['id_kategori_instruksi'],
                'detail_instruksi'      => $post['detail'],
                'tanggal_kirim'         => date('Y-m-d H:i:s'),
                'pengirim'              => $_SESSION['username'],
                'tanggal_mulai'         => $post['mulai'],
                'tanggal_selesai'       => $post['selesai'],
                'feedback'              => $post['feedback'],
                'b2b_token'             => $_SESSION['b2b_token'],
            ];
            $this->db->insert($this->table, $data);
            $id = $this->db->insert_id();

            foreach ($anggota as $value) {
                $this->db->insert('instruksi_anggota', ['instruksi_id' => $id, 'anggota_id' => $value]);
            }

            return TRUE;
        }
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id]);
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


    private function upload()
    {

        $config['upload_path']      = './assets/apps/assets/img/instruksi';
        $config['allowed_types']    = 'jpeg|jpg|png';
        $config['overwrite']        = true;
        $config['max_size']         = '13032';

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('image')) {
            $data = [
                'type'  => TRUE,
                'name'  => $this->upload->data('file_name'),
            ];
        } else {
            $data = [
                'type'  => FALSE,
                'error' => array('error' => $this->upload->display_errors()),
            ];
        }
        return $data;
    }

    public function get_data_anggota()
    {
        $b2b_token = $_SESSION['b2b_token'];
        $data = $this->db->select(['id', 'username', 'full_name', 'user_roles'])
            ->from('users')
            ->where('users.b2b_token', $b2b_token)
            ->where('users.user_roles', 'anggota')
            ->get()
            ->result();
        return $data;
    }
}

/* End of file M_intruksi.php */
/* Location: ./application/modules/intruksi/models/M_intruksi.php */
