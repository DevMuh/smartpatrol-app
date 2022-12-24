<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log_absen extends MX_Controller
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
		$this->load->model('log_absen/M_log_absen');
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
		$this->load->view('layout/global_script');
	}
	public function ajax($month = null, $year = null)
	{
		if (!$month) $month = date("m");
		if (!$year) $year = date("Y");
		$data = $this->M_log_absen->fetch($month, $year);
		echo json_encode($data);
	}

	public function get_by_id()
	{
		$id  = $this->input->get('id');
		$get = $this->M_log_absen->get_by_id($id)->row();

		$d = strtotime($get->date);
		$date = date('Y-m-d',$d);

		$data = array(
            "id" => $get->id,
			"user_id" => $get->user_id,
			"shift_id" => $get->shift_id,
			"status" => $get->status,
			"date" => $date,
			"time" => $get->time
        );

		echo json_encode($data);
	}

	public function validasi_save()
    {
        return [
            [
                'field' => 'shift_id',
                'label' => 'Shift',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'edit_tanggal_shift',
                'label' => 'Submit Date',
                'rules' => 'required|rtrim',
            ],
            [
                'field' => 'edit_waktu_shift',
                'label' => 'Submit Time',
                'rules' => 'required|rtrim',
            ],

        ];
    }

	public function update()
	{
		$this->form_validation->set_rules($this->validasi_save());

		if ($this->form_validation->run() == TRUE) {

			$id = $this->input->post('history_id');
			
			$check = $this->M_log_absen->check_historyid_exist($id);
			
			if ($check == 0) {
				$return = [
					'type'		=> 'error',
					'message' 	=> 'ID History Cannot Registered!'
				];
				echo json_encode($return);
				return;
			}
			$update = $this->M_log_absen->update($id);

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

	public function get_list_shift()
	{
		$user_id  = $this->input->get('user_id');
		$get_all_shift = $this->M_log_absen->get_list_shift($user_id);

		echo json_encode($get_all_shift);
	}
}
