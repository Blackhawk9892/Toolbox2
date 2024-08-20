<?php

session_start();
require_once("includes/connection.php");
$userId = $_COOKIE["userId"];


$expire = time() - 3600;
setcookie("user", $custId, $expire);

setcookie("hash_password", $hash_password, $expire);


session_destroy();
header("Location: index.php");

exit;
?> 