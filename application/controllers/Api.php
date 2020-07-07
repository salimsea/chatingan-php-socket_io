<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    public function __construct()
	{
		parent::__construct();
        header("Content-Type: application/json");
		if ( !auth_check() ) {
			redirect('home');
		}
    }
    
	public function index(){
        
		$this->load->view('login');
	
    }

    public function logout()
    {
        $this->session->unset_userdata('session_user');
		$this->session->unset_userdata('session_name');

		redirect('auth');
    }
    
    public function getRooms()
    {
        $this->db->order_by('id_room', 'DESC');
        $this->db->where('id_user_sender', $this->session->userdata('session_user'));
		$query = $this->db->get('tb_room_users');
        $room_user = $query->result();

        foreach ($room_user as $val)
        {
            $this->db->where('id_room', $val->id_room);
            $query0 = $this->db->get('tb_rooms');
            $room = $query0->row();

            $this->db->where('id_user', $val->id_user_sender);
            $query1 = $this->db->get('tb_users');
            $user_sender = $query1->row();

            $this->db->where('id_user', $val->id_user_receiver);
            $query2 = $this->db->get('tb_users');
            $user_receiver = $query2->row();
            
            $data[] = [
                "id_room"           =>	$val->id_room,
                "id_room_user"      =>	$val->id_room_user,
                "id_user_sender"    =>	$val->id_user_sender,
                "id_user_receiver"  =>	$val->id_user_receiver,
                "id_user"           =>  $room->id_user_update,
                "name_sender"       =>  $user_sender->name,
                "name_receiver"     =>  $user_receiver->name,
                "last_message"      =>  base64_decode($room->last_message),
                "last_sent"         =>  $room->updated_date, 
                "created_date"      =>  $room->created_date, 
                "str_last_sent"     =>  date("l \, h:i A", strtotime($room->updated_date)),
                "str_created_date"  =>  date("l \, h:i A", strtotime($room->created_date)),
            ];
        }
        
        print_r(json_encode($data, JSON_PRETTY_PRINT));
    }

    public function getRoomId($id)
    {
        $this->db->where('id_room', $id);
        $query = $this->db->get('tb_messages');
        $msg = $query->result();

        if(count($msg) > 0){
            foreach ($msg as $val)
            {
                $this->db->where('id_user', $val->id_user);
                $query2 = $this->db->get('tb_users');
                $user = $query2->row();
                
                $data[] = [
                    "id_message"        =>	$val->id_message,
                    "id_room"           =>	$val->id_room,
                    "id_user"           =>	$val->id_user,
                    "name_user"         =>  $user->name,
                    "sent_message"      =>  base64_decode($val->message),
                    "sent_file"         =>  $val->file, 
                    "type_message"      =>  $user->name == $this->session->userdata('session_name') ? "sent" : "replies",
                    "status"            =>  $val->status, 
                    "created_date"      =>  $val->created_date,
                    "str_created_date"  =>  date("l \, h:i A", strtotime($val->created_date)),
                ];
            }
        } else {
            $data = ["message" => "data tidak ada"];
        }
        
        print_r(json_encode($data, JSON_PRETTY_PRINT));
    }

    public function sentMsgNew(){
		$this->form_validation->set_rules('email', '<b>Email</b>', 'trim|required');
        $this->form_validation->set_rules('message_sent', '<b>Message</b>', 'trim|required');
        
        $user = $this->auth_model->get_user($this->input->post('email'));

		$room['last_message'] 	= base64_encode($this->input->post('message_sent'));
		$room['id_user_update'] = $this->session->userdata('session_user');
		$room['status'] 		= 1;
		$room['created_date'] 	= date('Y-m-d H:i:s');
        $room['updated_date'] 	= date('Y-m-d H:i:s');
        
        

		if ($this->form_validation->run() == FALSE) {

			$arr['success'] = false;
			$arr['notif'] = '<div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . validation_errors() . '</div>';

		} else {
            $this->db->where('id_user_sender', $this->session->userdata('session_user'));
            $this->db->where('id_user_receiver', $user->id_user);
            $query0 = $this->db->get('tb_room_users');
            $room_user = $query0->row();

            if($room_user == NULL){
                $this->db->insert('tb_rooms',$room);
                $detail_room = $this->db->select('*')->from('tb_rooms')->where('id_room',$this->db->insert_id())->get()->row();

                $room_users1['id_room'] 	     = $detail_room->id_room;
                $room_users1['id_user_sender'] 	 = $this->session->userdata('session_user');
                $room_users1['id_user_receiver'] = $user->id_user;
                $room_users1['created_date'] 	 = date('Y-m-d H:i:s');

                $room_users2['id_room'] 	     = $detail_room->id_room;
                $room_users2['id_user_sender'] 	 = $user->id_user;
                $room_users2['id_user_receiver'] = $this->session->userdata('session_user');
                $room_users2['created_date'] 	 = date('Y-m-d H:i:s');

                $arr['id_room']         = $detail_room->id_room;
                $arr['id_user'] 		= $this->session->userdata('session_user');
                $arr['message'] 		= base64_encode($this->input->post('message_sent'));
                $arr['file'] 			= '-';
                $arr['status'] 			= 1;
                $arr['created_date'] 	= date('Y-m-d H:i:s');

                $this->db->insert('tb_room_users',$room_users1);
                $this->db->insert('tb_room_users',$room_users2);
                $this->db->insert('tb_messages',$arr);
                $detail_msg = $this->db->select('*')->from('tb_messages')->where('id_message',$this->db->insert_id())->get()->row();
           
            } else {
                $this->db->where('id_room', $room_user->id_room);
                $this->db->update('tb_rooms', ['id_user_update' => $arr['id_user'], 'last_message' => base64_encode($this->input->post('message_sent'))]);
                $detail_room = $this->db->select('*')->from('tb_rooms')->where('id_room',$room_user->id_room)->get()->row();

                $room_users1['id_room'] 	     = $detail_room->id_room;
                $room_users1['id_user_sender'] 	 = $this->session->userdata('session_user');
                $room_users1['id_user_receiver'] = $user->id_user;
                $room_users1['created_date'] 	 = date('Y-m-d H:i:s');

                $room_users2['id_room'] 	     = $detail_room->id_room;
                $room_users2['id_user_sender'] 	 = $user->id_user;
                $room_users2['id_user_receiver'] = $this->session->userdata('session_user');
                $room_users2['created_date'] 	 = date('Y-m-d H:i:s');

                $arr['id_room']         = $detail_room->id_room;
                $arr['id_user'] 		= $this->session->userdata('session_user');
                $arr['message'] 		= base64_encode($this->input->post('message_sent'));
                $arr['file'] 			= '-';
                $arr['status'] 			= 1;
                $arr['created_date'] 	= date('Y-m-d H:i:s');

                // $this->db->insert('tb_room_users',$room_users1);
                // $this->db->insert('tb_room_users',$room_users2);
                $this->db->insert('tb_messages',$arr);
                $detail_msg = $this->db->select('*')->from('tb_messages')->where('id_message',$this->db->insert_id())->get()->row();
            }

			$date = $detail_msg->created_date;
			$unixTimestamp = strtotime($date);
			$dayOfWeek = date("l \, h:i A", $unixTimestamp); // output Friday , 10:42 PM

			$arr['id_room'] 		    =   $detail_msg->id_room;
			$arr['id_user_sender']      =   $user->id_user;
			$arr['id_user_receiver']    =   $this->session->userdata('session_user');
            $arr['name_receiver'] 	    =   $user->name;
            $arr['name_sender'] 	    =   $this->session->userdata('session_name');
			$arr['message'] 		    =   base64_decode($detail_msg->message);
			$arr['file'] 			    =   $detail_msg->file;
			$arr['type_message'] 	    =   $detail_msg->id_user == $this->session->userdata('session_user') ? "sent" : "replies";
			$arr['created_date'] 	    =   $dayOfWeek;
			$arr['success'] 		    =   true;
			$arr['notif'] 			    =   '<div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 alert alert-success" role="alert"> <i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Message sent ...</div>';

		}
		
		echo json_encode($arr);
	}
}
