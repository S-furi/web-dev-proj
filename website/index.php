<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

define("UPLOAD_DIR", "./img/");

sec_session_start();

$templateParams["Titolo"] = "Brogram - Home";
$templateParams["nome"] = "home-template.php";

$templateParams["posts"] = getPost(intval($_SESSION['user_id']), $mysqli);


require("template/new-base.php");

?>