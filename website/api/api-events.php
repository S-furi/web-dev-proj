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
        if (isUserPostAuthor($postId, $usrId, $mysqli)) {
          $result["isUserAuthor"] = true;
        } else {
          $result["isParticipating"] = isUserParticipating($usrId, $postId, $mysqli);
        }
    }
// action: 2 removes a participant from an event
} elseif (isset($_GET["action"]) && $_GET["action"] == 2) {
  $result["ok"] = false;
  if (isset($_POST["postId"])) {
      $postId = $_POST["postId"];
      $usrId = $_SESSION["user_id"];
      $result["ok"] = deleteParticipation($usrId, $postId, $mysqli);
  }
} else {
    header("HTTP/1.1 204 No Content");
}

header("Content-Type: application/json");
echo json_encode($result);
?>
