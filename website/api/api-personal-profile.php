<?php
require_once('../../database/db_connect.php');
require_once('../../database/db_functions.php');

sec_session_start();

if (!checkUserSession($mysqli)) {
    header("Location: login.php");
    echo "Sessione scaduta";
}

if ($_GET['azione'] == 0) {
  $posts = getPosts($_SESSION['user_id'], $mysqli);
  header("Content-Type: application/json");
  echo json_encode($posts);
} elseif ($_GET['azione'] == 1) {
  $user = getUser($_SESSION['user_id'], $mysqli);
  if (checkUserInfoExists($_SESSION['user_id'], $mysqli)) {
    $userInfo = getUserInfo($_SESSION['user_id'], $mysqli);
    $userInfo['profileImg'] = 'img/posts/' . $userInfo['profileImg'];
  } else {
    $userInfo = array('bio' => '', 'profileImg' => 'img/no-profile-pic.png');
  }
  $followers_n = getFollowersNum($user['usrId'], $mysqli);
  $following_n = getFollowingNum($user['usrId'], $mysqli);
  $data = array($user, $userInfo, $followers_n, $following_n); 
  header("Content-Type: application/json");
  echo json_encode($data);
}

header("Content-Type: application/json");

?>
