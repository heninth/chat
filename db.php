<?php
function auth_hash($id){
	$a = str_split($id);
	$z = hash('md5',$a[ord($a[0])%strlen($id)]);
	foreach($a as $b){
		$z = str_split($z);
		$x = hash('md5',$z[ord($b)%count($z)]);
		$y = hash('sha256',$z[ord($b)%count($z)]);
		$z = hash('sha512',$x.$y);
		$z = md5($z);
	}
	$z = str_split($z);
	$n = ord($z[0]);
	$z = implode($z);
	for($i=0;$i<$n;$i++){
		$z = hash('sha512',$z);
	}
	
	return $z;
}
date_default_timezone_set("Asia/Bangkok");
$m = new Mongo();
$db = $m->selectDB("chat");

?>