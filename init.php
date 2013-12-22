<?php

require 'db.php';

if(!isset($_COOKIE['auth'])){die(json_encode(array("error"=>1)));} //not login
$auth = unserialize(base64_decode($_COOKIE['auth']));
if($auth['auth']==auth_hash($auth['id'])) {
	$my = $db->user->findOne(array('_id'=> new MongoId($auth['id'])));
	$my['_id'] = (string) $my['_id'];
	unset($my['password']);
	
	$rows = array();
	$u = array();
	$user_data = array();

	$cursor = $db->chatrow->find()->sort(array('_id' => -1))->limit(20);
	while ($cursor->hasNext()) {
		$row = $cursor->getNext();
		$u[] = new MongoId($row['user']);
		$row['_id'] = (string) $row['_id'];
		$time = explode('.',$row['time']);
		$time = $time[0];
		$row['time'] = date("G:i:s",$time);
		unset($row['password']);
		
		$rows[] = $row;
	}
	$u = array_unique($u);
	$cursor = $db->user->find(
		array("_id" => array('$in' => $u)
		)
	);
	while ($cursor->hasNext()) {
		$user = $cursor->getNext();
		$id = (string) $user['_id'];
		unset($user['_id']);
		unset($user['password']);
		$user_data[$id] = $user;
	}

	echo json_encode(array('svtime'=>microtime(true),'my'=>$my,'row'=>array_reverse($rows),'user'=>$user_data));

}else{
	die(json_encode(array("error"=>2))); //Authentication Failed
}

?>