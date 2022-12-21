<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

sec_session_start();

if (!isStillLoggedIn($mysqli)) {
    header("Location: login.php");
    echo "Sessione scaduta";
}

$templateParams["Titolo"] = "Brogram - Profilo";
$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/home.js", "js/profile.js");

require("template/base.php");

?>