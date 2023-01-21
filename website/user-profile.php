<?php
require_once("bootstrap.php");

$user = getUser(intval($_GET['usrId']), $mysqli);
$posts = getPosts(intval($_GET['usrId']), $mysqli);
$followers_n = getFollowersNum($user['usrId'], $mysqli);
$following_n = getFollowingNum($user['usrId'], $mysqli);

if (checkUserInfoExists($_GET['usrId'], $mysqli)) {
  $userInfo = getUserInfo($_GET['usrId'], $mysqli);
  $userInfo[0]['profileImg'] = 'img/' . $user['username'] . "/propic/" . $userInfo[0]['profileImg'];
} else {
  $userInfo[0] = array('bio' => '', 'profileImg' => 'img/no-profile-pic.png');
}

$templateParams['Titolo'] = "Brogram - Profilo " . $templateParams['user']['username'];
$templateParams['template_name'] = "user-profile-template.php";

array_push($templateParams["js"], "js/profile.js");

require("template/base.php");


?>