<?php
require 'db.php';

$a = $db->user->findOne(array("name"=>$_POST['name']));
$hash = auth_hash($_POST['password']);
if($hash==$a['password']){

	$auth = array("id"=>(string)$a['_id'],"auth"=>auth_hash((string)$a['_id']));
	setcookie('auth',base64_encode(serialize($auth)),time()+(60*60*24*356));
	header("Location: /");
}else{
	die("Worng Password");
}





?>