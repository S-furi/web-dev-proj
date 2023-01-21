<?php
require_once("bootstrap.php");

$templateParams["Titolo"] = "Brogram - Comment";
$templateParams["template_name"] = "post-template.php";

$postId = $_GET['postId'];
$usrId = $_GET['usrId'];
$post = getPostFromId($usrId, $postId, $mysqli);
$user = getUser($usrId, $mysqli);
$comments = getComments($postId, $mysqli);


if (checkUserInfoExists($usrId, $mysqli)) {
  $userInfo = getUserInfo($usrId, $mysqli);
  $userInfo[0]['profileImg'] = 'img/' . $user['username'] . "/propic/" . $userInfo[0]['profileImg'];
} else {
  $userInfo[0] = array('bio' => '', 'profileImg' => 'img/no-profile-pic.png');
}

$date = date("d-m-Y H:i", strtotime($post['eventDate']));

array_push($templateParams["js"], "js/post.js");
array_push($templateParams["js"], "js/profile.js");

require("template/base.php");

?>
