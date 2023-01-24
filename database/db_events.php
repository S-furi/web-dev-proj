<?php
/**
 * This file contains function for retrieving informations from database about events
 * and participants.
 */

function createEvent($usrId, $postId, mysqli $mysqli)
{
    $stmt = $mysqli->prepare("INSERT INTO events (postId) VALUES (?)");
    $stmt->bind_param("i", $postId);
    return $stmt->execute() && insertParticipant($usrId, mysqli_insert_id($mysqli), $mysqli);
}

function insertParticipant($usrId, $eventId, mysqli $mysqli)
{
    $stmt = $mysqli->prepare("INSERT INTO participations (usrId, eventId) VALUES (?, ?);");
    $stmt->bind_param("ii", $usrId, $eventId);
    if ($stmt->execute()) {
        return incrementParticipants($eventId, $mysqli);
    }
}

function incrementParticipants($eventId, $mysqli)
{
    $stmt = $mysqli->prepare("UPDATE events SET events.participants = events.participants + 1 WHERE events.eventId = ?;");
    $stmt->bind_param("i", $eventId);
    return $stmt->execute();
}

function insertParticipantFromPostId($usrId, $postId, mysqli $mysqli)
{
    $query = "INSERT INTO participations (usrId, eventId) VALUES (?, ?);";
    $stmt = $mysqli->prepare($query);
    $eventId = getEventFromPost($postId, $mysqli)["eventId"];
    $postAuthor = getPostFromPostId($postId, $mysqli)["usrId"];

    $stmt->bind_param("ii", $usrId, $eventId);

    if (!$stmt->execute()) {
      return false;
    }
    $participationId = mysqli_insert_id($mysqli);

    return incrementParticipants($eventId, $mysqli)
      && notify("participation", $postAuthor, $participationId, $mysqli);
}

function getParticipants($postId, mysqli $mysqli)
{
  $eventId = getEventFromPost($postId, $mysqli)["eventId"];
  $query = "SELECT * FROM events WHERE eventId = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $eventId);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

  if (count($res) > 0) {
    return $res[0]["participants"];
  }
  return 0;
}

function isUserParticipating($usrId, $postId, mysqli $mysqli)
{
    $query = "SELECT * FROM participations WHERE usrId = ? AND eventId = ?;";
    $stmt = $mysqli->prepare($query);
    $eventId = getEventFromPost($postId, $mysqli)["eventId"];
    $stmt->bind_param("ii", $usrId, $eventId);
    $stmt->execute();

    $res = $stmt->get_result()->fetch_all();
    return count($res) > 0;
}

function getEventFromPost($postId, mysqli $mysqli)
{
    $query = "SELECT eventId FROM events JOIN posts ON (events.postId = posts.postId) WHERE posts.postId = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
}

function getAllEventsDetails($usrId, mysqli $mysqli)
{
    $query = "
            SELECT * 
            FROM events JOIN posts ON (events.postId = posts.postId)
            WHERE posts.usrId IN (SELECT followers.friendId
                                 FROM followers
                                 WHERE followers.usrId = ?)
            OR posts.usrId = ?;";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $usrId, $usrId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getParticipation($participationId, mysqli $mysqli) 
{
  $query = "SELECT * FROM participations WHERE participationId = ? LIMIT 1";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $participationId);
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function deleteParticipation($usrId, $postId, mysqli $mysqli) 
{
  $query = "SELECT * FROM events WHERE postId = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $postId);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

  if (count($res) > 0) {
    $query = "SELECT * FROM participations WHERE eventId = ? AND usrId = ?";
    $eventId = $res[0]['eventId'];
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $eventId, $usrId);
    if (!$stmt->execute()) {
      return false;
    }
    $participationId = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["participationId"];

    $query = "DELETE FROM participations WHERE eventId = ? AND usrId = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $eventId, $usrId);
    if (!$stmt->execute()) {
      return false;
    }
  } else {
    return false;
  }
  $query = "UPDATE events SET participants = participants - 1 WHERE eventId = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $eventId);

  return $stmt->execute() && deleteNotification("participation", $participationId, $mysqli);
}

?>
