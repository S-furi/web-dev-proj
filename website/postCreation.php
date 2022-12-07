<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

sec_session_start();

$templateParams["Titolo"] = "Brogram - Post Creation";
$templateParams["nome"] = "postCreation-t.php";

require("template/base.php");

?>
