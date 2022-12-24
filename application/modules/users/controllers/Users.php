<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model('M_users');
	}

	public function user_info($name)
	{
		$name = urlencode($name);
		echo json_encode($this->db->query("select 
		m_register_b2b.title_nm as org_name,
		users.full_name,
		users.username
		from users left join m_register_b2b
		on m_register_b2b.b2b_token = users.b2b_token
		where 
		users.full_name ilike '%$name%'
		or users.username ilike '%$name%'
		")->result());
	}

	public function index()
	{
		$script = array(
			'script' 	 => TRUE,
			'script_url' => 'main_script'
		);
		$data["listb2b"] =  modules::run('register_b2b/register_b2b/listb2b');
		$data["positions"] = $this->db->get("m_position")->result();
		$this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}
	public function ajax()
	{
		$data = $this->M_users->fetch();
		echo json_encode($data);
	}
	
	public function json()
	{
		$data = $this->M_users->get_data();
		echo json_encode($data);
	}
	public function select2()
	{
		$data = $this->M_users->get_data();
		$res = array_map(function($element){
			return array(
				'id' => $element->id,
				'text' => $element->full_name
			);
		}, $data);
		echo json_encode($res);
	}

	public function add()
	{

		$this->form_validation->set_rules($this->M_users->validasi_save())->run();

		if ($this->form_validation->run() == TRUE) {
			if ($this->M_users->is_username_exist($this->input->post("username"))) {
				$return = [
					'type'		=> 'error',
					'message' 	=> 'Username Already Exist!'
				];
				echo json_encode($return);
				return;
			}
			if ($this->M_users->is_phone_number_exist($this->input->post("no_tlp"))) {
				$return = [
					'type'		=> 'error',
					'message' 	=> 'Phone Number Already Exist!'
				];
				echo json_encode($return);
				return;
			}
			$save = $this->M_users->save();
			if ($save) { //success save
				$return = [
					'type'		=> 'success',
					'message' 	=> 'Your data has been successfully saved.'
				];
				echo json_encode($return);
			} else { //failed save
				$return = [
					'type'		=> 'error',
					'message' 	=> 'Failed to save data.'
				];
				echo json_encode($return);
			}
		} else { //error validation
			$error 		= $this->form_validation->error_array();
			$fields 	= array_keys($error);
			$err_msg 	= $error[$fields[0]];
			$return = [
				'type'		=> 'error',
				'message' 	=> $err_msg
			];
			echo json_encode($return);
		}
	}

	public function get_by_id()
	{
		$id  = $this->input->get('id');
		$get = $this->M_users->get_by_id($id)->row();

		echo json_encode($get);
	}
	public function get_by_regu()
	{
		$regu_id  = $this->input->get('regu_id');
		$get = $this->M_users->get_by_regu($regu_id)->result();
		echo json_encode($get);
	}

	public function update()
	{

		$this->form_validation->set_rules($this->M_users->validasi_save());

		if ($this->form_validation->run() == TRUE) {

			$id = $this->input->get('id');
			if ($this->M_users->is_username_exist($this->input->post("username"), $id)) {
				$return = [
					'type'		=> 'error',
					'message' 	=> 'Username Already Exist!'
				];
				echo json_encode($return);
				return;
			}

			$update = $this->M_users->update($id);

			if ($update) { //success update
				$return = [
					'type'		=> 'success',
					'message' 	=> 'Your data has been successfully update.'
				];
				echo json_encode($return);
			} else { //failed update
				$return = [
					'type'		=> 'error',
					'message' 	=> 'Failed to update data.'
				];
				echo json_encode($return);
			}
		} else { //error validation
			$error 		= $this->form_validation->error_array();
			$fields 	= array_keys($error);
			$err_msg 	= $error[$fields[0]];
			$return = [
				'type'		=> 'error',
				'message' 	=> $err_msg
			];
			echo json_encode($return);
		}
	}

	public function delete($id)
	{
		$delete = $this->M_users->delete($id);

		if ($delete) { //success update
			$this->session->set_flashdata('delete', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data berhasil dihapus. <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button></div>');
		} else { //failed update
			$this->session->set_flashdata('delete', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Data gagal dihapus. <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button></div>');
		}
		redirect('users');
	}

	public function changeStatus()
	{
		$id 	= $this->input->get('id');
		$row 	= $this->db->get_where('users', ['id' => $id])->row();
		// var_dump($row->status);die;
		if (strtolower($row->status) == 'active') {
			return $this->db->update('users', ['status' => 'inactive'], ['id' => $id]);
		} else {
			return $this->db->update('users', ['status' => 'active'], ['id' => $id]);
		}
	}

	public function conf()
	{
		echo json_encode($this->M_users->getconf());
	}

	public function upconf()
	{
		$data['active_at'] = $this->input->post('mode');
		if ($data['active_at'] == 1) {
			$data['id_project'] = $this->input->post('par');
			$this->db->where('id', $this->input->post('id'))->update('users', $data);
			echo 1;
		} elseif ($data['active_at'] == 2) {
			$data['id_cabang'] = $this->input->post('par');
			$this->db->where('id', $this->input->post('id'))->update('users', $data);
			echo 1;
		} else {
			echo 0;
		}
	}

	public function savetag()
	{
		$id = $_POST['id'];
		$json = $_POST['loc'];
		$radius = $_POST['radius'];
		$coordinate = array(
			"lat" => $json["lat"],
			"lng" => $json["lng"],
			"radius" => $radius
		);
		$data['geo_tag'] = json_encode($coordinate);
		$this->db->where('id', $id)->where('b2b_token', $_SESSION['b2b_token'])->update('users', $data);
		echo 1;
	}
	public function remove_device_info($id)
	{
		$query = $this->db->query(
			"UPDATE users SET device_info = ? WHERE id = ? ",
			[null, $id]
		);
		$data = $this->db->get_where("users", ['id' => $id])->row();
		if ($query) {
			echo json_encode(
				array(
					"status" => true,
					'message' => "Success Logout",
					'data' => $data
				)
			);
		} else {
			echo json_encode(
				array(
					"status" => false,
					'message' => "failed Logout",
					'data' => []
				)
			);
		}
	}

	public function set_visit()
	{
		$user_id = $_POST["user_id"];
		$visitb2b = $_POST["visitb2b"];
		$other_data = json_encode([
			"visitb2b" => $visitb2b
		]);
		return $this->M_users->update_other_data($other_data, $user_id);
	}
	public function get_visit($user_id)
	{
		echo json_encode($this->db->query("SELECT other_data->>'visitb2b' as visitb2b FROM users WHERE id = ? ", [$user_id])->row()->visitb2b);
	}

	public function get_hidden_column()
	{
		$table = $_POST['table'];
		echo json_encode($this->db->query("SELECT other_data->>'hidden_column_$table' as $table FROM users WHERE id = ? ", [$this->session->userdata("id")])->row()->$table);
	}
	public function set_hidden_column()
	{
		$table = strtolower($_POST['table']);
		$column = $_POST['column'];
		$other_data = json_encode([
			"hidden_column_" . $table => $column
		]);
		return $this->M_users->update_other_data($other_data, $this->session->userdata("id"));
	}


	// public function fix_fucked_user_table()
	// {
	// 	require_once 'xlsx.php';
	// 	if ($xlsx = SimpleXLSX::parse(__DIR__ . '/user_srb.xlsx')) {
	// 		$res = [];
	// 		foreach ($xlsx->rows() as $key) {
	// 			$user = $this->db->get_where("users", ['no_tlp' => strval($key[7])])->row();
	// 			if ($user) {
	// 				$no_tlp = $user->no_tlp;
	// 				if (!strpos($no_tlp, '/')) {
	// 					$c = $this->db->set(['no_tlp' => '0' . $no_tlp])->where("id", $user->id)->update("users");
	// 					if ($c) {
	// 						$res[] = 'ok' . $no_tlp;
	// 					}
	// 				} else {
	// 					$arr = explode('/', $no_tlp);
	// 					foreach ($arr as $a) {
	// 						$p = trim($a);
	// 						if ($user->password == md5($p)) {
	// 							$c = $this->db->set(['no_tlp' => $p])->where("id", $user->id)->update("users");
	// 							if ($c) {
	// 								$res[] = 'ok' . $p;
	// 							}
	// 						}
	// 					}
	// 				}
	// 			}
	// 		}
	// 		echo json_encode($res);
	// 	} else {
	// 		echo SimpleXLSX::parseError();
	// 	}
	// }
}
