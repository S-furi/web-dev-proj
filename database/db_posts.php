<?php
require_once("db_events.php");
require_once("db_notifications.php");

/**
 * This file containts functions for retrieving information from database
 * about posts, comments and likes.
 */

/**
 * Creates a post from given arguments.
 */
function createPost($usr_id, $title, $caption, $image, $locationId, $event_date, $mysqli)
{
    $query = "insert into posts (usrId, title, caption, image, locationId, creationDate, eventDate, likes) VALUES (?, ?, ?, ?, ?, NOW(), ?, 0)";
    $event_date = date("Y-m-d H:i:s", strtotime($event_date));
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("isssss", $usr_id, $title, $caption, $image, $locationId, $event_date);

    try {
      $stmt->execute();
    } catch (mysqli_sql_exception $th) {

      $err = $stmt->error;
      preg_match("/'(.*?)'/", $err, $matches);

      if (count($matches) > 0 && $matches[1] == "image") {
        // an error with image occurred
        $msg = "Nome immagine troppo lungo";
        return array(false, $msg);
      } else {
        return array(false, $err);
      }
    }
    return array(createEvent($usr_id, mysqli_insert_id($mysqli), $mysqli), "Post creato con successo!");
}

/**
 * Retrieves locationId from the given locations parameters.
 *
 * Note: the location paramenter is an associative array of 
 * the form $location["name"], $location["lon"], $location["lat"].
 *
 * @return locationInformations, or null if no locations are found
 */
function getLocation($location, mysqli $mysqli)
{
    $query = "SELECT * FROM locations WHERE locations.name = ? AND locations.lon = ? AND locations.lat = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $location["name"], $location["lon"], $location["lat"]);
    $stmt->execute();

    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (count($res) == 0) {
        return null;
    }
    return $res[0];
}

/**
 * Returns all locations informations and the postId 
 * of nearest event date associated with that location.
 */
function getLocations(mysqli $mysqli) {
    $query = "SELECT l.*, (
                  SELECT p.postId
                  FROM posts p
                  WHERE p.locationId = l.locationId
                  AND p.eventDate > NOW()
                  ORDER BY ABS(DATEDIFF(NOW(), p.eventDate)) ASC
                  LIMIT 1
                ) AS post
            FROM locations l";

    $stmt = $mysqli->prepare($query);
    $stmt->execute();

    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (count($res) > 0) {
        // removing results with NULL in `post` column
        $res = array_filter($res, function($arr) {
            return $arr["post"] != null;
        });

        return $res;
    }
    return null;
}

function getLocationFromLocationId($locationId, mysqli $mysqli)
{
    $stmt = $mysqli->prepare("SELECT * FROM locations WHERE locationId = ?");
    $stmt->bind_param("i", $locationId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
}

/**
 * Register the given location if it's not already present in db.
 *
 * @return locationId of the given location, -1 if an error occurs
 */
function registerLocation($location, mysqli $mysqli)
{
    // if the location is already in db, return true;
    $dbLocation= getLocation($location, $mysqli); 
    if ($dbLocation!= null) {
        return $dbLocation["locationId"];
    }

    // otherwise, insert the new location in db
    $query = "INSERT INTO locations (name, lon, lat) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $location["name"], $location["lon"], $location["lat"]);
    if ($stmt->execute()) {
        $locationId = mysqli_insert_id($mysqli);
        return $locationId;
    }
    return -1;
}

function getPosts($usrId, $mysqli)
{
    $query = "SELECT usrId, postId, caption, image, locationId, title, eventDate, likes FROM posts WHERE usrId=? ORDER BY creationDate DESC";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $usrId);

    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    for ($i = 0; $i < count($res); $i++) {
        $res[$i]["location"] = getLocationFromLocationId($res[$i]["locationId"], $mysqli)["name"];
        $res[$i]["participants"] = getParticipants($res[$i]["postId"], $mysqli);
    }
    return $res;
}

function getPostFromId($usrId, $postId, $mysqli)
{
    $query = "SELECT caption, image, locationId, title, eventDate, likes FROM posts WHERE usrId=? AND postId=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $usrId, $postId);

    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if ($res > 0) {
        $res = $res[0];
        $res["location"] = getLocationFromLocationId($res["locationId"], $mysqli)["name"];
        $res["participants"] = getParticipants($postId, $mysqli);
    }
    return $res;
}

function getPostFromPostId($postId, mysqli $mysqli)
{
    $stmt = $mysqli->prepare("SELECT * FROM posts WHERE postId = ?");
    $stmt->bind_param("i", $postId);

    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (count($res) > 0) {
        $res = $res[0];
        $res["location"] = getLocationFromLocationId($res["locationId"], $mysqli)["name"];
    }
    return $res;
}

/**
 * Function that returns all user's friends' posts 
 * Need to add the control over the event date.
 * (this function should be used only for future events)
 */
function getFriendsPosts($usrId, $mysqli)
{
    $query = "SELECT posts.*, username as author
        FROM posts JOIN users ON (posts.usrId = users.usrId)
        WHERE users.usrId in (SELECT friendId 
                                FROM followers 
                                WHERE followers.usrId = ?)
        ORDER BY eventDate DESC;";


    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $usrId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    for ($i = 0; $i < count($res); $i++) {
        $res[$i]["location"] = getLocationFromLocationId($res[$i]["locationId"], $mysqli)["name"];
        $res[$i]["participants"] = getParticipants($res[$i]["postId"], $mysqli);
    }
    return $res;
}

function getDiscoverPosts($usrId, $mysqli) {
  $query = "SELECT p.*, u.username as author FROM posts p
    JOIN users u ON p.usrId = u.usrId
    WHERE p.usrId IN (SELECT f.friendId FROM followers f WHERE f.usrId = ? AND p.eventDate >= NOW()) AND p.usrId != ?
    UNION 
    SELECT p.*, u.username as author FROM posts p
    JOIN followers f1 ON p.usrId = f1.friendId
    JOIN followers f2 ON f1.usrId = f2.friendId
    JOIN users u ON p.usrId = u.usrId
    WHERE f2.usrId = ? AND p.usrId != ? AND p.eventDate >= NOW();";
  
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("iiii", $usrId, $usrId, $usrId, $usrId);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

  for ($i = 0; $i < count($res); $i++) {
      $res[$i]["location"] = getLocationFromLocationId($res[$i]["locationId"], $mysqli)["name"];
  }
  return $res;
  
}

/* method used in the notification center for retrieving 
 * post information when querying notifications table.
 * using the commentID as EntityId.
 */
function fetchPostInfoFromCommentId($commentId, mysqli $mysqli)
{
    $query = "SELECT p.* 
                FROM comments c JOIN posts p ON (c.postId = p.postId)
                WHERE commentId = ?;";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    for ($i = 0; $i < count($res); $i++) {
        $res[$i]["location"] = getLocationFromLocationId($res[$i]["locationId"], $mysqli)["name"];
    }
    return $res;
}

/** Method used in the notification center for retrieving 
 * post information when querying notifications table,
 * using the likeId as EntityId.
 */
function fetchPostInfoFromLike($likeId, $mysqli)
{
    $query = "SELECT * 
                FROM likes JOIN posts ON (likes.postId = posts.postId)
                WHERE likeId = ?;";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $likeId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    for ($i = 0; $i < count($res); $i++) {
        $res[$i]["location"] = getLocationFromLocationId($res[$i]["locationId"], $mysqli)["name"];
    }
    return $res;
}

function getComments($postId, $mysqli)
{
    $query = "SELECT c.content, u.username, u.usrId, u.firstName, u.lastName FROM comments c INNER JOIN users u ON c.author = u.usrId WHERE c.postID=? ORDER BY date DESC";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $postId);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getCommentsOfPost($postId, $mysqli)
{
    $query = "SELECT content, date, author FROM comments WHERE postId = ?;";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $postId);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getCommentFromCommentId($commentId, mysqli $mysqli)
{
    $query = "SELECT postId, author, date, content FROM comments where commentId = ?;";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $commentId);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
}

function getLikeDetails($likeId, mysqli $mysqli)
{
    $stmt  = $mysqli->prepare("SELECT * FROM likes WHERE likeId = ?");
    $stmt->bind_param("i", $likeId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getUserLikeDetails($postId, mysqli $mysqli) {
  $stmt = $mysqli->prepare("SELECT u.firstName, u.lastName, u.usrId, u.username
                            FROM likes l
                            JOIN users u ON l.usrId = u.usrId
                            WHERE l.postId = ?
                            ");
  $stmt->bind_param("i", $postId);
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function addComment($postId, $usrId, $content, $mysqli)
{
    $query = "INSERT INTO comments (postId, author, date, content) VALUES (?, ?, ?, ?)";
    $date = date("Y-m-d H:i:s");
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iiss", $postId, $usrId, $date, $content);

    $stmt->execute();

    $commentId = mysqli_insert_id($mysqli);
    $forUser = getPostFromPostId($postId, $mysqli)["usrId"];

    return $stmt->affected_rows > 0 && notifyComment($forUser, $commentId, $mysqli);
}

function updateLikes($postId, $usrId, $mysqli)
{
    $insertLikeQuery = "INSERT INTO likes (usrId, postId) VALUES (?, ?)";
    $stmt = $mysqli->prepare($insertLikeQuery);
    $stmt->bind_param("ii", $usrId, $postId);
    if ($stmt->execute()) {
        $updateLikesQuery = "UPDATE posts SET likes = likes + 1 WHERE postId = ?";
        $stmt = $mysqli->prepare($updateLikesQuery);
        $stmt->bind_param("i", $postId);
        return $stmt->execute();
    }
    return false;
}

function registerLikeAction($usrId, $postId, mysqli $mysqli)
{
    if (hasAlreadyLikedPost($usrId, $postId, $mysqli)) {
        return removeLike($usrId, $postId, $mysqli);
    }
    return addLike($usrId, $postId, $mysqli);
}

function addLike($usrId, $postId, mysqli $mysqli)
{
    $insertLikeQuery = "INSERT INTO likes (usrId, postId) VALUES (?, ?)";
    $incrementPostLikesQuery = "UPDATE posts SET posts.likes = posts.likes + 1 WHERE postId = ?;";

    $stmt = $mysqli->prepare($insertLikeQuery);
    $stmt->bind_param("ii", $usrId, $postId);
    if ($stmt->execute()) {
        $likeId = mysqli_insert_id($mysqli);
        $stmt = $mysqli->prepare($incrementPostLikesQuery);
        $stmt->bind_param("i", $postId);
        return $stmt->execute() && notifyLike($postId, $likeId, $mysqli);
    }
    return false;
}

/**
 * Removes a like from the given post.
 *
 * NOTE: the user who has permissions for DELETE, may lead
 * to security issues. In this case, the user has DELETE permissions 
 * only on the table `likes`. For the sake of semplicity, we will
 * not make security improvements on the DB, but we still try to protect
 * against unwanted deletions thanks to prepared stamtens.
 *
 * @return bool: true if the like is succesfully removed
 */
function removeLike($usrId, $postId, mysqli $mysqli)
{
    $deleteLikeQuery = "DELETE FROM likes WHERE usrId = ? AND postId = ?";
    $decrementPostLikesQuery = "UPDATE posts SET posts.likes = posts.likes - 1 WHERE postId = ?;";

    $stmt = $mysqli->prepare($deleteLikeQuery);
    $stmt->bind_param("ii", $usrId, $postId);
    if ($stmt->execute()) {
        $stmt = $mysqli->prepare($decrementPostLikesQuery);
        $stmt->bind_param("i", $postId);
        return $stmt->execute();
    }
    return false;
}

function hasAlreadyLikedPost($usrId, $postId, mysqli $mysqli)
{
    $stmt = $mysqli->prepare("SELECT * FROM likes WHERE usrId = ? and postId = ?");
    $stmt->bind_param("ii", $usrId, $postId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    return count($res) > 0;
}

function getPostFromEventId($eventId, mysqli $mysqli) {
  $query = "SELECT * FROM events WHERE eventId = ?";
  $stmt = $mysqli->prepare($query);

  $stmt->bind_param("i", $eventId);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];

  if (count($res) > 0) {
    return getPostFromPostId($res['postId'], $mysqli);
  }
  return []; 
}

function isUserPostAuthor($postId, $usrId, mysqli $mysqli) {
  $query = "SELECT * FROM posts WHERE postId = ? AND usrId = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ii", $postId, $usrId);
  $stmt->execute();
  $stmt->store_result();
  return $stmt->num_rows() > 0;
}

function deletePost($postId, mysqli $mysqli) {
  deletePostNotifications($postId, $mysqli);

  // on cascade, every dependecy of the provided post will be deleted
  $query = "DELETE FROM posts WHERE postId = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $postId);
  return $stmt->execute();
}

?>
