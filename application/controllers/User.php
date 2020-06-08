<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		login();
	}

	public function index()
	{
		$data['title'] = "My Profile";
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

		$this->load->view('templates/header', $data);
		$this->load->view('user/index', $data);
		$this->load->view('templates/footer');

	}

	public function edit()
	{
		$data['title'] = "Edit Profile";
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

		$this->form_validation->set_rules('name', 'Full Name', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('templates/header', $data);
			$this->load->view('user/edit', $data);
			$this->load->view('templates/footer');
		} else {
			$name = $this->input->post('name');
			$email = $this->input->post('email');

			// cek jika ada gambar
			$upload_image = $_FILES['image']['name'];

			if ($upload_image) {
			
				$config['upload_path'] = './assets/img';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']  = '2048';
				
				$this->load->library('upload', $config);
				
				if ($this->upload->do_upload('image')){

					$old_image = $data['user']['image'];
					if ($old_image != 'default.jpg') {
						unlink(FCPATH . 'assets/img/'. $old_image);
					}

					$new_image = $this->upload->data('file_name');
					$this->db->set('image', $new_image);
				}
				else{
					echo $this->upload->display_errors();
				}

			}

			$this->db->set('name', $name);
			$this->db->where('email', $email);
			$this->db->update('user');

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your Profile has benn Updated!</div>');
			redirect('user');
		}
	}

	public function changePassword()
	{
		$data['title'] = "Edit Profile";
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

		$this->form_validation->set_rules('current_password', 'Current Password', 'trim|required');
		$this->form_validation->set_rules('new_password1', 'New Password', 'trim|required|min_length[3]|matches[new_password2]');
		$this->form_validation->set_rules('new_password2', 'New Password', 'trim|required|min_length[3]|matches[new_password1]');

		if ($this->form_validation->run() == FALSE) {

			$this->load->view('templates/header', $data);
			$this->load->view('user/changePassword', $data);
			$this->load->view('templates/footer');

		}else
		{
			$current_password = $this->input->post('current_password');
			$new_passsword = $this->input->post('new_password1');

			if (!password_verify($current_password, $data['user']['password'])) {
				// password tidak sama dengan yang lama
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong current password!</div>');
				redirect('user/changePassword');

			} else {
				if ($current_password == $new_passsword) {
					// password baru sama dengan yang lama
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Password same with current password!</div>');
					redirect('user/changePassword');
				} else {
					// password benar
					$password_hash = password_hash($new_passsword, PASSWORD_DEFAULT);

					$this->db->set('password', $password_hash);
					$this->db->where('email', $this->session->userdata('email'));
					$this->db->update('user');

					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password Change!</div>');
				redirect('user/changePassword');
				}

			}
		}
	}
}
