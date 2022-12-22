<?php
require_once("bootstrap.php");
require_once('../utils/utils_functions.php');



$templateParams["Titolo"] = "Brogram - Post Creation";
$templateParams["user"] = getUser(intval($_SESSION['user_id']), $mysqli);
$templateParams["suggested_users"] = getSuggestedUser($_SESSION["user_id"], $mysqli);
array_push($templateParams["js"], "js/post_submit.js");


if (isset($_POST["title"], $_POST["description"], $_POST["location"], $_POST["event-datetime"])) {
    $user_id = $_SESSION["user_id"];
    $title = $_POST["title"];
    $photo = $_FILES["photo"];

    list($res, $imgPath) = uploadImage(UPLOAD_POST_DIR, $photo);

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
