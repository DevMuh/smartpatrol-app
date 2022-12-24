<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_task_patrol extends CI_Model
{

    public function fetch($month, $year, $day=null)
    {
        $b2b = $this->session->userdata('b2b_token');
        $fetch = $this->db->query("SELECT t_task_patrol_header.*, users.full_name FROM t_task_patrol_header left join users on users.id::varchar=t_task_patrol_header.taken_user 
        WHERE t_task_patrol_header.b2b_token = '$b2b'
        AND  t_task_patrol_header.publish_date::TEXT LIKE '%$year-$month-$day%'")->result_array();
        $querytrack = $this->db->query("SELECT created_time FROM track_route WHERE b2b_token='$b2b' AND created_time::TEXT LIKE '%$year-$month-$day%'")->result_array();
        // var_dump($querytrack);die;
        $grouped_array = array();
        foreach ($fetch as $element) {
            $grouped_array[$element['taken_user']." ".$element['publish_date']][] = $element;
        }
        
        $data = array();
        foreach ($grouped_array as $key => $value) {
            $id_ = "";
            $b2b_token = "";
            $cluster_name = "";
            $full_name = "";
            $publish_date = "";
            $publish_time = "";
            $done_date = "";
            $done_time = "";
            $total_cp = "";
            $total_done = "";
            
            $row = $value[0];
            
            if (count($value) == 1) {
                $id_ = $value[0]['id_'];
                $b2b_token = $value[0]['b2b_token'];
                $cluster_name = $value[0]['cluster_name'];
                $full_name = $value[0]['full_name'];  
                $publish_date = $value[0]['publish_date'];
                $publish_time = $value[0]['publish_time'];
                $done_date = $value[0]['done_date'];
                $done_time = $value[0]['done_time'];
                $total_cp = $value[0]['total_cp'];
                $total_done = $value[0]['total_done'];
                // track route 
                $arr = [];
                foreach ($querytrack as $key => $valuetrack) {
                    $stringdate = explode(" ",$valuetrack["created_time"]);
                    // $stringtime = explode(" ",$valuetrack->created_time[0]);
                    // $stringdatedone = explode(" ",$valuetrack["created_time"]);
                    // $stringtimedone = explode(" ",$valuetrack->created_time[$counttrack]);
                    if ($value[0]['publish_date'] ==  $stringdate[0] ) {
                        array_push($arr,$stringdate);
                        // var_dump($stringdate);die;
                    }
                }
                $counttrack = count($arr)-1;
                $publish_date = $arr[0][0];
                $publish_time = $arr[0][1];
                $done_date = $arr[$counttrack][0];
                $done_time = $arr[$counttrack][1];
                // var_dump($arr);die;



                //echo $value[0]['cluster_name'];
                $row['done_time'] = $done_time;
            }else if (count($value) > 1) {
                $sum_total_cp = count($value);
                
                $id_ = $value[0]['id_'];
                $b2b_token = $value[0]['b2b_token'];
                $cluster_name = $value[0]['cluster_name'];
                $full_name = $value[0]['full_name'];  
                $publish_date = $value[0]['publish_date'];
                $publish_time = $value[0]['publish_time'];
                $done_date = $value[count($value)-1]['done_date'];
                $done_time = $value[count($value)-1]['done_time'];
                $total_cp = $sum_total_cp;
                $total_done = $value[0]['total_done'];
                
                $row['done_time'] = $done_time;
            }

            $temp = array();
            $url = base_url('task_patrol/checkpoint/' . $b2b_token . '/' . $id_);
            $temp[] = $cluster_name;
            $temp[] = $full_name == '' ? 'Anonim' : $full_name;
            $temp[] = $publish_date . " " . $publish_time;
            $temp[] = $done_date . " " . $done_time;
            if (!empty($done_time)) {
                $start_date = new DateTime($publish_time);
                $end_date = new DateTime($done_time);
                $dd = date_diff($end_date, $start_date);
                $temp[] = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
            } else {
                $temp[] = 0;
            }
            $temp[] = $total_cp;
            //$temp[] = $total_done;
            $temp[] = "<a title='Detail' class='btn mybt detail' target='_blank' href='$url'><span class=' badge badge-info'><i class='fa fa-map-marked'></i></span></a>
                        <a href='#' class='btn mybt detail' onclick='exportTaskPatrolPdf(this); return false;' data-id='".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8')."' title='Export Task Patrol PDF' ><span class=' badge badge-info'><i class='fa fa-file-pdf'></i></span></a>
                        <a href='#' class='btn mybt detail' onclick='exportTaskPatrolExcel(this); return false;' data-id='".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8')."' title='Export Task Patrol Excel' ><span class=' badge badge-info'><i class='fa fa-file-excel'></i></span></a>";
            $data[] = $temp;
        }
        
        // foreach ($fetch as $row) {
        //     $temp = array();
        //     $url = base_url('task_patrol/checkpoint/' . $row->b2b_token . '/' . $row->id_);
        //     $temp[] = $row->cluster_name;
        //     $temp[] = $row->full_name == '' ? 'Anonim' : $row->full_name;
        //     $temp[] = $row->publish_date . " " . $row->publish_time;
        //     $temp[] = $row->done_date . " " . $row->done_time;
        //     if (!empty($row->done_time)) {
        //         $start_date = new DateTime($row->publish_time);
        //         $end_date = new DateTime($row->done_time);
        //         $dd = date_diff($end_date, $start_date);
        //         $temp[] = $dd->h . " H, " . $dd->i . " M ". $dd->s . " S";
        //     } else {
        //         $temp[] = 0;
        //     }
        //     $temp[] = $row->total_cp;
        //     $temp[] = $row->total_done;
        //     $temp[] = "<a title='Detail' class='btn mybt detail' target='_blank' href='$url'><span class=' badge badge-info'><i class='fa fa-map-marked'></i></span></a>
        //                 <a href='#' class='btn mybt detail' onclick='exportTaskPatrolPdf(this); return false;' data-id='".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8')."' title='Export Task Patrol PDF' ><span class=' badge badge-info'><i class='fa fa-file-pdf'></i></span></a>";
        //     $data[] = $temp;
        // }
        
        // echo json_encode($grouped_array);die;
        
        return array('data' => $data);
    }
    public function fetchCp($id)
    {
        $b2b = $this->session->userdata('b2b_token');
        $fdetail = $this->db->where('id_header', $id)->where('b2b_token', $b2b)->order_by('cp_id', 'asc')->get('t_task_patrol_detail')->result();
        if (count($fdetail) == 0) {
            return FALSE;
        } else {
            $data = array();
            foreach ($fdetail as $row) {
                $temp = array();
                $url = base_url('task_patrol/detail/' . $row->id_);
                $temp[] = "<a class='btn btn-info' href='$url'>Detail</button";
                $temp[] = $row->cp_id;
                $temp[] = $row->cp_name;
                $data[] = $temp;
            }
            return array('data' => $data);
        }
    }
    public function fetchDetail($id)
    {
        $b2b = $this->session->userdata('b2b_token');
        $fimage = $this->db->select('img_name as a')
            ->where('id_header', $id)->where('b2b_token', $b2b)->get('t_task_patrol_image')->result();
        $image = array();
        if (count($fimage) == 0) {
            $url = base_url('assets/apps/assets/dist/img/no_pic.png');
            $image['images'][] = $url;
        } else {
            foreach ($fimage as $row) {
                $image['images'][] = $row->a;
            }
        }
        $fdetail = $this->db->where('id_', $id)->where('b2b_token', $b2b)->get('t_task_patrol_detail')->result_array();
        if (count($fdetail) == 0) {
            return FALSE;
        } else {
            return array_merge($fdetail[0], $image);
        }
    }
}
