<!DOCTYPE html>
<html>
    <title>Chatingan</title>
    <head>
        <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />  
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,300' rel='stylesheet' type='text/css'>
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
        <link href="<?php echo base_url('assets/css/chatv1.css');?>" rel="stylesheet">
    </head>
<body>

<audio id="notif_audio">
    <source src="<?php echo base_url('sounds/notifyy.mp3');?>" type="audio/mpeg">
    <source src="<?php echo base_url('sounds/notifyy.ogg');?>" type="audio/ogg">
    <source src="<?php echo base_url('sounds/notifyy.wav');?>" type="audio/wav">
</audio>

<div id="frame">
	<div id="sidepanel">
		<div id="profile">
			<div class="wrap">
				<img id="profile-img" src="<?=base_url("assets/images/user-profile3.png");?>" class="online" alt="" />
				<p><?=$this->session->userdata('session_name');?></p>
				<i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>
				<div id="status-options">
					<ul>
						<li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>
						<li id="status-away"><span class="status-circle"></span> <p>Away</p></li>
						<li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>
						<li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>
					</ul>
				</div>
				<div id="expanded">
					<label for="twitter"><i class="fa fa-facebook fa-fw" aria-hidden="true"></i></label>
					<input name="twitter" type="text" value="mikeross" />
					<label for="twitter"><i class="fa fa-twitter fa-fw" aria-hidden="true"></i></label>
					<input name="twitter" type="text" value="ross81" />
					<label for="twitter"><i class="fa fa-instagram fa-fw" aria-hidden="true"></i></label>
					<input name="twitter" type="text" value="mike.ross" />
				</div>
			</div>
		</div>
		<div id="search">
			<label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
			<input type="text" placeholder="Search contacts..." />
		</div>
		<div id="contacts">
            <input type="hidden" name="id_room" id="id_room" />
            <input type="hidden" name="id_user_receiver" id="id_user_receiver" />
			<ul>
				<div id="htmlRooms"></div>
			</ul>
		</div>
		<div id="bottom-bar">
			<button id="addcontact" style="background: #4caf50;" onclick="addMessage();"><i class="fa fa-plus-square-o fa-fw" aria-hidden="true"></i>Buat Pesan Baru<span></span></button>
			<button id="settings" style="background: #ff4444;" onclick="logout();"><i class="fa fa-sign-out" aria-hidden="true"></i> <span>Keluar</span></button>
		</div>
	</div>
    <div id="content-msg" class="content" style="display:none;">
        <div class="contact-profile">
            <span id="htmlImageUser"></span>
            <p id="htmlReceiver">Harvey Specter</p> <span id="htmlKetik" style="font-size:12px;color:green;display:none;"> &nbsp;&nbsp;&nbsp; sedang mengetik...</span>
            <div class="social-media">
                <i class="fa fa-search" aria-hidden="true"></i>
            </div>
        </div>
        <div class="messages">
            <ul>
                <div id="htmlMessage"></div>
            </ul>
        </div>
        <div class="message-input">
            <div class="wrap">
            <input type="text" name="message" id="message" placeholder="Write your message..." onkeyup="ketik(true)" onblur="ketik(false)" autofocus />
            <i class="fa fa-paperclip attachment" aria-hidden="true"></i>
            <button class="submit" id="submit" type="button" onclick="sentMsg()"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            </div>
        </div>
    </div>
    <div id="content-msg-empty" class="content">
        <div style="text-align: center;margin-top: 200px;">
            <img src="<?=base_url("/assets/images/empty.svg");?>" width="120px" />
            <br /><br />
            Build By Codeigniter & Socket.IO ♥
        </div>
    </div>
</div>

<div class="modal fade" id="ModalAddMsg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form>
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email Adresss">
                </div>
            </div>
            <div class="form-group row">
                <label for="message" class="col-sm-2 col-form-label">Message</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="message_sent" name="message_sent" placeholder="Write your message..."></textarea>
                </div>
            </div>
            <div class="form-group">
            <button type="button" class="btn btn-primary" onclick="sentMsgNew();">Sent Message</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('node_modules/socket.io/node_modules/socket.io-client/socket.io.js');?>"></script>
<script >
var base_url = "<?=base_url();?>";
var id_user = "<?=$this->session->userdata('session_user');?>";
getRoom();

function getRoom() {
    $.getJSON(base_url + "Api/getRooms", function(res){
        let html = "";
        $.each(res, (i, v) => {
            // $('span#htmlUserOnline'+v.id_user_receiver+'x').addClass('online')
            // $('span#htmlUserOnline'+v.id_user_receiver+'x').removeClass('offline')

            var message = v.last_message;
            var orYou = v.id_user == id_user ? "<u>You</u> : " : "";
            var resultMsg = (v.last_message).length > 50 ? message.substr(0, 50) + '...' : v.last_message;
            var param = "getIdRoom('" + v.id_room + "','"+v.name_receiver+"','"+v.id_user_receiver+"')";
            html += 
            '<li class="contact" onclick="'+param+'" id="contact'+v.id_room+'x">'+
            '    <div class="wrap" >'+
            '        <span id="htmlUserOnline'+v.id_user_receiver+'x" class="contact-status"></span>'+
            '        <img src="'+base_url+'/assets/images/user-profile1.png" alt="" />'+
            '        <div class="meta">'+
            '            <p class="name">'+v.name_receiver+'</p>'+
            '            <p class="preview"><span id="htmlOrYou'+v.id_room+'x">'+ orYou +'</span><span id="htmlLastMessage'+v.id_room+'x"> '+ resultMsg +'</span></p>'+
            '        </div>'+
            '    </div>'+
            '</li>';
        });
        $('#htmlRooms').empty();
        $('#htmlRooms').prepend(html);
    })
}

const getIdRoom = (id,receiver,id_user_receiver) => {
    $('#content-msg-empty').hide();
    $('#content-msg').fadeIn();
    $('#id_room').val(id);
    $('#id_user_receiver').val(id_user_receiver);
    $('.contact').removeClass('active');
    $('#contact'+id+'x').addClass('active');
    $('#htmlReceiver').html(receiver);
    $('#htmlImageUser').html('<img src="'+base_url+'/assets/images/user-profile1.png" alt="" />');
    
    $("input#message").focus();
    $.getJSON(base_url + "Api/getRoomId/" + id, function(res){
        let html = "";
        let htmlLastMessage = "";
        $.each(res, (i, v) => {
            htmlLastMessage = v.sent_message;
            if(v.type_message == "replies"){
                html += 
                '<li class="sent">'+
                '    <img src="'+base_url+'/assets/images/user-profile1.png" alt="" />'+
                '    <p>'+v.sent_message+'</p>'+
                '</li>';
            } else if(v.type_message == "sent") {
                html += 
                '<li class="replies">'+
                '    <img src="'+base_url+'/assets/images/user-profile1.png" alt="" />'+
                '    <p>'+v.sent_message+'</p>'+
                '</li>';
            } 
            
        });
        $('#htmlLastMessage'+id+'x').html(htmlLastMessage);
        $('#htmlMessage').empty();
        $('#htmlMessage').html(html);
    })
    $('.messages').animate({
    scrollTop: $('.messages').get(0).scrollHeight*10
  }, 1500);

}

const logout = () => {
    window.location.href = base_url + 'api/logout';
}

const addMessage = () => {
    $('#content-msg').fadeOut();
    $('#content-msg-empty').fadeIn();
    $('#ModalAddMsg').modal('show');
}

const sentMsgNew = () => {
    var dataString = { 
                email : $("input#email").val(),
                message_sent : $("textarea#message_sent").val(),
            };
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('api/sentMsgNew');?>",
        data: dataString,
        dataType: "json",
        cache : false,
        success: function(data){
            $('#ModalAddMsg').modal('hide');
            if(data.success == true){

            var socket = io.connect( 'http://'+window.location.hostname+':3000' );
            socket.emit('room', { 
                id_user_sender      : data.id_user_sender,
                id_user_receiver    : data.id_user_receiver,
                id_room             : data.id_room,
                name_receiver       : data.name_receiver,
                name_sender         : data.name_sender,
                message             : data.message,
                type_message        : data.type_message,
                file                : data.file,
                created_date        : data.created_date,
            });     
            //   $(".messages").animate({ scrollTop: $(document).height() }, "fast");

            } else if(data.success == false){
            }
        
        } ,error: function(xhr, status, error) {
            alert(error);
        },

    });
}

const sentMsg = () => {
    var dataString = { 
                id_room : $("input#id_room").val(),
                message : $("input#message").val(),
                file    : "-",
            };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('home/kirimPesan');?>",
            data: dataString,
            dataType: "json",
            cache : false,
            success: function(data){
                $("#message").val("");
                if(data.success == true){

                var socket = io.connect( 'http://'+window.location.hostname+':3000' );
                socket.emit('chat', { 
                    id_user_sender      : data.id_user_sender,
                    id_user_receiver    : data.id_user_receiver,
                    id_room             : data.id_room,
                    message             : data.message,
                    type_message        : data.type_message,
                    file                : data.file,
                    created_date        : data.created_date,
                });
                //   $(".messages").animate({ scrollTop: $(document).height() }, "fast");

                } else if(data.success == false){
                }
            
            } ,error: function(xhr, status, error) {
                alert(error);
            },

        });
}

const ketik = (param) => {
    var socket = io.connect( 'http://'+window.location.hostname+':3000' );
    socket.emit('ketik', { 
        id_room             : $('#id_room').val(),
        id_user_sender      : id_user,
        id_user_receiver    : $('#id_user_receiver').val(),
        status              : param,
    });
}

var socket = io.connect( 'http://'+window.location.hostname+':3000' );

socket.on('room', function(data) {
    var orYou = data.id_user == id_user ? "<u>You</u> : " : "";
    $('#htmlOrYou'+data.id_room+'x').html(orYou);

    if(id_user == data.id_user_sender)
    {
        getRoom();
        let receiver = "";
        if(data.name_sender == "<?=$this->session->userdata('session_name');?>"){
            receiver = data.name_receiver;
        } else {
            receiver = data.name_sender;
        }
        getIdRoom(data.id_room, receiver, data.id_user_receiver);
        if(data.id_user_sender == id_user){
            $('#notif_audio')[0].play();
        }
        console.log("sender")
    } else if(id_user == data.id_user_receiver) {
        getRoom();
        let receiver = "";
        if(data.name_sender == "<?=$this->session->userdata('session_name');?>"){
            receiver = data.name_receiver;
        } else {
            receiver = data.name_sender;
        }
        getIdRoom(data.id_room, receiver, data.id_user_receiver);
        if(data.id_user_sender == id_user){
            $('#notif_audio')[0].play();
        }
        console.log("respon room socket -> ", data)
    } else {
        console.log("id_user_receiver tidak sama dengan id user dipakai")

    }
    
});

socket.on('chat', function(data) {
    var orYou = data.id_user_receiver != id_user ? "<u>You</u> : " : "";
    $('#htmlOrYou'+data.id_room+'x').html(orYou);

    $('#htmlLastMessage'+data.id_room+'x').html(data.message);

    console.log("respons chat -> ", data);

    if(id_user == data.id_user_receiver)
    {
        // $('#htmlOrYou'+data.id_room+'x').html('you');
        $('#notif_audio')[0].play();
    } else {
        console.log("id_user_receiver tidak sama dengan id user dipakai")
    }

    if(data.id_room == $('#id_room').val()){
        
        if(data.id_user_sender != id_user){
            // $('#notif_audio')[0].play();
            var html = "";
            html += 
                '<li class="sent">'+
                '    <img src="'+base_url+'/assets/images/user-profile1.png" alt="" />'+
                '    <p>'+data.message+'</p>'+
                '</li>';
            $('div#htmlMessage').append(html);
        } else {
            var html = "";
            html += 
                '<li class="replies">'+
                '    <img src="'+base_url+'/assets/images/user-profile1.png" alt="" />'+
                '    <p>'+data.message+'</p>'+
                '</li>';
            $('div#htmlMessage').append(html);
        }

    }
    
    $('.messages').animate({
        scrollTop: $('.messages').get(0).scrollHeight*10
    }, 1000);

});

socket.on('ketik', function(data) {
    if(data.id_user_receiver == id_user && data.id_room == $('#id_room').val() ) {
        if(data.status){
        $('#htmlKetik').fadeIn();
        } else {
            $('#htmlKetik').fadeOut();
        }
    }
})

socket.on('online', function(data) {
    if(data.status == "visible"){
        $('span#htmlUserOnline'+data.id_user_sender+'x').addClass('online')
        $('span#htmlUserOnline'+data.id_user_sender+'x').removeClass('offline')
        console.log('browser on')
    } else {
        $('span#htmlUserOnline'+data.id_user_sender+'x').addClass('offline')
        $('span#htmlUserOnline'+data.id_user_sender+'x').removeClass('online')
        console.log('browser off')
    }
    console.log(data)
})

window.onload = function() {

    // check the visiblility of the page
    var hidden, visibilityState, visibilityChange;

    if (typeof document.hidden !== "undefined") {
        hidden = "hidden", visibilityChange = "visibilitychange", visibilityState = "visibilityState";
    }
    else if (typeof document.mozHidden !== "undefined") {
        hidden = "mozHidden", visibilityChange = "mozvisibilitychange", visibilityState = "mozVisibilityState";
    }
    else if (typeof document.msHidden !== "undefined") {
        hidden = "msHidden", visibilityChange = "msvisibilitychange", visibilityState = "msVisibilityState";
    }
    else if (typeof document.webkitHidden !== "undefined") {
        hidden = "webkitHidden", visibilityChange = "webkitvisibilitychange", visibilityState = "webkitVisibilityState";
    }


    if (typeof document.addEventListener === "undefined" || typeof hidden === "undefined") {
        // not supported
    }
    else {
        document.addEventListener(visibilityChange, function() {
            // console.log("hidden: " + document[hidden]);
            // console.log(document[visibilityState]);

            switch (document[visibilityState]) {
            case "visible":
                console.log('browser nyala')
                break;
            case "hidden":
                console.log('browser mati')
                break;
            }
            socket.emit('online', { 
                id_room             : $('#id_room').val(),
                id_user_sender      : id_user,
                id_user_receiver    : $('#id_user_receiver').val(),
                status              : document[visibilityState],
            });
        }, false);
    }

    if (document[visibilityState] === "visible") {
        // visible
    }

};  


$("#profile-img").click(function() {
	$("#status-options").toggleClass("active");
});

$(".expand-button").click(function() {
  $("#profile").toggleClass("expanded");
	$("#contacts").toggleClass("expanded");
});

$("#status-options ul li").click(function() {
	$("#profile-img").removeClass();
	$("#status-online").removeClass("active");
	$("#status-away").removeClass("active");
	$("#status-busy").removeClass("active");
	$("#status-offline").removeClass("active");
	$(this).addClass("active");
	
	if($("#status-online").hasClass("active")) {
		$("#profile-img").addClass("online");
	} else if ($("#status-away").hasClass("active")) {
		$("#profile-img").addClass("away");
	} else if ($("#status-busy").hasClass("active")) {
		$("#profile-img").addClass("busy");
	} else if ($("#status-offline").hasClass("active")) {
		$("#profile-img").addClass("offline");
	} else {
		$("#profile-img").removeClass();
	};
	
	$("#status-options").removeClass("active");
});

$(window).on('keydown', function(e) {
  if (e.which == 13) {
    sentMsg()
    return false;
  }
});
</script>
</body>
</html>