<?php
require_once('../../database/db_connect.php');
require_once('../../database/db_functions.php');

sec_session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isStillLoggedIn($mysqli)) {
    header("Location: login.php");
    echo "Sessione scaduta";
}

$response["loginok"] = false;
$response["errormsg"] = "No errors";

if(isset($_POST["title"], $_POST["photo"], $_POST["description"], $_POST['location'], $_POST['event-datetime'])) {
    $user_id = $_SESSION["user_id"];
    $title = $_POST["title"];
    $photo = $_POST["photo"];
    $description = $_POST["description"];
    $location = $_POST['location'];
    $date = $_POST['event-datetime'];
    
    if (createPost($user_id, $title, $description, $photo, $location, $date, $mysqli)) {
        $response["loginok"] = true;
    } else {
        $response["errormsg"] = "Query fallita";
    }
} else {
    $response["errormsg"] = "Variaible POST non settata";
    $response["post"] = $_POST;
}

header("Content-Type: application/json");
echo json_encode($response);
?>
