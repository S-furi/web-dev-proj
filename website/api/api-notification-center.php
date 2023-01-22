<?php
require_once("api-bootstrap.php");

function getNotificationInfoForComment(array $notification, $mysqli) {
    $commentInfo = getCommentFromCommentId($notification["entityId"], $mysqli);
    $postInfo = fetchPostInfoFromCommentId($notification["entityId"], $mysqli)[0];

    $reference = "post.php?usrId=" . $postInfo["usrId"] . "&postId=" . $postInfo["postId"];
    $fromUser = $commentInfo["author"];
    $msg = "ha commentato il tuo post";

    return [
        "notificationId" => $notification["notificationId"],
        "type" => $notification["type"],
        "fromUser" => getUser($fromUser, $mysqli),
        "fromUserInfo" => array('bio' => '', 'profileImg' => 'img/no-profile-pic.png'),
        "msg" => $msg,
        "reference" => $reference,
        "read" => $notification["read"],
        "datetime" => $notification["time"],
    ];
}

function getNotificationInfoForLike(array $notification, $mysqli) {
    $likeInfo = getLikeDetails($notification["entityId"], $mysqli);
    $postInfo = fetchPostInfoFromLike($notification["entityId"], $mysqli);

    if (count($postInfo) > 0 && count($postInfo)) {
        $postInfo = $postInfo[0];
        $likeInfo = $likeInfo[0];

        $reference = "post.php?usrId=" . $postInfo["usrId"] . "&postId=" . $postInfo["postId"];
        $fromUser = $likeInfo["usrId"];
        $msg = "ha messo mi piace al tuo post";

        return [
            "notificationId" => $notification["notificationId"],
            "type" => $notification["type"],
            "fromUser" => getUser($fromUser, $mysqli),
            "fromUserInfo" => array('bio' => '', 'profileImg' => 'img/no-profile-pic.png'),
            "msg" => $msg,
            "reference" => $reference,
            "read" => $notification["read"],
            "datetime" => $notification["time"],
        ];
    }
}

function getNotificationInfoForFollow(array $notification, $mysqli) {
    $userInfo = getUser($notification["entityId"], $mysqli);
    $reference = "user-profile.php?usrId=" . $userInfo["usrId"];
    $fromUser = $notification["entityId"];
    $msg = "ha iniziato a seguirti";

    return [
        "notificationId" => $notification["notificationId"],
        "type" => $notification["type"],
        "fromUser" => getUser($fromUser, $mysqli),
        "fromUserInfo" => array('bio' => '', 'profileImg' => 'img/no-profile-pic.png'),
        "msg" => $msg,
        "reference" => $reference,
        "read" => $notification["read"],
        "datetime" => $notification["time"],
    ];
}

function getNotificationInfoForParticipation(array $notification, $mysqli) {
  $participation = getParticipation($notification["entityId"], $mysqli);

  if (!count($participation) > 0) {
    return;
  }

  $participation = $participation[0];
  $postInfo = getPostFromEventId($participation["eventId"], $mysqli);
  
  $reference = "post.php?usrId=" . $postInfo["usrId"] . "&postId=" . $postInfo["postId"];
  $msg = " parteciperà al tuo evento";
  return [
      "notificationId" => $notification["notificationId"],
      "type" => $notification["type"],
      "fromUser" => getUser($participation['usrId'], $mysqli),
      "fromUserInfo" => array('bio' => '', 'profileImg' => 'img/no-profile-pic.png'),
      "msg" => $msg,
      "reference" => $reference,
      "read" => $notification["read"],
      "datetime" => $notification["time"],
  ];
}

function getNotificationsInfo(array $notifications, $mysqli) {
    $result = [];
    $notificationInfoFunctions = [
        'comment' => 'getNotificationInfoForComment',
        'like' => 'getNotificationInfoForLike',
        'follow' => 'getNotificationInfoForFollow',
        'participation' => 'getNotificationInfoForParticipation',
        // Add other types here
    ];

    foreach ($notifications as $notification) {
        $type = $notification['type'];

        if (isset($notificationInfoFunctions[$type])) {
            $notificationInfoFunction = $notificationInfoFunctions[$type];
            $result[] = $notificationInfoFunction($notification, $mysqli);

        }
    }

    $result = array_filter($result, function ($k) {
        return $k != null;
    });
    
    foreach ($result as &$res) {
      if (checkUserInfoExists($res['fromUser']['usrId'], $mysqli)) {
        $usrInfo = getUserInfo($res['fromUser']['usrId'], $mysqli)[0];
        $res['fromUserInfo']['profileImg'] = 'img/' . $res['fromUser']['username'] . "/propic/" . $usrInfo['profileImg'];
      } else {
        $res['fromUserInfo']['profileImg'] = 'img/no-profile-pic.png';
      }
    }

    return $result;
}

if (!isset($_GET['action'])) {
  header('HTTP/1.1 204 No Content');
}

if ($_GET['action'] == 1) {
    $forUser = $_SESSION["user_id"];
    $numNotifications = 10;

    $notifications = getNotificationsInfo(fetchNotifications($numNotifications, $forUser, $mysqli), $mysqli);
    header('Content-Type: application/json');
    echo json_encode($notifications);
    // need to add error handling and error msg passing
}

if ($_GET['action'] == 2) {
  // mark the provided notification as read if it's not

  $result['ok'] = false;
  if (markReadNotification($_POST['notificationId'], $mysqli)) {
    $result['ok'] = true;
  }

  header("Content-Type application/json");
  echo json_encode($result);
}
?>
