<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_menu extends CI_Model
{
    public function fetch_all()
    {
        $this->db->order_by('judul_menu', 'ASC');
        return $this->db->get('tabel_menu')->result();
    }

    public function fetch()
    {
        $this->db->where('is_main_menu', "0");
        $this->db->order_by('sequence', 'DESC');
        // $this->db->order_by('judul_menu', 'DESC');
        return $this->db->get('tabel_menu')->result();
    }

    public function fetch_single($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('tabel_menu')->row();
    }

    function build_menu($menus, $results = "")
    {
        $len = count($menus);
        if ($len > 0) {
            $item = $menus[$len - 1];
            array_pop($menus);
            $results .=
                "
            <li class='dd-item' data-id='" . $item->id . "'>
                <div class='dd-handle dd3-handle' style='background:#dc3545'></div>
                <div class='dd3-content'>
                    <div class='d-flex justify-content-between align-items-center'>
                        <div class='text-left'>
                            <div class='actions'>
                                <div class='form-check'>
                                    <input 
                                        class='form-check-input check-menu' 
                                        type='checkbox' 
                                        name='menu[]' 
                                        id='menu_" . $item->id . "' 
                                        data-id='" . $item->id . "' 
                                        data-is_main_menu='" . $item->is_main_menu . "' 
                                        value='" . $item->id . "'>
                                    <label class='mb-0 mt-1'>
                                    " . $item->judul_menu . "
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class='text-right'>
                            <div class='actions'>
                                <a href='#' data-toggle='modal' data-target='#editModal' data-id='" . $item->id . "' class='action_edit'>
                                    <span class='label label-warning'>
                                        <i class='fa fa-edit'></i>
                                    </span> 
                                </a>
                                <a href='#' onclick='hapus_data(" . $item->id . ")' data-toggle='modal' data-target='#hapusModal'>
                                    <span class='label label-danger'>
                                        <i class='fa fa-trash'></i>
                                    </span> 
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            ";

            $this->db->where('is_main_menu', $item->id);
            $this->db->order_by('sequence', 'DESC');
            $sub_menu = $this->db->get('tabel_menu')->result();
            if (count($sub_menu) > 0) {
                $results .= self::build_menu($sub_menu);
            }
            $results .= "</li>";
            return self::build_menu($menus, $results);
        } else {
            $results = '<ol class="dd-list">' . $results . '</ol>';
            return $results;
        }
    }

    function html_build_menu()
    {
        return self::build_menu(self::fetch());
    }

    function get_last_id()
    {
        $temp = $this->db->select('id')->order_by('id', 'DESC')->limit(1)->get('tabel_menu')->row();
        return ((int) $temp->id);
    }

    public function tambah()
    {
        $this->form_validation->set_rules('judul_menu', 'Judul Menu', 'required');
        $this->form_validation->set_rules('link', 'Link', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('judul_menu'), form_error('link'), form_error('icon')];
        } else {
            $data['id'] = self::get_last_id() + 1;
            $data['judul_menu'] = $this->input->post('judul_menu');
            $data['link'] = $this->input->post('link');
            $data['icon'] = $this->input->post('icon');
            $data['is_main_menu'] = $this->input->post('is_main_menu') ?: 0;
            $data['modul_code'] = $this->input->post('modul_code') ?: NULL;
            $this->db->insert('tabel_menu', $data);
            return 1;
        }
    }

    public function hapus()
    {
        $id = $this->input->post('hid');
        $this->db->where('id', $id)->delete('tabel_menu');
        $this->db->set('is_main_menu', "0");
        $this->db->where('is_main_menu', $id)->update('tabel_menu');
        return 1;
    }

    public function ubah()
    {
        $this->form_validation->set_rules('judul_menu_edit', 'Judul Menu', 'required');
        $this->form_validation->set_rules('link_edit', 'Link', 'required');
        $this->form_validation->set_rules('icon_edit', 'Icon', 'required');

        if ($this->form_validation->run() == FALSE) {
            return [form_error('judul_menu_edit'), form_error('link_edit'), form_error('icon_edit')];
        } else {

            $data['judul_menu'] = $this->input->post('judul_menu_edit');
            $data['link'] = $this->input->post('link_edit');
            $data['icon'] = $this->input->post('icon_edit');
            $data['is_main_menu'] = $this->input->post('is_main_menu_edit');
            $data['modul_code'] = $this->input->post('modul_code_edit') == "" ? null : $this->input->post('modul_code_edit');

            $this->db->where('id', $this->input->post('eid'));
            return $this->db->update('tabel_menu', $data);
        }
    }

    public function set_menu_sequence()
    {
        $data = $this->input->post('data');
        $list = "";
        foreach ($data as $key => $value) {
            $list .= "(" . $value['id'] . ", " . $value['is_main_menu'] . "," . $value['sequence'] . "),";
        }
        $list = rtrim($list, ", ");
        $query = "UPDATE tabel_menu as tm set
        is_main_menu = tm2.is_main_menu,
        sequence = tm2.sequence
        from (values
            $list
        ) as tm2(id, is_main_menu, sequence)
        where tm2.id = tm.id;
        ";
        $this->db->trans_start();

        $this->db->query($query);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_complete();
        }
        return $this->db->trans_status();
    }
}
                        
/* End of file */
