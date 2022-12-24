<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_login extends CI_Model
{

    public function login_validation($username, $app_id)
    {
        return $this->db->query("SELECT
        users.*,
        user_role.table_id,
        -- user_role.b2b_token,
        m_register_b2b.title_nm, 
        m_register_b2b.path_logo,
        m_register_b2b.other_data->>'hidden_feature' as hidden_feature 
        FROM
        users
        LEFT JOIN user_role ON users.user_roles::TEXT = user_role.roles_type::TEXT
        LEFT JOIN m_register_b2b ON m_register_b2b.b2b_token::TEXT = users.b2b_token::TEXT
        WHERE
        users.username = '$username' OR users.no_tlp = '$username'
        -- AND m_register_b2b.app_id::TEXT = '$app_id'::TEXT
        LIMIT 1");
    }
    public function menu($hidden_feature)
    {
        $w_hidden_feature = "'" . implode("','",  json_decode($hidden_feature)) . "'";
        return $this->db->query("SELECT * FROM tabel_menu WHERE id NOT IN (SELECT id FROM tabel_menu WHERE modul_code in ($w_hidden_feature)) ORDER BY id asc")->result();
    }
    public function menu_v2($hidden_feature, $granted)
    {
        $w_hidden_feature = "'" . implode("','",  json_decode($hidden_feature)) . "'";
        return $this->db->query("SELECT * FROM tabel_menu WHERE id NOT IN (SELECT id FROM tabel_menu WHERE modul_code in ($w_hidden_feature)) ORDER BY is_main_menu, sequence asc")->result();
    }
    public function menu_v3($role, $hidden_feature = "")
    {
        
        $w_hidden_feature = "'" . implode("','",  json_decode($hidden_feature)) . "'";
        return $this->db->query("SELECT * FROM tabel_menu 
        WHERE id::text IN (
            SELECT REPLACE(jsonb_array_elements(user_role.table_id->'id')::text, '\"', '') from user_role
            where roles_type = '$role'
        )
        and id NOT IN (SELECT id FROM tabel_menu WHERE modul_code in ($w_hidden_feature)) 
        ORDER BY is_main_menu, sequence asc;
		")->result();
    }
    public function detail_b2b($b2b)
    {
        return $this->db->query("SELECT *,other_data->>'hidden_feature' as hidden_feature FROM m_register_b2b WHERE b2b_token = '$b2b' ")->row();
    }
}
