<?php 
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

define("UPLOAD_POST_DIR", "../website/img/posts/");
define("IMG_DIR", "../website/img/");

sec_session_start();

if (!checkUserSession($mysqli)) {
    header("Location: login.php");
}

$templateParams["user"] = getUser(intval($_SESSION['user_id']), $mysqli);
if (checkUserInfoExists($_SESSION['user_id'], $mysqli)) {
  $templateParams["userInfo"] = getUserInfo($_SESSION['user_id'], $mysqli);
  $templateParams["userInfo"][0]['profileImg'] = 'img/' . $templateParams['user']['username'] . "/propic/" . $templateParams["userInfo"][0]['profileImg'];
} else {
  $templateParams["userInfo"] = array('bio' => '', 'profileImg' => 'img/no-profile-pic.png');
}
$templateParams["suggested_users"] = getSuggestedUser($_SESSION["user_id"], $mysqli);

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/home.js", "js/notification.js", "js/calendar.js");

?>
