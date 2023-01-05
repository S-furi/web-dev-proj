<?php
require_once("../../database/db_connect.php");
require_once("../../database/db_functions.php");

$usrId = $_GET['usrId'];

function getUsersList($usrId, $mysqli, $action) {
  $result['ok'] = false;
  $list = ($action == 0) ? getFollowedUsers($usrId, $mysqli) : getFollowingUsers($usrId, $mysqli);

  if ($list) {
    $result['ok'] = true;
    $result[($action == 0) ? 'followed' : 'following'] = [];
    foreach ($list as $row) {
      $userId = ($action == 1) ? $row['usrId'] : $row['friendId'];
      $user = getUser($userId, $mysqli);
      array_push($result[($action == 0) ? 'followed' : 'following'], $user);
    }
  }

  return $result;
}

$result = getUsersList($usrId, $mysqli, $_GET['azione']);

header("Content-Type: application/json");
echo json_encode($result);

?>