<?php
require_once("bootstrap.php");

$user = getUser(intval($_GET['usrId']), $mysqli);
$posts = getPosts(intval($_GET['usrId']), $mysqli);
$followers_n = getFollowersNum($user['usrId'], $mysqli);
$following_n = getFollowingNum($user['usrId'], $mysqli);

$templateParams['Titolo'] = "Brogram - Profilo " . $templateParams['user']['username'];
$templateParams['template_name'] = "user-profile-template.php";

require("template/base.php");


?>