<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absensi extends MX_Controller
{

	private const _BULAN = [
		'',
		'JANUARI',
		'FEBRUARI',
		'MARET',
		'APRIL',
		'MEI',
		'JUNI',
		'JULI',
		'AGUSTUS',
		'SEPTEMBER',
		'OKTOBER',
		'NOVEMBER',
		'DESEMBER'
	];

	public function tes()
	{
		$time1 = new DateTime('15:00');
		$time2 = new DateTime('14:51:38');
		$t = $time1;
		$t->modify("+0 minutes");
		$interval = $time1->diff($time2);
		echo  $interval->h . ' j, ' . $interval->i . ' m';
	}

	public function coba()
	{
		$url = "http://cudo.co.id:1515/assets/absen_checkpoint/66161b3b6e209c4672493060b282aefe/890/2022-06-21/161326/photo_890_POS_LOWER2.jpg";
        //$test = json_decode(fetch($url))->data;
		$base64_ = 'data:image/png;base64,' . base64_encode(fetch($url));
		echo '<pre>';
		print_r($base64_);
		echo '</pre>'; 
		die();
		$no_image = base_url("assets/apps/assets/dist/img/no-image.jpg");
		$img2 = file_get_contents($no_image);
        $base64_ = 'data:image/png;base64,' . base64_encode($img2);
		echo $base64_; 
		die();
	}

	public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model('M_absensi');
		$this->load->model('M_dashboard');
		$this->b2b = $this->session->userdata("b2b_token");
		$this->load->model('M_register_b2b');
	}


	public function index()
	{
		// $url = "http://cudo.co.id:1515/assets/absen_checkpoint/66161b3b6e209c4672493060b282aefe/890/2022-06-21/161326/photo_890_POS_LOWER2.jpg";
        // //$test = json_decode(fetch($url))->data;
		// $base64_ = 'data:image/png;base64,' . base64_encode(fetch($url));
		// echo '<pre>';
		// print_r($base64_);
		// echo '</pre>'; 
		// die();

		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$summary = $this->M_dashboard->summary_absen();
		$data['add_permission'] = false;
		$data['edit_permission'] = false;
		$data['role'] = $this->session->userdata('user_roles');
		if ($data['role'] == "srbadmin" || $data['role'] == "admin") {
			$data['add_permission'] = true;
		}else if ($data['role'] == "cudo" || $data['role'] == "superadmin") {
			$data['add_permission'] = true;
			$data['edit_permission'] = true;
		}
		$data['total_user'] = $summary['user']->all ?: 0;
		$data['total_attend'] = $summary['total_attend'] ?: 0;
		$data['total_absence'] = $summary['total_absence'] ?: 0;
		$data['total_onsite'] = $summary['total_onsite'] ?: 0;
		$data['total_via'] = $summary['total_via'] ?: 0;
		$data['table'] = $this->db->order_by('id', 'asc')->get('tabel_menu')->result();
		$data['select'] = $this->db->select("*, additional_flag->>'permission' as permission")->order_by('id', 'asc')->where_not_in('roles_type', 'admin')->where('flag_active', '1')->get('user_role')->result();
		$data['select2'] = $this->db->select("*, additional_flag->>'permission' as permission")->order_by('id', 'asc')->where(array('flag_active' => '1', 'roles_type' => $data['role']))->get('user_role')->result();
		// echo $this->db->last_query(); die();
		$current_page = $this->uri->segment(1);
		$select_current_page = $this->db->get_where('tabel_menu', array('link' => $current_page))->row();

		$all_permission = [];
		foreach ($data['select2'] as $value) {
			if ($value->permission) {
				$new_permission = json_decode($value->permission);
				//echo $value->permission;
				for ($i=0; $i < count($new_permission); $i++) { 
					$data_permission = $new_permission[$i]->text;
					$data_permission_action = $new_permission[$i]->action;
					if (str_contains($data_permission, $select_current_page->judul_menu)) {
						array_push($all_permission, $data_permission_action);
					}
				}
			}
		}

		$data['permission_hide'] = $all_permission;

		$this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}

	public function claim()
	{
		$this->M_absensi->add_claim();
		return redirect('absensi/index');
	}



	public function ajax_sum($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");
		$data = $this->M_absensi->all_summary($month, $year);
		echo json_encode(["data" => $data]);
	}

	public function ajax($month = null, $year = null, $return = false)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");
		$data = $this->M_absensi->fetch_v2($month, $year);
		if ($return) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}

	public function ajax_off_panggil($month = null, $year = null, $return = false)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");
		$data = $this->M_absensi->fetch_off_panggil($month, $year);
		if ($return) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}

	public function ajax_excel($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");
		//$data = $this->M_absensi->fetch_v2_export($month, $year);
		$data = $this->M_absensi->fetch_v3_export($day=null,$month, $year);
		$columns = array(
			(object) array(
				"header" => "Payrol ID",
				"key" => "Payrol ID",
				"width" => "37",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Nama Lengkap",
				"key" => "Nama Lengkap",
				"width" => "37",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Organization",
				"key" => "Organization",
				"width" => "35",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Nama Shift",
				"key" => "Nama Shift",
				"width" => "12",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Durasi Shift",
				"key" => "Durasi Shift",
				"width" => "15",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Shift Mulai",
				"key" => "Shift Mulai",
				"width" => "15",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Shift Akhir",
				"key" => "Shift Akhir",
				"width" => "15",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Waktu Masuk",
				"key" => "Waktu Masuk",
				"width" => "25",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Waktu Pulang",
				"key" => "Waktu Pulang",
				"width" => "25",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Waktu Telat Masuk",
				"key" => "Waktu Telat Masuk",
				"width" => "22",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Pulang Lebih Awal",
				"key" => "Pulang Lebih Awal",
				"width" => "22",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Total Kerja",
				"key" => "Total Kerja",
				"width" => "14",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Total Lembur Awal",
				"key" => "Total Lembur Awal",
				"width" => "22",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Total Lembur Akhir",
				"key" => "Total Lembur Akhir",
				"width" => "22",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Tempat Masuk",
				"key" => "Tempat Masuk",
				"width" => "35",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Tempat Keluar",
				"key" => "Tempat Keluar",
				"width" => "35",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Status Lembur",
				"key" => "Status Lembur",
				"width" => "16",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Remark Lembur",
				"key" => "Remark Lembur",
				"width" => "36",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
		);
		return (object)array(
			"columns" => $columns,
			"rows" => $data
		);
	}
	public function ajax_event($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");
		$data = $this->M_absensi->fetch_event($month, $year);
		echo json_encode($data);
	}
	public function ajax_checkpoint($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");

		$data = $this->M_absensi->fetch_checkpoint($month, $year);
		echo json_encode($data);
	}
	public function export_checkpoint_json($userid, $shiftname, $date, $time_masuk, $time_pulang)
	{
		$dateformat = date("Y-m-d", strtotime($date));
		$data = $this->M_absensi->fetch_checkpoint_export($userid,$dateformat);
		$data_header = array(
			"full_name" => id_to_name('users', 'id', $userid, 'full_name'),
			"shift_name" => $shiftname,
			"date" => $date,
			"waktu_masuk" => $time_masuk,
			"waktu_pulang" => $time_pulang
		);

		$new_data = array('data'=> $data, 'data_header' => $data_header);
		
		if ($data) {
			echo json_encode([
				"status" => true,
				"data" => $new_data
			]);
		} else {
			echo json_encode([
				"status" => false,
				"data" => $new_data
			]);
		}
	}
	public function table_absen_reguler($id, $date)
	{
		$dateformat = date("Y-m-d", strtotime($date));
		$b2b = $this->session->userdata('b2b_token');
		// $this->db->join('qr', 'log_absensi_reguler.qr_id = qr.qr_id', "left");
		// $query = $this->db->order_by('log_absensi_reguler.submit_time', 'asc')->get_where("log_absensi_reguler", [
		// 	"log_absensi_reguler.user_id" => $id,
		// 	"log_absensi_reguler.b2b_token" => $b2b,
		// 	'log_absensi_reguler.date' => $dateformat
		// 	// 'log_absensi_reguler.submit_time::date' => $dateformat
		// ])->result();
		$sql = "SELECT *
		FROM log_absensi_reguler
		LEFT JOIN qr ON log_absensi_reguler.qr_id = qr.qr_id
		WHERE log_absensi_reguler.user_id = '$id'
		AND log_absensi_reguler.b2b_token = '$b2b'
		AND log_absensi_reguler.submit_time::date = '$dateformat'
		ORDER BY log_absensi_reguler.submit_time ASC";
		$query_exec = $this->db->query($sql);
		$query = $query_exec->result();
		$data = [];
		$no = 1;
		foreach ($query as $key) {
			$temp = [];
			$temp[] = $key->name;
			$temp[] = date("H:i:s", strtotime($key->submit_time));
			$temp[] = $key->lat;
			$temp[] = $key->long;
			$data[] = $temp;
			$no++;
		}
		echo json_encode(["data" => $data]);
	}

	public function html_log_reguler()
	{
		$this->load->view('table_log_reguler');
	}

	public function html_absen()
	{
		$this->load->view('table_absen');
	}

	public function ajax_log_reguler($month, $year, $return = false)
	{
		$b2b = $this->session->userdata('b2b_token');
		$b2b_tokens_arr = [];
		$b2b_with_child = $this->M_register_b2b->b2b_with_child($b2b);
		foreach ($b2b_with_child as $key) {
			$b2b_tokens_arr[] = $key->b2b_token;
		}
		$day = $this->input->get('day') ?: false;
		$extra_query = "";
		if ($day) $extra_query .= "and extract(day from a.submit_time::TIMESTAMP) = '"  . $day . "'";
		$query = $this->db->query("SELECT * FROM log_absensi_reguler a
		left join qr on a.qr_id = qr.qr_id
		left join users u  on u.id::text = a.user_id
		WHERE
		a.b2b_token in ?
		$extra_query
		and extract(month from a.submit_time::TIMESTAMP) = ?
		and extract(year from a.submit_time::TIMESTAMP) = ?
		order by a.submit_time asc", [$b2b_tokens_arr, $month, $year])->result();
		// log_message('error', "======================== log absen reguler");
        // log_message('error', $this->db->last_query());
		$data = [];
		$no = 1;
		foreach ($query as $key) {
			$temp = [];
			$temp[] = $key->payroll_id;
			$temp[] = $key->full_name;
			$temp[] = date("d-m-Y", strtotime($key->submit_time));
			$temp[] = date("H:i:s", strtotime($key->submit_time));
			$temp[] = $key->lat;
			$temp[] = $key->long;
			$temp[] = ($key->name == NULL) ? "Check Point tidak terdaftar" : $key->name;
			$data[] = $temp;
			$no++;
		}
		if ($return) {
			return $data;
		} else {
			echo json_encode(["data" => $data]);
		}
	}

	public function form_claim()
	{
		$this->load->view('form_claim');
	}

	public function log_excel($month, $year)
	{
		$starttime = microtime(true);
		$data_log = $this->ajax_log_reguler($month, $year, true);
		$loadtime = "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds, the microtime : " . $starttime;
        log_message('error', "======================== loadtime controller : Absensi method : log_excel call : ajax_log_reguler");
        log_message('error', $loadtime);
		$columns = array(
			(object) array(
				"header" => "Payroll ID",
				"key" => "Payroll ID",
				"width" => "37",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Nama Lengkap",
				"key" => "Nama Lengkap",
				"width" => "37",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Tanggal",
				"key" => "Tanggal",
				"width" => "20",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Jam",
				"key" => "Jam",
				"width" => "20",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Latitude",
				"key" => "Latitude",
				"width" => "20",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "Longitude",
				"key" => "Longitude",
				"width" => "20",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
			(object) array(
				"header" => "QR ID",
				"key" => "QR ID",
				"width" => "30",
				"alignment" => (object) array(
					"vertical" => "center",
					"horizontal" => "left"
				),
			),
		);
		return (object) array(
			"columns_" => $columns,
			"rows_" => $data_log,
		);
	}

	public function export_excel()
	{
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
        log_message('error', "======================== x-x-x-x =========================");

		$starttime = microtime(true); // Top of page
		// Code
		
		$month = $_POST["month"] ?: date("m");
		// $month = '01';
        log_message('error', "month  : " . $month);
		
		$year = $_POST["year"] ?: date("Y");
		$data_log = $this->log_excel($month, $year);
		$data_absen = $this->ajax_excel($month, $year);
		// header("Content-type: text/javascript");
		// header('Content-Encoding: gzip');
		$loadtime = "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds";
        log_message('error', "======================== loadtime controller : Absensi method : export_excel call : export_excel");
        log_message('error', $loadtime);
		if ($data_log && $data_absen) {
			
			echo json_encode([
				"status" => true,
				"loadtime" => "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds",
				"bulan" => Absensi::_BULAN[(int) $month],
				"tahun" => $year,
				"data_absen" => $data_absen,
				"data_log" => $data_log
			]);
		} else {
			echo json_encode([
				"status" => false,
				"loadtime" => "Page loaded in : " . number_format(microtime(true) - $starttime,2) . " seconds",
				"bulan" => Absensi::_BULAN[(int) $month],
				"tahun" => $year,
				"data_absen" => [],
				"data_log" => []
			]);
		}
	}

	public function export_pdf()
	{
		ini_set('max_execution_time', '300');
		ini_set("pcre.backtrack_limit", "5000000");

		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'orientation' => 'L',
			'simpleTables' => true,
			'shrink_tables_to_fit' => 1
		]);

		$day = $_GET["day"] ?: date("d");
		$month = $_GET["month"] ?: date("m");
		$year = $_GET["year"] ?: date("Y");

		$timestamp = Absensi::_BULAN[(int) $month] . " $year";
		$title = "REKAP ABSENSI";

		$mpdf->SetTitle("$title $timestamp");
		$mpdf->setFooter("$title $timestamp Page {PAGENO} of {nb} ");

		$html = $this->pdf_table_absen();

		$mpdf->Bookmark('Rekap Absensi');
		$mpdf->WriteHTML($html);

		$mpdf->Output();
	}

	public function pdf_table_absen()
	{
		$day = $_GET["day"] ?: date("d");
		$month = $_GET["month"] ?: date("m");
		$year = $_GET["year"] ?: date("Y");
		$data_absen = $this->ajax_excel($month, $year);
		$data = [
			"status" => true,
			"bulan" => Absensi::_BULAN[(int) $month],
			"tahun" => $year,
			"data_absen" => $data_absen,
		];
		return $this->load->view('pdf_table_absen', $data, TRUE);
	}

	public function generate_pdf()
	{
		$this->load->view('pdf_export');
	}

	public function loading()
	{
		$this->load->view('loading');
	}
}
