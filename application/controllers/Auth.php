<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');

	}

	public function index()
	{
		if ($this->session->userdata('email')) {
			redirect('user');
		}


		$this->form_validation->set_rules('email','Email', 'required|valid_email|trim');
		$this->form_validation->set_rules('password','Password', 
			'required|trim');

		if ($this->form_validation->run() == false) {

		$data['title'] = 'Login';
		$this->load->view('templates/header', $data);
		$this->load->view('auth/login');
		$this->load->view('templates/footer');

		}else{
			// validasi sukses login
			//method privat
			$this->_login();
		}
	}

	private function _login()
	{
		

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();
		if ($user ) {
			// user ada
			// userny aktif tidak
			if ($user['is_active'] ==  1) {
				// cek password
				if (password_verify($password, $user['password'])) {
					# password benar
						$data = [
									'email' => $user['email'],
									'role_id' => $user['role_id']
								];
						$this->session->set_userdata($data);
						if ($user['role_id'] == 1) {
							redirect('admin');
						}else{
							redirect('user');
						}
					}else {
					// password salah
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">wrong password!</div>');
						redirect('auth');
					}
			}else
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not been Activated!</div>');
				redirect('auth');
			}

		}else{
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not Registered!</div>');
			redirect('auth');
		}
	}

	public function registration()
	{
		if ($this->session->userdata('email')) {
			redirect('user');
		}
		
		$data['title'] = 'Registration';

		$this->form_validation->set_rules('name','Name', 'required|trim');
		$this->form_validation->set_rules('email','Email', 'required|valid_email|trim|is_unique[user.email]');
		$this->form_validation->set_rules('password1','Password', 
			'required|trim|matches[password2]', 
			['matches' => 'password dont match!',
			'min_lenght' => 'password too short!']);
		$this->form_validation->set_rules('password2','Password', 'required|trim|matches[password1]');


		if ($this->form_validation->run() == false) {
			# code...
		$this->load->view('templates/header', $data);
		$this->load->view('auth/registration');
		$this->load->view('templates/footer');
		}else
		{
			$email = $this->input->post('email',true);
			$data = [
				'name' => htmlspecialchars($this->input->post('name',true)),
				'email' => htmlspecialchars($email),
				'image' => 'default.jpg',
				'password' =>password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'role_id' => 2,
				'is_active' => 0,
				'date_created' => time()
				];

			// siapkan token email
			$token = base64_encode(random_bytes(32));
			$user_token = [
					'email' => $email,
					'token' => $token,
					'date_created' => time()
				];

			$this->db->insert('user', $data);
			$this->db->insert('user_token', $user_token);

			// kirim email
			$this->_sendEmail($token, 'verify');

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Success! Activated Your Account</div>');
			redirect('auth');
		}
	}

	public function _sendEmail($token, $type)
	{
		$config = [
			'protocol' =>'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_user' => 'syarif182x@gmail.com',
			'smtp_pass' => '7september',
			'smtp_port' => 465,
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
			];	

		$this->email->initialize($config);	
		$this->email->from('syarif182x@gmail.com', 'WPU-Login');
		$this->email->to($this->input->post('email'));

		// tipe verifikasi / forgot password
		if ($type == 'verify') {
			
		$this->email->subject('Account Verification');
		$this->email->message('Klik Link : <a href="'. base_url() .'auth/verify?email='. $this->input->post('email').'&token='. urlencode($token).'">Active</a> ');

		} else if($type == 'forgot')
		{
			$this->email->subject('Reset Password');
			$this->email->message('Klik Link reset password : <a href="'. base_url() .'auth/resetpassword?email='. $this->input->post('email').'&token='. urlencode($token).'">Reset Password</a> ');
		}


		if($this->email->send())
		{
			return true;
		} else
		{
			echo $this->email->print_debugger();
			die;
		}
		
	}

	public function verify()
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user  = $this->db->get_where('user', ['email' => $email])->row_array();

		if ($user) {
			// query token
			$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

			// sarua teu token jeung db
			if ($user_token){

				// waktu token
				if (time() - $user_token['date_created'] < (60 *60 *24)){
					
					$this->db->set('is_active', 1);
					$this->db->where('email', $email);
					$this->db->update('user');

					// mun ges berhasil delete weh data token di tabel
					$this->db->delete('user_token', ['email' =>$email]);

					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">'. $email.' has been activated! Please login</div>');
					redirect('auth');
				} else {
					// waktu token habis

					// namun token waktu habis hapus user&token di db
					$this->db->delete('user', ['email' => $email]);
					$this->db->delete('user', ['email' => $email]);

					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account Activation Fail! token expired</div>');
					redirect('auth');
				}
			} else{
				// tokenna salah
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account Activation Fail! wrong token.</div>');
					redirect('auth');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account Activation Fail! wrong email</div>');
			redirect('auth');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');

		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logout!</div>');
			redirect('auth');

	}

	public function blocked()
	{
		$data['title'] = 'Blocked';
		$this->load->view('auth/blocked', $data);
	}

	public function forgotPassword()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		if ($this->form_validation->run() == false) {

			$data['title'] = 'Forgot Password';
			$this->load->view('templates/header', $data);
			$this->load->view('auth/forgot-password');
			$this->load->view('templates/footer');

		}else {
			$email = $this->input->post('email');
			$user = $this->db->get_where('user',['email' => $email, 'is_active' => 1])->row_array();

			if ($user) {
				$token = base64_encode(random_bytes(32));
				$user_token = [
					'email' => $email,
					'token' => $token,
					'date_created' => time()
				];

				$this->db->insert('user_token', $user_token);
				$this->_sendEmail($token, 'forgot');

				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Please check Your email!</div>');
				redirect('auth/forgotpassword');

			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not register or Activated!</div>');
				redirect('auth/forgotpassword');
			}
		}
	}

	public function resetpassword()
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();

		if ($user) {
			$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

			if ($user_token) {
				$this->session->set_userdata('reset_email', $email);
				$this->changePassword();
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset Password Fail! Wrong Token!</div>');
				redirect('auth');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset Password Fail!</div>');
				redirect('auth');
		}
	}

	public function changePassword()
	{
		if (!$this->session->userdata('reset_email')) {
			redirect('auth');
		}
		$this->form_validation->set_rules('password1', 'Password', 'trim|required|matches[password2]');
		$this->form_validation->set_rules('password2', 'Repeat Password', 'trim|required|matches[password1]');

		if ($this->form_validation->run() == false) {

			$data['title'] = 'change Password';
			$this->load->view('templates/header', $data);
			$this->load->view('auth/change-password');
			$this->load->view('templates/footer');

		} else {
			$password = password_hash($this->input->post('password1'),PASSWORD_DEFAULT);
			$email = $this->session->userdata('reset_email');

			$this->db->set('password', $password);
			$this->db->where('email',$email);
			$this->db->update('user');

			$this->session->unset_userdata('reset_email');

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password change! please login</div>');
				redirect('auth');
		}
	}
}
