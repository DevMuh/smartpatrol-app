<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


require FCPATH . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class Instruksi extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->M_global->firstload();
		$this->load->model('M_instruksi', 'model');
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
		$b2b = $_SESSION['b2b_token'];
		$data = [ 
			'instruksi' => $this->db->order_by('id', 'ASC')->get('m_kategori_instruksi')->result(),
			'anggota' 	=> $this->db->order_by('id', 'ASC')->where('user_roles', 'anggota')->or_where('user_roles', 'danru')->where('b2b_token', $_SESSION['b2b_token'])->where('status', 'active')->get('users')->result(),
			'regu'		=> $this->db->where('b2b_token', $b2b)->get('t_regu')->result(),
			'shift'		=> $this->db->where('b2b_token', $b2b)->get('t_shift')->result()
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
				redirect($this->uri->segment(1));
			}else{ //failed save
				$this->session->set_flashdata('failed', '<div class="alert alert-danger"><button type="button" class="close">×</button>Failed to save data.</div>');
				$script = array(
					'script' 	 => TRUE,
					'script_url' => 'main_script'
				);
				$data = [ 'instruksi' => $this->db->order_by('id', 'ASC')->get('m_kategori_instruksi')->result() ];
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
			$data = [ 'instruksi' => $this->db->order_by('id', 'ASC')->get('m_kategori_instruksi')->result() ];
			$this->load->view('layout/header');
			$this->load->view('post', $data);
			$this->load->view('layout/footer', $script);
			$this->load->view('main_script');
		}
	}

	public function get_by_id(){
		$id 		= $this->input->get('id');
		$b2b_token 	= $this->input->get('token');

		$detail = $this->db->select(['A.id', 'B.nama', 'A.detail_instruksi', 'A.tanggal_kirim', 'A.pengirim', 'A.tanggal_mulai', 'A.tanggal_selesai', 'A.lampiran', 'A.regu_id', 'A.perihal', 'A.feedback', 'A.lampiran'])
        					->from('t_instruksi AS A')
                            ->join('m_kategori_instruksi AS B', 'B.id=A.id_kategori_instruksi', 'left')
        					->where('A.id', $id)
        					->get()
        					->result();

        $anggota = $this->db->select(['C.username', 'C.full_name'])
        					->from('instruksi_anggota AS A')
        					->join('t_instruksi AS B', 'B.id=A.instruksi_id', 'left')
        					->join('users AS C', 'C.id=A.anggota_id', 'left')
        					->where('B.id', $id)
        					->get()
        					->result();
        $data = [
        	'detail'	=> $detail,
        	'anggota'	=> $anggota,
    			];

		echo json_encode($data);
	}

	public function export_anggota(){
	    $extension = 'xlsx';

	    $this->load->helper('download');  
	    $data = array();
	    $data['title'] = 'Export Excel Sheet | Coders Mag';
	    // get employee list
	    $empInfo 		= $this->model->get_data_anggota();
	    $fileName 		= 'Data Anggota'; 
	    $spreadsheet 	= new Spreadsheet();
	    $sheet 			= $spreadsheet->getActiveSheet();
	    
	    $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Full Name');
        $sheet->setCellValue('D1', 'Jabatan');
	 
        $styleArray = array(
		  'font' => array(
		    'bold' => true,
		  ),
		  'alignment' => array(
		    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		  ),
		  'borders' => array(
		      'bottom' => array(
		          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
		          'color' => array('rgb' => '333333'),
		      ),
		  ),
		  'fill' => array(
		    'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
		    'rotation'   => 90,
		    'startcolor' => array('rgb' => '0d0d0d'),
		    'endColor'   => array('rgb' => 'f2f2f2'),
		  ),
		);
		$spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

        $rowCount = 2;
        foreach ($empInfo as $element) {
            $sheet->setCellValue('A' . $rowCount, $element->id);
            $sheet->setCellValue('B' . $rowCount, $element->username);
            $sheet->setCellValue('C' . $rowCount, $element->full_name);
            $sheet->setCellValue('D' . $rowCount, $element->user_roles);
            $rowCount++;
        }
	 

      	$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      	$fileName = $fileName.'.xlsx';
	   
	 
	    $this->output->set_header('Content-Type: application/vnd.ms-excel');
	    $this->output->set_header("Content-type: application/csv");
	    $this->output->set_header('Cache-Control: max-age=0');
	    $writer->save(ROOT_UPLOAD_PATH.$fileName); 
	    //redirect(HTTP_UPLOAD_PATH.$fileName); 
	    $filepath = file_get_contents(ROOT_UPLOAD_PATH.$fileName);
	    force_download($fileName, $filepath);
	}

	public function import(){
		date_default_timezone_set("Asia/Jakarta");

		$this->form_validation->set_rules('file', 'Upload File', 'callback_checkFileValidation');

		if($this->form_validation->run() == false) {
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

                $createArray = array('Perihal','Tingkat_Urgensi','Detail_Instruksi','Tanggal_Mulai', 'Tanggal_Selesai', 'Feedback', 'Anggota');
                $makeArray = array(
                                'Perihal'			=> 'Perihal', 
                                'Tingkat_Urgensi'   => 'Tingkat_Urgensi', 
                                'Detail_Instruksi'  => 'Detail_Instruksi', 
                                'Tanggal_Mulai'     => 'Tanggal_Mulai', 
                                'Tanggal_Selesai'   => 'Tanggal_Selesai', 
                                'Feedback'          => 'Feedback',
                                'Anggota'          	=> 'Anggota',
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

                if ($flag == 1) { // match excel sheet column
                	for($i=2; $i<=$arrayCount; $i++){
                		$perihal 			= $SheetDataKey['Perihal'];
                		$tingkat_urgensi 	= $SheetDataKey['Tingkat_Urgensi'];
                		$detail_instruksi 	= $SheetDataKey['Detail_Instruksi'];
                		$tanggal_mulai 		= $SheetDataKey['Tanggal_Mulai'];
                		$tanggal_selesai 	= $SheetDataKey['Tanggal_Selesai'];
                		$feedback 			= $SheetDataKey['Feedback'];
                		$anggota 			= $SheetDataKey['Anggota'];

                        $perihal        	= filter_var(trim($allDataInSheet[$i][$perihal]), FILTER_SANITIZE_STRING);
                        $tingkat_urgensi    = filter_var(trim($allDataInSheet[$i][$tingkat_urgensi]), FILTER_SANITIZE_STRING);
                        $detail_instruksi   = filter_var(trim($allDataInSheet[$i][$detail_instruksi]), FILTER_SANITIZE_STRING);
                        $tanggal_mulai      = filter_var(trim($allDataInSheet[$i][$tanggal_mulai]), FILTER_SANITIZE_STRING);
                        $tanggal_selesai    = filter_var(trim($allDataInSheet[$i][$tanggal_selesai]), FILTER_SANITIZE_STRING);
                        $feedback        	= filter_var(trim($allDataInSheet[$i][$feedback]), FILTER_SANITIZE_STRING);
                        $anggota        	= filter_var(trim($allDataInSheet[$i][$anggota]), FILTER_SANITIZE_STRING);


                        $fetchData = array(
                        	'perihal' 				=> $perihal,
                        	'id_kategori_instruksi' => $tingkat_urgensi,
                        	'detail_instruksi' 		=> $detail_instruksi,
                        	'tanggal_kirim' 		=> date('Y-m-d H:i:s'),
                        	'pengirim'				=> $_SESSION['username'],
                        	'tanggal_mulai' 		=> $tanggal_mulai,
                        	'tanggal_selesai' 		=> $tanggal_selesai,
                        	'feedback'				=> $feedback,
                        	'b2b_token' 			=> $_SESSION['b2b_token'],
                        );

                        $this->db->insert('t_instruksi', $fetchData);
			            $id = $this->db->insert_id();

			            $fetchAnggota = explode(",",$anggota);
			            foreach ($fetchAnggota as $value) {
			                $this->db->insert('instruksi_anggota', ['instruksi_id'=>$id, 'anggota_id'=>$value]);
			            }

                	} //end for
                }

                $message = ['type'   	=> 'success',
 	                    	'message'  	=> 'Import data berhasil.' ];


            }else{//end cek empty file 
            	$message = ['type'   	=> 'error',
                     	'message'  	=> 'Please choose correct fileee.'];
            }
		}else{
			$message = ['type'   	=> 'error',
                     	'message'  	=> 'Please choose correct file.'];
		}
		echo json_encode($message);
	}

	public function checkFileValidation($string) {
      if(isset($_FILES['file']['name'])) {
            $arr_file = explode('.', $_FILES['file']['name']);
            $extension = end($arr_file);
            if(($extension == 'xlsx' || $extension == 'xls') && in_array($_FILES['file']['type'], $file_mimes)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

	
//------------------ COBA --------------------//
	public function export_anggota2(){;

		$extension = 'xlsx';

	    $this->load->helper('download');  
	    $data = array();
	    $data['title'] = 'Export Excel Sheet | Coders Mag';
	    // get employee list
	    $empInfo 		= $this->model->get_data_anggota();
	    $fileName 		= 'Data Anggota'; 
	    $spreadsheet 	= new Spreadsheet();
	    $sheet 			= $spreadsheet->getActiveSheet();
	    
	    $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Full Name');
        $sheet->setCellValue('D1', 'Jabatan');
	 
        $styleArray = array(
		  'font' => array(
		    'bold' => true,
		  ),
		  'alignment' => array(
		    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		  ),
		  'borders' => array(
		      'bottom' => array(
		          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
		          'color' => array('rgb' => '333333'),
		      ),
		  ),
		  'fill' => array(
		    'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
		    'rotation'   => 90,
		    'startcolor' => array('rgb' => '0d0d0d'),
		    'endColor'   => array('rgb' => 'f2f2f2'),
		  ),
		);
		$spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

        $rowCount = 2;
        foreach ($empInfo as $element) {
            $sheet->setCellValue('A' . $rowCount, $element->id);
            $sheet->setCellValue('B' . $rowCount, $element->username);
            $sheet->setCellValue('C' . $rowCount, $element->full_name);
            $sheet->setCellValue('D' . $rowCount, $element->user_roles);
            $rowCount++;
        }


        $spreadsheet->addSheet();
	    $sheett 			= $spreadsheet->getActiveSheet(1);

	    $sheett->setCellValue('A1', 'ID');
        $sheett->setCellValue('B1', 'Username');
        $sheett->setCellValue('C1', 'Full Name');
        $sheett->setCellValue('D1', 'Jabatan');

      	

      	$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      	$fileName = $fileName.'.xlsx';
	   
	    $this->output->set_header('Content-Type: application/vnd.ms-excel');
	    $this->output->set_header("Content-type: application/csv");
	    $this->output->set_header('Cache-Control: max-age=0');
	    $writer->save(ROOT_UPLOAD_PATH.$fileName); 
	    //redirect(HTTP_UPLOAD_PATH.$fileName); 
	    $filepath = file_get_contents(ROOT_UPLOAD_PATH.$fileName);
	    force_download($fileName, $filepath);
	}

	public function export_anggota3(){
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();
		// Set document properties
		$spreadsheet->getProperties()->setCreator('PhpOffice')
		        ->setLastModifiedBy('PhpOffice')
		        ->setTitle('Office 2007 XLSX Test Document')
		        ->setSubject('Office 2007 XLSX Test Document')
		        ->setDescription('PhpOffice')
		        ->setKeywords('PhpOffice')
		        ->setCategory('PhpOffice');
		// Add some data
		$spreadsheet->setActiveSheetIndex(0)
		        ->setCellValue('A1', 'Hello');
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('URL Added');
		$spreadsheet->createSheet();
		// Add some data
		$spreadsheet->setActiveSheetIndex(1)
		        ->setCellValue('A1', 'world!');
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('URL Removed');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Xlsx)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="01simple.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}
}

/* End of file Intruksi.php */
/* Location: ./application/modules/intruksi/controllers/Intruksi.php */