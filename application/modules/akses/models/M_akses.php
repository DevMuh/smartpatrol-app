<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_akses extends CI_Model
{

    public function fetch()
    {
        $fetch = $this->db->order_by('id', 'desc')->get('user_role')->result();
        $table = $this->db->order_by('id', 'desc')->get('tabel_menu')->result();
        $data = array();
        $i = 1;
        foreach ($fetch as $row) {
            $tlist = '';
            $temp = array();
            $temp[] = $i;
            $temp[] = $row->roles_type;
            $list = json_decode($row->table_id);
            foreach ($table as $row2) {
                for ($j = 0; $j < count($list->id); $j++) {
                    if ($row2->id == $list->id[$j]) {
                        $tlist = $row2->judul_menu . ', ' . $tlist;
                    }
                    if ($list->id[0] == 'ALL') {
                        $tlist = $row2->judul_menu . ', ' . $tlist;
                    }
                }
            }
            $temp[] = substr($tlist, 0, -2);
            $temp[] = $row->flag_active == 1 ? 'Active' : 'Inactive';
            $temp[] = "<button data-toggle='modal' data-row='" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . "' data-target='#editModal' onclick='edit($row->id, event,this)' class='btn mybt'><span class='typcn typcn-pencil'></span></button>";
            $temp[] = "<button data-toggle='modal' data-target='#hapusModal' onclick='hapus($row->id, event)' class='btn mybt'><span style='font-size:18px' class='typcn typcn-power'></span></button>";
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }

    public function add_akses()
    {
        $this->form_validation->set_rules('roles_type', 'Role/Peran', 'required', array('required' => 'Kolom {field} harus diisi!'));
        $this->form_validation->set_rules('table[]', 'Ijin', 'required', array('required' => 'Kolom {field} harus diisi!'));

        if ($this->form_validation->run() == FALSE) {
            return [form_error('roles_type'), form_error('table[]')];
        } else {
            $data['flag_active'] = 1;
            $data['roles_type'] = $this->input->post('roles_type');
            $data['table_id'] = json_encode(array('id' => $this->input->post('table[]')));
            $data_permission = [];
            foreach ($this->input->post('permission[]') as $value) {
                $new_value = explode("|",$value);

                $temp = new stdClass();
		        $temp->action = $new_value[0];
                $temp->text = $new_value[1];
                
                array_push($data_permission,$temp);
            }
            $data['additional_flag'] = json_encode(array('permission' => $data_permission));
            $this->db->insert('user_role', $data);
            return 1;
        }
    }

    public function edit_akses()
    {
        $this->form_validation->set_rules('eroles_type', 'Role/Peran', 'required', array('required' => 'Kolom {field} harus diisi!'));
        $this->form_validation->set_rules('etable[]', 'Ijin', 'required', array('required' => 'Kolom {field} harus diisi!'));

        if ($this->form_validation->run() == FALSE) {
            return [form_error('eroles_type'), form_error('etable[]')];
        } else {
            $id = $this->input->post('eid');
            $data['flag_active'] = 1;
            $data['roles_type'] = $this->input->post('eroles_type');
            $data['table_id'] = json_encode(array('id' => $this->input->post('etable[]')));
            $data_permission = [];
            foreach ($this->input->post('epermission[]') as $value) {
                $new_value = explode("|",$value);

                $temp = new stdClass();
		        $temp->action = $new_value[0];
                $temp->text = $new_value[1];
                
                array_push($data_permission,$temp);
            }
            $data['additional_flag'] = json_encode(array('permission' => $data_permission));
            $this->db->where('id', $id)->update('user_role', $data);
            return 1;
        }
    }

    public function hapus_akses()
    {
        $id = $this->input->post('hid');
        $stat = $this->input->post('stat');
        $data['flag_active'] = $stat;
        $this->db->where('id', $id)->update('user_role', $data);
        return 1;
    }

    public function fetchuser()
    {
        $fetch  = $this->db->select(['id', 'username', 'full_name', 'user_roles', 'status'])->from('users')->where('users.user_roles !=', 'admin')->get()->result();
        $data = array();
        $i = 1;
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $i;
            $temp[] = $row->username;
            $temp[] = $row->full_name;
            $temp[] = $row->user_roles;
            // if(strtolower($row->status)=='active'){
            //     $temp[] = '<div id="active" onclick="changeStatus(\''.$row->id.' \')">
            //                     <div class="switch-button switch-button-lg">
            //                         <label for="item3"></label>
            //                     </div>
            //                 </div>' ;
            // }else{
            //     $temp[] = '<div id="inactive" onclick="changeStatus(\''.$row->id.' \')">
            //                     <div class="switch-button switch-button-lg">
            //                         <span><label for="item3"></label></span>
            //                     </div>
            //                 </div>';
            // }
            $temp[] = $row->status;
            $temp[] = "<button onclick='edituser($row->id)' class='btn mybt'><span class='typcn typcn-pencil'></span></button>";
            $data[] = $temp;
            $i++;
        }
        return array('data' => $data);
    }
}
                        
/* End of file x.php */
