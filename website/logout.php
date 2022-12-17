<?php 
require_once("../database/db_functions.php");
// http://paololatella.blogspot.com/2017/10/lezione-nov-2017-script-sicuro-per-il.html
sec_session_start();

$_SESSION = array();

$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
session_destroy();
header("Location: login.php");
?>
