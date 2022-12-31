<?php
require_once("api-bootstrap.php");


if (isset($_GET["action"]) && $_GET["action"] == 0) {
    $result["ok"] = false;
    if (isset($_POST["postId"])) {
        $postId = $_POST["postId"];
        $usrId = $_SESSION["user_id"];

        $result["ok"] = insertParticipantFromPostId($usrId, $postId, $mysqli);
    }
} elseif (isset($_GET["action"]) && $_GET["action"] == 1) {
    $result["isParticipating"] = false;
    if (isset($_POST["postId"])) {
        $postId = $_POST["postId"];
        $usrId = $_SESSION["user_id"];
        $result["isParticipating"] = isUserParticipating($usrId, $postId, $mysqli);
    }
}

header("Content-Type: application/json");
echo json_encode($result);
?>
