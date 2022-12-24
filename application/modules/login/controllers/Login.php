<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->M_global->realip() == 'ID') {
			$this->lang->load('information_lang', 'indonesia');
		} else {
			$this->lang->load('information_lang', 'english');
		}
		$this->load->model('login/M_login');
		$this->load->library('session');
		$this->load->helper('get_domain_helper');
	}


	public function default_redirect()
	{
		$table_id = json_decode($this->session->userdata('table_id'))->id;
		$menus = $this->session->userdata('table');
		foreach ($menus as $m) {
			if (in_array($m->id, $table_id) && $m->link != '#') {
				redirect($m->link);
				break;
			}
		}
	}

	public function index()
	{



		if ($this->session->userdata("b2b_token")) {
			$this->default_redirect();
		} else {
			$apps = json_decode(file_get_contents('app.json'));
			foreach ($apps as $app) {
				if (get_domain() === $app->app_domain) {
					$app_id = $app->app_id;
					$color_1 = $app->color1;
					$color_2 = $app->color2;
					$logo =  $app->logo;
					$company_logo =  $app->company_logo;
					$company_name =  $app->company_name;
					$icon =  $app->icon;
					$app_domain = $app->app_domain;
					break;
				}
			}
			$script = array(
				'script' 	 => TRUE,
				'script_url' => 'main_script'
			);
			$data = [
				'app_id' => $app_id,
				'color_1' => $color_1,
				'color_2' => $color_2,
				'logo' => $logo,
				'icon' => $icon,
				'company_logo' => $company_logo,
				'company_name' => $company_name,
				'app_domain' =>  strtoupper(explode('.', $app_domain)[0]),
			];

			// $this->load->view('layout/header');
			$this->load->view('main', $data);
			// $this->load->view('login/views/main_script');
		}
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


	public function login_validation()
	{
		// $this->form_validation->set_rules([
		// 	[
		// 		'field' => 'username',
		// 		'label' => 'Username',
		// 		'rules' => 'required|rtrim',
		// 	],
		// 	[
		// 		'field' => 'password',
		// 		'label' => 'Password',
		// 		'rules' => 'required|rtrim',
		// 	]
		// ]);
		// if ($this->form_validation->run() == TRUE) {
		$username = $this->input->post('username') ?: $this->input->get('x', TRUE);
		$password = $this->input->post('password') ?: $this->input->get('xx', TRUE);
		$remember_me = $this->input->post('remember_me') ?: $this->input->get('xxx', TRUE);
		if (!$this->input->get('xx', TRUE)) {
			$password = md5($password);
		}
		$apps = json_decode(file_get_contents('app.json'));
		foreach ($apps as $app) {
			if (get_domain() === $app->app_domain) {
				$app_id = $app->app_id;
				$color_1 = $app->color1;
				$color_2 = $app->color2;
				$logo =  $app->logo;
				$icon =  $app->icon;
				$app_domain = $app->app_domain;
				break;
			}
		}

		$data_user = $this->M_login->login_validation($username, $app_id)->row();
		if ($data_user) {
			if ($data_user->password === $password) {
				if ($data_user->status != 'active') {
					$this->session->set_flashdata('category_error', 'User Ini Tidak Aktif');
					$this->index();
					return;
				}

				$choose_b2b = [];
				if ($data_user->user_roles == 'cudo' || $data_user->user_roles == 'superadmin') {
					// if ($data_user->b2b_token) {
					// 	$array = explode(' | ', $data_user->b2b_token);
					// 	$this->db->where_in('b2b_token', $array);
					// }
					$choose_b2b = $this->db->where('flag_active', '1')->order_by("title_nm", "ASC")->get("m_register_b2b")->result();
				} else {
					$query_choose_b2b = $this->db->where('flag_active', '1')
										->order_by("title_nm", "ASC")
										->get_where("m_register_b2b", ["b2b_token" => $data_user->b2b_token])
										->result();
					foreach ($query_choose_b2b as $row) {
						$choose_b2b[] = $row;
						$sub = $this->subB2B($row->b2b_token);
						if ($sub) {
							foreach ($sub as $s) {
								$choose_b2b[] = $s;
							}
						}
					}
				}

				// echo json_encode($choose_b2b);
				// die;

				if ($data_user->table_id) {
					// echo json_encode( $this->M_login->menu_v2($data_user->hidden_feature, $data_user->table_id)); die;
					$this->session->set_userdata([
						'id' => $data_user->id,
						'app_id' => $app_id,
						'color_1' => $color_1,
						'color_2' => $color_2,
						'logo' => $logo,
						'icon' => $icon,
						'app_domain' => strtoupper(explode('.', $app_domain)[0]),
						'username'  => $data_user->username,
						'password'  => $data_user->password,
						'full_name' => $data_user->full_name,
						'user_roles' => $data_user->user_roles,
						'user_b2b_token' => $data_user->b2b_token,
						'token' => $data_user->token,
						'jwt_access' => $data_user->jwt_access,
						'status' => $data_user->status,
						'email' => $data_user->email,
						'hidden_feature' => $data_user->hidden_feature,
						'table_id' => $data_user->table_id,
						'title_nm' => $data_user->title_nm,
						'path_logo' => $data_user->path_logo != '' ?  $data_user->path_logo : 'noimage.png',
						'table' => $this->M_login->menu_v3($data_user->user_roles, $data_user->hidden_feature)
					]);
					// echo json_encode($_SESSION); die;
					if (count($choose_b2b) > 1) {
						$this->session->set_userdata(['choose_b2b' => $choose_b2b]);
						redirect("login/choose_b2b");
					}
					$this->session->set_userdata(['b2b_token' => $data_user->b2b_token]);
					if ($remember_me) {
						$this->input->set_cookie([
							'name'   => 'remember_me',
							'value'  => $data_user->id,
							'expire' => '30000000000000',
							'secure' => TRUE
						]);
					}
					$this->default_redirect();
				} else {
					$this->session->set_flashdata('category_error', 'No modules available');
					$this->index();
				}
			} else {
				$this->session->set_flashdata('category_error', 'Password Salah!');
				$this->index();
			}
		} else {
			$this->session->set_flashdata('category_error', 'Username tidak ada!');
			$this->index();
		}
		// } else {
		// 	$this->index();
		// }
	}

	public function logout()
	{
		delete_cookie("remember_me");
		$user_data = $this->session->all_userdata();
		foreach ($user_data as $key => $value) {
			$this->session->unset_userdata($key);
		}
		$this->session->sess_destroy();
		$this->session->set_flashdata('category_error', 'Smart Patrol By Cudo Cudo Communication');
		redirect('login');
	}

	public function choose_b2b()
	{
		$data["data"] = $this->session->userdata("choose_b2b");
		if (!$data["data"]) redirect("");
		$this->load->view('choose_b2b', $data);
	}

	public function goredirect($b2b, $url = null)
	{
		if (!$this->session->userdata("choose_b2b")) redirect("");
		$data_b2b = $this->M_login->detail_b2b($b2b);
		$data_b2b->user_roles = $_SESSION["user_roles"];
		$this->session->set_userdata([
			// 'table' => $this->M_login->menu($data_b2b->hidden_feature),
			'table' => $this->M_login->menu_v3($data_b2b->user_roles, $data_b2b->hidden_feature),
			'hidden_feature' => $data_b2b->hidden_feature,
			'title_nm' => $data_b2b->title_nm,
			'path_logo' => $data_b2b->path_logo != '' ?  $data_b2b->path_logo : 'noimage.png',
			"b2b_token" => $b2b
		]);
		if ($url) {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			$this->default_redirect();
		}
	}

	public function cronscheduler()
	{
		date_default_timezone_set("Asia/Jakarta");
		$time = new DateTime(date('H:i'));
		$days = ["", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
		$data = $this->db->where('flag_active', '1')->get('cluster_route');
		$reset = date_diff($time, new DateTime('00:00'));
		if ($data->num_rows() > 0) {
			foreach ($data->result() as $row) {
				$ot = json_decode($row->otherdata);
				if (is_object($ot)) {

					foreach ($ot->day as $day) {
						if ($days[date('N')] == $day) {
							// echo $day;
							$id = $row->id_route;
							foreach ($ot->hours as $hours) {
								$start = new DateTime($hours);
								$dd = date_diff($start, $time);
								if ($dd->h == 0 && $dd->i <= 5) {
									$this->db->query("INSERT INTO t_task_patrol_header(
									b2b_token
								  	,publish_date 
									,publish_time 
									,id_route
									,cluster_name 
									,total_cp 
									,finish_time 
								)
								SELECT 
									a1.b2b_token
									,current_date as publish_date
									,to_char(CURRENT_TIMESTAMP, 'HH24:MI:SS') AS publish_time
									,a1.id_route
									,a1.cluster_name
									,count(a2.id) as total_cp
									,a1.finish_time 
								 FROM cluster_route a1 LEFT JOIN check_point a2
								 ON a2.b2b_token=a1.b2b_token AND a2.cluster_route::integer = a1.id_route
								 WHERE a1.id_route='$id' AND a1.flag_active =1 AND a2.flag_disable=1
								 GROUP BY a1.id_route;");
									// echo " - Task Sent! <br>";
									echo "Task Sent!";
								}
							}
						}
					}
				}
			}
		}
	}
}
