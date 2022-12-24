<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_register extends CI_Model
{


	public function validasi_form()	
    {	
    	return [
    		[
    			'field' 	=> 'name',
				'label'		=> 'Company Name',
				'rules'		=> 'required|rtrim',
			],
    		[
    			'field' 	=> 'email',
				'label'		=> 'Email',
				'rules'		=> 'required|rtrim|is_unique[m_register_b2b.email]',
				'errors'	=> ['is_unique'=>'This email already exists.']
			],
			[
    			'field' 	=> 'password',
				'label'		=> 'Password',
				'rules'		=> 'required|rtrim|min_length[8]',
			]

    	];
    }

    public function validasi_complate()
    {
    	return [
    		[
    			'field' 	=> 'pic',
				'label'		=> 'PIC Name',
				'rules'		=> 'required|rtrim',
			],
    		[
    			'field' 	=> 'phone',
				'label'		=> 'Phone Number',
				'rules'		=> 'required|rtrim|numeric|min_length[8]|max_length[15]',
			],
			[
    			'field' 	=> 'address',
				'label'		=> 'Address',
				'rules'		=> 'required|rtrim',
			]
    	];
    }

    public function get_by_name($name)
    {
    	return $this->db->get_where('m_register_b2b', ['title_nm'=>$name]);
    }


    public function save_register()
    {
    	$post 		= $this->input->post();
    	
    	$data = [
    		'title_nm'		=> $post['name'],
    		'email'			=> $post['email'],
    		'password'		=> md5($post['password']),
    		'tgl_join'		=> date("Y-m-d"),
    	];

        return $this->db->insert('m_register_b2b', $data);
    }


	private function upload($name)
	{
		
		$config['upload_path'] 		= './assets/apps/images';
		$config['allowed_types'] 	= 'jpeg|jpg|png';
		$config['file_name']		= $name;
		$config['overwrite']		= true;
		$config['max_size']  		= '13032';
		
		$this->load->library('upload', $config);
		
		if ( $this->upload->do_upload('image')){
			return $this->upload->data('file_name');
		}
		
		$error = array('error' => $this->upload->display_errors());

		$this->session->set_flashdata('category_error', $error['error']);
		redirect('login');
	}


    
}
                        
/* End of file x.php */
