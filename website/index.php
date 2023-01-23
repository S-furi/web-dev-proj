<?php
require_once("bootstrap.php");

$templateParams["Titolo"] = "Brogram - Home";

$templateParams["template_name"] = "timeline.php";
$templateParams["active-home"] = "active";
$templateParams["posts"]= getFriendsPosts($_SESSION["user_id"], $mysqli);

array_push($templateParams["js"], "js/profile.js");

// fixing images path
for($i = 0; $i < count($templateParams["posts"]); $i++) {
    $username = getUser($templateParams['posts'][$i]['usrId'], $mysqli)['username'];
    $templateParams["posts"][$i]["image"] = IMG_DIR.$username.'/posts/'.$templateParams["posts"][$i]["image"];
    $templateParams["posts"][$i]["eventDate"]= date("d-m-Y H:i", strtotime($templateParams["posts"][$i]['eventDate']));
}

require("template/base.php");

?>
