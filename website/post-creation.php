<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

sec_session_start();

if (!isStillLoggedIn($mysqli)) {
    header("Location: login.php");
}

$templateParams["Titolo"] = "Brogram - Post Creation";
$templateParams["user"] = getUser(intval($_SESSION['user_id']), $mysqli);
$templateParams["suggested_users"] = getSuggestedUser($_SESSION["user_id"], $mysqli);
$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/home.js", "js/post_submit.js");

require("template/base.php");

?>
