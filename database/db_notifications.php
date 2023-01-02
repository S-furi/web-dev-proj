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
        $query = "UPDATE notifications SET read = 0, time = NOW() WHERE forUser = ? AND entityId = ? AND type = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("iis", $forUser, $entityId, $type);
        return $stmt->execute();
    } else {
        $query = "INSERT INTO notifications (type, forUser, entityId) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sii", $type, $forUser, $entityId);
        return $stmt->execute();
    }
}

function notifyLike($postId, $likeId, mysqli $mysqli)
{
    $forUser = getPostFromPostId($postId, $mysqli)["usrId"];
    return notify("like", $forUser, $likeId, $mysqli);
}

function fetchNotifications(int $num, $forUser, $mysqli)
{
    $query = "SELECT type, forUser, entityId, read, time FROM notifications WHERE forUser = ? ORDER BY time DESC LIMIT ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_params("ii", $forUser, $num);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/* Check if there are any new notifications for the user
 *
 */
function getNotificationsForUser($userId, $lastNotificationId, mysqli $mysqli)
{
    $query = "SELECT * FROM notifications WHERE forUser = ? AND notificationId > ?";
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

?>
