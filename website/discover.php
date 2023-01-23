<?php
require_once("bootstrap.php");

$templateParams["Titolo"] = "Brogram - Discover";

$templateParams["template_name"] = "timeline.php";
$templateParams["active-explore"] = "active";
$templateParams["posts"] = getDiscoverPosts($_SESSION["user_id"], $mysqli);

// fixing images path
for($i = 0; $i < count($templateParams["posts"]); $i++) {
    $username = getUser($templateParams['posts'][$i]['usrId'], $mysqli)['username'];
    $templateParams["posts"][$i]["image"] = IMG_DIR.$username.'/posts/'.$templateParams["posts"][$i]["image"];
    $templateParams["posts"][$i]["eventDate"]= date("d-m-Y H:i", strtotime($templateParams["posts"][$i]['eventDate']));
}

array_push($templateParams["js"], "js/profile.js");

require("template/base.php");
?>