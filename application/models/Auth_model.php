<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function get_user($email)
	{
		$this->db->where('email', $email);
		$query = $this->db->get('tb_users');
		return $query->row();
	}
	
	public function get_userz($id_user)
	{
		$this->db->where('id_user', $id_user);
		$query = $this->db->get('tb_users');
		return $query->row();
    }
    
    // public function get_users()
	// {
	// 	$query = $this->db->get('tb_users');
	// 	return $query->result();
    // }
    
	public function is_logged_in()
	{
		if ($this->session->userdata('session_login') == true) {
			$user = $this->get_userz($this->session->userdata('session_user'));
			if (!empty($user)) {
				if ($user->status == 1) {
					return true;
				}
			}
		}
		return false;
	}

	public function get_logged_user()
	{
		if ($this->is_logged_in()) {
			$id_user = $this->session->userdata('session_user');
			$this->db->where('id_user', $id_user);
			$query = $this->db->get('tb_users');
			return $query->row();
		}
	}

}