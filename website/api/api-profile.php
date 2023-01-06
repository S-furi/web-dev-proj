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
      array_push($result[($action == 0) ? 'followed' : 'following'], $user);
    }
  }

  return $result;
}

// function getUsersList($usrId, $mysqli, $action) {
//   $result['ok'] = false;
//   if ($action == 0) {
//     $usrId = $_GET['usrId'];
//     $list = getFollowedUsers($usrId, $mysqli);
//     $resultKey = 'followed';
//     $userIdKey = 'friendId';
//   } elseif ($action == 1) {
//     $usrId = $_GET['usrId'];
//     $list = getFollowingUsers($usrId, $mysqli);
//     $resultKey = 'following';
//     $userIdKey = 'usrId';
//   } 

function getUserLikes($postId, $mysqli, $action) {
  if ($action == 2) {
    $postId = $_GET['postId'];
    $list = getUserLikeDetails($postId, $mysqli);
    $result['likes'] = $list;
    $result['ok'] = true;
    return $result;
  }
}

//   if ($list) {
//     $result['ok'] = true;
//     $result[$resultKey] = [];
//     foreach ($list as $row) {
//       $userId = $row[$userIdKey];
//       $user = getUser($userId, $mysqli);
//       array_push($result[$resultKey], $user);
//     }
//   }

//   return $result;
// }

if ($_GET['azione'] == 0 || $_GET['azione'] == 1) {
  $result = getUsersList($_GET['usrId'], $mysqli, $_GET['azione']);
} elseif ($_GET['azione'] == 2) {
  $result = getUserLikes($_GET['postId'], $mysqli, $_GET['azione']);
}

header("Content-Type: application/json");
echo json_encode($result);

?>