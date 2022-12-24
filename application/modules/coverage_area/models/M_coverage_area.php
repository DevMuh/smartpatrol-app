<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class M_client_list extends CI_Model {
                        
public function fetch(){
    $fetch = $this->db->select(['no_kavling', 'client_name', 'phone'])->
        where('flag_disable', 1)->get('m_client')->result();
    $data = array();
    $i = 1;
    foreach($fetch as $row){
        $temp = array();
        $temp[] = $i;
        $temp[] = $row->no_kavling;
        $temp[] = $row->client_name;
        $temp[] = $row->phone;
        $i++;
        $data[] = $temp;
    }
    return array('data' => $data);
}
                        
                            
                        
}
                        
/* End of file x.php */
    
                        