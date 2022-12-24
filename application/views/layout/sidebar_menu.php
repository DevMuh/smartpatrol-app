<?php

$table = json_decode($this->session->userdata('table_id'));
$raw = $this->session->userdata('table');

$main = array_filter($raw, function($val){
    return $val->is_main_menu == 0;
});

usort($main, function($a, $b) {
    return $a->sequence > $b->sequence;
});

usort($table->id, function($a, $b) {
    return $a > $b;
});

$html = "
<ul class='metismenu'>
    <li class='nav-label'>" . $this->lang->line('main_menu') . "</li>";
foreach ($main as $key => $value) {
    $i = array_search($value->id, $table->id);
    if (!(array_search($value->id, $table->id) === FALSE)) {
           
        if (!(array_search($value->id, array_column($raw, 'is_main_menu'))  === FALSE)) {
            $html .= 
            "<li id='" . $value->judul_menu . "'>
                <a id='subm_" . $value->judul_menu . "' class='has-arrow material-ripple' href='" . $value->link . "'>
                    <i class='" . $value->icon . "'></i>
                    " .  $this->lang->line(str_replace(' ', '_', strtolower($value->judul_menu)))  . "
                </a>"; 
            $sub = array_filter($raw, function($val) use ($value){
                return $val->is_main_menu == $value->id;
            });
            usort($sub, function($a, $b) {
                return $a->sequence > $b->sequence;
            });
            $html .= "<ul class='nav-second-level'>";
            foreach ($sub as $key => $single) {
                
                    $html .= 
                    "<li id='" . $single->judul_menu . "'>
                        <a href='" . base_url() . '' . $single->link . "'>
                            <i class='" . $single->icon . "'></i>" . $this->lang->line(str_replace(' ', '_', strtolower($single->judul_menu))) ."
                        </a>
                    </li>";
            }
            $html .= "</ul>";
            $html .= "</li>";
        } else {
            $html .= 
                "<li id='" . $value->judul_menu . "'>
                    <a href='" . base_url() . '' . $value->link . "'>
                        <i class='" . $value->icon . "'></i>" . $this->lang->line(str_replace(' ', '_', strtolower($value->judul_menu))) . "
                    </a>
                </li>";
        }
    }
}
$html .= '</ul>';

echo $html;