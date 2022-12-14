<?php
require_once('../../database/db_connect.php');
require_once('../../database/db_functions.php');
require_once('../../utils/utils_functions.php');

define("UPLOAD_POST_DIR", "../img/posts/");

sec_session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!checkUserSession($mysqli)) {
    header("Location: login.php");
}

$msg = "Internal server error";

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

$msg = htmlentities($msg);

header("Location: ../post-creation.php?err=".$msg);

?>
