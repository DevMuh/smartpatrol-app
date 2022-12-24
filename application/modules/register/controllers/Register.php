<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		if($this->M_global->realip() == 'ID'){
			$this->lang->load('information_lang', 'indonesia');
		} else {
			$this->lang->load('information_lang', 'english');
		}
		$this->load->model('register/M_register');
		$this->load->library(['session', 'form_validation']);
		$this->load->helper('email');
	}
	public function index()
	{
		$this->form_validation->set_rules($this->M_register->validasi_form());

		if($this->form_validation->run()==TRUE){
			
			$save = $this->M_register->save_register();

			if(isset($save)){ //saved successfully
			// simpan data ke table token
				$post 	= $this->input->post();
				$token 	= $this->random();
				// $token	= base64_encode(random_bytes(32)); //for php 7

				$data_token = [
					'email'			=> $post['email'],
					'token' 		=> $token,
					'date_create'	=> time()	
				];
				$this->db->insert('token', $data_token);
				
				//kirim email
				$email = $this->send_email($post['name'], $post['email'], $token);

				$return = [
					'type'		=> 'success',
					'message' 	=> 'You have successfully registered.<br>Please check your email.'
					];
				echo json_encode($return);

			}else{ //failed to save
				$return = [
					'type'		=> 'error',
					'message' 	=> 'The registration failed.'
					];
				echo json_encode($return);
			}
		}else{ //Validation error
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

	public function random(){
		$karakter 	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$acak 		= str_shuffle($karakter);
		$panjang 	= substr($acak, 0, 32);
		return $panjang;
	}


	private function send_email($name, $email, $token)
	{
		$config = array(
            'protocol' 		=> 'smtp',
            'smtp_host' 	=> 'ssl://smtp.gmail.com',
            'smtp_port' 	=> 465,
            // 'smtp_user' 	=> 'tt.program.mail@gmail.com',
            // 'smtp_pass' 	=> 'cudocudo',
            'smtp_user' 	=> 'andywij4ya@gmail.com',
            'smtp_pass' 	=> 'rmzflganejowibjf',
            'mailtype' 		=> 'html',
            'smtp_timeout'	=> '4',
			'charset' 		=> 'iso-8859-1',
			'priority'		=> 1
        );


        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from('TT_notification@gmail.com', 'Register Smart Patrol');
		$this->email->to($email);
		// $this->email->cc('dodisupriatna8@gmail.com');


        $this->email->subject('Register Smart Patrol');
		   $this->email->message(
		'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>BAT Smart Patrol ||</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		</head>
		<body style="margin: 0; padding: 0;">
		    <table border="0" cellpadding="0" cellspacing="0" width="100%"> 
		        <tr>
		            <td style="padding: 10px 0 30px 0;">
		                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
		                    <tr>
		                        <td align="center" bgcolor="#b63031" style="padding: 40px 0 30px 0; color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
		                            <img src="http://smartpatrol.cudo.co.id/assets/apps/assets/plugins/login/images/smart.png"  style="display: block;" />
		                        </td>
		                    </tr>
		                    <tr>
		                        <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
		                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
		                                <tr>
		                                    <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
		                                        <b>Hello,</b>
		                                        <p> <b>'.$name.'</b></p>
		                                    </td>
		                                </tr>
		                                <tr>
		                                    <td style="padding-bottom:10px; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
		                                        Congratulations, you have <b style="color:#b63031">SUCCESSFULLY</b> registered on our Smart Patrol&trade; system.
		                                    </td>
		                                    
		                                </tr>
		                               
		                                <tr>
		                                    <td>
		                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
		                                            <tr>
		                                                <td width="260" valign="top">
		                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
		                                                        <tr>
		                                                            <td>
		                                                                <img src="http://smartpatrol.cudo.co.id/assets/apps/assets/plugins/login/images/mobile1.jpg" alt="" width="100%"  style="display: block;" />
		                                                            </td>
		                                                        </tr>
		                                                        <tr>
		                                                            <td style="padding-top: 10px; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
		                                                                To continue this registration, please complete the registration form by clicking on the link below.
		                                                            </td>
		                                                            
		                                                        </tr>
		                                                        <tr>
		                                                            <td style="padding-top: 10px; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
		                                                                <a href="'.base_url().'register/complate?email='.$email.'&token='.urlencode($token).'" target="_blank">Continue Registration</a>
		                                                            </td>
		                                                            
		                                                        </tr>
		                                                        <tr>
		                                                            <td style="padding: 25px 0 0 0; color: #153643; font-family: Arial, sans-serif; font-size: 12px; line-height: 20px;">
		                                                                We will always do the best service for you, to always stay by your side and put your safety on the Smart Patrol&trade; application.
		                                                                the security we provide will always protect you and your family every day and every time.
		                                                            </td>
		                                                        </tr>
		                                                        <tr>
		                                                            <td style="padding-top: 50px; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
		                                                                <b>Best Regards</b>
		                                                            </td>
		                                                            
		                                                        </tr>
		                                                        <tr>
		                                                            <td>
		                                                                <img src="https://drive.google.com/uc?export=view&id=13liJ9TmoGIxIYlv8eerf3whub4qh1pOf" height="60px" />
		                                                            </td>
		                                                        </tr>
		                                                    </table>
		                                                </td>
		                                                <td style="font-size: 0; line-height: 0;" width="20">
		                                                    &nbsp;
		                                                </td>
		                                                
		                                            </tr>
		                                        </table>
		                                    </td>
		                                </tr>
		                            </table>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td bgcolor="#b63031" style="padding: 30px 30px 30px 30px;">
		                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
		                                <tr>
		                                    <td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;" width="75%">
		                                        &copy; Powered by, Smart Patrol&trade; 2019<br/>
		                                        Product of <a style="color:white;" href="https://www.cudocomm.com/">Cudo Communications</a>, All Rights Reserved.
		                                    </td>
		                                    <td align="right" width="25%">
		                                        <table border="0" cellpadding="0" cellspacing="0">
		                                            <tr>
		                                                
		                                                <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
		                                                <td style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">
		                                                    <a href="https://www.cudocomm.com/" style="color: #ffffff;">
		                                                        <img src="https://drive.google.com/uc?export=view&id=1ZNVZiONR9jz0NElwAtE67eY-46Ux45Ss" alt="Facebook" width="90px" target="_blank" style="display: block;" border="0" />
		                                                    </a>
		                                                </td>
		                                            </tr>
		                                        </table>
		                                    </td>
		                                </tr>
		                            </table>
		                        </td>
		                    </tr>
		                </table>
		            </td>
		        </tr>
		    </table>
		</body>
		</html>'); 


	    if (!$this->email->send()) {  
	    	echo $this->email->print_debugger();
	    }else{  
			return true;
	    }  
	}


	public function complate(){
		//cek token & email bener apa nggak
		$get = $this->input->get();
		$cek = $this->db->get_where('token', ['email'=>$get['email'],'token' => $get['token']] )->result();

		if(count($cek)==1){
			$script = array(
	            'script' 		=> TRUE,
	            'script_url' 	=> 'main_script'
	        );
	        $this->load->view('header');
			$this->load->view('main');
			$this->load->view('footer', $script);
		}else{
			show_404();
		}
	}

	public function save_complate()
	{
		// cek token & email 
		$get 	= $this->input->get();
		$cek 	= $this->db->get_where('token', ['email'=>$get['email'], 'token'=>$get['token'] ])->result();
		date_default_timezone_set("Asia/Jakarta");
		$name 	= date("Ymdhis");


		if(count($cek)==1){

			//save
			$post = $this->input->post();
			$this->form_validation->set_rules($this->M_register->validasi_complate());

			if($this->form_validation->run()==TRUE){

				if($_FILES["logo"]['name'] && $_FILES["document"]['name']){

					$config =array();
	                $config['upload_path']    = './assets/apps/images';
	                $config['allowed_types']  = 'jpeg|jpg|png';
	                $config['file_name']      = $name;
	                $this->load->library('upload', $config, 'logo');
	                $this->logo->initialize($config);
	                $upload_logo  = $this->logo->do_upload('logo');


	                $config =array();
	                $config['upload_path']    = './assets/apps/document';
	                $config['allowed_types']  = 'pdf';
	                $config['file_name']      = $name;
	                $this->load->library('upload', $config, 'document');
	                $this->document->initialize($config);
	                $upload_document  = $this->document->do_upload('document');            

	                if ( !$upload_logo || !$upload_document)
	                {
	    				$this->session->set_flashdata('failed', '<div class="alert alert-danger"><button type="button" class="close">×</button> Failed to upload Logo or Document. </div>');


	                    $script = array(
				            'script' 		=> TRUE,
				            'script_url' 	=> 'main_script'
				        );
				        $this->load->view('header');
						$this->load->view('main');
						$this->load->view('footer', $script);

	                }else{
	                	$extLogo = pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);
	                	$data = [
	                		'pic'			=> $post['pic'],
	                		'phone'			=> $post['phone'],
	                		'alamat'		=> $post['address'],
	                		'path_logo'		=> $name.'.'.$extLogo,
	                		'path_doc'		=> $name.'.pdf',
	                		'flag_active'	=> 2,
	                	];
	                	$this->db->update('m_register_b2b', $data, ['email'=>$get['email']]);
	                	redirect('register/done','refresh');
	                }
				}

			}else{ //error validation
				$error 		= $this->form_validation->error_array();
				$fields 	= array_keys($error);
	    		$err_msg 	= $error[$fields[0]];
	    		$this->session->set_flashdata('failed', '<div class="alert alert-danger"><button type="button" class="close">×</button> '.$err_msg.' </div>');


				$script = array(
	            'script' 		=> TRUE,
	            'script_url' 	=> 'main_script'
		        );
		        $this->load->view('header');
				$this->load->view('main');
				$this->load->view('footer', $script);
			}
		}else{
			show_404();
		}

	}

	public function done()
	{
		$script = array(
            'script' 		=> TRUE,
            'script_url' 	=> 'main_script'
        );
        $this->load->view('header');
		$this->load->view('done');
		$this->load->view('footer', $script);
	}

}
