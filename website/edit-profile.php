<?php
require_once("bootstrap.php");
require_once('../utils/utils_functions.php');

$templateParams["Titolo"] = "Brogram - Modifica Profilo";
$templateParams["user"] = getUser(intval($_SESSION['user_id']), $mysqli);
$templateParams["suggested_users"] = getSuggestedUser($_SESSION["user_id"], $mysqli);
$templateParams["template_name"] = "edit-profile-template.php";
$templateParams["mysqli"] = $mysqli;

require("template/base.php");

?>