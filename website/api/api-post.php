<?php
require_once('api-bootstrap.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$msg = "Internal server error";

// action = 0 means that a new post has to be inserted
if (isset($_GET["action"]) && $_GET["action"] == 0){
    if(isset($_POST["title"], $_POST["description"], $_POST['location'], $_POST['event-datetime']) && $_FILES["photo"]["error"] == 0) {
        $user_id = $_SESSION["user_id"];
        $title = $_POST["title"];
        $photo = $_FILES["photo"];

        list($err, $imgPath) = uploadImage(UPLOAD_POST_DIR, $photo);

        if ($err != 0) {
            $caption= $_POST["description"];
            $location = $_POST["location"];
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

// action = 1 means that clients need all followed users events information
} else if (isset($_GET["action"]) && $_GET["action"] == 1) {
    $usrId = $_SESSION["user_id"];
    $events = getAllEventsDetails($usrId, $mysqli);

    header("Content-Type: application/json");
    echo json_encode($events);
    return;

// action = 2 means that a user liked the post
} else if (isset($_GET["action"]) && $_GET["action"] == 2) {
    //TODO
}

$msg = htmlentities($msg);

header("Location: ../post-creation.php?err=".$msg);

?>
