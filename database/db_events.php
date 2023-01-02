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
    $query = "INSERT INTO participations VALUES (?, ?);";
    $stmt = $mysqli->prepare($query);
    $eventId = getEventFromPost($postId, $mysqli)["eventId"];
    $stmt->bind_param("ii", $usrId, $eventId);
    return $stmt->execute() && incrementParticipants($eventId, $mysqli);
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

?>