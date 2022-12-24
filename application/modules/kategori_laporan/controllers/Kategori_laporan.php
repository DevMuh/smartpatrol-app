<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_laporan extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model('M_kategori_laporan', 'model');
	}

	public function index(){
		$script = array(
			'script' 	 => TRUE,
			'script_url' => 'main_script'
		);
		$this->load->view('layout/header');
		$this->load->view('main');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}
	public function ajax(){
		$data = $this->model->fetch();
		echo json_encode($data);
	}

	public function add(){

		$this->form_validation->set_rules($this->model->validasi_save())->run();

		if($this->form_validation->run()==TRUE){

			$save = $this->model->save();
			if ($save) { //success save
				$return = [
					'type'		=> 'success',
					'message' 	=> 'Your data has been successfully saved.'
					];
				echo json_encode($return);
			}else{ //failed save
				$return = [
					'type'		=> 'error',
					'message' 	=> 'Failed to save data.'
					];
				echo json_encode($return);
			}

		}else{ //error validation
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

	public function get_by_id(){
		$id = $this->input->get('id');
		$get = $this->model->get_by_id($id)->result();
		echo json_encode($get);
	}

	public function update(){

		$this->form_validation->set_rules($this->model->validasi_save());

		if($this->form_validation->run()==TRUE){

			$id = $this->input->get('id');
			$update = $this->model->update($id);

			if ($update) { //success update
				$return = [
					'type'		=> 'success',
					'message' 	=> 'Your data has been successfully update.'
					];
				echo json_encode($return);
			}else{ //failed update
				$return = [
					'type'		=> 'error',
					'message' 	=> 'Failed to update data.'
					];
				echo json_encode($return);
			}


		}else{ //error validation
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

	public function delete($id){
		$delete = $this->model->delete($id);

		if ($delete) { //success update
			$this->session->set_flashdata('delete', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data berhasil dihapus. <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button></div>');
		}else{ //failed update
			$this->session->set_flashdata('delete', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Data gagal dihapus. <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button></div>');
		}
		redirect('kategori_laporan');
	}
	
}
