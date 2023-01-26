<?php
require_once("db_posts.php");
require_once("db_users.php");
require_once("db_events.php");
require_once("db_notifications.php");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function sec_session_start()
{
    $session_name = "sec_session_id";
    $secure = false; // not https
    $httponly = true; // js can't access to sess-id
    ini_set("session.use_only_cookies", 1); // force session to use cookies only
    $cookie_params = session_get_cookie_params();
    session_set_cookie_params(array(
        'lifetime' => $cookie_params['lifetime'],
        'path' => $cookie_params['path'],
        'domain' => $cookie_params['domain'],
        'secure' => $secure,
        'httponly' => $httponly,
        'samesite' => 'lax',
    ));
    session_name($session_name);
    session_start();
    session_regenerate_id(); // regenerate the session and delete the one created before
}

/**
 * Checks wether a provided email and password are inside
 * the database. 
 */
function login($email, $password, $mysqli)
{
    $query = "SELECT usrId, password, username FROM users WHERE email=? LIMIT 1;";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (count($data) == 0) {
            return "Nome utente o password errati";
        }

        $username = $data[0]['username'];
        $user_id = $data[0]['usrId'];
        $db_password = $data[0]['password'];

        if (checkBrute($user_id, $mysqli)) {
            return "Limite tentativi massimo superato, si prega di riprovare più tardi";
        }

        if (!password_verify($password, $db_password)) {
            $now = time();
            $mysqli->query("INSERT INTO login_attempts (usrId, time) VALUES ('$user_id', '$now')");
            return "Nome utente o password errati";
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
        $_SESSION['login_string'] = $_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"] . $db_password;

        return true;
    }
    return false;
}

/**
 * Checks wether the account is being brute forced 
 * by checking if there are more than 5 failed
 * login attempts in the last two hours.
 */
function checkBrute($usr_id, $mysqli): bool
{
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

function checkUserSession($mysqli): bool
{
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

            $login_check_string = $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $password;

            if ($login_string == $login_check_string && $username == $db_username) {
                return true;
            }
        }
    }
    return false;
}
