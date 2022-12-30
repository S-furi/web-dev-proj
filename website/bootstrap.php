<?php 
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

define("UPLOAD_POST_DIR", "../website/img/posts/");

sec_session_start();

if (!checkUserSession($mysqli)) {
    header("Location: login.php");
}

$templateParams["user"] = getUser(intval($_SESSION['user_id']), $mysqli);
$templateParams["suggested_users"] = getSuggestedUser($_SESSION["user_id"], $mysqli);

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/home.js", "js/notification.js", "js/calendar.js");

?>
