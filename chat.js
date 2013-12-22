$(document).ready(function(){
	
	var emo = ['hen01.jpg','hen02.jpg','hen03.jpg','hen04.jpg','hen05.jpg','hen06.jpg','hen07.jpg','hen08.jpg','hen09.jpg','hen10.jpg','hen11.jpg','hen12.jpg','hen13.jpg','hen14.jpg','hen15.jpg','hen16.jpg','hen17.gif','hen18.gif','hen19.gif','hen20.jpg','hen21.jpg','hen22.jpg','hen23.jpg','hen24.jpg','hen25.jpg','hen26.jpg','hen27.jpg','hen28.jpg','hen29.jpg','hen30.jpg','hen31.jpg','hen32.jpg','hen33.jpg','hen34.jpg','hen35.jpg','hen36.jpg','hen37.jpg','hen38.jpg','hen39.jpg','hen40.jpg','hen41.jpg','hen42.jpg','hen43.jpg'];

	$("#msgbox").keydown(function(d){if(d.keyCode=="13"){send();}});
	$("#emobox").on('click',".emo",function(){
		$("#emobox").modal('hide');
		send_emo($(this).attr('src'));
	});
	init();
	
	$("#send").on('click',function(){
		send();
	});
	
	$("#reg").on('click',function(){
		$('#loginbox').hide();
		$('#regisbox').show();
	});
	
	$("#login").on('click',function(){
		$('#regisbox').hide();
		$('#loginbox').show();
	});
	
	$("#setbox button").on('click',function(){
		save_setting();
	});
	
	for(i=0;i<43;i++){
		$("#emobox .modal-body").append('<img src="emotion/'+emo[i]+'" class="emo"/>');
	}
});
	
var utime = 0;
var my;
		
function init(){
	$.ajax({ url: "init.php", dataType: "json", success: function(data){
		my = data.my;
		utime = data.svtime;
		
		if(data.error == 1){
			$('#loginbox').show();
		}else if(data.error == 2){
			alert("Authentication Failed");
			window.location = 'logout.php';
		}else{
			$('#loginbox').hide();
			$('#main').show();
			$('#avturl').val(data.user.avt);
			newrow(data);
			console.log("Chat : Init - Ok.");
			poll();
		}
	}});
}

function save_setting(){
	$.ajax({ url: "setting.php", type: "POST", data: { "id": my._id,"avt": $('#avturl').val()}, success: function(data){
		$("#setbox").modal('hide');
	}});

	return false;
}	

function send(){
	if($("#msgbox").val() != ''){
		a = $("#msgbox").val();
		$("#msgbox").val("");
		$.ajax({ url: "send.php", type: "POST", dataType: "json", data: { "user": my._id, "msg": a}, success: function(data){
			console.log("Chat : Send - Ok.");
		}});
	}
	return false;
}

function send_emo(emo){
	$.ajax({ url: "send.php", type: "POST", dataType: "json", data: { "user": my._id, "msg": '<img src="'+emo+'" />'}, success: function(data){
		console.log("Chat : Send - Ok.");
	}});
	return false;
}		

function poll(){
	console.log("Chat : Poll - Start.");
	$.ajax({ url: "update.php", type: "POST", data: { "utime": utime}, dataType: "json", success: function(data){
		console.log(data);
		utime = data.svtime;
		newrow(data);
		console.log("Chat : Poll - Ok.");
	}, complete: poll, timeout: 30000});
}

function newrow(data){
	user = data.user;
	row = data.row;
			
	$.each(row, function(i, val){
		if(val.user == my._id){
			$("#chatbox").append('<div class="row-fluid own"><div class="span9 offset2 well"><p class="text-right">'+val.msg+'</p></div><div class="span1 avt"><img src="'+user[val.user].avt+'" /><small>'+val.time+'</small></div></div>');
		}else{
			$("#chatbox").append('<div class="row-fluid"><div class="span1 avt"><img src="'+user[val.user].avt+'" /><small>'+user[val.user].name+'</small><br /><small>'+val.time+'</small></div><div class="span9 well"><p class="text-left">'+val.msg+'</p></div></div>');
		}
	});
	$("html, body").animate({ scrollTop: $(document).height() });
}