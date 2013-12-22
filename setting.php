<?php
	require 'db.php';
	
	$db->user->update(array('_id'=> new MongoId($_POST['id'])),array('$set'=>array("avt"=>$_POST['avt'])));
?>