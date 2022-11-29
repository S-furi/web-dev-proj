<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define("HOST", "localhost");
define("USER", "sec_user");
define("PASSWORD", "eKcGZr59zAa2BEWU");
define("DATABASE", "brogram");

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
?>
