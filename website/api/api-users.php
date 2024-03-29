<?php
require_once("../../database/db_connect.php");
require_once("../../database/db_functions.php");

$result["ok"] = false;
sec_session_start();

if (isset($_GET["action"])){
    // action 1 is autocompletion search for users
    if ($_GET["action"] == 1 && isset($_POST["queryFragment"])) {
        $users = getUserFromFragments($_POST["queryFragment"], $mysqli);
        if (count($users) > 0) {
            $result["ok"] = true;
            $result["users"] = $users;
            $result["pid"] = $_SESSION['user_id'];
        }
    // action 2 handles follow of followed by user
    } else if ($_GET["action"] == 2 && isset($_POST["user"], $_POST["followed"])) {
        $user = $_POST["user"];
        $followed = $_POST["followed"];

        if (insertNewFollower($user, $followed, $mysqli)) {
            notify("follow", $followed, $user, $mysqli);
            $result["ok"] = true;
        }
    // action 3 handles unufollowing
    } elseif ($_GET["action"] == 3 && isset($_POST["user"], $_POST["followed"])) {
        $user = $_POST["user"];
        $followed = $_POST["followed"];

        $result["ok"] = false;

        if ($user == $_SESSION["user_id"]) {
          if (unfollow($user, $followed, $mysqli)) {
            $result['ok'] = true;
          }
        }
    }
}

header("Content-Type: application/json");
echo json_encode($result);

?>
