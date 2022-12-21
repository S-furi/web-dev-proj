<?php
require_once("../database/db_connect.php");
require_once("../database/db_functions.php");

$result["ok"] = false;

if (isset($_POST["queryFragment"])) {
    $users = getUserFromFragments($_POST["queryFragment"], $mysqli);
    if (count($users) > 0) {
        $result["ok"] = true;
        $result["users"] = $users;
    }
}

header("Content-Type: application/json");
echo json_encode($result);

?>
