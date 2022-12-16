<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

sec_session_start();

$response['ok'] = false;

if (isset($_POST['email'], $_POST['password'])) {
  if (login($_POST['email'], $_POST['password'], $mysqli)) {
    $response['ok'] = true;
  } else {
    $response['msg'] = "login fallito!";
  }
}

header("Content-Type: application/json");
echo json_encode($response);
?>
