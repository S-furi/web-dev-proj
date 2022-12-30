<?php
require_once("bootstrap.php");

$templateParams["Titolo"] = "Brogram - Comment";
$templateParams["template_name"] = "post-template.php";

$postId = $_GET['postId'];
$usrId = $_GET['usrId'];
$post = getPostFromId($usrId, $postId, $mysqli);
$user = getUser($usrId, $mysqli);
$comments = getComments($postId, $mysqli);

$date = date("d-m-Y H:i", strtotime($post['eventDate']));

array_push($templateParams["js"], "js/post.js");

require("template/base.php");

?>
