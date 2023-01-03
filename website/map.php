<?php
require_once("bootstrap.php");

$templateParams["template_name"] = "map-template.php";
$templateParams["active-radar"] = "active";
array_push($templateParams["js"], "https://openlayers.org/en/v5.3.0/build/ol.js", "js/map.js");

require("template/base.php");

?>
