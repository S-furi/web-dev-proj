<?php
require_once("api-bootstrap.php");

function getNotificationsInfoForComments(array $notification, $mysqli) {
    $commentInfo = getCommentFromCommentId($notification["entityId"], $mysqli);
    $postInfo = fetchPostInfoFromCommentId($notification["entityId"], $mysqli);

    // soon this filename will change
    $reference = "comment.php?usrId=" . $postInfo["usrId"] . "&postId" . $postInfo["postId"];
    $fromUser = $commentInfo["author"];
    $msg = "ha commentato il tuo post";

    return [
        "type" => $notification["type"],
        "fromUser" => $fromUser,
        "msg" => $msg,
        "reference" => $reference,
        "read" => $notification["read"],
        "datetime" => $notification["time"],
    ];
}

function getNotificationInfoForLike(array $notification, $mysqli) {
    $likeInfo = getLikeDetails($notification["entityId"], $mysqli);
    $postInfo = fetchPostInfoFromLike($notification["entityId"], $mysqli);

    // soon this filename will change
    $reference = "comment.php?usrId=" . $postInfo["usrId"] . "&postId" . $postInfo["postId"];
    $fromUser = $likeInfo["usrId"];
    $msg = "ha messo mi piace al tuo post";

    return [
        "type" => $notification["type"],
        "fromUser" => $fromUser,
        "msg" => $msg,
        "reference" => $reference,
        "read" => $notification["read"],
        "datetime" => $notification["time"],
    ];
}

function getNotificationInfoForFollow(array $notification, $mysqli) {
    $userInfo = getUser($notification["entityId"], $mysqli);
    $reference = "user-profile.php?usrId=" . $userInfo["usrId"];
    $fromUser = $notification["entityId"];
    $msg = "ha iniziato a seguirti";

    return [
        "type" => $notification["type"],
        "fromUser" => $fromUser,
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
        // Add other types here
    ];

    foreach ($notifications as $notification) {
        $type = $notification['type'];

        if (isset($notificationInfoFunctions[$type])) {
            $notificationInfoFunction = $notificationInfoFunctions[$type];
            $result[] = $notificationInfoFunction($notification, $mysqli);
        }
    }

    return $result;
}

if (isset($_POST['usrId'])) {
    $forUser = $_POST['usrId'];
    $numNotifications = 10;

    $notifications = getNotificationsInfo(fetchNotifications($numNotifications, $forUser, $mysqli), $mysqli);
}

header('Content-Type: application/json');
echo json_encode($notifications);
// need to add error handling and error msg passing

?>
