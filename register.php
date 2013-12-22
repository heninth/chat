<?php

require 'db.php';

$b = $db->user->findOne(array("name"=>$_POST['name']));
if(!$b){

	$insert = array(
		"name"=>$_POST['name'],
		"password"=>auth_hash($_POST['password'])
	);
	$db->user->insert($insert);
	$id = (string)$insert['_id'];
	$auth = array("id"=>(string)$id,"auth"=>auth_hash((string)$id));
	setcookie('auth',base64_encode(serialize($auth)),time()+(60*60*24*356));
	header("Location: /");
}else{
	die("Username already exists");
}

?>