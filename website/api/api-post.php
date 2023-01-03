<?php
require_once('api-bootstrap.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);


// action = 0 means that a new post has to be inserted
if (isset($_GET["action"]) && $_GET["action"] == 0){
    if(isset($_POST["title"], $_POST["description"], $_POST['location-id'], $_POST['event-datetime']) && $_FILES["photo"]["error"] == 0) {
        $user_id = $_SESSION["user_id"];
        $title = $_POST["title"];
        $photo = $_FILES["photo"];

        list($err, $imgPath) = uploadImage(UPLOAD_POST_DIR, $photo);

        if ($err != 0) {
            $caption= $_POST["description"];
            $location = $_POST["location-id"];
            $event_datetime = $_POST["event-datetime"];

            if (createPost($user_id, $title, $caption, $imgPath, $location, $event_datetime, $mysqli)) {
                $msg = "Post creato con successo!";
            } else {
                $msg = "Impossibile creare post...";
            }
        } else {
            $msg = $imgPath;
        }
    } else {
        $msg = "Riempi tutti i campi";
    }

    $msg = htmlentities($msg);

    header("Location: ../post-creation.php?err=".$msg);


// action = 1 means that clients need all followed users events information
} else if (isset($_GET["action"]) && $_GET["action"] == 1) {
    $usrId = $_SESSION["user_id"];
    $events = getAllEventsDetails($usrId, $mysqli);

    header("Content-Type: application/json");
    echo json_encode($events);
    return;

// action = 2: like has been toggled, checks whether to delete the like or add
} else if (isset($_GET["action"]) && $_GET["action"] == 2) {
    if (isset($_POST["userId"], $_POST["postId"])){
        $usrId = $_POST["userId"];
        $postId = $_POST["postId"];

        $response["ok"] = false;

        if (registerLikeAction($usrId, $postId, $mysqli)) {
            $response["ok"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($response);
        return;

    } else {
        header("HTTP/1.1 204 No Content");
    }

// action = 3: return true if user has liked the provided post
} else if (isset($_GET["action"]) && $_GET["action"] == 3) {
    if (isset($_POST["postId"])){
        $usrId = $_SESSION["user_id"];
        $postId = $_POST["postId"];

        $response["hasAlreadyLikedPost"] = false;

        if (hasAlreadyLikedPost($usrId, $postId, $mysqli)) {
            $response["hasAlreadyLikedPost"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($response);
        return;
    } else {
        header("HTTP/1.1 204 No Content");
    }
}
