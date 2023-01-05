<?php
require_once("bootstrap.php");

$templateParams["Titolo"] = "Brogram - Profilo Personale";
array_push($templateParams["js"], "js/personal-profile.js");
array_push($templateParams["js"], "js/profile.js");

require("template/base.php");

?>
