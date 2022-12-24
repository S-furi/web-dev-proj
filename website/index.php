<?php
require_once("bootstrap.php");

$templateParams["Titolo"] = "Brogram - Home";

// TODOS
$templateParams["template_name"] = "timeline.php";
$templateParams["posts"]= getFriendsPosts($_SESSION["user_id"], $mysqli);

// fixing images path
for($i = 0; $i < count($templateParams["posts"]); $i++) {
     $templateParams["posts"][$i]["image"] = UPLOAD_POST_DIR.$templateParams["posts"][$i]["image"];
}

require("template/base.php");

?>
