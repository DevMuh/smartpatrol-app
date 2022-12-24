<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register_b2b extends MY_Controller
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
		$this->load->model('M_register_b2b');
		$this->b2b = $this->session->userdata('b2b_token');
	}
	public function index()
	{
		if ($this->session->userdata("user_roles") != "cudo" || $this->session->userdata("user_roles") != "superadmin") {
			echo '<script>history.go(-2)</script>';
		}
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$this->load->view('layout/header');
		$this->load->view('main');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}
	public function detail($id)
	{
		$data['id'] = $id;
		$this->load->view('layout/header');
		$this->load->view('detail', $data);
		$this->load->view('layout/footer');
		$this->load->view('detail_script');
		$this->load->view('layout/global_script');
	}
	public function ajax()
	{
		$data = $this->M_register_b2b->fetch();
		echo json_encode($data);
	}
	public function ajax2($id = 0)
	{
		$data = $this->M_register_b2b->fetchDetail($id);
		if ($id == 0 || count($data) == 0) {
			echo "<h1 style='text-align: center'>404 Not Found</1>";
		} else {
			echo json_encode($data);
		}
	}

	public function add()
	{
		$status = $this->M_register_b2b->add_cluster();
		echo json_encode($status);
	}
	public function edit()
	{
		$status = $this->M_register_b2b->edit_cluster();
		echo json_encode($status);
	}
	public function hapus()
	{
		$status = $this->M_register_b2b->hapus_cluster();
		if ($status == 1) {
			echo '<script>history.go(-1)</script>';
		} else {
			echo '<script>history.go(-1)</script>';
		}
	}
	function has_children($rows, $id)
	{
		foreach ($rows as $row) {
			if ($row->parent_id == $id)
				return true;
		}
		return false;
	}
	function build_treeview($rows, $parent = 0)
	{
		$result = "<ul>";
		foreach ($rows as $row) {
			if ($row->parent_id == $parent) {
				$result .= "<li data-b2b='$row->b2b_token'>{$row->title_nm}";
				if ($this->has_children($rows, $row->b2b_token))
					$result .= $this->build_treeview($rows, $row->b2b_token);
				$result .= "</li>";
			}
		}
		$result .= "</ul>";

		return $result;
	}

	public function subB2B($parent_id = null)
	{
		$query_choose_b2b = $this->db->where('flag_active', '1')->order_by("title_nm", "ASC")->get_where("m_register_b2b", ["parent_id" => $parent_id])->result();
		$arr = array();
		foreach ($query_choose_b2b as $row) {
			$arr[] = $row;
			$sub = $this->subB2B($row->b2b_token);
			if ($sub) {
				foreach ($sub as $s) {
					$arr[] = $s;
				}
			}
		}
		return $arr;
	}

	public function listb2b()
	{
		$choose_b2b = [];
		if ($this->session->userdata("user_roles") == 'cudo' || $this->session->userdata("user_roles") == 'superadmin') {
			$choose_b2b = $this->db->where('flag_active', '1')->order_by("title_nm", "ASC")->get("m_register_b2b")->result();
		} else {
			$query_choose_b2b = $this->db->where('flag_active', '1')->order_by("title_nm", "ASC")->get_where("m_register_b2b", ["b2b_token" => $this->session->userdata("user_b2b_token")])->result();
			foreach ($query_choose_b2b as $row) {
				$choose_b2b[] = $row;
				$sub = $this->subB2B($row->b2b_token);
				if ($sub)
					foreach ($sub as $s)
						$choose_b2b[] = $s;
			}
		}
		return $choose_b2b;
	}


	public function get_b2b_with_parent($b2b_token = null)
	{
		$query = $this->db->where('flag_active', '1')->get_where("m_register_b2b", ["b2b_token" => $b2b_token])->row();
		$arr = array();
		$arr[] = $query;
		if ($query->parent_id)
			$parent = $this->get_b2b_with_parent($query->parent_id);
		if ($parent)
			foreach ($parent as $p)
				$arr[] = $p;

		return $arr;
	}


	public function edit_geofence()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$b2b = $this->session->userdata('b2b_token');
		$choose_b2b = $this->listb2b();
		$data["b2b_treeview"] = $this->build_treeview($choose_b2b);
		$data["table"] = $this->db->query("SELECT 
			other_data->>'area_color,' as area_color, 
			other_data->>'area_lat_long' as area_lat_long,
			other_data->>'is_allow_fake_gps' as is_allow_fake_gps,
			other_data->>'status_schedule' as status_schedule
			FROM m_register_b2b WHERE b2b_token = ? ", [$b2b])->row();
		// $data['id'] = $id;
		// $data['geo'] = $geo->area_lat_long;
		$this->load->view('layout/header');
		$this->load->view('edit_geofence', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}

	public function edit_geofence_process()
	{
		$other_data = json_encode([
			"area_lat_long" => json_decode($this->input->post("area_lat_long")),
			"area_color" => $this->input->post("area_color"),
			"is_allow_fake_gps" => json_decode($this->input->post("is_allow_fake_gps")),
			"status_schedule" => json_decode($this->input->post("status_schedule")),
		]);
		$b2b = $this->session->userdata('b2b_token');

		$this->db->query("UPDATE m_register_b2b SET other_data = other_data || ? WHERE b2b_token = ?", [$other_data, $b2b]);
	}

	public function add2()
	{
		$status = $this->M_register_b2b->add_b2b();
		$this->session->set_flashdata('success', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
														Success Create Data Register B2B
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">&times;</span>
														</button>
													</div>');
		redirect('register_b2b', 'refresh');
	}
	public function edit2()
	{
		$status = $this->M_register_b2b->edit_b2b();
		$this->session->set_flashdata('success', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
														Success Update Data Register B2B
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">&times;</span>
														</button>
													</div>');
		redirect('register_b2b', 'refresh');
	}
	public function hapus2()
	{
		$status = $this->M_register_b2b->hapus_b2b();
		if ($status == 1) {
			redirect('register_b2b');
		} else {
			redirect('register_b2b');
		}
	}

	public function upload_geofence_xml()
	{
		$coords_xml = $_FILES['coords_xml']['tmp_name'];
		$xml = simplexml_load_file($coords_xml);
		if (!$xml) {
			$this->session->set_flashdata('failed', '<div class="alert alert-danger"><button type="button" class="close">×</button>Failed Load kml!.</div>');
			redirect('register_b2b/edit_geofence');
			return;
		}
		$placemarks = $xml->Document->Placemark;
		$coordinates  =  explode(' ', $placemarks[0]->Polygon->outerBoundaryIs->LinearRing->coordinates);
		if (!$coordinates) {
			$this->session->set_flashdata('failed', '<div class="alert alert-danger"><button type="button" class="close">×</button>Empty coordinates!.</div>');
			redirect('register_b2b/edit_geofence');
			return;
		}
		$coords_tmp = array();
		foreach ($coordinates as $value) {
			$tmp = explode(',', $value);
			if ($tmp[1] && $tmp[0])
				array_push($coords_tmp, [
					'lat' => floatval($tmp[1]),
					'lng' => floatval($tmp[0])
				]);
		}
		$other_data = json_encode([
			"area_lat_long" => json_encode($coords_tmp, JSON_UNESCAPED_SLASHES)
		]);
		$b2b = $this->b2b;
		$query = $this->db->query("UPDATE m_register_b2b SET other_data = other_data || ? WHERE b2b_token = ?", [$other_data, $b2b]);
		if ($query) {
			$this->session->set_flashdata('success', '<div class="alert alert-success"><button type="button" class="close">×</button>Success Update geofence.</div>');
		} else {
			$this->session->set_flashdata('failed', '<div class="alert alert-danger"><button type="button" class="close">×</button>Failed update geofence.</div>');
		}
		redirect('register_b2b/edit_geofence');
	}
}
