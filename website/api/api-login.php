<?php
require_once("api-bootstrap.php");

ini_set("display_errors", 1);
error_reporting(E_ALL);

$response['ok'] = false;

if (isset($_POST['email'], $_POST['password'])) {
  $msg = login($_POST['email'], $_POST['password'], $mysqli);
  if ($msg === true) {
    $response['ok'] = true;
  } else {
    $response['msg'] = $msg;
  }
}

header("Content-Type: application/json");
echo json_encode($response);
?>
