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
function createPost($usr_id, $title, $caption, $image, $location, $event_date, $mysqli): bool
{
    $query = "insert into posts (usrId, title, caption, image, location, creationDate, eventDate, likes) VALUES (?, ?, ?, ?, ?, NOW(), ?, 0)";
    $event_date = date("Y-m-d H:i:s", strtotime($event_date));
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("isssss", $usr_id, $title, $caption, $image, $location, $event_date);

    if ($stmt->execute()) {
        return createEvent($usr_id, mysqli_insert_id($mysqli), $mysqli);
    }
}

function getPosts($usrId, $mysqli)
{
    $query = "SELECT usrId, postId, caption, image, location, title, eventDate, likes FROM posts WHERE usrId=? ORDER BY creationDate DESC";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $usrId);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getPostFromId($usrId, $postId, $mysqli)
{
    $query = "SELECT caption, image, location, title, eventDate, likes FROM posts WHERE usrId=? AND postId=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $usrId, $postId);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
}

function getPostFromPostId($postId, mysqli $mysqli)
{
    $stmt = $mysqli->prepare("SELECT * FROM posts WHERE postId = ?");
    $stmt->bind_param("i", $postId);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
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
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/** Method used in the notification center for retrieving 
 * post information when querying notifications table,
 * using the likeId as EntityId.
 */
function fetchPostInfoFromLike($likeId, $mysqli)
{
    $query = "SELECT * 
                FROM likes l JOIN posts p ON (l.postId = p.postId)
                WHERE likeId = ?;";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $likeId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getComments($postId, $mysqli)
{
    $query = "SELECT c.content, u.username, u.firstName, u.lastName FROM comments c INNER JOIN users u ON c.author = u.usrId WHERE c.postID=? ORDER BY date DESC";
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
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getLikeDetails($likeId, mysqli $mysqli)
{
    $stmt  = $mysqli->prepare("SELECT * FROM likes WHERE likeId = ?");
    $stmt->bind_param("i", $likeId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// TODO funzione come sopra ma per arrivare alla pagina dell'utente
// che ti ha seguito. Si implementa la funzione quando è pronto il 
// file php per visualizzare il profilo di un utente.

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

?>
