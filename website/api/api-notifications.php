<?php
require_once("api-bootstrap.php");

// retruns a simple 
function getNotificationsInfo($nots) {
    $result = array();
    foreach($nots as $notification) {
        switch ($notification["type"]) {
            case 'comment':
                $commentInfo = getCommentFromCommentId($notification["entityId"], $mysqli);
                $postInfo = fetchPostInfoFromCommentId($notification["entityId"], $mysqli);

                // soon this filename will change
                $reference = "comment.php?usrId=" . $postInfo["usrId"] . "&postId" . $postInfo["postId"];
                $fromUser = $commentInfo["author"];
                $msg = "ha commentato il tuo post";
                break;

            case 'like':
                $likeInfo = getLikeDetails($notification["entityId"], $mysqli);
                $postInfo= fetchPostInfoFromLike($notification["entityId"], $mysqli);

                // soon this filename will change
                $reference = "comment.php?usrId=" . $postInfo["usrId"] . "&postId" . $postInfo["postId"];
                $fromUser = $likeInfo["usrId"];
                $msg = "ha messo mi piace al tuo post";
                break;

            case 'follow':
                $userInfo = getUser($notification["entityId"], $mysqli);

                // TODO
                /* $reference = "" . $userInfo["usrId"]; */
                $fromUser = $notification["entityId"];
                $msg = "ha iniziato a seguirti";
                break;

            // add messages when ready

            default:
                # code...
                break;
        }

        $notificationInfo= array(
            "type" => $notification["type"],
            "fromUser" => $fromUser,
            "msg" => $msg,
            "reference" => $reference,
            "read" => $notification["read"],
            "datetime" => $notification["time"],
        );
        array_push($result, $notificationInfo);
    }
    return $result;
}

if (isset($_POST["usrId"])) {
    $forUser = $_POST["usrId"];
    $numNotifications= 10;

    $notifications = getNotificationsInfo( fetchNotifications($numNotifications, $forUser, $mysqli) );
}

header("Content-Type: application/json");
echo json_encode($notifications);
// need to add error handling and error msg passing

?>
