<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task_tamu extends MY_Controller
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
		$this->M_global->firstload();
		$this->load->model('task_tamu/M_task_tamu');
	}
	public function index()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$data["penghuni"] = $this->db->order_by('no_kavling', 'ASC')->where('b2b_token', $_SESSION["b2b_token"])->get('m_client')->result();
		$this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}
	public function detail($id)
	{
		$data['item'] = $this->M_task_tamu->fetchDetail($id);
		

		if (!$data) {
			$data['code'] = 404;
		} else {
			$data['code'] = 200;
			$status_server = false;
			if (get_img_to_server_other($this->config->item("base_url_server_cudo"))) {
				$status_server = true;
			}else{
				$status_server = false;
			}

			$no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");

			$new_foto_identitas = "";
			$new_foto_tamu = "";
			if ($status_server) {
				$this->load->library('curl');
				$image_cudo_identitas = $this->config->item("base_url_server_cudo") . "assets/identitas/" . $data['item']->foto_identitas;
				$image_cudo_pengunjung = $this->config->item("base_url_server_cudo") . "assets/tamu/" . $data['item']->foto_pengunjung;

				$image_identitas = $this->config->item('base_url_api') . "assets/images/tamu/foto_identitas/" . $data['item']->foto_identitas;
				$image_tamu = $this->config->item('base_url_api') . "assets/images/tamu/foto_pengunjung/" . $data['item']->foto_pengunjung;

				$result = $this->curl->simple_get($image_cudo_identitas);
				$result2 = $this->curl->simple_get($image_cudo_pengunjung);

				if($result != "" || $result2 != ""){
					$new_foto_identitas = $image_cudo_identitas;
					$new_foto_tamu = $image_cudo_pengunjung;
				}elseif(@getimagesize($image_identitas) || @getimagesize($image_tamu)){
					$new_foto_identitas = $image_identitas;
					$new_foto_tamu = $image_tamu;
				}else{
					$new_foto_identitas = $no_image;
					$new_foto_tamu = $no_image;
				}
			} else {
				$image_identitas = $this->config->item('base_url_api') . "assets/images/identitas/" . $data['item']->foto_identitas;
				$image_tamu = $this->config->item('base_url_api') . "assets/images/tamu/" . $data['item']->foto_pengunjung;

				if(@getimagesize($image_identitas) || @getimagesize($image_tamu)){
					$new_foto_identitas = $image_identitas;
					$new_foto_tamu = $image_tamu;
				}else{
					$new_foto_identitas = $no_image;
					$new_foto_tamu = $no_image;
				}
			}

			$data['image_cudo_identitas'] = $new_foto_identitas;
			$data['image_cudo_pengunjung'] = $new_foto_tamu;

			// $image_cudo_pengunjung = $this->config->item("base_url_server_cudo") . "assets/identitas/" . $data['item']->foto_identitas;
			// $image_cudo_identitas = $this->config->item("base_url_server_cudo") . "assets/tamu/" . $data['item']->foto_pengunjung;
			
			// if(@getimagesize($image_cudo_identitas)){
			// 	$data['image_cudo_identitas'] = $image_cudo_identitas;
			// }else {
			// 	$data['image_cudo_identitas'] = base_url("assets/apps/assets/dist/img/no-image.jpg");
			// }

			// if(@getimagesize($image_cudo_pengunjung)){
			// 	$data['image_cudo_pengunjung'] = $image_cudo_pengunjung;
			// }else {
			// 	$data['image_cudo_pengunjung'] = base_url("assets/apps/assets/dist/img/no-image.jpg");
			// }
		}

		$this->load->view('layout/header');
		$this->load->view('detail', $data);
		$this->load->view('layout/footer');
		$this->load->view('detail_script');
	}
	public function ajax($id_penghuni = 0, $month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");
		$data = $this->M_task_tamu->fetch($id_penghuni, $month, $year);
		echo json_encode($data);
	}
	public function chart()
	{
		$b2b_token = $_SESSION['b2b_token'];
		$fillter = '';
		if (isset($_POST["id_penghuni"]) && $_POST["id_penghuni"] != 0) {
			$id_penghuni = $_POST["id_penghuni"];
			$fillter = " AND id_client='$id_penghuni' ";
		}
		$Lmon = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		$out = $this->db->query("SELECT COUNT
            ( a1.datecont ) AS 
            VALUE
                ,
                RIGHT(a1.datee::varchar, 2) AS label,
                a1.datee AS DATE 
            FROM
                (
                SELECT
                    x1.datee AS datee,
                    x2.start_date as datecont 
                FROM
                    (
                    SELECT CAST
                        ( date_trunc( 'month', CURRENT_DATE ) AS DATE ) + i AS datee 
                    FROM
                        generate_series ( 0, DATE_PART( 'days', DATE_TRUNC( 'month', NOW( ) ) + '1 MONTH' :: INTERVAL - '1 DAY' :: INTERVAL )::INTEGER-1 ) i 
                    ) x1
                    LEFT JOIN ( SELECT start_date, start_time FROM t_task_tamu WHERE b2b_token = '" . $b2b_token . "' $fillter) x2 ON x2.start_date :: DATE = x1.datee 
                ) a1 
            GROUP BY
			a1.datee ORDER BY a1.datee ASC")->result_array();
		$temp = array();
		if (count($out) > 0) {
			foreach ($out as $row) {
				$temp["value"][] = (int)$row["value"];
				$temp["label"][] = $row["label"];
			}
		}
		$data["monthly"] = $temp;

		$out = $this->db->query("SELECT COUNT
            ( a1.monCnt ) AS 
            VALUE
                ,
                a1.mon AS label 
            FROM
                (
                SELECT
                    x1.mon,
                    x2.mon AS monCnt 
                FROM
                    (
                    SELECT UNNEST
                        mon 
                    FROM
                        UNNEST ( ARRAY [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ] ) 
                    ) x1
                    LEFT JOIN ( SELECT start_date, start_time, date_part( 'month', start_date ) AS MON FROM t_task_tamu WHERE start_date >= date_trunc( 'year', CURRENT_DATE ) AND b2b_token = '" . $b2b_token . "' $fillter) x2 ON x2.MON :: INTEGER = x1.mon :: INTEGER 
                ) a1 
            GROUP BY
                a1.mon 
            ORDER BY
			a1.mon ASC")->result_array();
		$temp = array();
		if (count($out) > 0) {
			foreach ($out as $row) {
				$temp["value"][] = (int)$row["value"];
				$temp["label"][] = $Lmon[$row["label"]];
			}
		}
		$data["year"] = $temp;
		echo json_encode($data);
	}
}
