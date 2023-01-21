<?php
require_once("bootstrap.php");

$templateParams["Titolo"] = "Brogram - Discover";

$templateParams["template_name"] = "timeline.php";
$templateParams["active-explore"] = "active";
$templateParams["posts"] = getDiscoverPosts($_SESSION["user_id"], $mysqli);

// fixing images path
for($i = 0; $i < count($templateParams["posts"]); $i++) {
     $templateParams["posts"][$i]["image"] = UPLOAD_POST_DIR.$templateParams["posts"][$i]["image"];
    $templateParams["posts"][$i]["eventDate"]= date("d-m-Y H:i", strtotime($templateParams["posts"][$i]['eventDate']));
}

require("template/base.php");
?>