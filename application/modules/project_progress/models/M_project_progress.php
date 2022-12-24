<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_project_progress extends CI_Model
{

    public function fetch()
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->query('SELECT
        "public".project_progress."id",
        "public".project_progress.b2b_token,
        "public".project_progress.submit_time,
        "public".project_progress.project_photo,
        "public".project_progress.progress,
        "public".project_progress.notes,
        "public".project_progress.id_user,
        "public".project_progress.project_name,
        "public".users.full_name
        FROM
        "public".project_progress
        INNER JOIN "public".users ON "public".project_progress.id_user::integer = "public".users."id"
        WHERE "public".project_progress.b2b_token = \''.$b2b.'\'
        ORDER BY
        "public".project_progress."id" DESC
        ')->result();
        $data = array();
        $i = 1;
        foreach ($fetch as $row) {
            $temp = array();
            $temp[] = $i;
            $temp[] = $row->project_name;
            $temp[] = $row->submit_time;
            $temp[] = $row->progress.'%';
            $temp[] = $row->notes;
            $temp[] = $row->full_name;
            $i++;
            $data[] = $temp;
        }
        return array('data' => $data);
    }
}
                        
/* End of file x.php */
