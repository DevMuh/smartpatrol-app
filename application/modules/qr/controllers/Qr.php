<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qr extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model('M_Qr');
	}

	public function index()
	{
		$script = array(
			'script' 	 => TRUE,
			'script_url' => 'main_script'
		);
		$this->load->view('layout/header');
		$this->load->view('main');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}
	public function ajax()
	{
		$data = $this->M_Qr->fetch();
		echo json_encode($data);
	}

	public function add()
	{
		$name = $this->input->post('name');
		$b2b_token = $this->session->userdata('b2b_token');
		$qr_id = $this->input->post('qr_id');
		$cek = $this->M_Qr->is_qr_exist($qr_id);
		if ($cek) {
			echo json_encode([
				'type'		=> 'error',
				'message' 	=> 'QR ID already exist!'
			]);
			return;
		}
		$query = $this->M_Qr->insert(array(
			'name' => $name,
			'qr_id' => $qr_id,
			'b2b_token' => $b2b_token,
		));
		if ($query) { //success update
			$return = [
				'type'		=> 'success',
				'message' 	=> 'Your data has been successfully added.'
			];
			echo json_encode($return);
		} else { //failed update
			$return = [
				'type'		=> 'error',
				'message' 	=> 'Failed to added data.'
			];
			echo json_encode($return);
		}
	}
	public function update()
	{
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$qr_id = $this->input->post('qr_id');
		$cek = $this->M_Qr->is_qr_exist($qr_id, $id);
		if ($cek) {
			echo json_encode([
				'type'		=> 'error',
				'message' 	=> 'QR ID already exist!'
			]);
			return;
		}
		$query = $this->M_Qr->update(array(
			'name' => $name,
			'qr_id' => $qr_id
		), $id);
		if ($query) { //success update
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
	}

	public function delete($id)
	{
		$query = $this->M_Qr->delete($id);
		if ($query) { //success 
			$return = [
				'type'		=> 'success',
				'message' 	=> 'Success Delete .'
			];
			echo json_encode($return);
		} else { //failed save
			$return = [
				'type'		=> 'error',
				'message' 	=> 'Failed to delete data.'
			];
			echo json_encode($return);
		}
	}
}
