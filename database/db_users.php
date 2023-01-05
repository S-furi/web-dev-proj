<?php
/**
 * This file contains Users and followers utility methotds
 * for retrieving information from database.
 */

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

function getUser($usrId, $mysqli)
{
    $query = "SELECT usrId, email, username, firstName, lastName FROM users WHERE usrId=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $usrId);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
}

/**
 * Returns friends of friends.
 *
 * THIS IS A TEMPORARY VERSION, returns first 5 users in db
 * not followed by provided usrId
 */
function getSuggestedUser($usrId, $mysqli)
{
    // Note: if DB is big `ORDER BY RAND()` will have bad performances
    $query =
        "SELECT usrId, username, firstName, lastName 
        FROM users 
        WHERE usrId <> ? 
        AND usrId NOT IN (SELECT friendId FROM followers where usrId = ?)
        ORDER BY RAND() LIMIT 3";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $usrId, $usrId);
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

?>
