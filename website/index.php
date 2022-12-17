<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

define("UPLOAD_DIR", "./img/");

sec_session_start();

if (!isStillLoggedIn($mysqli)) {
    header("Location: login.php");
    echo "Sessione scaduta";
}

$templateParams["Titolo"] = "Brogram - Home";
/* $templateParams["nome"] = "home-template.php"; */

/* $templateParams["posts"] = getPost(intval($_SESSION['user_id']), $mysqli); */
$templateParams["user"] = getUser(intval($_SESSION['user_id']), $mysqli);
$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/home.js");

require("template/base.php");

