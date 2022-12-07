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

    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
    
    $username = $data['username'];
    $user_id = $data['usrId'];
    $db_password = $data['password'];

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
  return $stmt->execute();
}

function createPost($usr_id, $title, $caption, $image, $location, $mysqli) : bool {
  $query = "INSERT INTO posts (usrId, title, caption, image, location) VALUES (?, ?, ?, ?, ?)";
  $stmt = $mysqli->preapre($query);
  $stmt->bind_params("issss", $usr_id, $title, $caption, $image, $location);

  return $stmt->execute();
}

function getPost($usrId, $mysqli) {
  $query = "SELECT caption, image, location, title FROM posts WHERE usrId=?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $usrId);
  
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getUsername($usrId, $mysqli) {
  $query = "SELECT username, email FROM users WHERE usrId=?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $usrId);
  
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

?>
