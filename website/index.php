<?php
require_once("bootstrap.php");

$templateParams["Titolo"] = "Brogram - Home";
$templateParams["active-home"] = "active";

// inserting the new script, after importing axios
array_splice($templateParams["js"], 1, 0, "js/timeline.js");
array_push($templateParams["js"], "js/profile.js");

require("template/base.php");

?>

