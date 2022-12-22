<?php
require_once("bootstrap.php");

$templateParams["Titolo"] = "Brogram - Profilo";
array_push($templateParams["js"], "js/profile.js");

require("template/base.php");

?>
