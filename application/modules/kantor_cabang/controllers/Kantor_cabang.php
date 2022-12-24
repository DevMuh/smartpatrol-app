<?php
defined('BASEPATH') or exit('No direct script access allowed');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Kantor_cabang extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model('M_kantor_cabang', 'model');
		$this->load->library('encryption');
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

	public function post(){	
		$script = array(
			'script' 	 => TRUE,
			'script_url' => 'main_script'
		);
		$data = [
			'provinsi' => $this->db->order_by('name', 'ASC')->get('data_provinsi')->result()
		];
		$this->load->view('layout/header');
		$this->load->view('post', $data);
		$this->load->view('layout/footer', $script);
		$this->load->view('main_script');
	}

	public function save(){
		$this->form_validation->set_rules($this->model->validasi_save())->run();

		if($this->form_validation->run()==TRUE){

			$save = $this->model->save();

			if ($save) { //success save
				$this->session->set_flashdata('success', '<div class="alert alert-success"><button type="button" class="close">×</button>Your data has been successfully saved.</div>');
				redirect('kantor_cabang');
			}else{ //failed save
				$this->session->set_flashdata('failed', '<div class="alert alert-danger"><button type="button" class="close">×</button>Failed to save data.</div>');
				$script = array(
					'script' 	 => TRUE,
					'script_url' => 'main_script'
				);
				$data = [
					'provinsi' => $this->db->order_by('name', 'ASC')->get('data_provinsi')->result()
				];
				$this->load->view('layout/header');
				$this->load->view('post', $data);
				$this->load->view('layout/footer', $script);
				$this->load->view('main_script');
			}

		}else{ //error validation
			$error 		= $this->form_validation->error_array();
			$fields 	= array_keys($error);
    		$err_msg 	= $error[$fields[0]];
			$this->session->set_flashdata('failed', '<div class="alert alert-danger"><button type="button" class="close">×</button>'.$err_msg.'</div>');
			$script = array(
				'script' 	 => TRUE,
				'script_url' => 'main_script'
			);
			$data = [
				'provinsi' => $this->db->order_by('name', 'ASC')->get('data_provinsi')->result()
			];
			$this->load->view('layout/header');
			$this->load->view('post', $data);
			$this->load->view('layout/footer', $script);
			$this->load->view('main_script');
		}
	}

	public function edit($id){
		if(!isset($id)) show_404();
		$idd = base64_decode($id);
        $idd = str_replace(['-', '_'], ['+', '/'],$idd);
		$get = $this->model->get_by_id($idd)->result();
		if (count($get)>0) {
			$script = array(
				'script' 	 => TRUE,
				'script_url' => 'main_script'
			);
			$data = [
				'provinsi' 	=> $this->db->order_by('name', 'ASC')->get('data_provinsi')->result(),
				'kota'		=> $this->db->order_by('name', 'ASC')->get_where('data_kota', ['provinsi_id'=>$get[0]->provinsi])->result(),
				'data'		=> $get
			];
			$this->load->view('layout/header');
			$this->load->view('edit', $data);
			$this->load->view('layout/footer', $script);
			$this->load->view('main_script');
		}else{
			redirect('kantor_cabang');
		}	
	}

	public function update(){

		$this->form_validation->set_rules($this->model->validasi_save());

		if($this->form_validation->run()==TRUE){

			$id 	= $this->input->get('id');
			$idd 	= base64_decode($id);
        	$idd 	= str_replace(['-', '_'], ['+', '/'],$idd);
			$update = $this->model->update($idd);

			if ($update) { //success update
				$this->session->set_flashdata('success', '<div class="alert alert-success"><button type="button" class="close">×</button>Your data has been successfully update.</div>');
				redirect('kantor_cabang');
			}else{ //failed update
				$this->session->set_flashdata('failed', '<div class="alert alert-danger"><button type="button" class="close">×</button>Failed to update data.</div>');
				$script = array(
					'script' 	 => TRUE,
					'script_url' => 'main_script'
				);
				$data = [
					'provinsi' 	=> $this->db->order_by('name', 'ASC')->get('data_provinsi')->result(),
					'kota'		=> $this->db->order_by('name', 'ASC')->get_where('data_kota', ['provinsi_id'=>$get[0]->provinsi])->result(),
					'data'		=> $get
				];
				$this->load->view('layout/header');
				$this->load->view('edit', $data);
				$this->load->view('layout/footer', $script);
				$this->load->view('main_script');
			}


		}else{ //error validation
			$error 		= $this->form_validation->error_array();
			$fields 	= array_keys($error);
    		$err_msg 	= $error[$fields[0]];
    		$this->session->set_flashdata('failed', '<div class="alert alert-danger"><button type="button" class="close">×</button>'.$err_msg.'</div>');
			$script = array(
				'script' 	 => TRUE,
				'script_url' => 'main_script'
			);
			$data = [
				'provinsi' 	=> $this->db->order_by('name', 'ASC')->get('data_provinsi')->result(),
				'kota'		=> $this->db->order_by('name', 'ASC')->get_where('data_kota', ['provinsi_id'=>$get[0]->provinsi])->result(),
				'data'		=> $get
			];
			$this->load->view('layout/header');
			$this->load->view('edit', $data);
			$this->load->view('layout/footer', $script);
			$this->load->view('main_script');
		}
	}

	public function delete($id){
		if(!isset($id)) show_404();
		$idd = base64_decode($id);
        $idd = str_replace(['-', '_'], ['+', '/'],$idd);
		$delete = $this->model->delete($idd);

		if ($delete) { //success update
			$this->session->set_flashdata('delete', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data berhasil dihapus. <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button></div>');
		}else{ //failed update
			$this->session->set_flashdata('delete', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Data gagal dihapus. <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button></div>');
		}
		redirect('kantor_cabang');
	}


	public function kota(){ //get data kota by provinsi_id
		$provinsi 	= $this->input->post('provinsi_');
		$kota  		= $this->db->order_by('name', 'ASC')->get_where('data_kota', ['provinsi_id'=>$provinsi])->result();
		$jum 		= count($kota);
		if($jum>0){
			foreach ($kota as $m) {
				echo "<option value='$m->id'>$m->name</option>";
			}
		}else{
				echo "<option value=''>-- Pilih --</option>";
		}
	}

	public function changeStatus(){
		$id 	= $this->input->get('id');
		$row 	= $this->db->get_where('t_kantor_cabang', ['id'=>$id])->row();
		// var_dump($row->status);die;
		if(strtolower($row->status)==1){
			return $this->db->update('t_kantor_cabang', ['status'=>0], ['id'=>$id]);
		}else{
			return $this->db->update('t_kantor_cabang', ['status'=>1], ['id'=>$id]);
		}
	}

	public function uploadData(){

		$this->form_validation->set_rules('file', 'Upload File', 'callback_checkFileValidation');
		if($this->form_validation->run() == TRUE) {
            if(!empty($_FILES['file']['name'])) { // If file uploaded
            	// get file extension
                $extension  = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $reader     = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

                // file path
                $spreadsheet    = $reader->load($_FILES['file']['tmp_name']);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray("", true, true, true);
                    
                // array Count
                $arrayCount = count($allDataInSheet);
                $flag = 0;

                $createArray = array('Kode_Cabang','Nama_Cabang','Wilayah','Nama_Manager', 'Telepon', 'Fax', 'Email', 'Alamat_Detail', 'Deskripsi', 'Kode_Pos', 'Longitude', 'Latitude');
                $makeArray = array(
                                'Kode_Cabang'		=> 'Kode_Cabang', 
                                'Nama_Cabang'     	=> 'Nama_Cabang', 
                                'Wilayah'          	=> 'Wilayah', 
                                'Nama_Manager'     	=> 'Nama_Manager', 
                                'Telepon'          	=> 'Telepon', 
                                'Fax'          		=> 'Fax', 
                                'Email'       		=> 'Email', 
                                'Alamat_Detail'    	=> 'Alamat_Detail', 
                                'Deskripsi'        	=> 'Deskripsi', 
                                'Kode_Pos'  		=> 'Kode_Pos', 
                                'Longitude'   		=> 'Longitude',
                                'Latitude'      	=> 'Latitude',
                            );
                $SheetDataKey = array();
                foreach ($allDataInSheet as $dataInSheet) {
                    foreach ($dataInSheet as $key => $value) {
                        if (in_array(trim($value), $createArray)) {
                            $value = preg_replace('/\s+/', '', $value);
                            $SheetDataKey[trim($value)] = $key;
                        } 
                    }
                }

                $dataDiff = array_diff_key($makeArray, $SheetDataKey);
                if (empty($dataDiff)) {
                    $flag = 1;
                }

                $jum=0;
                $same_id=0;
                $null_id=0;

                if ($flag == 1) { // match excel sheet column
                	for($i=2; $i<=$arrayCount; $i++){
                		$kode_cabang 	= $SheetDataKey['Kode_Cabang'];
                		$nama_cabang 	= $SheetDataKey['Nama_Cabang'];
                		$wilayah 		= $SheetDataKey['Wilayah'];
                		$nama_manager 	= $SheetDataKey['Nama_Manager'];
                		$telepon 		= $SheetDataKey['Telepon'];
                		$fax 			= $SheetDataKey['Fax'];
                		$email 			= $SheetDataKey['Email'];
                		$alamat_detail 	= $SheetDataKey['Alamat_Detail'];
                		$deksripsi 		= $SheetDataKey['Deskripsi'];
                		$kode_pos 		= $SheetDataKey['Kode_Pos'];
                		$longitude 		= $SheetDataKey['Longitude'];
                		$latitude 		= $SheetDataKey['Latitude'];

                        $kode_cabang        = filter_var(trim($allDataInSheet[$i][$kode_cabang]), FILTER_SANITIZE_STRING);
                        $nama_cabang        = filter_var(trim($allDataInSheet[$i][$nama_cabang]), FILTER_SANITIZE_STRING);
                        $wilayah        	= filter_var(trim($allDataInSheet[$i][$wilayah]), FILTER_SANITIZE_STRING);
                        $nama_manager       = filter_var(trim($allDataInSheet[$i][$nama_manager]), FILTER_SANITIZE_STRING);
                        $telepon        	= filter_var(trim($allDataInSheet[$i][$telepon]), FILTER_SANITIZE_STRING);
                        $fax        		= filter_var(trim($allDataInSheet[$i][$fax]), FILTER_SANITIZE_STRING);
                        $email        		= filter_var(trim($allDataInSheet[$i][$email]), FILTER_SANITIZE_STRING);
                        $alamat_detail      = filter_var(trim($allDataInSheet[$i][$alamat_detail]), FILTER_SANITIZE_STRING);
                        $deksripsi        	= filter_var(trim($allDataInSheet[$i][$deksripsi]), FILTER_SANITIZE_STRING);
                        $kode_pos        	= filter_var(trim($allDataInSheet[$i][$kode_pos]), FILTER_SANITIZE_STRING);
                        $longitude        	= filter_var(trim($allDataInSheet[$i][$longitude]), FILTER_SANITIZE_STRING);
                        $latitude        	= filter_var(trim($allDataInSheet[$i][$latitude]), FILTER_SANITIZE_STRING);


                        $fetchData = array(
                        	'kode' 			=> $kode_cabang,
                        	'nama' 			=> $nama_cabang,
                        	'wilayah' 		=> $wilayah,
                        	'manager' 		=> $nama_manager,
                        	'telepon' 		=> $telepon,
                        	'fax' 			=> $fax,
                        	'email' 		=> $email,
                        	'alamat'		=> $alamat_detail,
                        	'deskripsi' 	=> $deksripsi,
                        	'kode_pos' 		=> $kode_pos,
                        	'longitude' 	=> $longitude,
                        	'latitude' 		=> $latitude,
                        	'status'		=> 0
                        );


                       	$SQL = "SELECT * FROM t_kantor_cabang WHERE kode ='".$kode_cabang."'";
                       	$CHECK = $this->db->query($SQL)->num_rows();
                        if($kode_cabang!=""){
                            if($CHECK == 0) { // Jika tersedia
                                $this->db->insert('t_kantor_cabang', $fetchData);
                                $jum++;
                            }else{
                                $same_id++;
                            }
                        }else{
                            $null_id++;
                        }
                	} //end for
                	var_dump($SQL);die;
                }

            	if($null_id!=0){
                    if($same_id==0){
                        $message = ['type'   	=> 'success',
                                 	'message'  	=> 'Upload is done, '.$jum.' data was insert and '.$null_id.' Kode Cabang null' ];
                    }else{
                        $message = ['type'   	=> 'success',
                                 	'message'  	=> 'Upload is done, '.$jum.' data was insert, '.$same_id.' Kode Cabang and '.$null_id.' Kode Cabang is null' ];
                    }
                }else{
                    if($same_id==0){
                        $message = ['type'   	=> 'success',
                                 	'message'  	=> 'Upload is done, '.$jum.' data was insert ' ];
                    }else{
                        $message = ['type'   	=> 'success',
                                 	'message'  	=> 'Upload is done, '.$jum.' data was insert and '.$same_id.' same Kode Cabang ' ];
                    }
                }

            }else{//end cek empty file 
            	$message = ['type'   	=> 'error',
                     	'message'  	=> 'Please choose correct file.'];
            }
		}else{
			$message = ['type'   	=> 'error',
                     	'message'  	=> 'Please choose correct file.'];
		}
		echo json_encode($message);
	}

	public function checkFileValidation() {
      $file_mimes = array('text/x-comma-separated-values', 
        'text/comma-separated-values', 
        'application/octet-stream', 
        'application/vnd.ms-excel',  
        'application/excel', 
        'application/vnd.msexcel', 
        'text/plain', 
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
      );
      if(isset($_FILES['file']['name'])) {
            $arr_file = explode('.', $_FILES['file']['name']);
            $extension = end($arr_file);
            if(($extension == 'xlsx' || $extension == 'xls') && in_array($_FILES['file']['type'], $file_mimes)){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
	
}
