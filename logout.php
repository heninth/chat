<?php

setcookie("auth", "", time()-3600);
header("Location: /");

?>