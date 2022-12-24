<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Faq extends MX_Controller
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
		$this->load->model('faq/M_faq');
	}

	public function index()
	{
		$this->M_global->firstload();
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
	public function mobile()
	{
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$this->load->view('layout/header_mobile');
		$this->load->view('main');
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}
	public function table()
	{
		$this->M_global->firstload();
		$script = array(
			'script' => TRUE,
			'script_url' => 'main_script'
		);
		$data["faqs"] = $this->M_faq->get_faq();
		$this->load->view('layout/header');
		$this->load->view('table', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
		$this->load->view('layout/global_script');
	}
	public function add()
	{
		$this->M_global->firstload();
		$questions = $this->input->post('question');
		$answers = $this->input->post('answer');
		$sequence_tos = $this->input->post('sequence_to');
		$faq_id = $this->input->post('faq_id');
		for ($i = 0; $i < count($questions); $i++) {
			$query = $this->M_faq->insert(array(
				'sequence_to' => $sequence_tos[$i],
				'question' => $questions[$i],
				'answer' => $answers[$i],
				'faq_id' => $faq_id,
			));
		}
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
		$this->M_global->firstload();
		$id = $this->input->post('qna_id');
		$question = $this->input->post('question');
		$answer = $this->input->post('answer');
		$faq_id = $this->input->post('faq_id');
		$sequence_to = $this->input->post('sequence_to');
		$query = $this->M_faq->update(array(
			'question' => $question,
			'answer' => $answer,
			'faq_id' => $faq_id,
			'sequence_to' => $sequence_to,
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
	public function get()
	{

		$query = $this->M_faq->lists();
		if ($query) {
			echo json_encode([
				"status" => true,
				"message" => "SUCCESS",
				"data" => $query
			]);
		} else {
			echo json_encode([
				"status" => FALSE,
				"message" => "FAILED",
				"data" => []
			]);
		}
	}

	public function delete($id)
	{
		$this->M_global->firstload();
		$query = $this->M_faq->delete($id);
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

	public function ajax()
	{
		$this->M_global->firstload();
		$data = $this->M_faq->fetch();
		echo json_encode($data);
	}
}
