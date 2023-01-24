<?php
require_once("../../database/db_connect.php");
require_once("../../database/db_functions.php");

function getUsersList($usrId, $mysqli, $action) {
  $result['ok'] = false;
  $list = ($action == 0) ? getFollowedUsers($usrId, $mysqli) : getFollowingUsers($usrId, $mysqli);

  if ($list) {
    $result['ok'] = true;
    $result[($action == 0) ? 'followed' : 'following'] = [];
    foreach ($list as $row) {
      $userId = ($action == 1) ? $row['usrId'] : $row['friendId'];
      $user = getUser($userId, $mysqli);
      
      if (checkUserInfoExists($userId, $mysqli)) {
        $user['profileImg'] =  'img/' . $user['username'] . "/propic/" . getUserInfo($userId, $mysqli)[0]['profileImg'];
      } else {
        $user['profileImg'] = 'img/no-profile-pic.png';
      }

      array_push($result[($action == 0) ? 'followed' : 'following'], $user);
    }
  }

  return $result;
}

function getUserLikes($postId, $mysqli) {
  $result['ok'] = false;
  $list = getUserLikeDetails($postId, $mysqli);
  foreach ($list as &$user) {
    if (checkUserInfoExists($user['usrId'], $mysqli)) {
      $userInfo = getUserInfo($user['usrId'], $mysqli);
      $user['profileImg'] = 'img/' . $user['username'] . "/propic/" . $userInfo[0]['profileImg'];
    } else {
      $user['profileImg'] = 'img/no-profile-pic.png';
    }
  }
  $result['likes'] = $list;
  $result['ok'] = true;
  return $result;
}

function getPostParticipantsUsers($postId, $mysqli) {
  $result['ok'] = false;
  $list = getUsersParticipating($postId, $mysqli);
  foreach ($list as &$user) {
    if (checkUserInfoExists($user['usrId'], $mysqli)) {
      $userInfo = getUserInfo($user['usrId'], $mysqli);
      $user['profileImg'] = 'img/' . $user['username'] . "/propic/" . $userInfo[0]['profileImg'];
    } else {
      $user['profileImg'] = 'img/no-profile-pic.png';
    }
  }
  $result['participants'] = $list;
  $result['ok'] = true;
  return $result;
}

if ($_GET['azione'] == 0 || $_GET['azione'] == 1) {
  $result = getUsersList($_GET['usrId'], $mysqli, $_GET['azione']);
} elseif ($_GET['azione'] == 2) {
  $result = getUserLikes($_GET['postId'], $mysqli);
} elseif ($_GET['azione'] == 3) {
  $result = getPostParticipantsUsers($_GET['postId'], $mysqli);
}

header("Content-Type: application/json");
echo json_encode($result);

?>