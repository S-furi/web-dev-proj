<?php

/**
 * This file contains Users and followers utility methotds
 * for retrieving information from database.
 */

require_once("db_notifications.php");

function insertNewUser($email, $username,  $password, $first_name, $last_name, $mysqli): bool
{
  // We are using password_hash() because it's safe and simple to use.
  // It uses a secure salt for hashing the password, and the 
  // algorithm used changes overtime using PASSWORD_DEFAULT,
  // reulting in the most secure algorithm available at 
  // the current version of PHP.
  $password = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $mysqli->prepare("INSERT INTO users (email, password, username, firstName, lastName) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $email, $password, $username, $first_name, $last_name);

  // An exeption is thrown in the case where email or username are already in DB
  // (and of course all the cases where db queries fails for other internal reasons)
  try {
    $stmt->execute();
  } catch (\Throwable $th) {
    return false;
  }
  return true;
}

function insertNewUserInfo($usrId, $bio, $propic, $mysqli)
{
  $query = "INSERT INTO user_profile (users_usrId, bio, profileImg) VALUES (?, ?, ?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("iss", $usrId, $bio, $propic);

  try {
    $stmt->execute();
  } catch (\Throwable $th) {
    return false;
  }
  return true;
}

function updateProfileInfo($usrId, $bio, $propic, $mysqli)
{
  $query = "UPDATE user_profile SET bio=?, profileImg=? WHERE users_usrId=?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ssi", $bio, $propic, $usrId);

  try {
    $stmt->execute();
  } catch (\Throwable $th) {
    return false;
  }
  return true;
}

function getUser($usrId, $mysqli)
{
  $query = "SELECT usrId, email, username, firstName, lastName FROM users WHERE usrId=?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $usrId);

  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
}

function getUserInfo($usrId, $mysqli)
{
  $query = "SELECT bio, profileImg FROM user_profile WHERE users_usrId = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $usrId);

  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function checkUserInfoExists($usrId, $mysqli)
{
  $query = "SELECT COUNT(1) FROM user_profile WHERE users_usrId = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $usrId);

  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]['COUNT(1)'];
}

/**
 * Returns friends of friends.
 *
 * Returns first 5 users in db not followed by provided usrId
 */
function getSuggestedUser($usrId, $mysqli)
{
  $suggestionNum = 3; // how many suggestions are fetched

  $suggUsers = getFriendSFriends($usrId, $suggestionNum, $mysqli);

  if (count($suggUsers) < $suggestionNum) {
    $randomUsers = getRandomUsers($usrId, $suggestionNum - count($suggUsers), $mysqli);
    $suggUsers = array_merge($suggUsers, $randomUsers);
  }

  return $suggUsers;
}


function getRandomUsers($usrId, $suggestionNum, mysqli $mysqli)
{
  // Note: if DB is big `ORDER BY RAND()` will have bad performances
  $query =
    "SELECT usrId, username, firstName, lastName 
    FROM users 
    WHERE usrId <> ? 
    AND usrId NOT IN (SELECT friendId FROM followers where usrId = ?)
    ORDER BY RAND() LIMIT ?";


  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("iii", $usrId, $usrId, $suggestionNum);
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getFriendSFriends($usrId, $suggestionNum, mysqli $mysqli)
{
  $query =
    "SELECT DISTINCT users.* FROM users
      JOIN followers ON users.usrId = followers.friendId
      WHERE users.usrId NOT IN (SELECT friendId FROM followers WHERE usrId = ?)
      AND followers.usrId IN (SELECT friendId FROM followers WHERE usrId = ?)
      AND users.usrId != ?
      ORDER BY RAND()
      LIMIT ?;";

  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("iiii", $usrId, $usrId, $usrId, $suggestionNum);

  $stmt->execute();

  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getUserFromFragments($queryFragment, $mysqli)
{
  // removing special chars
  $condition = preg_replace('/[^A-Za-z0-9\- ]/', '', $queryFragment);
  $condition = '%' . $condition . '%';

  $query = "
        SELECT email, username, usrId, firstName, lastName 
        FROM users 
        WHERE username LIKE ?
        OR firstName LIKE ?
        OR lastName LIKE ?
        ORDER BY usrId DESC
        LIMIT 10;";

  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('sss', $condition, $condition, $condition);
  $stmt->execute();

  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function insertNewFollower($user, $followed, $mysqli)
{
  $stmt = $mysqli->prepare("INSERT INTO followers (usrId, friendId) VALUES (?, ?)");
  // friendships aren't bidirectional
  $stmt->bind_param("ii", $user, $followed);

  try {
    $stmt->execute();
  } catch (mysqli_sql_exception $e) {
    return false;
  }
  return true;
}

function getFollowedUsers($user, $mysqli)
{
  $stmt = $mysqli->prepare("SELECT friendId FROM followers WHERE usrId = ?");
  $stmt->bind_param("i", $user);
  $stmt->execute();

  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getFollowingUsers($user, $mysqli)
{
  $stmt = $mysqli->prepare("SELECT usrId FROM followers WHERE friendId = ?");
  $stmt->bind_param("i", $user);
  $stmt->execute();

  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getFollowingNum($userId, $mysqli)
{
  $stmt = $mysqli->prepare("SELECT COUNT(*) as follow_num FROM followers WHERE usrId = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();

  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]['follow_num'];
}

function getFollowersNum($userId, $mysqli)
{
  $stmt = $mysqli->prepare("SELECT COUNT(*) as follow_num FROM followers WHERE friendId = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();

  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]['follow_num'];
}

function checkFollow($userId, $friendId, $mysqli)
{
  $stmt = $mysqli->prepare("SELECT COUNT(*) as follow_num FROM followers WHERE usrId = ? AND friendId = ?");
  $stmt->bind_param("ii", $userId, $friendId);
  $stmt->execute();

  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]['follow_num'] > 0;
}

function unfollow($userId, $followedId, mysqli $mysqli)
{
  $query = "DELETE FROM followers WHERE usrId = ? AND friendId = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ii", $userId, $followedId);

  return $stmt->execute() && deleteFollowNotification($userId, $followedId, $mysqli);
}

?>
