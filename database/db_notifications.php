<?php
require_once("db_posts.php");

/**
 * This file containts methods and utility function for retrieving and 
 * inserting informations about notifications.
 */

/**
 * Notify the specified user of the actions (type) occurred.
 *
 * If the notification is already present in DB, mark it as unread
 * and restore the notification date time.
 */
function notify(string $type, int $forUser, int $entityId, mysqli $mysqli)
{
    $query = "SELECT notificationId FROM notifications WHERE forUser = ? AND entityId = ? AND type = ?";
    $stmt = $mysqli->prepare($query);

    $stmt->bind_param("iis", $forUser, $entityId, $type);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows() > 0) {
        $query = "UPDATE notifications SET notifications.read = 0, time = NOW() WHERE forUser = ? AND entityId = ? AND type = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("iis", $forUser, $entityId, $type);
        return $stmt->execute();
    } else {
        if ($type != "follow" && checkSelfNotification($type, $forUser, $entityId, $mysqli)) {
          return true;
        }
        $query = "INSERT INTO notifications (type, forUser, entityId) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sii", $type, $forUser, $entityId);
        return $stmt->execute();
    }
}

function checkSelfNotification(string $type, int $forUser, int $entityId, mysqli $mysqli) {
  switch ($type) {
    case 'comment':
      $query = "SELECT * FROM comments WHERE commentId = ?";
      return checkNotificationEquality($query, "author", $forUser, $entityId, $mysqli);
    case 'like':
      $query = "SELECT * FROM likes WHERE likeId = ?";
      return checkNotificationEquality($query, "usrId", $forUser, $entityId, $mysqli);
    case 'participation':
      $query = "SELECT * FROM participations WHERE participationId = ?";
      return checkNotificationEquality($query, "usrId", $forUser, $entityId, $mysqli);
  }
}

function checkNotificationEquality(string $query, string $authorColName, string $forUser, string $entityId, mysqli $mysqli) {
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $entityId);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

  if (count($res) != 0) {
    $usrId = $res[0][$authorColName];
    return $forUser == $usrId;
  }
  return false;
}

function notifyLike($postId, $likeId, mysqli $mysqli)
{
    $forUser = getPostFromPostId($postId, $mysqli)["usrId"];
    return notify("like", $forUser, $likeId, $mysqli);
}

function notifyComment($forUser, $commentId, mysqli $mysqli) {
    return notify("comment", $forUser, $commentId, $mysqli);
}

function fetchNotifications(int $num, $forUser, $mysqli)
{
  $query = "SELECT notificationId, type, forUser, entityId, notifications.read, time 
            FROM notifications 
            WHERE forUser = ? 
            ORDER BY notifications.read, time DESC LIMIT ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $forUser, $num);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/* Check if there are any new notifications for the user
 *
 */
function getNotificationsForUser($userId, $lastNotificationId, mysqli $mysqli)
{
    $query = "SELECT * FROM notifications WHERE forUser = ? AND notificationId > ? AND NOT notifications.read";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ii', $userId, $lastNotificationId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // If there are new notifications, return them as an associative array
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        return $notifications;
    }
    return null;
}

function markReadNotification($notificationId, mysqli $mysqli) {
  $query = "UPDATE `notifications` SET `read` = '1' WHERE `notifications`.`notificationId` = ?";
  $stmt = $mysqli->prepare($query);

  $stmt->bind_param("i", $notificationId);

  return $stmt->execute();
}

function deletePostNotifications($postId, mysqli $mysqli) {
  // delete the associated comments notifications
  deleteCommentNotificationsFromPost($postId, $mysqli);
  // delete the associated likes notifications
  deleteLikeNotificationsFromPost($postId, $mysqli);
  // delete the associated events/participations notifications
  deleteEventNotificationsFromPost($postId, $mysqli);
}

function deleteCommentNotificationsFromPost($postId, mysqli $mysqli): bool {
  $stmt = $mysqli->prepare("SELECT * FROM comments WHERE postId = ?");
  $stmt->bind_param("i", $postId);
  $stmt->execute();

  $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

  if (count($res) == 0) {
    return false;
  }

  for ($i = 0; $i < count($res); $i++) {
    deleteNotification("comment", $res[$i]["commentId"], $mysqli);
  }

  return true;
}

function deleteLikeNotificationsFromPost($postId, mysqli $mysqli): bool {
  $stmt = $mysqli->prepare("SELECT * FROM likes WHERE postId = ?");
  $stmt->bind_param("i", $postId);
  $stmt->execute();

  $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

  if (count($res) == 0) {
    return false;
  }

  for ($i = 0; $i < count($res); $i++) {
    deleteNotification("like", $res[$i]["likeId"], $mysqli);
  }

  return true;
}

function deleteEventNotificationsFromPost($postId, mysqli $mysqli) {
  // find the event
  $query = "SELECT * FROM events WHERE postId = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $postId);

  $stmt->execute();
  $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

  if (count($res) > 0) {
    // find all the particiaptions associated with that event
    $eventId = $res[0]["eventId"];
    $query = "SELECT * FROM participations WHERE eventId = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $eventId);

    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (count($res) == 0) {
      return false;
    }

    for ($i = 0; $i < count($res); $i++) {
      deleteNotification("participation", $res[$i]["participationId"], $mysqli);
    }
    return true;
  }
  return false;
}

function deleteFollowNotification($entityId, $forUser, mysqli $mysqli) {
  $type = "follow";

  $query = "DELETE FROM notifications WHERE type = 'follow' AND entityId = ? AND forUser = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ii", $entityId, $forUser);

  return $stmt->execute();
}

function deleteNotification($type, $entityId, mysqli $mysqli) {
  $query = "DELETE FROM notifications WHERE type = '" . $type . "' AND entityId = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $entityId);

  return $stmt->execute();
}

?>
