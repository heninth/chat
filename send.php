<?php

require 'db.php';
$m = microtime(true);
$insert = array(
	'user' => $_POST['user'],
	'msg' => $_POST['msg'],
	'time' => $m
);

$db->chatrow->insert($insert);
file_put_contents('update.txt',$m);
die(json_encode(array("success"=>1)));

?>