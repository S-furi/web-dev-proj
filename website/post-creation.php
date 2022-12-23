<?php
require_once("bootstrap.php");
require_once('../utils/utils_functions.php');

$templateParams["Titolo"] = "Brogram - Creazione Post";
$templateParams["user"] = getUser(intval($_SESSION['user_id']), $mysqli);
$templateParams["suggested_users"] = getSuggestedUser($_SESSION["user_id"], $mysqli);
$templateParams["template_name"] = "post-creation-t.php";

require("template/base.php");

?>
