<?php
// Include the database functions
require_once("api-bootstrap.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the "last_notification_id" parameter is set in the request
if (isset($_POST['lastNotificationId'])) {
    $lastNotificationId = (int)$_POST['lastNotificationId'];
    $userId = (int)$_SESSION['user_id'];

    // Fetch the notifications for the user
    $notifications = getNotificationsForUser($userId, $lastNotificationId, $mysqli);
    if ($notifications !== null) {
        // If there are new notifications, return them to the client
        header('Content-Type: application/json');
        echo json_encode($notifications);
    } else {
        // If there are no new notifications, wait for a specified period of time before checking again
        sleep(5);
        header("HTTP/1.1 204 No Content");
    }
} elseif (isset($_POST['usrId'], $_POST['lastNotificationId'])) {
  // the provided notificiation has to be marked as read if it's not
  $usrId = $_POST['usrId'];
  $notificationId = $_POST['notificationId'];

  $result['ok'] = false;
  if (markReadNotification($notificationId, $mysqli)) {
    $result['ok'] = true;
  }

  header("Content-Type application/json");
  echo json_encode($result);
}

?>

