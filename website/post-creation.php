<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');
require_once('../utils/utils_functions.php');

define("UPLOAD_DIR", "../website/img/posts/");

sec_session_start();

if (!isStillLoggedIn($mysqli)) {
    header("Location: login.php");
}

$templateParams["Titolo"] = "Brogram - Post Creation";
$templateParams["user"] = getUser(intval($_SESSION['user_id']), $mysqli);
$templateParams["suggested_users"] = getSuggestedUser($_SESSION["user_id"], $mysqli);
$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/home.js", "js/post_submit.js");

if (isset($_POST["title"], $_POST["description"], $_POST["location"], $_POST["event-datetime"])) {
    $user_id = $_SESSION["user_id"];
    $title = $_POST["title"];
    $photo = $_FILES["photo"];

    list($res, $imgPath) = uploadImage(UPLOAD_DIR, $photo);

    if ($res != 0) {
        $caption= $_POST["description"];
        $location = $_POST["location"];
        $event_datetime = $_POST["event-datetime"];

        if (createPost($user_id, $title, $caption, $imgPath, $location, $event_datetime, $mysqli)) {
            // post created succesfully
            header("Location: index.php");
        }
    }
    // need to handle all the cases where something goes wrong
}

require("template/base.php");

?>
