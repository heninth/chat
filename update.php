<?php
$timeout = 50;
//$_POST['utime'] = '1378794443.0008';
while($timeout>0){
	$svupdate = file_get_contents('update.txt');
	if($_POST['utime']>=$svupdate){
		usleep(500000);
		$timeout--;
	}else{
		break;
	}
}

require 'db.php';
$rows = array();
$u = array();
$user_data = array();

$cursor = $db->chatrow->find(array( "time" => array('$gt' => (float)$_POST['utime'])))->sort(array('_id' => -1));
while ($cursor->hasNext()) {
    $row = $cursor->getNext();
	$u[] = new MongoId($row['user']);
    $row['_id'] = (string) $row['_id'];
	unset($row['password']);
	$time = explode('.',$row['time']);
	$time = $time[0];
	$row['time'] = date("G:i:s",$time);
	
    $rows[] = $row;
}
$u = array_unique($u);
$cursor = $db->user->find(
	array("_id" => array('$in' => $u)
	)
);
while ($cursor->hasNext()) {
    $user = $cursor->getNext();
	print_r(json_encode($user));
	die();
    $id = (string) $user['_id'];
	unset($user['_id']);
	unset($user['password']);
    $user_data[$id] = $user;
}

echo json_encode(array("accept"=>$_POST['utime'],"svtime"=>microtime(true),"row"=>array_reverse($rows),"user"=>$user_data));
die();
?>