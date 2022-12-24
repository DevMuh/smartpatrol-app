<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task_kejadian extends MY_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model('task_kejadian/M_task_kejadian');
	}

	public function index()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$this->load->view('layout/header');
		$this->load->view('main');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}
	public function ajax($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");
		$data = $this->M_task_kejadian->fetch($month, $year);
		echo json_encode($data);
	}
	function check($url){
		if(@getimagesize($url)){
			return $url;
		}else {
			$url = base_url("assets/apps/assets/dist/img/no-image.jpg");
			return $url;
		}
	}
	public function detail($id)
	{
		$get = $this->M_task_kejadian->fetchDetail(urldecode($id));
		// cek status server storage
		$status_server = false;
        if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
            $status_server = true;
        }else{
            $status_server = false;
        }

		// $url_base = $this->config->item("base_url_api");
		// $url_server_cudo = $this->config->item("base_url_server_cudo");
		$no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");

		$base_url = "";
		$new_image_1 = "";
		$new_image_2 = "";
		$new_image_3 = "";
		if ($status_server) {
			// img in server storage
			$this->load->library('curl');
			$image_1_cudo = $this->config->item("base_url_server_cudo") . "assets/kejadian/" . $get['data']->image_1;
			$image_2_cudo = $this->config->item("base_url_server_cudo") . "assets/kejadian/" . $get['data']->image_2;
			$image_3_cudo = $this->config->item("base_url_server_cudo") . "assets/kejadian/" . $get['data']->image_3;
			
			$image1 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $get['data']->image_1;
			$image2 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $get['data']->image_2;
			$image3 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $get['data']->image_3;

			$result = $this->curl->simple_get($image_1_cudo);
			$result2 = $this->curl->simple_get($image_2_cudo);
			$result3 = $this->curl->simple_get($image_3_cudo);
			
			if($result != "" || $result2 != "" || $result3 != ""){
				$new_image_1 = $image_1_cudo;
				$new_image_2 = $image_2_cudo;
				$new_image_3 = $image_3_cudo;
			}elseif(@getimagesize($image1) || @getimagesize($image2) || @getimagesize($image3)){
				$new_image_1 = $image1;
				$new_image_2 = $image2;
				$new_image_3 = $image3;
			}else{
				$new_image_1 = $no_image;
				$new_image_2 = $no_image;
				$new_image_3 = $no_image;
			}
		} else {
			$image1 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $get['data']->image_1;
			$image2 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $get['data']->image_2;
			$image3 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $get['data']->image_3;

			if(@getimagesize($image1) || @getimagesize($image2) || @getimagesize($image3)){
				$new_image_1 = $image1;
				$new_image_2 = $image2;
				$new_image_3 = $image3;
			}else{
				$new_image_1 = $no_image;
				$new_image_2 = $no_image;
				$new_image_3 = $no_image;
			}
		}

		$data['datanya'] = $get['data'];
		// $data['image_1_cudo'] = $this->check(str_replace($url_base,$url_server_cudo."asset/",$get['data']->image_1));
		// $data['image_2_cudo'] = $this->check(str_replace($url_base,$url_server_cudo."asset/",$get['data']->image_2));
		// $data['image_3_cudo'] = $this->check(str_replace($url_base,$url_server_cudo."asset/",$get['data']->image_3));
		$data['image_1_cudo'] = $this->check($new_image_1);
		$data['image_2_cudo'] = $this->check($new_image_2);
		$data['image_3_cudo'] = $this->check($new_image_3);
		
		$data['loc'] = $get['loc'];
		// echo $data['datanya']->kategori;
		// die();
		if (!$data) {
			$data['code'] = 404;
		} else {
			$data['code'] = 200;
			switch ($data['datanya']->kategori) {
				case '1':
					$icon = 'car-crash';
					$color = '#b81919';
					break;
				case '2':
					$icon = 'fire';
					$color = '#b81919';
					break;
				case '3':
					$icon = 'mask';
					$color = '#ed9a00';
					break;
				case '4':
					$icon = 'skull-crossbones';
					$color = '#101010';
					break;
				default:
					$icon = 'minus';
					$color = 'black';
					break;
			}
			$data['icon'] = $icon;
			$data['color'] = $color;
		}
		// echo json_encode($data);

		$this->load->view('layout/header');
		$this->load->view('detail', $data);
		$this->load->view('layout/footer');
		$this->load->view('detail_script', $data);
	}
	public function export_pdf_json($b2b_token, $id)
	{
		$fdetail = $this->db->query("
					SELECT A.id_,
						A.no_kavling_tujuan AS block,
						A.lokasi as lokasi,
						A.submit_date,
						A.submit_time,
						A.kategori,
						A.kategori_name AS status,
						A.remark as note,
						CONCAT ( A.submit_date,' ', b.submit_time)  AS TIME,
						
						b.img_name AS image_1,
						c.img_name AS image_2,
						d.img_name AS image_3	
					FROM
						t_task_kejadian AS A 
						LEFT JOIN t_task_kejadian_image b ON A.id_ = b.id_kejadian :: INTEGER AND b.img_name LIKE'%image_kejadian_1%'
						LEFT JOIN t_task_kejadian_image c ON A.id_ = c.id_kejadian :: INTEGER AND c.img_name LIKE'%image_kejadian_2%'
						LEFT JOIN t_task_kejadian_image d ON A.id_ = d.id_kejadian :: INTEGER AND d.img_name LIKE'%image_kejadian_3%'
						WHERE A.id_='" . $id . "' AND A.b2b_token='$b2b_token'")->row();

		$detail_task_kejadian = new stdClass();
		$detail_task_kejadian->kategori_name = $fdetail->status;
		$detail_task_kejadian->submit_date = date_format(date_create($fdetail->submit_date), "d F Y") . " - " . $fdetail->submit_time . ' WIB';
		$detail_task_kejadian->block = $fdetail->block;
		$detail_task_kejadian->location = $fdetail->location;
		$detail_task_kejadian->note = $fdetail->note;

		$status_server = false;
        if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
            $status_server = true;
        }else{
            $status_server = false;
        }

		$no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");

		$new_image_1 = "";
		$new_image_2 = "";
		$new_image_3 = "";

		$new_img1_label = "";
		$new_img2_label = "";
		$new_img3_label = "";

		if ($status_server) {
			// img in server storage
			$this->load->library('curl');
			$image_1_cudo = $this->config->item("base_url_server_cudo") . "assets/kejadian/" . $fdetail->image_1;
			$image_2_cudo = $this->config->item("base_url_server_cudo") . "assets/kejadian/" . $fdetail->image_2;
			$image_3_cudo = $this->config->item("base_url_server_cudo") . "assets/kejadian/" . $fdetail->image_3;
			
			$image1 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $fdetail->image_1;
			$image2 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $fdetail->image_2;
			$image3 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $fdetail->image_3;

			$date = strtotime($fdetail->submit_date);
			$time = strtotime($fdetail->submit_time);
            $newformat = date('Ymd',$date).date('His',$time);

			$label = "img1_".$fdetail->id_."_".$newformat.".jpg";
			$label2 = "img2_".$fdetail->id_."_".$newformat.".jpg";
			$label3 = "img3_".$fdetail->id_."_".$newformat.".jpg";

			$result = $this->curl->simple_get($image_1_cudo);
			$result2 = $this->curl->simple_get($image_2_cudo);
			$result3 = $this->curl->simple_get($image_3_cudo);
			
			if($result != "" || $result2 != "" || $result3 != ""){
				// $new_image_1 = $image_1_cudo;
				// $new_image_2 = $image_2_cudo;
				// $new_image_3 = $image_3_cudo;
				$new_image_1 = 'data:image/png;base64,' . base64_encode(fetch($image_1_cudo));
				$new_image_2 = 'data:image/png;base64,' . base64_encode(fetch($image_2_cudo));
				$new_image_3 = 'data:image/png;base64,' . base64_encode(fetch($image_3_cudo));

				$new_img1_label = $label;
				$new_img2_label = $label2;
				$new_img3_label = $label3;
			}elseif(@getimagesize($image1) || @getimagesize($image2) || @getimagesize($image3)){
				// $new_image_1 = $image1;
				// $new_image_2 = $image2;
				// $new_image_3 = $image3;
				$new_image_1 = 'data:image/png;base64,' . base64_encode(fetch($image1));
				$new_image_2 = 'data:image/png;base64,' . base64_encode(fetch($image2));
				$new_image_3 = 'data:image/png;base64,' . base64_encode(fetch($image3));

				$new_img1_label = $label;
				$new_img2_label = $label2;
				$new_img3_label = $label3;
			}else{
				// $new_image_1 = $no_image;
				// $new_image_2 = $no_image;
				// $new_image_3 = $no_image;
				$new_image_1 = 'data:image/png;base64,' . base64_encode(fetch($no_image));
				$new_image_2 = 'data:image/png;base64,' . base64_encode(fetch($no_image));
				$new_image_3 = 'data:image/png;base64,' . base64_encode(fetch($no_image));

				$new_img1_label = "no image available";
				$new_img2_label = "no image available";
				$new_img3_label = "no image available";
			}
		} else {
			$image1 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $fdetail->image_1;
			$image2 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $fdetail->image_2;
			$image3 = $this->config->item('base_url_api') . "assets/images/kejadian/" . $fdetail->image_3;

			$date = strtotime($fdetail->submit_date);
			$time = strtotime($fdetail->submit_time);
            $newformat = date('Ymd',$date).date('His',$time);

			$label = "img1_".$fdetail->id_."_".$newformat.".jpg";
			$label2 = "img2_".$fdetail->id_."_".$newformat.".jpg";
			$label3 = "img3_".$fdetail->id_."_".$newformat.".jpg";

			if(@getimagesize($image1) || @getimagesize($image2) || @getimagesize($image3)){
				// $new_image_1 = $image1;
				// $new_image_2 = $image2;
				// $new_image_3 = $image3;
				$new_image_1 = 'data:image/png;base64,' . base64_encode(fetch($image1));
				$new_image_2 = 'data:image/png;base64,' . base64_encode(fetch($image2));
				$new_image_3 = 'data:image/png;base64,' . base64_encode(fetch($image3));

				$new_img1_label = $label;
				$new_img2_label = $label2;
				$new_img3_label = $label3;
			}else{
				// $new_image_1 = $no_image;
				// $new_image_2 = $no_image;
				// $new_image_3 = $no_image;
				$new_image_1 = 'data:image/png;base64,' . base64_encode(fetch($no_image));
				$new_image_2 = 'data:image/png;base64,' . base64_encode(fetch($no_image));
				$new_image_3 = 'data:image/png;base64,' . base64_encode(fetch($no_image));

				$new_img1_label = "no image available";
				$new_img2_label = "no image available";
				$new_img3_label = "no image available";
			}
		}

		$detail_task_kejadian->base64_img1 = $new_image_1;
		$detail_task_kejadian->base64_img2 = $new_image_2;
		$detail_task_kejadian->base64_img3 = $new_image_3;
		$detail_task_kejadian->label_img1 = $new_img1_label;
		$detail_task_kejadian->label_img2 = $new_img2_label;
		$detail_task_kejadian->label_img3 = $new_img3_label;

		$websiteURL = $this->config->item('base_url_api') . "cli_trigger/map_task_kejadian/".$id;
        $opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
        //Basically adding headers to the request
        $context = stream_context_create($opts);
        $api_response = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$websiteURL&screenshot=true", false, $context);
		$result = json_decode($api_response, true);
		$map_screen_shoot = $result['lighthouseResult']['audits']['full-page-screenshot']['details']['screenshot']['data'];

		$new_map = "";
		if ($map_screen_shoot) {
			$new_map = $map_screen_shoot;
		}else{
			$new_map = 'data:image/png;base64,' . base64_encode(fetch($no_image));
		}

		$detail_task_kejadian->map_screen_shoot = $new_map;

		if ($detail_task_kejadian) {
			echo json_encode([
				"status" => true,
				"data" => $detail_task_kejadian
			]);
		} else {
			echo json_encode([
				"status" => false,
				"data" => $detail_task_kejadian
			]);
		}
	}

	public function test_map()
	{	
		$websiteURL = $this->config->item('base_url_api') . "cli_trigger/map_task_kejadian/298";
        $opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
        //Basically adding headers to the request
        $context = stream_context_create($opts);
        $api_response = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$websiteURL&screenshot=true", false, $context);
		//var_dump($api_response);
		$result = json_decode($api_response, true);
        // //screenshot data
        // $screenshot = $result['screenshot']['data'];
		echo $result['lighthouseResult']['audits']['full-page-screenshot']['details']['screenshot']['data'];
	}
}
