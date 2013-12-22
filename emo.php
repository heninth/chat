<?php

$dh  = opendir('emotion');
while (false !== ($filename = readdir($dh))) {
if($filename!='.'&&$filename!='..')
echo "'$filename',";
}

?>