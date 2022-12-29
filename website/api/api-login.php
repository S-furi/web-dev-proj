<?php
require_once("api-bootstrap.php");

$response['ok'] = false;

if (isset($_POST['email'], $_POST['password'])) {
  if (login($_POST['email'], $_POST['password'], $mysqli)) {
    $response['ok'] = true;
  } else {
    $response['msg'] = "Nome Utente o Password errati";
  }
}

header("Content-Type: application/json");
echo json_encode($response);
?>
