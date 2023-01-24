<?php
require_once("api-bootstrap.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

function retrieveUsrInfo($post, mysqli $mysqli) {
  $user = getUser($post['usrId'], $mysqli);
  if (checkUserInfoExists($post['usrId'], $mysqli)) {
    $userInfo = getUserInfo($post['usrId'], $mysqli)[0];
    $userInfo['profileImg'] = IMG_DIR . $user['username'] . "/propic/" . $userInfo['profileImg'];
  } else {
    $userInfo = array('bio' => '', 'profileImg' => 'img/no-profile-pic.png');
  }                  
  return $userInfo;
}

if (!isset($_GET["action"])) {
  header("HTTP/1.1 204 No Content");
}

if ($_GET["action"] == "home"){
  $result["usrId"] = $_SESSION["user_id"];
  $posts = getFriendsPosts($_SESSION['user_id'], $mysqli);

  // fixing images path, eventDate and filling authors informations
  for($i = 0; $i < count($posts); $i++) {
    $username = getUser($posts[$i]['usrId'], $mysqli)['username'];
    $posts[$i]["image"] = IMG_DIR.$username.'/posts/'.$posts[$i]["image"];
    $posts[$i]["eventDate"] = date("d-m-Y H:i", strtotime($posts[$i]['eventDate']));
    $posts[$i]["userInfo"] = retrieveUsrInfo($posts[$i], $mysqli);
  }

  $result["posts"] = $posts;

} elseif ($_GET["action"] == "discover") {
  $result["usrId"] = $_SESSION["user_id"];
  $posts = getDiscoverPosts($_SESSION['user_id'], $mysqli);

  // fixing images path, eventDate and filling authors informations
  for($i = 0; $i < count($posts); $i++) {
    $username = getUser($posts[$i]['usrId'], $mysqli)['username'];
    $posts[$i]["image"] = IMG_DIR.$username.'/posts/'.$posts[$i]["image"];
    $posts[$i]["eventDate"] = date("d-m-Y H:i", strtotime($posts[$i]['eventDate']));
    $posts[$i]["userInfo"] = retrieveUsrInfo($posts[$i], $mysqli);
  }

  $result["posts"] = $posts;

} else {
  header("HTTP/1.1 204 No Content");
}

header("Content-Type: application/json");
echo json_encode($result);

?>
