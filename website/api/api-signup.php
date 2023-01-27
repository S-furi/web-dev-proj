<?php
require_once("../../database/db_connect.php");
require_once("../../database/db_functions.php");

$response["ok"] = false;

if (isset($_POST['email'], $_POST['password'], $_POST['username'], $_POST['first-name'], $_POST['last-name'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    
    if (insertNewUser($email,$username, $password, $first_name, $last_name, $mysqli)) {
      $response["msg"] = "Utente inserito correttamente!";
      $response["ok"] = true;
      } else {
          $response["msg"] = "Nome utente o email già in uso!";
  }
} else {
    $response['msg'] = "Riprovare più tardi";
}
header("Content-Type: application/json");
echo json_encode($response);
?>
