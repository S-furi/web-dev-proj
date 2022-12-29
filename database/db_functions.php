<?php
// http://paololatella.blogspot.com/2017/10/lezione-nov-2017-script-sicuro-per-il.html

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function sec_session_start() {
  $session_name = "sec_session_id";
  $secure = false; // not https
  $httponly = true; // js can't access to sess-id
  ini_set("session.use_only_cookies", 1); // force session to use cookies only
  $cookie_params = session_get_cookie_params();
  session_set_cookie_params($cookie_params['lifetime'], $cookie_params['path'], $cookie_params['domain'], $secure, $httponly);
  session_name($session_name);
  session_start();
  session_regenerate_id(); // regenerate the session and delete the one created before
}

/**
 * Checks wether a provided email and password are inside
 * the database. 
 */
function login($email, $password, $mysqli) {
  $query = "SELECT usrId, password, username FROM users WHERE email=? LIMIT 1;";
  if ($stmt = $mysqli->prepare($query)) {
    $stmt->bind_param('s', $email);
    $stmt->execute();

    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    if (count($data) == 0) {
      return false;
    }    
    
    $username = $data[0]['username'];
    $user_id = $data[0]['usrId'];
    $db_password = $data[0]['password'];

    if (checkBrute($user_id, $mysqli)) {
      return false;
    }

    if (!password_verify($password, $db_password)) {
      $now = time();
      $mysqli->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
    }
    // correct password inserted

    // user broser should be inserted crypted in 
    // $_SESSION['login_string'] with psw;

    // $user_browser = $_SERVER['HTTP_USER_AGENT'];

    // trying to avoid XSS attacks using htmlentities()
    // used for replacing with HTML escape sequences
    // insed of symbols like <, >, / or others simple ways
    // to inject malicious code inside forms.
    $_SESSION['user_id'] = htmlentities($user_id); 
    $_SESSION['username'] = htmlentities($username); 
    $_SESSION['login_string'] = $_SERVER["REMOTE_ADDR"].$_SERVER["HTTP_USER_AGENT"].$db_password;

    return true;
  }
}

/**
 * Checks wether the account is being brute forced 
 * by checking if there are more than 5 failed
 * login attempts in the last two hours.
 */
function checkBrute($usr_id, $mysqli) : bool {
  $now = time();
  $valid_attempts = $now - (2 * 60 * 60);
  if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE usrId=? AND time > '$valid_attempts'")) {
    $stmt->bind_param("i", $usr_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 5) {
      return true;
    }
  }
  return false;
}

function insertNewUser($email, $username,  $password, $first_name, $last_name, $mysqli) : bool {
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

function createPost($usr_id, $title, $caption, $image, $location, $event_date, $mysqli) : bool {
  $query = "insert into posts (usrId, title, caption, image, location, creationDate, eventDate, likes) VALUES (?, ?, ?, ?, ?, NOW(), ?, 0)";
  $event_date = date("Y-m-d H:i:s", strtotime($event_date));
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("isssss", $usr_id, $title, $caption, $image, $location, $event_date);

  return $stmt->execute();
}

function getPosts($usrId, $mysqli) {
  $query = "SELECT usrId, postId, caption, image, location, title, eventDate, likes FROM posts WHERE usrId=? ORDER BY creationDate DESC";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $usrId);
  
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getPostFromId($usrId, $postId, $mysqli) {
  $query = "SELECT caption, image, location, title, eventDate, likes FROM posts WHERE usrId=? AND postId=?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ii", $usrId, $postId);
  
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
}

function getUser($usrId, $mysqli) {
  $query = "SELECT usrId, email, username, firstName, lastName FROM users WHERE usrId=?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $usrId);
  
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
}

function getComments($postId, $mysqli) {
  $query = "SELECT caption FROM comments WHERE postID=?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $postId);

  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Returns friends of friends.
 *
 * THIS IS A TEMPORARY VERSION, returns first 5 users in db
 * not followed by provided usrId
 */
function getSuggestedUser($usrId, $mysqli) {
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

function getUserFromFragments($queryFragment, $mysqli) {
    $condition = preg_replace('/[^A-Za-z0-9\- ]/', '', $queryFragment);
    // % sign are SQL wildcards
    $condition = '%'.$condition.'%';

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

function insertNewFollower($user, $followed, $mysqli) {
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

function getFollowedUsers($user, $mysqli) {
    $stmt = $mysqli->prepare("SELECT friendId FROM followers WHERE usrId = ?");
    $stmt->bind_param("i", $user);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getFollowingNum($userId, $mysqli) {
    $stmt = $mysqli->prepare("SELECT COUNT(*) as follow_num FROM followers WHERE usrId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]['follow_num'];
}


function getFollowersNum($userId, $mysqli) {
    $stmt = $mysqli->prepare("SELECT COUNT(*) as follow_num FROM followers WHERE friendId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]['follow_num'];
}

/**
 * Function that returns all user's friends' posts 
 * Need to add the control over the event date.
 * (this function should be used only for future events)
 */
function getFriendsPosts($usrId, $mysqli) {
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

function getCommentsOfPost($postId, $mysqli) {
    $query = "SELECT content, date, author FROM comments WHERE postId = ?;";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $postId);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function updateLikes($postId, $usrId, $mysqli) {
    $insert_like_query = "INSERT INTO likes (usrId, postId) VALUES (?, ?)";
    $stmt = $mysqli->prepare($insert_like_query);
    $stmt->bind_param("ii", $usrId, $postId);
    if ($stmt->execute()) {
        $update_likes_query = "UPDATE posts SET likes = likes + 1 WHERE postId = ?";
        $stmt = $mysqli->prepare($update_likes_query);
        $stmt->bind_param("i", $postId);
        return $stmt->execute();
    }
    return false;
}

function notify(string $type, int $forUser, int $entityId, mysqli $mysqli) {
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

function fetchNotifications(int $num, $forUser, $mysqli) {
    $query = "SELECT type, forUser, entityId, read, time FROM notifications WHERE forUser = ? ORDER BY time DESC LIMIT ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_params("ii", $forUser, $num);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/* method used in the notification center for retrieving 
 * post information when querying notifications table.
 * using the commentID as EntityId.
 */
function fetchPostInfoFromCommentId($commentId, mysqli $mysqli) {
    $query = "SELECT p.* 
                FROM comments c JOIN posts p ON (c.postId = p.postId)
                WHERE commentId = ?;";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/* method used in the notification center for retrieving 
 * post information when querying notifications table,
 * using the likeId as EntityId.
 */
function fetchPostInfoFromLike($likeId, $mysqli) {
    $query = "SELECT * 
                FROM likes l JOIN posts p ON (l.postId = p.postId)
                WHERE likeId = ?;";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $likeId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getLikeDetails($likeId, mysqli $mysqli) {
    $stmt  = $mysqli->prepare("SELECT * FROM likes WHERE likeId = ?");
    $stmt->bind_param("i", $likeId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// TODO funzione come sopra ma per arrivare alla pagina dell'utente
// che ti ha seguito. Si implementa la funzione quando è pronto il 
// file php per visualizzare il profilo di un utente.

function getCommentFromCommentId($commentId, mysqli $mysqli) {
    $query = "SELECT postId, author, date, content FROM comments where commentId = ?;";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $commentId);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function checkUserSession($mysqli) : bool {
  if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
    $user_id = $_SESSION['user_id'];
    $login_string = $_SESSION['login_string'];
    $username = $_SESSION['username'];

    if ($stmt = $mysqli->prepare("SELECT username, password FROM users WHERE usrId=?")) {
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];

      $password = $result["password"];
      $db_username = $result["username"];

      $login_check_string = $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$password;

      if ($login_string == $login_check_string && $username == $db_username) {
        return true;
      }
    }
  }
  return false;
}
?>
