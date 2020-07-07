<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('auth_model');
		if ( !auth_check() ) {
			redirect('auth');
		}
	}
	
	public function index(){
		$data['title'] = "Chating";
		$this->db->where('id_user_sender', $this->session->userdata('session_user'));
		$query = $this->db->get('tb_room_users');
		$room_user = $query->row();
		if($room_user != NULL){
			$this->db->where('id_room', $room_user->id_room);
			$query = $this->db->get('tb_messages');
			$data['msg'] = $query->result();
	
			// $this->db->where('id_user', $this->session->userdata('session_user'));
			$query = $this->db->get('tb_rooms');
			$data['room'] = $query->result();
		} else {
			$data['msg'] = NULL;
			$data['room'] = NULL;
		}
		

		$this->load->view('home',$data);
	
	}

	public function kirimPesan(){
		$this->form_validation->set_rules('message', '<b>Pesan</b>', 'trim|required');

		$arr['id_room'] 		= $this->input->post('id_room');
		$arr['id_user'] 		= $this->session->userdata('session_user');
		$arr['message'] 		= base64_encode($this->input->post('message'));
		$arr['file'] 			= $this->input->post('file');
		$arr['status'] 			= 1;
		$arr['created_date'] 	= date('Y-m-d H:i:s');

		if ($this->form_validation->run() == FALSE) {

			$arr['success'] = false;
			$arr['notif'] = '<div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . validation_errors() . '</div>';

		} else {
			$this->db->where('id_room', $arr['id_room']);
			$this->db->update('tb_rooms', ['id_user_update' => $arr['id_user'] ,'last_message' => $arr['message'], 'updated_date' => $arr['created_date']]);

			$this->db->insert('tb_messages',$arr);
			$detail = $this->db->select('*')->from('tb_messages')->where('id_message',$this->db->insert_id())->get()->row();
			
			$this->db->where('id_room', $detail->id_room);
			$this->db->where('id_user_sender', $detail->id_user);
			$query = $this->db->get('tb_room_users');
			$room_user = $query->row();

			$date = $detail->created_date;
			$unixTimestamp = strtotime($date);
			$dayOfWeek = date("l \, h:i A", $unixTimestamp); // output Friday , 10:42 PM

			$arr['id_room'] 			= $detail->id_room;
			$arr['id_user_sender'] 		= $detail->id_user;
			$arr['id_user_receiver'] 	= $room_user->id_user_receiver;
			$arr['message'] 			= base64_decode($detail->message);
			$arr['file'] 				= $detail->file;
			$arr['type_message'] 		= $detail->id_user == $this->session->userdata('session_user') ? "sent" : "replies";
			$arr['created_date'] 		= $dayOfWeek;
			$arr['success'] 			= true;
			$arr['notif'] 				= '<div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 alert alert-success" role="alert"> <i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Message sent ...</div>';

		}
		
		echo json_encode($arr);
	}
}
