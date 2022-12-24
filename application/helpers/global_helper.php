<?php if(!defined('BASEPATH')) exit('no direct access script allowed');

if (!function_exists('id_to_name'))
{
  	function id_to_name($table,$field,$id,$output_name) {
    	$ci=& get_instance();
   		$ci->load->database();

   			    $ci->db->limit(1);
   		$exec = $ci->db->get_where($table, array($field=>$id));

	    $a      = $exec->result_array();

	    if($exec->num_rows()>0){
	      foreach($a as $row){
	        $data=$row[$output_name];
	      }
	    } else {
            $data=$id;
        }

    	return (empty($data)) ? "" : $data;;
  	}
}

if (!function_exists('fetch')) {
    function fetch(string $url, array $headers = [], $data = []): string
    {
        $curl = \curl_init($url);

        if (!$curl) {
            throw new \RuntimeException('Unable to initialize curl.', 12);
        }

        \curl_setopt($curl, \CURLOPT_FAILONERROR, true);
        \curl_setopt($curl, \CURLOPT_FOLLOWLOCATION, true);
        \curl_setopt($curl, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($curl, \CURLOPT_TIMEOUT, 1500);

        if (!empty($headers)) {
            \curl_setopt($curl, \CURLOPT_HTTPHEADER, $headers);
        }
        if ($data) {
            \curl_setopt_array($curl, array(
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data
            ));
        }
        \curl_setopt($curl, \CURLOPT_USERAGENT,  "Mozilla/5.0 (Windows; U;   Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

        // if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        //     \curl_setopt($curl, \CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // }

        \curl_setopt($curl, \CURLOPT_SSL_VERIFYPEER, false);

        \curl_setopt($curl, \CURLOPT_HEADER, false);
        \curl_setopt($curl, \CURLOPT_CONNECTTIMEOUT, 120);

        $response = \curl_exec($curl);

        \curl_close($curl);

        if (!empty($response) && \is_string($response)) {
            return $response;
        }

        throw new \RuntimeException('Could not fetch data.');
    }
}