<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct()
	{
		parent::__construct();
		if ( auth_check() ) {
			redirect('home');
		}
    }
    
	public function index(){
        
		$this->load->view('login');
	
    }
    
    public function login()
    {
        
            $email 			= $this->input->post('email');
            $password		= $this->input->post('password');

            $user = $this->auth_model->get_user($email);
            
            if ( $user ) {

                if ( $password !== $user->password ) {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger">Password Yang Anda Masukan Salah</div>');
                    redirect('auth');

                }

                if ( $user->status == 0 ) {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger">Terjadi Kesalahan Pada Akun</div>');
                    redirect('auth');
                }

                $user_data = array(
                    'session_user' 		=> $user->id_user,
                    'session_name' 		=> $user->name,
                    'session_login' 	=> true
                );
            
                $this->session->set_userdata($user_data);
                redirect('home');

            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger">Username / Password Salah</div>');

                redirect('auth');

            }
    }
}
