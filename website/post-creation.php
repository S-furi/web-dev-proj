<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

sec_session_start();

if (!isStillLoggedIn($mysqli)) {
    header("Location: login.php");
    echo "Sessione scaduta";
}

$templateParams["Titolo"] = "Brogram - Post Creation";
$templateParams["nome"] = "post-creation-t.php";

require("template/base.php");

?>
