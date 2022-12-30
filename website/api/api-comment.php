<?php
require_once("../../database/db_connect.php");
require_once("../../database/db_functions.php");

$result['ok'] = false;

$usrId = $_GET['usrId'];
$postId = $_GET['postId'];
$content = $_GET['content'];

if (addComment($postId, $usrId, $content, $mysqli)) {
  $result['ok'] = true;
}

header("Content-Type: application/json");
echo json_encode($result);

?>